<?php

namespace Tests\Unit\Services\Seller;

use App\Models\MercadoLivre;
use App\Models\MercadoLivreNotification;
use App\Services\Seller\MercadoLivreNotificationService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Request;
use Tests\TestCase;

/**
 * @group ServiceTest
 */
class MercadoLivreNotificationServiceTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private $service;

    public function setUp(): void
    {
        parent::setUp();

        $this->service = new MercadoLivreNotificationService(new MercadoLivreNotification());
    }

    /** @test  */
    public function should_list_items_by_user()
    {
        $mercadolivre = MercadoLivre::factory()->create([
            'mel_after_sales_enabled' => 1,
        ]);
        MercadoLivreNotification::factory()->count(2)->create([
            'men_user_id' => $mercadolivre->mel_user_id,
            'men_topic' => 'payments',
            'men_send_message' => 0,
        ]);

        $response = $this->service->findByUser();

        $this->assertCount(2, $response);
        $this->assertInstanceOf(Collection::class, $response);
    }

    /** @test  */
    public function should_store_item()
    {
        $mercadolivre = MercadoLivre::factory()->create();
        MercadoLivreNotification::factory()
            ->count(2)
            ->for($mercadolivre)
            ->create();
        $request = Request::create('/', 'POST', [
            'resource' => $this->faker->word,
            'user_id' => $mercadolivre->mel_user_id,
            'topic' => 'questions',
            'application_id' => $this->faker->randomNumber(6),
            'attempts' => $this->faker->randomNumber(1),
            'sent' => $this->faker->date,
            'received' => $this->faker->date,
        ]);

        $this->service->store($request);

        $this->assertCount(3, $mercadolivre->notifications);
    }
}
