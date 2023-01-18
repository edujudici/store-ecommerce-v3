<?php

namespace App\Services;

use App\Api\MercadoLibre;
use App\Exceptions\BusinessError;
use App\Models\MercadoLivre;
use App\Models\MercadoLivreComment;
use Illuminate\Database\Eloquent\Collection;

class MercadoLivreCommentService extends BaseService
{
    private const STATUS_NOT_FOUND = 404;

    private $mercadoLibre;
    private $mercadoLivre;
    private $mercadoLivreComment;

    public function __construct(
        MercadoLibre $mercadoLibre,
        MercadoLivre $mercadoLivre,
        MercadoLivreComment $mercadoLivreComment
    ) {
        $this->mercadoLibre = $mercadoLibre;
        $this->mercadoLivre = $mercadoLivre;
        $this->mercadoLivreComment = $mercadoLivreComment;
    }

    public function index($request): array
    {
        $query = $this->mercadoLivreComment
            ->with(['mercadolivre', 'user', 'product'])
            ->orderBy('mec_date_created');

        if ($request->has('from') && $request->has('item')) {
            return $query->where('mec_from_id', $request->input('from'))
                ->where('mec_item_id', $request->input('item'))
                ->get()
                ->toArray();
        }

        $comments = $query
            ->where('mec_status', '=', 'UNANSWERED')
            ->paginate($request->input('amount', 12))
            ->onEachSide(1);
        return [
            'comments' => isset($comments->toArray()['data'])
                ? $comments->toArray()['data']
                : [],
            'pagination' => (string) $comments
                ->setPath('')
                ->links(),
        ];
    }

    public function questionsNotAnswered(): Collection
    {
        return $this->mercadoLivreComment
            ->with(['mercadolivre'])
            ->where('mec_status', '=', 'UNANSWERED')
            ->get();
    }

    public function existsComment($id)
    {
        return $this->mercadoLivreComment
            ->where('mec_id_secondary', $id)
            ->exists();
    }

    public function store($question, $loadDate): MercadoLivreComment
    {
        $mercadoLivre = $this->mercadoLivre
            ->where('mel_user_id', $question->seller_id)
            ->firstOrFail();
        return $mercadoLivre->comments()->create(
            $this->prepareQuestions($question, $loadDate)
        );
    }

    public function answer($request): void
    {
        $this->_validate($request->all(), [
            'id' => 'required',
            'text' => 'required|string',
        ], [
            'id' => 'Id da Pergunta',
            'text' => 'Resposta',
        ]);
        $comment = $this->mercadoLivreComment->findOrFail(
            $request->input('id')
        );
        $response = $this->mercadoLibre->answerQuestion(
            $comment->mercadolivre,
            $comment->mec_id_secondary,
            $request->input('text')
        );
        $this->update($response, $comment);
    }

    public function destroy($request): bool
    {
        $comment = $this->mercadoLivreComment->findOrFail(
            $request->input('id')
        );
        $this->mercadoLibre->deleteQuestions(
            $comment->mercadolivre,
            $comment->mec_id_secondary
        );
        return $comment->delete();
    }

    public function update($response, $comment, $answerLocal = true): void
    {
        if (isset($response->status) && isset($response->answer)) {
            $comment->update([
                'mec_status' => $response->status,
                'mec_answer_local' => $answerLocal,
                'mec_answer_date' => date('Y-m-d H:i:s', strtotime(
                    $response->answer->date_created
                )),
                'mec_answer_status' => $response->answer->status,
                'mec_answer_text' => $response->answer->text,
            ]);
        } else {
            if (isset($response->status)
                && $response->status === self::STATUS_NOT_FOUND) {
                $comment->delete();
                return;
            }
            throw new BusinessError(
                'Ocorreu um erro ao sincronizar resposta desta pergunta.'
            );
        }
    }

    private function prepareQuestions($question, $loadDate): array
    {
        $answer = $question->answer;
        return [
            'mec_date_created' => date(
                'Y-m-d H:i:s',
                strtotime($question->date_created)
            ),
            'mec_item_id' => $question->item_id,
            'mec_seller_id' => $question->seller_id,
            'mec_status' => $question->status,
            'mec_text' => $question->text,
            'mec_id_secondary' => $question->id,
            'mec_deleted_from_listing' => $question->deleted_from_listing,
            'mec_hold' => $question->hold,
            'mec_answer_date' => $answer
                ? date('Y-m-d H:i:s', strtotime($answer->date_created)) : null,
            'mec_answer_status' => $answer ? $answer->status : null,
            'mec_answer_text' => $answer ? $answer->text : null,
            'mec_from_id' => $question->from->id,
            'mec_load_date' => $loadDate,
        ];
    }
}
