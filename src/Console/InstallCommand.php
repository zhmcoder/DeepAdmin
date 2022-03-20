<?php

namespace Andruby\DeepAdmin\Console;

use Andruby\DeepAdmin\AdminServiceProvider;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class InstallCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'deep_admin:install {cmd}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install the deep-admin package.cmd:\n
                              db install database;\n
                              admin create admin files\n
                              publish publish resource and config files';

    /**
     * Install directory.
     *
     * @var string
     */
    protected $directory = '';

    protected $cmd = '';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->cmd = $this->argument('cmd');
        $this->info( $this->cmd);
        if($this->cmd=='db'){
            $this->initDatabase();
        }

        if($this->cmd=='admin'){
            $this->initAdminDirectory();
        }
        if($this->cmd=='publish'){
            $this->initResources();
        }


    }

    /**
     * Create tables and seed it.
     *
     * @return void
     */
    public function initDatabase()
    {
//        $this->call('migrate');
//
//        $userModel = config('deep_admin.database.users_model');
//
//        if ($userModel::count() == 0) {
//            $this->call('db:seed', ['--class' => \Andruby\DeepAdmin\Auth\Database\AdminTablesSeeder::class]);
//        }
//        DB::statement(file_get_contents($this->laravel['files']->get(__DIR__ . "/stubs/deep_admin.sql")));

        DB::unprepared($this->laravel['files']->get(__DIR__ . "/stubs/deep_admin.sql"));
    }

    /**
     * Initialize the admAin directory.
     *
     * @return void
     */
    protected function initAdminDirectory()
    {
        $this->directory = config('deep_admin.directory');

        if (is_dir($this->directory)) {
            $this->line("<error>{$this->directory} directory already exists !</error> ");

            return;
        }

        $this->makeDir('/');
        $this->line('<info>Admin directory was created:</info> ' . str_replace(base_path(), '', $this->directory));

        $this->makeDir('Controllers');

        $this->createHomeController();
        $this->createAuthController();

        $this->createBootstrapFile();
        $this->createRoutesFile();
    }

    /**
     * Create HomeController.
     *
     * @return void
     */
    public function createHomeController()
    {
        $homeController = $this->directory . '/Controllers/HomeController.php';
        $contents = $this->getStub('HomeController');

        $this->laravel['files']->put(
            $homeController,
            str_replace('DummyNamespace', config('deep_admin.route.namespace'), $contents)
        );
        $this->line('<info>HomeController file was created:</info> ' . str_replace(base_path(), '', $homeController));
    }

    /**
     * Create AuthController.
     *
     * @return void
     */
    public function createAuthController()
    {
        $authController = $this->directory . '/Controllers/AuthController.php';
        $contents = $this->getStub('AuthController');

        $this->laravel['files']->put(
            $authController,
            str_replace('DummyNamespace', config('deep_admin.route.namespace'), $contents)
        );
        $this->line('<info>AuthController file was created:</info> ' . str_replace(base_path(), '', $authController));
    }


    protected function createBootstrapFile()
    {
        $file = $this->directory . '/bootstrap.php';

        $contents = $this->getStub('bootstrap');
        $this->laravel['files']->put($file, $contents);
        $this->line('<info>Bootstrap file was created:</info> ' . str_replace(base_path(), '', $file));
    }

    /**
     * Create routes file.
     *
     * @return void
     */
    protected function createRoutesFile()
    {
        $file = $this->directory . '/routes.php';
        $contents = $this->getStub('routes');
        $this->laravel['files']->put($file, str_replace('DummyNamespace', config('deep_admin.route.namespace'), $contents));
        $this->line('<info>Routes file was created:</info> ' . str_replace(base_path(), '', $file));
    }

    /**
     * Get stub contents.
     *
     * @param $name
     *
     * @return string
     */
    protected function getStub($name)
    {
        return $this->laravel['files']->get(__DIR__ . "/stubs/$name.stub");
    }

    /**
     * Make new directory.
     *
     * @param string $path
     */
    protected function makeDir($path = '')
    {
        $this->laravel['files']->makeDirectory("{$this->directory}/$path", 0755, true, true);
    }

    private function initResources()
    {
        $this->call('vendor:publish', ['--provider' => AdminServiceProvider::class]);
    }
}
