<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory()
            ->hasAddresses(1)
            ->create([
                'uuid' => Str::uuid(),
                'role' => 'admin',
                'name' => 'admin',
                'email' => 'admin@admin.com',
                'password' => bcrypt('admin123')
            ]);

        User::factory()
            ->hasAddresses(1)
            ->create([
                'uuid' => Str::uuid(),
                'role' => 'shopper',
                'name' => 'shopper',
                'email' => 'shopper@shopper.com',
                'password'  => bcrypt('shopper123')
            ]);

        User::factory()
            ->hasAddresses(1)
            ->create([
                'uuid' => Str::uuid(),
                'role' => 'api',
                'name' => 'sanctum',
                'email' => 'sanctum@sanctum.com',
                'password'  => bcrypt('sanctum123')
            ]);
    }
}
