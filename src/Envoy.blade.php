@setup
    require __DIR__.'/vendor/autoload.php';

    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);

    try {
        $dotenv->load();
        $dotenv->required(['DEPLOY_USER', 'DEPLOY_SERVER', 'DEPLOY_BRANCH', 'DEPLOY_REPO', 'DEPLOY_BASE_DIR'])->notEmpty();
    } catch( Exception $e)  {
        echo 'Environments error: ' . $e->getMessage();
    }

    $branch = env('DEPLOY_BRANCH');
    $repository = env('REPOSITORY');
    $appDir = env('DEPLOY_BASE_DIR');
    $releasesDir = $appDir . '/releases';
    $release = date('YmdHis');
    $newReleaseDir = $releasesDir .'/'. $release;

    function logMessage($message) {
        return "echo '\033[32m" .$message. "\033[0m';\n";
    }
@endsetup

@servers(['web' => env('DEPLOY_USER').'@'.env('DEPLOY_SERVER')])

@story('deploy')
    testing_environments
@endstory

@task('testing_environments')
    {{ logMessage("Testing environments") }}

    echo 'Testing deploy host: ' . env('DEPLOY_HOST')
    echo 'Testing branch: ' . env('BRANCH')
    echo 'Testing repository: ' . env('REPOSITORY')
    echo 'Testing app dir: ' . env('APP_DIR')
@endtask
