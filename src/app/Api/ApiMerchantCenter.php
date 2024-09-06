<?php

namespace App\Api;

use App\Traits\MakeCurl;

class ApiMerchantCenter
{
    use MakeCurl;

    private $shoppingUrl;
    private $baseUrl;
    private $clientId;
    private $secret;
    private $redirectUri;
    private $merchantId;

    private const URI_PRODUCTS = '/content/v2.1/{merchantId}/products';
    private const URI_PRODUCTS_BATCH = '/content/v2.1/products/batch';
    private const URI_OAUTH_TOKEN = '/token';
    private const AUTHORIZATION_CODE = 'authorization_code';
    private const REFRESH_TOKEN = 'refresh_token';
    private const CUSTOM_REQUEST_GET = 'GET';
    private const CUSTOM_REQUEST_DELETE = 'DELETE';
    private const CUSTOM_REQUEST_PATCH = 'PATCH';
    private const CONTENT_TYPE_JSON = 'application/json';

    public function __construct()
    {
        $this->shoppingUrl = env('API_GOOGLE_SHOPPING_URL');
        $this->baseUrl = env('API_GOOGLE_URL');
        $this->clientId = env('API_GOOGLE_CLIENT_ID');
        $this->secret = env('API_GOOGLE_SECRET');
        $this->redirectUri = env('API_GOOGLE_REDIRECT_URI');
        $this->merchantId = env('API_GOOGLE_MERCHANT_ID');
    }

    /**
     * Change authorization code to a valid token to use in Merchant Center service
     *
     * @param string $code
     * @return array
     */
    public function accessToken($code): array
    {
        $url = $this->baseUrl . self::URI_OAUTH_TOKEN;
        $params = [
            'grant_type' => self::AUTHORIZATION_CODE,
            'client_id' => $this->clientId,
            'client_secret' => $this->secret,
            'redirect_uri' => $this->redirectUri,
            'code' => $code,
        ];

        return json_decode(self::runCurl($url, [
            'postFields' => $params,
        ]), true);
    }

    /**
     * RefreshToken to continue consuming Merchant Center service
     *
     * @param string $refreshToken
     * @return array
     */
    public function refreshToken($refreshToken): array
    {
        $url = $this->baseUrl . self::URI_OAUTH_TOKEN;
        $params = [
            'grant_type' => self::REFRESH_TOKEN,
            'client_id' => $this->clientId,
            'client_secret' => $this->secret,
            'refresh_token' => $refreshToken,
        ];

        return json_decode(self::runCurl($url, [
            'postFields' => $params,
        ]), true);
    }

    /**
     * Get list of all products of Merchant Center service
     *
     * @param string $token
     * @return array
     */
    public function allProducts($token): array
    {
        $url = $this->shoppingUrl . str_replace('{merchantId}', $this->merchantId, self::URI_PRODUCTS);
        return json_decode(self::runCurl($url, [
            'bearerKey' => $token,
            'contentType' => self::CONTENT_TYPE_JSON,
        ]), true);
    }

    /**
     * Get product by id in Merchant Center service
     *
     * @param string $token
     * @param string|integer $productId
     * @return array
     */
    public function getProduct($token, $productId): array
    {
        $url = sprintf('%s%s/%s', $this->shoppingUrl, str_replace('{merchantId}', $this->merchantId, self::URI_PRODUCTS), $productId);
        return json_decode(self::runCurl($url, [
            'customRequest' => self::CUSTOM_REQUEST_GET,
            'bearerKey' => $token,
            'contentType' => self::CONTENT_TYPE_JSON,
        ]), true);
    }

    /**
     * Add new product in Merchant Center service
     *
     * @param string $token
     * @param array $params
     * @return array
     */
    public function addProduct($token, $params): array
    {
        $url = $this->shoppingUrl . str_replace('{merchantId}', $this->merchantId, self::URI_PRODUCTS);
        $postFields = json_encode($params);
        return json_decode(self::runCurl($url, [
            'postFields' => $postFields,
            'bearerKey' => $token,
            'contentType' => self::CONTENT_TYPE_JSON,
        ]), true);
    }

    /**
     * Handling of multiple products in Merchant Center service
     *
     * @param string $token
     * @param array $params
     * @return array
     */
    public function customBatchProduct($token, $params): array
    {
        $url = $this->shoppingUrl . self::URI_PRODUCTS_BATCH;
        $postFields = json_encode($params);
        return json_decode(self::runCurl($url, [
            'postFields' => $postFields,
            'bearerKey' => $token,
            'contentType' => self::CONTENT_TYPE_JSON,
        ]), true);
    }

    /**
     * Update product by id in Merchant Center service
     *
     * @param string $token
     * @param string|integer $productId
     * @return array
     */
    public function updateProduct($token, $productId, $params): array
    {
        $url = sprintf('%s%s/%s', $this->shoppingUrl, str_replace('{merchantId}', $this->merchantId, self::URI_PRODUCTS), $productId);
        $postFields = json_encode($params);
        return json_decode(self::runCurl($url, [
            'postFields' => $postFields,
            'customRequest' => self::CUSTOM_REQUEST_PATCH,
            'bearerKey' => $token,
            'contentType' => self::CONTENT_TYPE_JSON,
        ]), true);
    }

    /**
     * Delete product by id in Merchant Center service
     *
     * @param string $token
     * @param string|integer $productId
     * @return null|array
     */
    public function deleteProduct($token, $productId): ?array
    {
        $url = sprintf('%s%s/%s', $this->shoppingUrl, str_replace('{merchantId}', $this->merchantId, self::URI_PRODUCTS), $productId);
        return json_decode(self::runCurl($url, [
            'customRequest' => self::CUSTOM_REQUEST_DELETE,
            'bearerKey' => $token,
            'contentType' => self::CONTENT_TYPE_JSON,
        ]), true);
    }
}
