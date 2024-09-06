<?php

namespace App\Services\MerchantCenter;

use App\Api\ApiMerchantCenter;
use App\Exceptions\BusinessError;
use App\Models\MerchantCenter;
use App\Services\BaseService;

class MerchantCenterService extends BaseService
{
    private $merchantCenter;
    private $apiMerchantCenter;
    private $apiMerchantCenterID;

    private const METHOD_TYPE_INSERT = 'insert';
    private const METHOD_TYPE_UPDATE = 'update';

    public function __construct(
        MerchantCenter $merchantCenter,
        ApiMerchantCenter $apiMerchantCenter,
    ) {
        $this->merchantCenter = $merchantCenter;
        $this->apiMerchantCenter = $apiMerchantCenter;
        $this->apiMerchantCenterID = env('API_GOOGLE_MERCHANT_ID');
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

    public function addProduct($params): array
    {
        $merchantCenter = $this->firstMerchantCenter();
        $token = $merchantCenter->mec_access_token;

        $data = $this->fillProductData($params, self::METHOD_TYPE_INSERT);

        $response = $this->apiMerchantCenter->addProduct($token, $data);

        if ($this->isUnauthorized($response)) {
            return $this->refreshTokenAndRetry('addProduct', $params);
        }

        return $response;
    }

    public function findById($productId): array
    {
        $merchantCenter = $this->firstMerchantCenter();
        $response = $this->apiMerchantCenter->getProduct($merchantCenter->mec_access_token, $productId);

        if ($this->isUnauthorized($response)) {
            return $this->refreshTokenAndRetry('findById', $productId);
        }

        return $response;
    }

    public function updateProduct($productId, $params): array
    {
        $merchantCenter = $this->firstMerchantCenter();
        $token = $merchantCenter->mec_access_token;

        $data = $this->fillProductData($params, self::METHOD_TYPE_UPDATE);

        $response = $this->apiMerchantCenter->updateProduct($token, $productId, $data);

        if ($this->isUnauthorized($response)) {
            return $this->refreshTokenAndRetry('updateProduct', $productId, $params);
        }

        return $response;
    }

    public function destroy($productId): ?array
    {
        $merchantCenter = $this->firstMerchantCenter();
        $response = $this->apiMerchantCenter->deleteProduct($merchantCenter->mec_access_token, $productId);

        if (!empty($response) && $this->isUnauthorized($response)) {
            return $this->refreshTokenAndRetry('destroy', $productId);
        }

        return $response;
    }

    public function createOrUpdateProducts($type, $params): array
    {
        $merchantCenter = $this->firstMerchantCenter();
        $token = $merchantCenter->mec_access_token;

        $data = [
            "entries" => []
        ];

        foreach ($params as $product) {
            $batchEntry = [
                "batchId" => randomNumber(1, 99999),
                "merchantId" => $this->apiMerchantCenterID,
                "method" => $type,
                "product" => $this->fillProductData($product, $type),
            ];
            if ($type === self::METHOD_TYPE_UPDATE) {
                $batchEntry['productId'] = $product['productId'];
            }

            $data['entries'][] = $batchEntry;
        }

        $response = $this->apiMerchantCenter->customBatchProduct($token, $data);

        if ($this->isUnauthorized($response)) {
            return $this->refreshTokenAndRetry('createOrUpdateProducts', $type, $params);
        }

        return $response;
    }

    private function store($response, $code): MerchantCenter
    {
        $params = [
            'mec_token_type' => $response['token_type'],
            'mec_expires_in' => $response['expires_in'],
            'mec_access_token' => $response['access_token'],
            'mec_refresh_token' => $response['refresh_token'],
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

    private function refreshTokenAndRetry(string $methodName, ...$params): array
    {
        debug("Merchant Center refresh token to method {$methodName}");
        $merchantCenter = $this->firstMerchantCenter();
        $response = $this->apiMerchantCenter->refreshToken($merchantCenter->mec_refresh_token);

        if (!$this->isInvalidGrant($response) && isset($response['access_token'])) {
            $merchantCenter->update([
                'mec_token_type' => $response['token_type'],
                'mec_expires_in' => $response['expires_in'],
                'mec_access_token' => $response['access_token'],
            ]);

            return call_user_func_array([$this, $methodName], $params);
        } else {
            debug(['Merchant Center refresh token error: ' => $response]);
            throw new BusinessError('Failed to refresh Merchant Center token');
        }
    }

    private function isInvalidGrant(array $response): bool
    {
        return isset($response['error']) && $response['error'] === 'invalid_grant';
    }

    private function fillProductData(array $params, string $methodType): array
    {
        $defaultData = [
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
        ];

        $extraData = [
            'offerId' => $params['offerId'],
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

        return $methodType === self::METHOD_TYPE_INSERT
            ? array_merge($defaultData, $extraData)
            : $defaultData;
    }
}
