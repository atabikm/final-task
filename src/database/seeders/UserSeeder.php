<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $user1 = new User();
        $user1->name = 'Mutawakil';
        $user1->email = 'biki@here';
        $user1->password = Hash::make('pw');
        $user1->save();

        $user2 = new User();
        $user2->name = 'Admin Chat';
        $user2->email = 'admin@example.com';
        $user2->password = Hash::make('password');
        $user2->save();
    }
}