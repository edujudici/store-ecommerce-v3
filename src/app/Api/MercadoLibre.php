<?php

namespace App\Api;

use App\Models\MercadoLivre;
use App\Traits\MakeCurl;

class MercadoLibre
{
    use MakeCurl;

    private const ML = 'https://api.mercadolibre.com';
    private const ML_ITEMS = self::ML . '/items';
    private const ML_SITES = self::ML . '/sites/MLB/search?';
    private const ML_CATEGORIES = self::ML . '/categories/';
    private const ML_OAUTH = self::ML . '/oauth/token?';
    private const ML_QUESTIONS = self::ML . '/questions/';
    private const ML_USERS = self::ML . '/users/';
    private const ML_USERS_ME = self::ML . '/users/me';
    private const ML_ANSWERS = self::ML . '/answers';
    private const ML_MESSAGES = self::ML . '/messages/packs/PCK_ID/sellers/SEL_ID';
    private const ITEMS_SEARCH = '/items/search?';
    private const DESCRIPTION = '/description';
    private const REFRESH_TOKEN = 'refresh_token';
    private const AUTHORIZATION_CODE = 'authorization_code';
    private const STATUS_NOT_AUTH = 401;
    private const STATUS_INVALID_GRANT = 400;
    private const ITEMS_ATTRIBUTES = ['id', 'title', 'price', 'seller_id', 'category_id', 'condition', 'permalink', 'thumbnail', 'secure_thumbnail', 'accepts_mercadopago', 'sold_quantity', 'status', 'item_relations',];

    private $mercadoLivre;

    public function __construct(MercadoLivre $mercadoLivre)
    {
        $this->mercadoLivre = $mercadoLivre;
    }

    /**
     * Gets product data according to the attribute list
     *
     * @param string $sku
     * @param array $attributes
     * @return mixed
     */
    public function getSingleProduct($sku, $attributes = []): mixed
    {
        $url = self::ML_ITEMS . '/' . $sku
            . '?attributes='
            . implode(
                ',',
                array_merge($attributes, self::ITEMS_ATTRIBUTES)
            );
        return json_decode(self::runCurl($url));
    }

    /**
     * Gets list of products according to the informed offset and limit
     *
     * @param \App\Models\MercadoLivre $model
     * @param integer $offset
     * @param integer $limit
     * @return mixed
     */
    public function getMultipleProducts(MercadoLivre $model, $offset = 0, $limit = 50): mixed
    {
        $params = http_build_query([
            'offset' => $offset,
            'limit' => $limit,
            'status' => 'active',
        ]);
        $url = self::ML_USERS . $model->mel_user_id . self::ITEMS_SEARCH . $params;
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

    /**
     * Gets products with details according to the attribute list
     *
     * @param \App\Models\MercadoLivre $model
     * @param array $skus
     * @param array $attributes
     * @return mixed
     */
    public function getMultipleProductsDetails(MercadoLivre $model, array $skus, $attributes = []): mixed
    {
        $url = self::ML_ITEMS
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

    /**
     * Obtain category details by id
     *
     * @param string|integer $categoryId
     * @return mixed
     */
    public function getDetailCategory($categoryId): mixed
    {
        $url = self::ML_CATEGORIES . $categoryId;
        return json_decode(self::runCurl($url));
    }

    /**
     * Obtain product description long by sku
     *
     * @param string $sku
     * @return mixed
     */
    public function getDescriptionProduct($sku): mixed
    {
        $url = self::ML_ITEMS . '/' . $sku . self::DESCRIPTION;
        return json_decode(self::runCurl($url));
    }

    /**
     * Get my user data
     *
     * @param \App\Models\MercadoLivre $model
     * @return mixed
     */
    public function getMyUserDetails(MercadoLivre $model): mixed
    {
        $response = json_decode(self::runCurl(self::ML_USERS_ME, [
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

    public function getUserDetails($userId)
    {
        $url = self::ML_USERS . $userId;
        return json_decode(self::runCurl($url));
    }

    public function getUserDetailsByNickname($nickname)
    {
        $nickname = urlencode($nickname);
        $url = self::ML_SITES . "nickname={$nickname}";
        return json_decode(self::runCurl($url));
    }

    /**
     * Send message for client after sales
     *
     * @param \App\Models\MercadoLivre $model
     * @param string|integer $packId
     * @param string|integer $to
     * @param string|integer $text
     * @return void
     */
    public function afterSalesMessage(MercadoLivre $model, $packId, $to, $text): void
    {
        $fakeParams = ['PCK_ID', 'SEL_ID'];
        $params = [$packId, $model->mel_user_id];
        $url = str_replace($fakeParams, $params, self::ML_MESSAGES);
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
                $this->afterSalesMessage($model, $packId, $to, $text);
            }
        }
    }

    /**
     * Get notification data by notification resource
     *
     * @param \App\Models\MercadoLivre $model
     * @param string $url
     * @return mixed
     */
    public function getNotificationResource(MercadoLivre $model, $url): mixed
    {
        $response = json_decode(self::runCurl(self::ML . $url, [
            'bearerKey' => $model->mel_access_token,
        ]));
        if ($this->hasStatus($response, self::STATUS_NOT_AUTH)) {
            $resToken = $this->refreshToken($model);
            if (!$this->hasStatus($resToken, self::STATUS_INVALID_GRANT)) {
                return $this->getNotificationResource($model, $url);
            }
        }
        return $response;
    }

    /**
     * Get question data by id
     *
     * @param \App\Models\MercadoLivre $model
     * @param string|integer $questionId
     * @return mixed
     */
    public function getQuestion(MercadoLivre $model, $questionId): mixed
    {
        $url = self::ML_QUESTIONS . $questionId;
        $response = json_decode(self::runCurl($url, [
            'bearerKey' => $model->mel_access_token,
        ]));
        if ($this->hasStatus($response, self::STATUS_NOT_AUTH)) {
            $resToken = $this->refreshToken($model);
            if (!$this->hasStatus($resToken, self::STATUS_INVALID_GRANT)) {
                return $this->getQuestion($model, $questionId);
            }
        }
        return $response;
    }

    /**
     * Filter child questions for the same user
     *
     * @param \App\Models\MercadoLivre $model
     * @param string|integer $item
     * @param string|integer $fromId
     * @return mixed
     */
    public function getQuestionsFilter($model, $item, $fromId): mixed
    {
        return $this->getQuestions($model, 0, 50, http_build_query([
            'api_version' => 4,
            'item' => $item,
            'from' => $fromId,
        ]));
    }

    /**
     * Gets list of questions according to the informed offset and limit
     *
     * @param \App\Models\MercadoLivre $model
     * @param integer $offset
     * @param integer $limit
     * @param mixed $data
     * @return mixed
     */
    public function getQuestions($model, $offset, $limit = 50, $data = null): mixed
    {
        $params = !is_null($data) ? $data : http_build_query([
            'seller_id' => $model->mel_user_id,
            'sort_fields' => 'date_created',
            'sort_types' => 'DESC',
            'status' => 'UNANSWERED',
            'api_version' => 4,
            'offset' => $offset,
            'limit' => $limit,
        ]);
        $url = self::ML_QUESTIONS . 'search?' . $params;
        $response = json_decode(self::runCurl($url, [
            'bearerKey' => $model->mel_access_token,
        ]));
        if ($this->hasStatus($response, self::STATUS_NOT_AUTH)) {
            $resToken = $this->refreshToken($model);
            if (!$this->hasStatus($resToken, self::STATUS_INVALID_GRANT)) {
                return $this->getQuestions($model, $offset, $limit, $data);
            }
        }
        return $response;
    }

    /**
     * Answering question according to specified id
     *
     * @param \App\Models\MercadoLivre $model
     * @param string|integer $questionId
     * @param string $text
     * @return mixed
     */
    public function answerQuestion(MercadoLivre $model, $questionId, $text): mixed
    {
        $params = json_encode([
            'question_id' => $questionId,
            'text' => $text,
        ]);
        $response = json_decode(self::runCurl(self::ML_ANSWERS, [
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

    /**
     * Delete question by id
     *
     * @param \App\Models\MercadoLivre $model
     * @param string|integer $questionId
     * @return void
     */
    public function deleteQuestion(MercadoLivre $model, $questionId): void
    {
        $url = self::ML_QUESTIONS . $questionId;
        $response = json_decode(self::runCurl($url, [
            'customRequest' => 'DELETE',
            'bearerKey' => $model->mel_access_token,
        ]));
        if ($this->hasStatus($response, self::STATUS_NOT_AUTH)) {
            $resToken = $this->refreshToken($model);
            if (!$this->hasStatus($resToken, self::STATUS_INVALID_GRANT)) {
                $this->deleteQuestion($model, $questionId);
            }
        }
    }

    /**
     * Get access token
     *
     * @param \App\Models\MercadoLivre $model
     * @return void
     */
    public function accessToken(MercadoLivre $model): void
    {
        $params = http_build_query([
            'grant_type' => self::AUTHORIZATION_CODE,
            'client_id' => env('MERCADO_LIVRE_CLIENT_ID'),
            'client_secret' => env('MERCADO_LIVRE_CLIENT_SECRET'),
            'redirect_uri' => env('MERCADO_LIVRE_REDIRECT_URI'),
            'code' => $model->mel_code_tg,
        ]);
        $response = json_decode(self::runCurl(self::ML_OAUTH, [
            'postFields' => $params,
        ]));
        $this->update($model, $response);
    }

    /**
     * Refresh token by account access
     *
     * @param \App\Models\MercadoLivre $model
     * @return mixed
     */
    private function refreshToken(MercadoLivre $model): mixed
    {
        $params = http_build_query([
            'grant_type' => self::REFRESH_TOKEN,
            'client_id' => env('MERCADO_LIVRE_CLIENT_ID'),
            'client_secret' => env('MERCADO_LIVRE_CLIENT_SECRET'),
            'refresh_token' => $model->mel_refresh_token,
        ]);
        $response = json_decode(self::runCurl(self::ML_OAUTH, [
            'postFields' => $params,
        ]));
        $this->update($model, $response);

        return $response;
    }

    /**
     * Update auth account access
     *
     * @param \App\Models\MercadoLivre $model
     * @param mixed $response
     * @return void
     */
    private function update(MercadoLivre $model, $response): void
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

    /**
     * Verify if status exists
     *
     * @param mixed $response
     * @param integer $status
     * @return boolean
     */
    private function hasStatus($response, $status): bool
    {
        return isset($response->status)
            && $response->status === $status;
    }
}
