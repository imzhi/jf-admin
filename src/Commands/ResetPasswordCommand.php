<?php

namespace Imzhi\InspiniaAdmin\Commands;

use Illuminate\Console\Command;
use Imzhi\InspiniaAdmin\Models\AdminUser;

class ResetPasswordCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'inspinia-admin:reset-password';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'reset password for admin user';

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
        $users = AdminUser::all(['id', 'name']);
        $users_data = $users->pluck('name')->all();
        do {
            $anticipate = $this->anticipate('please enter admin name', $users_data, reset($users_data));
            $user = $users->first(function ($item) use ($anticipate) {
                return $item->name === $anticipate;
            });
            $repeat = !$user;
            if ($repeat) {
                $this->error("admin name \"{$anticipate}\" does not exist");
            }
        } while ($repeat);

        do {
            $secret = $this->secret("please enter password for \"{$anticipate}\"");
            $repeat = strlen($secret) < 6;
            if ($repeat) {
                $this->error('password must not be less than 6 characters, please re-enter password');
                continue;
            }
            $repeat = $secret !== $this->secret("please enter confirm password for \"{$anticipate}\"");
            if ($repeat) {
                $this->error('inconsistent password, please re-enter password');
            }
        } while ($repeat);

        $user->password = bcrypt($secret);
        $user->save();

        $this->info('reset password successfully');
    }
}
