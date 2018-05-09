<?php
use App\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{

    public function run()
    {
        DB::table('users')->delete();

        $users = [
            [
                'name' => 'John Smith',
                'email' => 'john@test.com',
                'password' => Hash::make('qwerty'),
                'role' => 'admin',
                'resources' => '{
                 "home" :  ["index"],
                 "items" :  ["index", "view", "create", "delete"]
            }'
            ],
            [
                'name' => 'George Best',
                'email' => 'george@test.com',
                'password' => Hash::make('qwerty'),
                'role' => 'viewer',
                'resources' => '{
                 "home" :  ["index"],
                 "items" :  ["index", "view"]
            }'
            ],
            [

                'name' => 'Sam Best',
                'email' => 'sam@test.com',
                'password' => Hash::make('qwerty'),
                'role' => 'user',
                'resources' => '{
                 "home" :  ["index"]
            }'
            ]
        ];

        foreach ($users as $k => $user) {
            User::create($user);
        }
    }
}