<?php

namespace Imzhi\JFAdmin\Seeds;

use Carbon\Carbon;
use Imzhi\JFAdmin\Models\Role;
use Illuminate\Database\Seeder;
use Imzhi\JFAdmin\Models\AdminUser;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        AdminUser::truncate();
        $user = AdminUser::create([
            'name' => 'admin',
            'password' => bcrypt('admin'),
        ]);

        $role = Role::firstOrCreate([
            'name' => config('jf-admin.super_role'),
            'guard_name' => 'admin_user',
        ]);

        $user->assignRole($role);
    }
}
