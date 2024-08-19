<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductRelated;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Category::factory()
            ->count(3)
            ->state(new Sequence(
                ['cat_id_secondary' => 1],
                ['cat_id_secondary' => 2],
                ['cat_id_secondary' => 3],
            ))
            ->create();

        Product::factory()
            ->count(15)
            ->state(new Sequence(
                fn($sequence) => ['cat_id' => Category::all()->random()],
            ))
            ->state(new Sequence(
                ['pro_seller_id' => 1],
                ['pro_seller_id' => 2],
            ))
            ->has(
                ProductRelated::factory()
                    ->count(3)
                    ->state(function (array $attributes, Product $product) {
                        return [
                            'pro_sku_related' => $product->pro_sku
                        ];
                    }),
                'productsRelated'
            )
            ->hasExclusiveDeal(1)
            ->hasVisited(1)
            ->hasSpecifications(3)
            ->hasPictures(3)
            ->hasComments(3)
            ->create(['pro_category_id' => null]);
    }
}
