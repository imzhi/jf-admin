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
        if (AdminUser::count() !== 0) {
            $this->comment('already installed');
            return false;
        }

        $this->call('db:seed', ['--class' => \Imzhi\InspiniaAdmin\Seeds\AdminSeeder::class]);

        $this->info('install successfully');
    }
}
