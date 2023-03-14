@setup
    require __DIR__.'/vendor/autoload.php';

    echo "1";

    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);

    echo "2";

    try {
        $dotenv->load();

        echo "3";

        $dotenv->required(['DEPLOY_USER', 'DEPLOY_SERVER', 'DEPLOY_BRANCH', 'DEPLOY_REPO', 'DEPLOY_BASE_DIR'])->notEmpty();

        echo "4";

    } catch( Exception $e)  {
        echo "Environments error: " . $e->getMessage();
    }

    echo "5";

    $branch = env('DEPLOY_BRANCH');
    $repository = env('REPOSITORY');
    $appDir = env('DEPLOY_BASE_DIR');
    $releasesDir = $appDir . '/releases';
    $release = date('YmdHis');
    $newReleaseDir = $releasesDir .'/'. $release;

    echo "6";
@endsetup

@servers(['prod' => env('DEPLOY_USER').'@'.env('DEPLOY_SERVER')])

@story('deploy')
    echo "7";
    testing_environments
@endstory

@task('testing_environments')
    echo "8";
    echo 'Testing deploy host: ' . env('DEPLOY_HOST')
    echo 'Testing branch: ' . env('BRANCH')
    echo 'Testing repository: ' . env('REPOSITORY')
    echo 'Testing app dir: ' . env('APP_DIR')
@endtask
