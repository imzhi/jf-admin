<?php

namespace Imzhi\JFAdmin\Console;

use Illuminate\Console\Command;
use Imzhi\JFAdmin\Models\AdminUser;

class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'jfadmin:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install the jf-admin package.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if (!file_exists(config_path('jfadmin.php'))) {
            $config = str_replace(base_path(), '', config_path('jfadmin.php'));
            $this->comment("Install stop, file <{$config}> does not exist.");
            return;
        }

        $this->initDatabase();

        $this->initDirectory();

        $this->info('Install successfully.');
    }

    protected function initDatabase()
    {
        $this->call('migrate');

        if (AdminUser::count() !== 0) {
            $this->comment('Admin user already exist.');
        } else {
            $this->call('db:seed', ['--class' => \Imzhi\JFAdmin\Seeds\AdminSeeder::class]);
        }

        $this->info('Init database successfully.');
    }

    protected function initDirectory()
    {
        $directory = config('jfadmin.directory');

        $this->makeDir($directory);

        $this->makeDir("{$directory}/Controllers");

        $this->createFile("{$directory}/Controllers/HomeController.php", 'HomeController', function ($content) {
            return str_replace('DummyNamespace', config('jfadmin.route.namespace'), $content);
        });

        $this->createFile("{$directory}/routes.php", 'routes');
    }

    protected function makeDir($path)
    {
        $result = $this->laravel['files']->makeDirectory($path, 0755, true, true);
        $directory = str_replace(base_path(), '', $path);
        if (!$result) {
            $this->comment("Directory <{$directory}> already exist.");
            return false;
        }

        $this->info("Init directory <{$directory}> successfully.");
        return true;
    }

    protected function createFile($path, $stub, $callback = null)
    {
        $file = str_replace(base_path(), '', $path);
        if (file_exists($path)) {
            $this->comment("File <{$file}> already exist.");
            return false;
        }

        $file_content = $this->getStub($stub);
        $file_content = $callback ? $callback($file_content) : $file_content;
        $this->laravel['files']->put($path, $file_content);

        $this->info("Init file <{$file}> successfully.");
        return true;
    }

    protected function getStub($name)
    {
        return $this->laravel['files']->get(__DIR__ . "/stubs/{$name}.stub");
    }
}
