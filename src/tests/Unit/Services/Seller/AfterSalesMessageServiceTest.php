<?php

namespace Tests\Unit\Services\Seller;

use App\Api\MercadoLibre;
use App\Jobs\AfterSalesMessage;
use App\Models\MercadoLivre;
use App\Models\MercadoLivreNotification;
use App\Services\Seller\AfterSalesMessageService;
use App\Services\Seller\MercadoLivreNotificationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

/**
 * @group ServiceTest
 */
class AfterSalesMessageServiceTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private $apiMercadoLibreMock;
    private $mlNotificationService;
    private $service;

    public function setUp(): void
    {
        parent::setUp();

        $this->apiMercadoLibreMock = $this->mock(MercadoLibre::class)
            ->makePartial();
        $this->mlNotificationService = $this
            ->mock(MercadoLivreNotificationService::class)
            ->makePartial();

        $this->service = new AfterSalesMessageService(
            $this->apiMercadoLibreMock,
            $this->mlNotificationService
        );
    }

    /** @test  */
    public function should_dispatch_notifications()
    {
        Queue::fake();

        $notifications = MercadoLivreNotification::factory()
            ->count(2)
            ->create();
        $this->mlNotificationService->shouldReceive('findByUser')
            ->once()
            ->andReturn($notifications);


        $this->service->send();

        Queue::assertPushedOn('aftersales-message', AfterSalesMessage::class);
        Queue::assertPushed(AfterSalesMessage::class, 2);

    }

    /** @test  */
    public function should_send_message()
    {
        $mercadolivre = MercadoLivre::factory()->create([
            'mel_after_sales_enabled' => 1,
        ]);
        $notification = MercadoLivreNotification::factory()->create([
            'men_user_id' => $mercadolivre->mel_user_id,
        ]);

        $this->apiMercadoLibreMock->shouldReceive('searchByUrl')
            ->once()
            ->andReturn($this->mockNotification());

        $this->apiMercadoLibreMock->shouldReceive('getUserDetails')
            ->once()
            ->andReturn($this->mockUser());

        $this->apiMercadoLibreMock->shouldReceive('afterSalesMessage')
            ->once();

        $this->service->sendMessage($notification);
    }

    private function mockNotification()
    {
        return json_decode(json_encode([
            'status' => 'approved',
            'payer' => [
                'id' => 'abc123'
            ],
            'order_id' => 'xyz999'
        ]));
    }

    private function mockUser()
    {
        return json_decode(json_encode([
            'nickname' => 'ABC123',
            'id' => 'abc123',
        ]));
    }
}
