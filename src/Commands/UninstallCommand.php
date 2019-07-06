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
    protected $signature = 'jf-admin:uninstall';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'uninstall the jf-admin package';

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
        if (!$this->confirm('confirm uninstall jf-admin package?')) {
            return;
        }
        $this->delFile(config_path('admin.php'));

        $this->delDir(config('jf-admin.directory'));

        $this->delDir(public_path('vendor/jf-admin'));

        $this->info('uninstall successfully');
    }

    protected function delDir($path)
    {
        $result = $this->laravel['files']->deleteDirectory($path);
        $directory = str_replace(base_path(), '', $path);
        if (!$result) {
            $this->comment("directory \"{$directory}\" delete failed");
            return false;
        }

        $this->info("directory \"{$directory}\" delete successfully");
        return true;
    }

    protected function delFile($path)
    {
        $result = $this->laravel['files']->delete($path);
        $file = str_replace(base_path(), '', $path);
        if (!$result) {
            $this->comment("file \"{$file}\" delete failed");
            return false;
        }

        $this->info("file \"{$file}\" delete successfully");
        return true;
    }
}
