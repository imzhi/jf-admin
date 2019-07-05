<?php

namespace Imzhi\InspiniaAdmin\Seeds;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Imzhi\InspiniaAdmin\Models\Role;
use Imzhi\InspiniaAdmin\Models\AdminUser;

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
            'name' => '超级管理员',
            'guard_name' => 'admin_user',
        ]);

        $user->assignRole($role);
    }
}
