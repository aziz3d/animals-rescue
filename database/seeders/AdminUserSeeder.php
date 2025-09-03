<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        \App\Models\User::firstOrCreate(
            ['email' => 'sunrise300@gmail.com'],
            [
                'name' => 'aziz3d',
                'email' => 'sunrise300@gmail.com',
                'password' => \Illuminate\Support\Facades\Hash::make('azizkhan'),
                'email_verified_at' => now(),
            ]
        );

        $this->command->info('Admin user created: sunrise300@gmail.com / azizkhan');
    }
}
