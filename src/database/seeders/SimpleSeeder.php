<?php

namespace Database\Seeders;

use App\Models\Banner;
use App\Models\Brand;
use App\Models\Company;
use App\Models\Contact;
use App\Models\Faq;
use App\Models\Feature;
use App\Models\Newsletter;
use App\Models\Voucher;
use Illuminate\Database\Seeder;

class SimpleSeeder extends Seeder
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
    }
}
