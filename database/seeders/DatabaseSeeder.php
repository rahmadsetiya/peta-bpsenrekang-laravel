<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $users = [
            ['username' => 'admin',          'role' => 'ADMIN'],
            ['username' => 'tyo',            'role' => 'ADMIN'],
            ['username' => 'ipds',           'role' => 'IPDS'],
            ['username' => 'subject_matter', 'role' => 'SUBJECT_MATTER'],
            ['username' => 'guest',          'role' => 'GUEST'],
        ];

        foreach ($users as $data) {
            User::firstOrCreate(
                ['username' => $data['username']],
                [
                    'id' => Str::uuid(),
                    'role' => $data['role'],
                    'password' => Hash::make('password'),
                ]
            );
        }
    }
}
