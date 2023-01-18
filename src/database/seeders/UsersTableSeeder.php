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
        User::create([
            'uuid'      => Str::uuid(),
            'role'      => 'admin',
            'name'      => 'admin',
            'email'     => 'admin@admin.com',
            'password'  => bcrypt('admin123'),
        ]);
    }
}
