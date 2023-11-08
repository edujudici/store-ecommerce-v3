<?php

namespace Database\Seeders;

use App\Models\MercadoLivre;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class SellersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        MercadoLivre::factory()
            ->count(2)
            ->state(new Sequence(
                ['mel_user_id' => 1],
                ['mel_user_id' => 2],
            ))
            ->create();
    }
}
