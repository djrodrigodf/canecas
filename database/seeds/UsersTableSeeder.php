<?php

use App\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        $users = [
            [
                'id'             => 1,
                'name'           => 'Admin',
                'email'          => 'admin@admin.com',
                'password'       => '$2y$10$pTLKjRVmmUE/yrYj7CZQDu2V3q7s.KVGmoNYl6UI3ubbrnr.NsJ/e',
                'remember_token' => null,
            ],
        ];

        User::insert($users);
    }
}
