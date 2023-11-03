<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\Banner;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Company;
use App\Models\Contact;
use App\Models\Faq;
use App\Models\ProductExclusive;
use App\Models\Feature;
use App\Models\MercadoLivre;
use App\Models\Newsletter;
use App\Models\Order;
use App\Models\OrderAddress;
use App\Models\OrderComment;
use App\Models\OrderHistory;
use App\Models\OrderItem;
use App\Models\Picture;
use App\Models\Product;
use App\Models\ProductComment;
use App\Models\ProductRelated;
use App\Models\ProductSpecification;
use App\Models\Voucher;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class FillAllSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Banner::factory()->count(5)->create();
        Brand::factory()->count(5)->create();
        Contact::factory()->count(5)->create();
        Feature::factory()->count(5)->create();
        Newsletter::factory()->count(5)->create();
        Faq::factory()->count(5)->create();
        Voucher::factory()->count(5)->create();
        Company::factory()->count(1)->create();
        MercadoLivre::factory()->count(1)->create();

        // create categories and products data
        $categories = Category::factory()
            ->count(6)
            ->has(
                Product::factory()
                    ->count(3)
                    ->state(function (array $attributes, Category $category) {
                        return [
                            'pro_category_id' => $category->cat_id_secondary
                        ];
                    })
                    ->has(ProductExclusive::factory(), 'exclusiveDeal')
                    ->has(
                        ProductRelated::factory()
                            ->count(5)
                            ->state(function (array $attributes, Product $product) {
                                return [
                                    'pro_sku_related' => $product->pro_sku
                                ];
                            }),
                        'productsRelated'
                    )
                    ->has(ProductSpecification::factory()->count(5), 'specifications')
                    ->has(Picture::factory()->count(5), 'pictures')
                    ->has(ProductComment::factory()->count(5), 'comments'),
                'products'
            )
            ->create();

        // create users data
        User::factory()
            ->count(3)
            ->has(Address::factory(), 'addresses')
            ->create();
        $shopper = User::factory()
            ->has(Address::factory(), 'addresses')
            ->create([
                'role' => 'shopper',
                'email' => 'shopper@shopper.com',
                'password'  => bcrypt('shopper123')
            ]);

        // create orders data by user
        Order::factory()
            ->count(5)
            ->has(
                OrderItem::factory()
                    ->count(5)
                    ->state([
                        'ori_pro_id' => $categories[0]->products[0]->pro_id,
                        'ori_pro_sku' => $categories[0]->products[0]->pro_sku
                    ]),
                'items'
            )
            ->has(OrderAddress::factory(), 'address')
            ->has(OrderComment::factory()->count(5), 'comments')
            ->has(
                OrderHistory::factory()
                    ->count(6)
                    ->state(new Sequence(
                        ['orh_collection_status' => Order::STATUS_NEW],
                        ['orh_collection_status' => Order::STATUS_PAYMENT_IN_PROCESS],
                        ['orh_collection_status' => Order::STATUS_PAID],
                        ['orh_collection_status' => Order::STATUS_PRODUCTION],
                        ['orh_collection_status' => Order::STATUS_TRANSPORT],
                        ['orh_collection_status' => Order::STATUS_COMPLETE],
                    )),
                'histories'
            )
            ->create([
                'user_id' => $shopper->id
            ]);
    }
}
