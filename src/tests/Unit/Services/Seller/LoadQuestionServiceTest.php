<?php

namespace Tests\Unit\Services\Seller;

use App\Api\MercadoLibre;
use App\Exceptions\BusinessError;
use App\Jobs\LoadQuestion;
use App\Models\LoadQuestionHistory;
use App\Models\MercadoLivre;
use App\Models\MercadoLivreComment;
use App\Services\Seller\LoadQuestionHistoryService;
use App\Services\Seller\LoadQuestionService;
use App\Services\Seller\MercadoLivreCommentService;
use App\Services\Seller\MercadoLivreProductService;
use App\Services\Seller\MercadoLivreService;
use App\Services\Seller\MercadoLivreUserService;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

/**
 * @group ServiceTest
 */
class LoadQuestionServiceTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private $apiMercadoLibreMock;
    private $mlServiceMock;
    private $mlCommentServiceMock;
    private $mlUserServiceMock;
    private $mlProductServiceMock;
    private $questHistServiceMock;
    private $service;

    public function setUp(): void
    {
        parent::setUp();

        $this->apiMercadoLibreMock = $this->mock(MercadoLibre::class)
            ->makePartial();
        $this->mlServiceMock = $this->mock(MercadoLivreService::class)
            ->makePartial();
        $this->mlCommentServiceMock = $this->mock(MercadoLivreCommentService::class)
            ->makePartial();
        $this->mlUserServiceMock = $this->mock(MercadoLivreUserService::class)
            ->makePartial();
        $this->mlProductServiceMock = $this->mock(MercadoLivreProductService::class)
            ->makePartial();
        $this->questHistServiceMock = $this->mock(LoadQuestionHistoryService::class)
            ->makePartial();

        $this->service = new LoadQuestionService(
            $this->apiMercadoLibreMock,
            $this->mlServiceMock,
            $this->mlCommentServiceMock,
            $this->mlUserServiceMock,
            $this->mlProductServiceMock,
            $this->questHistServiceMock
        );
    }

    /** @test  */
    public function should_dispatch_questions_not_comments()
    {
        $accounts = MercadoLivre::factory()->count(2)->create();
        $accounts[0]->mel_user_id = null;

        $this->mlServiceMock->shouldReceive('index')
            ->once()
            ->andReturn($accounts);

        $this->apiMercadoLibreMock->shouldReceive('getQuestions')
            ->once()
            ->andReturn($this->mockQuestionEmpty());

        $this->questHistServiceMock->shouldReceive('store')
            ->once();

        $this->service->dispatchQuestions();
    }

    /** @test  */
    public function should_dispatch_questions()
    {
        Queue::fake();

        $accounts = MercadoLivre::factory()->count(2)->create();
        $accounts[0]->mel_user_id = null;

        $history = LoadQuestionHistory::factory()->create();

        $this->mlServiceMock->shouldReceive('index')
            ->once()
            ->andReturn($accounts);

        $this->apiMercadoLibreMock->shouldReceive('getQuestions')
            ->once()
            ->andReturn($this->mockQuestion());

        $this->questHistServiceMock->shouldReceive('store')
            ->once()
            ->andReturn($history);

        $this->service->dispatchQuestions();

        Queue::assertPushedOn('questions', LoadQuestion::class);
        Queue::assertPushed(LoadQuestion::class, 2);
    }

    /** @test  */
    public function should_load_questions_exception()
    {
        $this->expectException(Exception::class);

        $mercadolivre = MercadoLivre::factory()->create();
        $history = LoadQuestionHistory::factory()->create();
        $offset = 0;
        $loadDate = date('Y-m-d H:i:s');
        $mlId = $mercadolivre->mel_id;
        $histId = $history->lqh_id;

        $this->apiMercadoLibreMock->shouldReceive('getQuestions')
            ->once()
            ->andReturn(null);

        $this->service->loadQuestions($offset, $loadDate, $mlId, $histId);
    }

    /** @test  */
    public function should_load_other_questions_exception()
    {
        $this->expectException(Exception::class);

        $mercadolivre = MercadoLivre::factory()->create();
        $history = LoadQuestionHistory::factory()->create();
        $comment = MercadoLivreComment::factory()->create();
        $offset = 0;
        $loadDate = date('Y-m-d H:i:s');
        $mlId = $mercadolivre->mel_id;
        $histId = $history->lqh_id;

        $this->apiMercadoLibreMock->shouldReceive('getQuestions')
            ->once()
            ->andReturn($this->mockQuestion());

        $this->mlCommentServiceMock->shouldReceive('existsComment')
            ->once()
            ->andReturn(false);

        $this->mlCommentServiceMock->shouldReceive('store')
            ->once()
            ->andReturn($comment);

        $this->apiMercadoLibreMock->shouldReceive('getQuestionsFilter')
            ->once()
            ->andReturn(null);

        $this->service->loadQuestions($offset, $loadDate, $mlId, $histId);
    }

    /** @test  */
    public function should_load_questions()
    {
        $mercadolivre = MercadoLivre::factory()->create();
        $history = LoadQuestionHistory::factory()->create();
        $comment = MercadoLivreComment::factory()->create();
        $offset = 0;
        $loadDate = date('Y-m-d H:i:s');
        $mlId = $mercadolivre->mel_id;
        $histId = $history->lqh_id;

        $this->apiMercadoLibreMock->shouldReceive('getQuestions')
            ->once()
            ->andReturn($this->mockQuestion());

        $this->mlCommentServiceMock->shouldReceive('existsComment')
            ->twice()
            ->andReturn(false);

        $this->mlCommentServiceMock->shouldReceive('store')
            ->twice()
            ->andReturn($comment);

        $this->apiMercadoLibreMock->shouldReceive('getQuestionsFilter')
            ->once()
            ->andReturn($this->mockQuestion());

        $this->mlUserServiceMock->shouldReceive('exists')
            ->once()
            ->andReturn(false);

        $this->apiMercadoLibreMock->shouldReceive('getUserDetails')
            ->once();

        $this->mlUserServiceMock->shouldReceive('store')
            ->once();

        $this->mlProductServiceMock->shouldReceive('exists')
            ->once()
            ->andReturn(false);

        $this->apiMercadoLibreMock->shouldReceive('getSingleProduct')
            ->once();

        $this->mlProductServiceMock->shouldReceive('store')
            ->once();

        $this->questHistServiceMock->shouldReceive('update')
            ->once();

        $this->service->loadQuestions($offset, $loadDate, $mlId, $histId);
    }

    /** @test  */
    public function should_load_questions_answered_exception()
    {
        $comments = MercadoLivreComment::factory()->count(1)->create();
        $this->mlCommentServiceMock->shouldReceive('questionsNotAnswered')
            ->once()
            ->andReturn($comments);

        $this->apiMercadoLibreMock->shouldReceive('getQuestion')
            ->once();

        $this->mlCommentServiceMock->shouldReceive('update')
            ->once()
            ->andThrow(new BusinessError());

        $this->service->loadQuestionsAnswered();
    }

    private function mockQuestionEmpty()
    {
        $data = [
            'total' => 0
        ];

        return json_decode(json_encode($data));
    }

    private function mockQuestion()
    {
        $data = [
            'total' => 60,
            'questions' => [
                [
                    'id' => 1,
                    'from' => [
                        'id' => 1
                    ],
                    'item_id' => 1
                ]
            ]
        ];

        return json_decode(json_encode($data));
    }
}
