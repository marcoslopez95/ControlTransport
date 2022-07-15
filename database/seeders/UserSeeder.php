<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role_id = (Role::firstWhere('name','Administrador'))->id;
        if(!User::firstWhere('email','admin@controltransport.com')){
            User::create([
                'first_name'    => 'Admin',
                'last_name'     => 'Admin',
                'email'         => 'admin@controltransport.com',
                'password'      => Hash::make('admin123admin'),
                'role_id'       => $role_id
            ]);
        }
        if(!User::firstWhere('email','user@controltransport.com')){
            User::create([
                'first_name'    => 'User',
                'last_name'     => 'User',
                'email'         => 'user@controltransport.com',
                'password'      => 'user123user',
                'role_id'       => $role_id
            ]);
        }
    }
}
