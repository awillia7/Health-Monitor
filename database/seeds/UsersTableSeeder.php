<?php

use Illuminate\Database\Seeder;
use App\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = ["ADMIN"];
        
        $admin = new User;
        $admin->name = 'ellucian';
        $admin->username = 'ellucian';
        $admin->guid = '7f9902dd-d1d2-407e-a372-cf8c42516099';
        $admin->domain = 'default';
        $admin->roles = $roles;
        $admin->save();
    }
}
