@setup
    require __DIR__.'/vendor/autoload.php';

    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);

    try {
        $dotenv->load();
        $dotenv->required(['DEPLOY_USER', 'DEPLOY_SERVER', 'DEPLOY_BRANCH', 'DEPLOY_REPO', 'DEPLOY_BASE_DIR'])->notEmpty();
    } catch ( Exception $e )  {
        echo "Environments doesn`t exists or empty value, error: " . $e->getMessage();
    }

    $branch = env('DEPLOY_BRANCH');
    $repository = env('DEPLOY_REPO');
    $baseDir = env('DEPLOY_BASE_DIR');

    $releaseDir = $baseDir . '/releases';
    $currentDir = $baseDir . '/current';
    $release = date('YmdHis');
    $currentReleaseDir = $releaseDir .'/'. $release;
@endsetup

@servers(['prod' => env('DEPLOY_USER').'@'.env('DEPLOY_SERVER')])

@story('deploy')
    stop_containeres
    clone_repository
    update_symlinks
    {{--  fix_permissions  --}}
    start_containeres
    run_composer
    run_migrations
    run_npm
@endstory

@task('stop_containeres')
    echo 'Stopping all actual services containeres'
    cd {{ $currentDir }}
    docker-compose -f docker-compose-production.yml down
@endtask

@task('clone_repository')
    echo 'Cloning repository'
    [ -d {{ $currentReleaseDir }} ] || mkdir -p {{ $currentReleaseDir }}
    git clone --depth 1 --single-branch --branch {{ $branch }} {{ $repository }} {{ $currentReleaseDir }}
@endtask

@task('update_symlinks')
    echo "Remove storage folder"
    rm -rf {{ $currentReleaseDir }}/src/storage

    echo 'Copy storage and env inside new version'
    cp -r {{ $baseDir }}/shared/storage {{ $currentReleaseDir }}/src
    cp {{ $baseDir }}/shared/.env {{ $currentReleaseDir }}/src

    echo 'Linking current release'
    ln -nfs {{ $currentReleaseDir }} {{ $currentDir }}
@endtask

{{--  @task('fix_permissions')
    echo "Setting file and folder permissions"
    cd {{ $currentDir }}
    ./setup.sh
@endtask  --}}

@task('start_containeres')
    echo 'Start all services containeres'
    cd {{ $currentDir }}
    docker-compose -f docker-compose-production.yml up -d --build
    sleep 15
@endtask

@task('run_composer')
    echo "Starting deployment ({{ $release }})"
    cd {{ $currentDir }}
    docker-compose -f docker-compose-production.yml exec -T app composer install --no-interaction --prefer-dist --optimize-autoloader --no-dev
@endtask

@task('run_migrations')
    echo "Running migrations..."
    cd {{ $currentDir }}
    docker-compose -f docker-compose-production.yml exec -T app php artisan migrate --force
@endtask

 @task('run_npm')
    echo "Running npm..."
    cd {{ $currentDir }}
    docker compose -f docker-compose-production.yml run --rm --service-ports npm run production
@endtask

@finished
    echo "Envoy deployment script finished.\r\n";
@endfinished
