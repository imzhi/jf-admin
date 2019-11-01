<?php

namespace Imzhi\JFAdmin\Console;

use Illuminate\Console\Command;
use Imzhi\JFAdmin\Models\AdminUser;

class ResetPasswordCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'jfadmin:reset-password';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset password for admin user.';

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
                $this->error("Admin name \"{$anticipate}\" does not exist.");
            }
        } while ($repeat);

        do {
            $secret = $this->secret("Please enter password for \"{$anticipate}\".");
            $repeat = strlen($secret) < 6;
            if ($repeat) {
                $this->error('Password must not be less than 6 characters, please re-enter password.');
                continue;
            }
            $repeat = $secret !== $this->secret("Please enter confirm password for \"{$anticipate}\".");
            if ($repeat) {
                $this->error('Repeated password is inconsistent, please re-enter password.');
            }
        } while ($repeat);

        $user->password = bcrypt($secret);
        $user->save();

        $this->info('Reset password successfully.');
    }
}
