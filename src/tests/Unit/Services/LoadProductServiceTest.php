<?php

namespace Tests\Unit\Services;

use App\Models\Category;
use App\Models\Product;
use App\Services\LoadProductService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;

/**
 * @group ServiceTest
 */
class LoadProductServiceTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private $service;

    public function setUp(): void
    {
        parent::setUp();

        $this->service = new LoadProductService(new Product());
    }

    /** @test  */
    public function should_store_item()
    {
        $category = Category::factory()->create();
        $request = [
            'cat_id' => $category->cat_id,
            'pro_price' => $this->faker->randomFloat(2, 1, 100),
            'pro_oldprice' => $this->faker->randomFloat(2, 1, 100),
            'pro_title' => $this->faker->title,
            'pro_description' => $this->faker->title,
            'pro_description_long' => $this->faker->title,
            'pro_image' => $this->faker->url,
            'pro_sku' => 'MLB9999999999',
            'pro_seller_id' => $this->faker->randomNumber(9),
            'pro_category_id' => 'MLB99999',
            'pro_condition' => 'new',
            'pro_permalink' => $this->faker->url,
            'pro_thumbnail' => $this->faker->url,
            'pro_secure_thumbnail' => $this->faker->url,
            'pro_accepts_merc_pago' => '1',
            'pro_load_date' => $this->faker->date(),
            'pro_sold_quantity' => $this->faker->randomNumber(9),
        ];

        $response = $this->service->store($request);

        $this->assertInstanceOf(Product::class, $response);
        $this->assertEquals($request['pro_sku'], $response->pro_sku);
    }

    /** @test  */
    public function should_save_many_items()
    {
        $category = Category::factory()->create();
        $request = [
            'cat_id' => $category->cat_id,
            'pro_price' => $this->faker->randomFloat(2, 1, 100),
            'pro_oldprice' => $this->faker->randomFloat(2, 1, 100),
            'pro_title' => $this->faker->title,
            'pro_description' => $this->faker->title,
            'pro_description_long' => $this->faker->title,
            'pro_image' => $this->faker->url,
            'pro_sku' => 'MLB9999999999',
            'pro_seller_id' => $this->faker->randomNumber(9),
            'pro_category_id' => $category->cat_id_secondary,
            'pro_condition' => 'new',
            'pro_permalink' => $this->faker->url,
            'pro_thumbnail' => $this->faker->url,
            'pro_secure_thumbnail' => $this->faker->url,
            'pro_accepts_merc_pago' => '1',
            'pro_load_date' => $this->faker->date(),
            'pro_sold_quantity' => $this->faker->randomNumber(9),
        ];

        $this->service->storeProducts($request);

        $this->assertCount(1, $category->productsML);
        $this->assertInstanceOf(Collection::class, $category->productsML);
    }

    /** @test  */
    public function should_destroy_item()
    {
        $category = Category::factory()->create();
        Product::factory()
            ->count(3)
            ->for($category)
            ->state([
                'pro_category_id' => $category->cat_id_secondary
            ])
            ->create();

        $this->service->destroy('2020-01-01');

        $this->assertCount(3, $category->productsML);
        $this->assertInstanceOf(Collection::class, $category->productsML);
    }
}
