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
        $admin->name = 'hlthcertapi';
        $admin->username = 'hlthcertapi';
        $admin->guid = '63579ece-ba3b-4d5c-900c-9f917f29635d';
        $admin->domain = 'default';
        $admin->roles = $roles;
        $admin->save();
    }
}
