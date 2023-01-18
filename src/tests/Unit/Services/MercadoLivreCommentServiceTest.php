<?php

namespace Tests\Unit\Services;

use App\Api\MercadoLibre;
use App\Exceptions\BusinessError;
use App\Models\MercadoLivre;
use App\Models\MercadoLivreComment;
use App\Models\Product;
use App\Services\MercadoLivreCommentService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Request;
use Tests\TestCase;

/**
 * @group ServiceTest
 */
class MercadoLivreCommentServiceTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function setUp(): void
    {
        parent::setUp();

        $this->apiMercadoLibreMock = $this->mock(MercadoLibre::class)
            ->makePartial();

        $this->service = new MercadoLivreCommentService(
            $this->apiMercadoLibreMock,
            new MercadoLivre(),
            new MercadoLivreComment()
        );
    }

    /** @test  */
    public function should_list_items()
    {
        MercadoLivreComment::factory()->count(3)->create();

        $request = Request::create('/', 'POST', []);

        $response = $this->service->index($request);

        $this->assertIsArray($response);
        $this->assertArrayHasKey('comments', $response);
        $this->assertArrayHasKey('pagination', $response);
        $this->assertCount(3, $response['comments']);
    }

    /** @test  */
    public function should_list_items_filtered()
    {
        MercadoLivreComment::factory()->count(3)->create([
            'mec_from_id' => 1,
            'mec_item_id' => 2
        ]);

        $request = Request::create('/', 'POST', [
            'from' => 1,
            'item' => 2
        ]);

        $response = $this->service->index($request);

        $this->assertIsArray($response);
        $this->assertArrayNotHasKey('comments', $response);
        $this->assertArrayNotHasKey('pagination', $response);
        $this->assertCount(3, $response);
    }

    /** @test  */
    public function should_questions_not_answered()
    {
        MercadoLivreComment::factory()->count(3)->create([
            'mec_status' => 'UNANSWERED'
        ]);

        $response = $this->service->questionsNotAnswered();

        $this->assertInstanceOf(Collection::class, $response);
        $this->assertCount(3, $response);
    }

    /** @test  */
    public function should_exists_comment()
    {
        $id = 123;
        MercadoLivreComment::factory()->create([
            'mec_id_secondary' => $id
        ]);

        $response = $this->service->existsComment($id);

        $this->assertTrue($response);
    }

    /** @test  */
    public function should_store_item()
    {
        $mercadolivre = MercadoLivre::factory()->create();
        $product = Product::factory()->create();

        $mockQuestion = $this->mockQuestion(
            $mercadolivre->mel_user_id,
            $product->pro_sku
        );
        $loadDate = date('Y-m-d H:i:s');

        $response = $this->service->store($mockQuestion, $loadDate);

        $this->assertInstanceOf(MercadoLivreComment::class, $response);
        $this->assertEquals(
            $mercadolivre->mel_user_id,
            $response->mec_seller_id
        );
        $this->assertEquals($product->pro_sku, $response->mec_item_id);
        $this->assertEquals(123, $response->mec_from_id);
    }

    /** @test  */
    public function should_answer_item()
    {
        $comment = MercadoLivreComment::factory()->create();
        $request = Request::create('/', 'POST', [
            'id' => $comment->mec_id,
            'text' => 'Minha resposta de teste'
        ]);

        $this->apiMercadoLibreMock->shouldReceive('answerQuestion')
            ->once()
            ->andReturn($this->mockAnswerQuestion($request->input('text')));

        $this->service->answer($request);

        $comment = MercadoLivreComment::find($request->input('id'));

        $this->assertEquals($request->input('text'), $comment->mec_answer_text);
        $this->assertEquals('ANSWERED', $comment->mec_status);
    }

    /** @test  */
    public function should_destroy_item()
    {
        $comment = MercadoLivreComment::factory()->create();
        $request = Request::create('/', 'POST', [
            'id' => $comment->mec_id
        ]);

        $this->apiMercadoLibreMock->shouldReceive('deleteQuestions')
            ->once();

        $response = $this->service->destroy($request);

        $this->assertTrue($response);
    }

    /** @test  */
    public function should_update_question_not_found()
    {
        $mockQuestionNotFound = $this->mockQuestionNotFound();
        $comment = MercadoLivreComment::factory()->create();

        $this->service->update($mockQuestionNotFound, $comment);

        $comment = MercadoLivreComment::find($comment->mec_id);

        $this->assertNull($comment);
    }

    /** @test  */
    public function should_update_question_exception()
    {
        $this->expectException(BusinessError::class);

        $this->service->update(null, null);
    }

    private function mockQuestion($mlId, $sku)
    {
        $data = [
            'answer' => [
                'date_created' => $this->faker->date,
                'status' => 'ACTIVE',
                'text' => $this->faker->word
            ],
            'date_created' => $this->faker->date,
            'item_id' => $sku,
            'seller_id' => $mlId,
            'status' => 'ANSWERED',
            'text' => $this->faker->word,
            'id' => $this->faker->randomNumber(9),
            'deleted_from_listing' => 0,
            'hold' => 0,
            'from' => [
                'id' => 123
            ]
        ];

        return json_decode(json_encode($data));
    }

    private function mockAnswerQuestion($text)
    {
        $data = [
            'status' => 'ANSWERED',
            'answer' => [
                'date_created' => $this->faker->date,
                'status' => 'ACTIVE',
                'text' => $text
            ]
        ];

        return json_decode(json_encode($data));
    }

    private function mockQuestionNotFound()
    {
        $data = [
            'status' => 404,
        ];

        return json_decode(json_encode($data));
    }
}
