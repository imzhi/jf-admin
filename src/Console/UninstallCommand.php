<?php

namespace Imzhi\JFAdmin\Console;

use Illuminate\Console\Command;

class UninstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'jfadmin:uninstall';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Uninstall the jf-admin package.';

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
        if (!$this->confirm('Confirm uninstall jf-admin package?')) {
            return;
        }

        if (!file_exists(config_path('jfadmin.php'))) {
            $this->comment('Uninstall already.');
            return;
        }

        $this->delFile(config_path('jfadmin.php'));

        $this->delDir(config('jfadmin.directory'));

        $this->delDir(public_path('vendor/jfadmin'));

        $this->delDir(resource_path('lang/vendor/jfadmin'));

        $this->delDir(resource_path('views/vendor/jfadmin'));

        $this->info('Uninstall successfully.');
    }

    protected function delDir($path)
    {
        $result = $this->laravel['files']->deleteDirectory($path);
        $directory = str_replace(base_path(), '', $path);
        if (!$result) {
            $this->comment("Delete directory <{$directory}> failed.");
            return false;
        }

        $this->info("Delete directory <{$directory}> successfully.");
        return true;
    }

    protected function delFile($path)
    {
        $result = $this->laravel['files']->delete($path);
        $file = str_replace(base_path(), '', $path);
        if (!$result) {
            $this->comment("Delete file <{$file}> failed.");
            return false;
        }

        $this->info("Delete file <{$file}> successfully.");
        return true;
    }
}
