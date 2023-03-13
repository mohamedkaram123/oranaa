<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Hash;

class UserSeader extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $user = new User();
        $user->name = "mohamed karam";
        $user->email = "admin@admin.com";
        $user->user_type = "admin";
        $user->password = Hash::make("admin") ;
        $user->save();
    }
}
