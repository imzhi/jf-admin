<?php

namespace Imzhi\InspiniaAdmin\Commands;

use Illuminate\Console\Command;
use Imzhi\InspiniaAdmin\Models\AdminUser;

class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'inspinia-admin:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'install the inspinia-admin package';

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
        $this->initDatabase();

        $this->initDirectory();

        $this->info('install successfully');
    }

    protected function initDatabase()
    {
        $this->call('migrate');

        if (AdminUser::count() !== 0) {
            $this->comment('database already init');
            return false;
        }

        $this->call('db:seed', ['--class' => \Imzhi\InspiniaAdmin\Seeds\AdminSeeder::class]);

        $this->info('database init successfully');
        return true;
    }

    protected function initDirectory()
    {
        $directory = app_path('Admin');

        $this->makeDir($directory);

        $this->makeDir("{$directory}/Controllers");

        $this->createFile("{$directory}/routes.php", 'routes');

        $this->createFile("{$directory}/Controllers/HomeController.php", 'HomeController');
    }

    protected function makeDir($path = '')
    {
        $result = $this->laravel['files']->makeDirectory($path, 0755, true, true);
        $directory = str_replace(base_path(), '', $path);
        if (!$result) {
            $this->comment("directory \"{$directory}\" already init");
            return false;
        }

        $this->info("directory \"{$directory}\" init successfully");
        return true;
    }

    protected function createFile($path, $stub)
    {
        $file = str_replace(base_path(), '', $path);
        if (file_exists($path)) {
            $this->comment("file \"{$file}\" already init");
            return false;
        }

        $this->laravel['files']->put($path, $this->getStub($stub));

        $this->info("file \"{$file}\" init successfully");
        return true;
    }

    protected function getStub($name)
    {
        return $this->laravel['files']->get(__DIR__ . "/stubs/{$name}.stub");
    }
}
