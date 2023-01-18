<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SimplePagesTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_pages_success()
    {
        /**
         * Routes website
         */
        $this->get('/')->assertStatus(200);
        $this->get('/')->assertViewIs('site.home');

        $this->get('/shop')->assertStatus(200);
        $this->get('/shop')->assertViewIs('site.shop');

        $this->get('/cart')->assertStatus(200);
        $this->get('/cart')->assertViewIs('site.cart');

        $this->get('/checkout')->assertStatus(302);
        $this->get('/checkout')->assertRedirect('/shopper/login');

        $this->get('/faq')->assertStatus(200);
        $this->get('/faq')->assertViewIs('site.faq');

        $this->get('/contact')->assertStatus(200);
        $this->get('/contact')->assertViewIs('site.contact');
    }
}
