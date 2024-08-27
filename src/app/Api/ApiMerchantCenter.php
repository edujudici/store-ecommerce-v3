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

    private const URI_PRODUCTS = "/content/v2.1/{merchantId}/products";
    private const URI_OAUTH_TOKEN = "/token";
    private const AUTHORIZATION_CODE = "authorization_code";
    private const REFRESH_TOKEN = "refresh_token";

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
     * @return mixed
     */
    public function accessToken($code)
    {
        $url = $this->baseUrl . self::URI_OAUTH_TOKEN;
        $params = [
            'grant_type' => self::AUTHORIZATION_CODE,
            'client_id' => $this->clientId,
            'client_secret' => $this->secret,
            'redirect_uri' => $this->redirectUri,
            'code' => $code,
        ];

        $response = json_decode(self::runCurl($url, [
            'postFields' => $params,
        ]));
        return $response;
    }

    /**
     * RefreshToken to continue consuming Merchant Center service
     *
     * @param string $refreshToken
     * @return mixed
     */
    public function refreshToken($refreshToken)
    {
        $url = $this->baseUrl . self::URI_OAUTH_TOKEN;
        $params = [
            'grant_type' => self::REFRESH_TOKEN,
            'client_id' => $this->clientId,
            'client_secret' => $this->secret,
            'refresh_token' => $refreshToken,
        ];

        $response = json_decode(self::runCurl($url, [
            'postFields' => $params,
        ]));
        return $response;
    }

    /**
     * Get list of all products of Merchant Center service
     *
     * @param string $token
     * @return null|array
     */
    public function allProducts($token): ?array
    {
        $url = $this->shoppingUrl . str_replace('{merchantId}', $this->merchantId, self::URI_PRODUCTS);
        return json_decode(self::runCurl($url, [
            'bearerKey' => $token,
            'contentType' => 'application/json'
        ]), true);
    }

    /**
     * Get product by id in Merchant Center service
     *
     * @param string $token
     * @param string|integer $productId
     * @return mixed
     */
    public function getProduct($token, $productId): mixed
    {
        $url = $this->shoppingUrl . str_replace('{merchantId}', $this->merchantId, self::URI_PRODUCTS);
        $url = $url + '/' + $productId;
        return json_decode(self::runCurl($url, [
            'customRequest' => 'GET',
            'bearerKey' => $token,
        ]));
    }

    /**
     * Add new product in Merchant Center service
     *
     * @param string $token
     * @param array $params
     * @return mixed
     */
    public function addProduct($token, $params): mixed
    {
        $url = $this->shoppingUrl . str_replace('{merchantId}', $this->merchantId, self::URI_PRODUCTS);
        $postFields = json_encode($params);
        return json_decode(self::runCurl($url, [
            'postFields' => $postFields,
            'bearerKey' => $token,
            'contentType' => 'application/json'
        ]), true);
    }

    /**
     * Delete product by id in Merchant Center service
     *
     * @param string $token
     * @param string|integer $productId
     * @return mixed
     */
    public function deleteProduct($token, $productId): mixed
    {
        $url = $this->shoppingUrl . str_replace('{merchantId}', $this->merchantId, self::URI_PRODUCTS);
        $url = $url + '/' + $productId;
        return json_decode(self::runCurl($url, [
            'customRequest' => 'DELETE',
            'bearerKey' => $token,
        ]));
    }
}
