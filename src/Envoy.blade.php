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
    $repository = env('REPOSITORY');
    $appDir = env('DEPLOY_BASE_DIR');
    $releasesDir = $appDir . '/releases';
    $release = date('YmdHis');
    $newReleaseDir = $releasesDir .'/'. $release;
@endsetup

@servers(['prod' => env('DEPLOY_USER').'@'.env('DEPLOY_SERVER')])

@story('deploy')
    testing_environments
    {{--  stop_containeres  --}}
    {{--  clone_repository
    update_symlinks  --}}
    {{--  fix_permissions  --}}
    {{--  start_containeres
    run_composer
    run_migrations
    run_npm  --}}
@endstory

@task('testing_environments')
    echo 'Testing deploy host: ' . env('DEPLOY_HOST')
    echo 'Testing branch: ' . env('BRANCH')
    echo 'Testing repository: ' . env('REPOSITORY')
    echo 'Testing app dir: ' . env('APP_DIR')
@endtask

@task('stop_containeres')
    echo 'Stopping all services containeres'
    cd {{ $appDir }}/current
    docker-compose -f docker-compose-production.yml down
@endtask

@task('clone_repository')
    echo 'Cloning repository'
    [ -d {{ $releasesDir }} ] || mkdir {{ $releasesDir }}
    git clone --depth 1 --single-branch --branch {{ $branch }} {{ $repository }} {{ $newReleaseDir }}
    cd {{ $newReleaseDir }}
    git reset --hard {{ $commit }}
@endtask

@task('update_symlinks')
    echo "Remove storage folder"
    rm -rf {{ $newReleaseDir }}/storage

    echo 'Copy storage and env inside new version'
    cp -r {{ $appDir }}/shared/storage {{ $newReleaseDir }}
    cp {{ $appDir }}/shared/.env {{ $newReleaseDir }}

    echo 'Linking current release'
    ln -nfs {{ $newReleaseDir }} {{ $appDir }}/current
@endtask

{{--  @task('fix_permissions')
    echo "Setting file and folder permissions"
    cd {{ $appDir }}/current
    ./setup.sh
@endtask  --}}

@task('start_containeres')
    echo 'Start all services containeres'
    cd {{ $appDir }}/current
    docker-compose -f docker-compose-production.yml up -d --build
    sleep 15
@endtask

@task('run_composer')
    echo "Starting deployment ({{ $release }})"
    cd {{ $appDir }}/current
    docker-compose -f docker-compose-production.yml exec app composer install --no-interaction --prefer-dist --optimize-autoloader --no-dev
@endtask

@task('run_migrations')
    echo "Running migrations..."
    cd {{ $appDir }}/current
    docker-compose -f docker-compose-production.yml exec app php artisan migrate --force
@endtask

 @task('run_npm')
    echo "Running npm..."
    cd {{ $appDir }}/current
    docker compose -f docker-compose-production.yml run --rm --service-ports npm run production
@endtask
