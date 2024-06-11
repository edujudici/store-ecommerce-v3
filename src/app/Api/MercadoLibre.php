<?php

namespace App\Api;

use App\Models\MercadoLivre;
use App\Traits\MakeCurl;

class MercadoLibre
{
    use MakeCurl;

    private const ML = 'https://api.mercadolibre.com';
    private const ML_ITEMS = '/items';
    private const ML_ITEMS_SEARCH = '/items/search?';
    private const ML_SITES = '/sites/MLB/search?';
    private const ML_CATEGORIES = '/categories/';
    private const ML_OAUTH = '/oauth/token?';
    private const ML_DESCRIPTION = '/description';
    private const ML_QUESTIONS = '/questions/';
    private const ML_USERS = '/users/';
    private const ML_USERS_ME = '/users/me';
    private const ML_ANSWERS = '/answers';
    private const ML_MESSAGES = '/messages/packs/PCK_ID/sellers/SEL_ID';
    private const REFRESH_TOKEN = 'refresh_token';
    private const AUTHORIZATION_CODE = 'authorization_code';
    private const STATUS_NOT_AUTH = 401;
    private const STATUS_INVALID_GRANT = 400;
    private const ITEMS_ATTRIBUTES = ['id', 'title', 'price', 'seller_id', 'category_id', 'condition', 'permalink', 'thumbnail', 'secure_thumbnail', 'accepts_mercadopago', 'sold_quantity', 'status',];

    private $mercadoLivre;

    public function __construct(MercadoLivre $mercadoLivre)
    {
        $this->mercadoLivre = $mercadoLivre;
    }

    public function getSingleProduct($sku, $attributes = [])
    {
        $url = self::ML . self::ML_ITEMS . '/' . $sku
            . '?attributes='
            . implode(
                ',',
                array_merge($attributes, self::ITEMS_ATTRIBUTES)
            );
        return json_decode(self::runCurl($url));
    }

    public function getMultipleProducts($model, $offset = 0, $limit = 50)
    {
        $params = http_build_query([
            'offset' => $offset,
            'limit' => $limit,
            'status' => 'active',
        ]);
        $url = self::ML . self::ML_USERS . $model->mel_user_id . self::ML_ITEMS_SEARCH . $params;
        $response = json_decode(self::runCurl($url, [
            'bearerKey' => $model->mel_access_token,
        ]));
        if ($this->hasStatus($response, self::STATUS_NOT_AUTH)) {
            $resToken = $this->refreshToken($model);
            if (!$this->hasStatus($resToken, self::STATUS_INVALID_GRANT)) {
                return $this->getMultipleProducts($model, $offset, $limit);
            }
        }
        return $response;
    }

    public function getMultipleProductsDetails($model, $skus, $attributes = [])
    {
        $url = self::ML . self::ML_ITEMS
            . '?ids=' . implode(',', $skus)
            . '&attributes='
            . implode(
                ',',
                array_merge($attributes, self::ITEMS_ATTRIBUTES)
            );
        $response = json_decode(self::runCurl($url, [
            'bearerKey' => $model->mel_access_token,
        ]));
        if ($this->hasStatus($response, self::STATUS_NOT_AUTH)) {
            $resToken = $this->refreshToken($model);
            if (!$this->hasStatus($resToken, self::STATUS_INVALID_GRANT)) {
                return $this->getMultipleProductsDetails($model, $skus, $attributes);
            }
        }
        return $response;
    }

    public function getDetailCategory($categoryId)
    {
        $url = self::ML . self::ML_CATEGORIES . $categoryId;
        return json_decode(self::runCurl($url));
    }

    public function getDescriptionProduct($sku)
    {
        $url = self::ML . self::ML_ITEMS . '/' . $sku . self::ML_DESCRIPTION;
        return json_decode(self::runCurl($url));
    }

    public function getQuestion($model, $questionId)
    {
        $url = self::ML . self::ML_QUESTIONS . $questionId;
        $response = json_decode(self::runCurl($url, [
            'bearerKey' => $model->mel_access_token,
        ]));
        if ($this->hasStatus($response, self::STATUS_NOT_AUTH)) {
            $resToken = $this->refreshToken($model);
            if (!$this->hasStatus($resToken, self::STATUS_INVALID_GRANT)) {
                return $this->deleteQuestions($model, $questionId);
            }
        }
        return $response;
    }

    public function getQuestionsFilter($mlAccountId, $item, $fromId)
    {
        return $this->getQuestions(0, $mlAccountId, 50, http_build_query([
            'api_version' => 4,
            'item' => $item,
            'from' => $fromId,
        ]));
    }

    public function getQuestions($offset, $accountId, $limit = 50, $data = null)
    {
        $model = $this->findById($accountId);
        $params = !is_null($data) ? $data : http_build_query([
            'seller_id' => $model->mel_user_id,
            'sort_fields' => 'date_created',
            'sort_types' => 'DESC',
            'status' => 'UNANSWERED',
            'api_version' => 4,
            'offset' => $offset,
            'limit' => $limit,
        ]);
        $url = self::ML . self::ML_QUESTIONS . 'search?' . $params;
        $response = json_decode(self::runCurl($url, [
            'bearerKey' => $model->mel_access_token,
        ]));
        if ($this->hasStatus($response, self::STATUS_NOT_AUTH)) {
            $resToken = $this->refreshToken($model);
            if (!$this->hasStatus($resToken, self::STATUS_INVALID_GRANT)) {
                return $this->getQuestions($offset, $accountId, $limit, $data);
            }
        }
        return $response;
    }

    public function answerQuestion($model, $questionId, $text)
    {
        $url = self::ML . self::ML_ANSWERS;
        $params = json_encode([
            'question_id' => $questionId,
            'text' => $text,
        ]);
        $response = json_decode(self::runCurl($url, [
            'postFields' => $params,
            'bearerKey' => $model->mel_access_token,
        ]));
        if ($this->hasStatus($response, self::STATUS_NOT_AUTH)) {
            $resToken = $this->refreshToken($model);
            if (!$this->hasStatus($resToken, self::STATUS_INVALID_GRANT)) {
                return $this->answerQuestion($model, $questionId, $text);
            }
        }

        return $response;
    }

    public function deleteQuestions($model, $questionId)
    {
        $url = self::ML . self::ML_QUESTIONS . $questionId;
        $response = json_decode(self::runCurl($url, [
            'customRequest' => 'DELETE',
            'bearerKey' => $model->mel_access_token,
        ]));
        if ($this->hasStatus($response, self::STATUS_NOT_AUTH)) {
            $resToken = $this->refreshToken($model);
            if (!$this->hasStatus($resToken, self::STATUS_INVALID_GRANT)) {
                return $this->deleteQuestions($model, $questionId);
            }
        }
        return $response;
    }

    public function getUserDetails($userId)
    {
        $url = self::ML . self::ML_USERS . $userId;
        return json_decode(self::runCurl($url));
    }

    public function getMyUserDetails($model)
    {
        $url = self::ML . self::ML_USERS_ME;
        $response = json_decode(self::runCurl($url, [
            'bearerKey' => $model->mel_access_token,
        ]));
        if ($this->hasStatus($response, self::STATUS_NOT_AUTH)) {
            $resToken = $this->refreshToken($model);
            if (!$this->hasStatus($resToken, self::STATUS_INVALID_GRANT)) {
                return $this->getMyUserDetails($model);
            }
        }
        return $response;
    }

    public function getUserDetailsByNickname($nickname)
    {
        $nickname = urlencode($nickname);
        $url = self::ML . self::ML_SITES . "nickname={$nickname}";
        return json_decode(self::runCurl($url));
    }

    public function afterSalesMessage($model, $packId, $to, $text)
    {
        $fakeParams = ['PCK_ID', 'SEL_ID'];
        $params = [$packId, $model->mel_user_id];
        $url = self::ML . str_replace($fakeParams, $params, self::ML_MESSAGES);
        $params = json_encode([
            'from' => [
                'user_id' => $model->mel_user_id,
            ],
            'to' => [
                'user_id' => $to,
            ],
            'text' => $text,
        ]);
        $response = json_decode(self::runCurl($url, [
            'postFields' => $params,
            'bearerKey' => $model->mel_access_token,
        ]));
        if ($this->hasStatus($response, self::STATUS_NOT_AUTH)) {
            $resToken = $this->refreshToken($model);
            if (!$this->hasStatus($resToken, self::STATUS_INVALID_GRANT)) {
                return $this->answerQuestion($model, $to, $text);
            }
        }

        return $response;
    }

    public function searchByUrl($model, $url)
    {
        $url = self::ML . $url;
        $response = json_decode(self::runCurl($url, [
            'bearerKey' => $model->mel_access_token,
        ]));
        if ($this->hasStatus($response, self::STATUS_NOT_AUTH)) {
            $resToken = $this->refreshToken($model);
            if (!$this->hasStatus($resToken, self::STATUS_INVALID_GRANT)) {
                return $this->searchByUrl($model, $url);
            }
        }
        return $response;
    }

    public function accessToken($model)
    {
        $url = self::ML . self::ML_OAUTH;
        $params = http_build_query([
            'grant_type' => self::AUTHORIZATION_CODE,
            'client_id' => env('MERCADO_LIVRE_CLIENT_ID'),
            'client_secret' => env('MERCADO_LIVRE_CLIENT_SECRET'),
            'redirect_uri' => env('MERCADO_LIVRE_REDIRECT_URI'),
            'code' => $model->mel_code_tg,
        ]);
        $response = json_decode(self::runCurl($url, [
            'postFields' => $params,
        ]));
        $this->update($model, $response);

        return $response;
    }

    private function refreshToken($model)
    {
        $url = self::ML . self::ML_OAUTH;
        $params = http_build_query([
            'grant_type' => self::REFRESH_TOKEN,
            'client_id' => env('MERCADO_LIVRE_CLIENT_ID'),
            'client_secret' => env('MERCADO_LIVRE_CLIENT_SECRET'),
            'refresh_token' => $model->mel_refresh_token,
        ]);
        $response = json_decode(self::runCurl($url, [
            'postFields' => $params,
        ]));
        $this->update($model, $response);

        return $response;
    }

    private function update($model, $response): void
    {
        if (
            !$this->hasStatus($response, self::STATUS_INVALID_GRANT)
            && isset($response->access_token)
        ) {
            $accessToken = $response->access_token;
            $tokenType = $response->token_type;
            $expiresIn = $response->expires_in;
            $scope = $response->scope;
            $userId = $response->user_id;
            $refreshToken = $response->refresh_token ?? null;
            $model->update([
                'mel_access_token' => $accessToken,
                'mel_token_type' => $tokenType,
                'mel_expires_in' => $expiresIn,
                'mel_scope' => $scope,
                'mel_user_id' => $userId,
                'mel_refresh_token' => $refreshToken,
            ]);
        }
    }

    private function hasStatus($response, $status): bool
    {
        return isset($response->status)
            && $response->status === $status;
    }

    private function findById($id): MercadoLivre
    {
        return $this->mercadoLivre->findOrFail($id);
    }
}
