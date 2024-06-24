<?php

namespace App\Services\Seller;

use App\Api\MercadoLibre;
use App\Exceptions\BusinessError;
use App\Jobs\LoadQuestion;
use App\Services\BaseService;
use Exception;
use Illuminate\Http\Request;

class LoadQuestionService extends BaseService
{
    private const LIMIT = 50;

    private $apiMercadoLibre;
    private $mercadoLivreService;
    private $mercadoLivreCommentService;
    private $mercadoLivreUserService;
    private $mercadoLivreProductService;
    private $loadQuestionHistoryService;

    public function __construct(
        MercadoLibre $apiMercadoLibre,
        MercadoLivreService $mercadoLivreService,
        MercadoLivreCommentService $mercadoLivreCommentService,
        MercadoLivreUserService $mercadoLivreUserService,
        MercadoLivreProductService $mercadoLivreProductService,
        LoadQuestionHistoryService $loadQuestionHistoryService
    ) {
        $this->apiMercadoLibre = $apiMercadoLibre;
        $this->mercadoLivreService = $mercadoLivreService;
        $this->mercadoLivreCommentService = $mercadoLivreCommentService;
        $this->mercadoLivreUserService = $mercadoLivreUserService;
        $this->mercadoLivreProductService = $mercadoLivreProductService;
        $this->loadQuestionHistoryService = $loadQuestionHistoryService;
    }

    public function dispatchQuestions(): void
    {
        debug('Requested via command questions:load');

        $loadDate = date('Y-m-d H:i:s');
        $offset = 0;
        $accounts = $this->mercadoLivreService->index();
        foreach ($accounts as $account) {
            if (!$account->mel_user_id) {
                continue;
            }
            $data = $this->apiMercadoLibre->getQuestions($account, 0, 1);
            $total = $data->total ?? 0;
            $history = $this->loadQuestionHistoryService->store(
                $loadDate,
                $total,
                0,
                $account->mel_id,
                $account->mel_title
            );
            if ($total > 0) {
                do {
                    LoadQuestion::dispatch(
                        $offset,
                        $loadDate,
                        $account->mel_id,
                        $history->lqh_id
                    )->onQueue('questions');
                    $offset += self::LIMIT;
                } while ($offset < $total);
            } else {
                debug(['Não existe novos comentários' => $data]);
            }
        }
    }

    public function loadQuestions($offset, $loadDate, $mlId, $histId): void
    {
        debug('Job LoadQuestion on date ' . $loadDate . ' and offset ' . $offset
            . ' to mercado livre account ' . $mlId);

        $newRequest = Request::create('/', 'POST', [
            'id' => $mlId,
        ]);
        $account = $this->mercadoLivreService->findById($newRequest);
        $data = $this->apiMercadoLibre->getQuestions($account, $offset);
        if (isset($data->questions)) {
            $totalSync = 0;
            foreach ($data->questions as $value) {
                $exists = $this->mercadoLivreCommentService->existsComment(
                    $value->id
                );
                if (!$exists) {
                    $comment = $this->mercadoLivreCommentService
                        ->store($value, $loadDate);
                    $this->loadOtherQuestions($mlId, $loadDate, $value);
                    $this->loadUserData($comment, $value);
                    $this->loadProductData($comment, $value);
                    $totalSync += 1;
                }
            }
            $this->loadQuestionHistoryService->update($histId, $totalSync);
            return;
        }
        throw new Exception(json_encode($data));
    }

    public function loadQuestionsAnswered()
    {
        debug('Requested via command questions:answered');
        $comments = $this->mercadoLivreCommentService->questionsNotAnswered();
        foreach ($comments as $comment) {
            $question = $this->apiMercadoLibre->getQuestion(
                $comment->mercadolivre,
                $comment->mec_id_secondary
            );
            try {
                $this->mercadoLivreCommentService->update(
                    $question,
                    $comment,
                    false
                );
            } catch (BusinessError $exc) {
                debug($exc->getMessage());
                continue;
            }
        }
    }

    private function loadOtherQuestions($accountId, $date, $question): void
    {
        debug('request other questions to: ' . $question->from->id
            . ' and item: ' . $question->item_id . ' on date: ' . $date);

        $newRequest = Request::create('/', 'POST', [
            'id' => $accountId,
        ]);
        $account = $this->mercadoLivreService->findById($newRequest);
        $data = $this->apiMercadoLibre->getQuestionsFilter(
            $account,
            $question->item_id,
            $question->from->id
        );
        if (isset($data->questions)) {
            foreach ($data->questions as $value) {
                $exists = $this->mercadoLivreCommentService->existsComment(
                    $value->id
                );
                if (!$exists) {
                    $this->mercadoLivreCommentService->store($value, $date);
                }
            }
            return;
        }
        throw new Exception(json_encode($data));
    }

    private function loadUserData($comment, $params)
    {
        debug('load user data: ' . $params->from->id);
        $exists = $this->mercadoLivreUserService->exists($params->from->id);
        if (!$exists) {
            $data = $this->apiMercadoLibre->getUserDetails($params->from->id);
            $this->mercadoLivreUserService->store($comment, $data);
        }
    }

    private function loadProductData($comment, $params)
    {
        debug('load product data: ' . $params->item_id);
        $exists = $this->mercadoLivreProductService->exists(
            $params->item_id
        );
        if (!$exists) {
            $data = $this->apiMercadoLibre->getSingleProduct($params->item_id);
            $this->mercadoLivreProductService->store($comment, $data);
        }
    }
}
