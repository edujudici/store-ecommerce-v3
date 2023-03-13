@servers(['web' => env('DEPLOY_HOST')])

@setup
    $branch = env('BRANCH');
    $repository = env('REPOSITORY');
    $app_dir = env('APP_DIR');
    $releases_dir = $app_dir . '/releases';
    $release = date('YmdHis');
    $new_release_dir = $releases_dir .'/'. $release;
@endsetup

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
    cd {{ $app_dir }}/current
    docker-compose -f docker-compose-production.yml down
@endtask

@task('clone_repository')
    echo 'Cloning repository'
    [ -d {{ $releases_dir }} ] || mkdir {{ $releases_dir }}
    git clone --depth 1 --single-branch --branch {{ $branch }} {{ $repository }} {{ $new_release_dir }}
    cd {{ $new_release_dir }}
    git reset --hard {{ $commit }}
@endtask

@task('update_symlinks')
    echo "Remove storage folder"
    rm -rf {{ $new_release_dir }}/storage

    echo 'Copy storage and env inside new version'
    cp -r {{ $app_dir }}/shared/storage {{ $new_release_dir }}
    cp {{ $app_dir }}/shared/.env {{ $new_release_dir }}

    echo 'Linking current release'
    ln -nfs {{ $new_release_dir }} {{ $app_dir }}/current
@endtask

{{--  @task('fix_permissions')
    echo "Setting file and folder permissions"
    cd {{ $app_dir }}/current
    ./setup.sh
@endtask  --}}

@task('start_containeres')
    echo 'Start all services containeres'
    cd {{ $app_dir }}/current
    docker-compose -f docker-compose-production.yml up -d --build
    sleep 15
@endtask

@task('run_composer')
    echo "Starting deployment ({{ $release }})"
    cd {{ $app_dir }}/current
    docker-compose -f docker-compose-production.yml exec app composer install --no-interaction --prefer-dist --optimize-autoloader --no-dev
@endtask

@task('run_migrations')
    echo "Running migrations..."
    cd {{ $app_dir }}/current
    docker-compose -f docker-compose-production.yml exec app php artisan migrate --force
@endtask

 @task('run_npm')
    echo "Running npm..."
    cd {{ $app_dir }}/current
    docker compose -f docker-compose-production.yml run --rm --service-ports npm run production
@endtask
