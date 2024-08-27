<?php

namespace App\Services\MerchantCenter;

use App\Api\ApiMerchantCenter;
use App\Models\MerchantCenter;
use App\Services\BaseService;
use Exception;

class MerchantCenterService extends BaseService
{
    private $merchantCenter;
    private $apiMerchantCenter;

    public function __construct(
        MerchantCenter $merchantCenter,
        ApiMerchantCenter $apiMerchantCenter,
    ) {
        $this->merchantCenter = $merchantCenter;
        $this->apiMerchantCenter = $apiMerchantCenter;
    }

    public function firstMerchantCenter(): MerchantCenter|null
    {
        return $this->merchantCenter->first();
    }

    public function auth($request): MerchantCenter
    {
        $code = $request->input('code');

        $response = $this->apiMerchantCenter->accessToken($code);

        return $this->store($response, $code);
    }

    public function index(): array
    {
        $merchantCenter = $this->firstMerchantCenter();
        $response = $this->apiMerchantCenter->allProducts($merchantCenter->mec_access_token);

        if ($this->isUnauthorized($response)) {
            return $this->refreshTokenAndRetry('index');
        }

        return $response;
    }

    public function findById($productId): mixed
    {
        $merchantCenter = $this->firstMerchantCenter();
        $response = $this->apiMerchantCenter->getProduct($merchantCenter->mec_access_token, $productId);

        if ($this->isUnauthorized($response)) {
            return $this->refreshTokenAndRetry('findById', $productId);
        }

        return $response;
    }

    public function addProduct($params): array
    {
        $merchantCenter = $this->firstMerchantCenter();
        $token = $merchantCenter->mec_access_token;

        $data = [
            'offerId' => $params['offerId'],
            'title' => $params['title'],
            'description' => $params['description'],
            'link' => $params['link'],
            'imageLink' => $params['imageLink'],
            'additionalImageLinks' => $params['additionalImageLinks'],
            'price' => [
                'value' => $params['price'],
                'currency' => 'BRL'
            ],
            'productTypes' => [
                'Artigos de festa',
                'Lembrancinhas'
            ],
            'contentLanguage' => 'pt',
            'targetCountry' => 'BR',
            'feedLabel' => 'BR',
            'channel' => 'online',
            'availability' => 'in stock',
            'condition' => 'new',
            'shipping' => [
                [
                    'country' => 'BR'
                ]
            ],
        ];

        $response = $this->apiMerchantCenter->addProduct($token, $data);

        if ($this->isUnauthorized($response)) {
            return $this->refreshTokenAndRetry('addProduct', $params);
        }

        return $response;
    }

    public function destroy($productId): mixed
    {
        $merchantCenter = $this->firstMerchantCenter();
        $response = $this->apiMerchantCenter->deleteProduct($merchantCenter->mec_access_token, $productId);

        if ($this->isUnauthorized($response)) {
            return $this->refreshTokenAndRetry('destroy', $productId);
        }

        return $response;
    }

    private function store($response, $code): MerchantCenter
    {
        $params = [
            'mec_token_type' => $response->token_type,
            'mec_expires_in' => $response->expires_in,
            'mec_access_token' => $response->access_token,
            'mec_refresh_token' => $response->refresh_token,
            'mec_authorize_code' => $code,
        ];

        $merchantCenter = $this->firstMerchantCenter();

        return $merchantCenter
            ? tap($merchantCenter)->update($params)
            : $this->merchantCenter->create($params);
    }

    private function isUnauthorized(array $response): bool
    {
        return isset($response['error']['code']) && $response['error']['code'] === 401;
    }

    private function refreshTokenAndRetry(string $methodName, ...$params): mixed
    {
        debug("Merchant Center refresh token to method {$methodName}");
        $merchantCenter = $this->firstMerchantCenter();
        $response = $this->apiMerchantCenter->refreshToken($merchantCenter->mec_refresh_token);

        if (isset($response->access_token)) {
            $this->merchantCenter->update([
                'mec_token_type' => $response->token_type,
                'mec_expires_in' => $response->expires_in,
                'mec_access_token' => $response->access_token,
            ]);

            return call_user_func_array([$this, $methodName], $params);
        } else {
            throw new Exception('Failed to refresh Merchant Center token');
        }
    }
}
