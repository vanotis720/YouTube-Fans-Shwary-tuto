<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        DB::transaction(function () {
            \App\Models\User::create([
                'name' => 'Yoshuwa Kafeda',
                'email' => 'Jkafeda@gmail.com',
                'email_verified_at' => now(),
                'password' => bcrypt('password@#$'),
                'role' => 'admin',
            ]);

            \App\Models\Account::create([
                'name' => 'Yoshuwa',
                'lastname' => 'Kafeda',
                'email' => 'Jkafeda@gmail.com',
                'phone' => '+243808893623',
                'status' => 'verified',
            ]);
        });
    }
}
