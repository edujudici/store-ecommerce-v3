<?php

namespace App\Services\Google;

use App\Models\Google;
use App\Services\BaseService;
use Google\Client;
use Google\Service\ShoppingContent;
use Google\Service\ShoppingContent\Price;
use Google\Service\ShoppingContent\Product;
use Google\Service\ShoppingContent\ProductsCustomBatchRequest;
use Google\Service\ShoppingContent\ProductsCustomBatchRequestEntry;
use Google\Service\ShoppingContent\ProductShipping;

class GoogleService extends BaseService
{
    private $google;
    private $googleMerchantID;

    private const METHOD_TYPE_INSERT = 'insert';
    private const METHOD_TYPE_UPDATE = 'update';

    public function __construct(
        Google $google
    ) {
        $this->google = $google;
        $this->googleMerchantID = env('API_GOOGLE_MERCHANT_ID');
    }

    /**
     * Summary of firstGoogle
     * @return \App\Models\Google|null
     */
    public function firstGoogle(): Google|null
    {
        return $this->google->first();
    }

    /**
     * Summary of getAuthUrl
     * @return string
     */
    public function getAuthUrl(): string
    {
        return $this->getClient()->createAuthUrl();
    }

    /**
     * Summary of handleGoogleCallback
     * @param mixed $request
     * @return \App\Models\Google
     */
    public function handleGoogleCallback($request): Google
    {
        $response = $this->getClient()->fetchAccessTokenWithAuthCode($request->input('code'));
        return $this->store($response);
    }

    /**
     * Summary of index
     * @return array
     */
    public function index(): array
    {
        $google = $this->firstGoogle();
        $client = $this->getClient();
        $client->setAccessToken([
            'access_token' => $google->goo_access_token,
            'refresh_token' => $google->goo_refresh_token,
            'expires_in' => $google->goo_expires_in,
            'scope' => $google->goo_scope,
            'token_type' => $google->goo_token_type,
            'created' => $google->goo_created,
        ]);

        if ($client->isAccessTokenExpired()) {
            $client->fetchAccessTokenWithRefreshToken($google->goo_refresh_token);
            $client->setAccessToken($client->getAccessToken());

            $google->goo_access_token = $client->getAccessToken()['access_token'];
            $google->goo_refresh_token = $client->getAccessToken()['refresh_token'];
            $google->goo_created = $client->getAccessToken()['created'];
            $google->goo_expires_in = $client->getAccessToken()['expires_in'];
            $google->save();
        }

        $service = new ShoppingContent($client);
        $response = $service->products->listProducts($this->googleMerchantID);
        return $response->getResources();
    }

    /**
     * Summary of addProduct
     * @param mixed $params
     * @return array
     */
    public function addProduct($params): array
    {
        $google = $this->firstGoogle();
        $client = $this->getClient();
        $client->setAccessToken([
            'access_token' => $google->goo_access_token,
            'refresh_token' => $google->goo_refresh_token,
            'expires_in' => $google->goo_expires_in,
            'scope' => $google->goo_scope,
            'token_type' => $google->goo_token_type,
            'created' => $google->goo_created,
        ]);

        if ($client->isAccessTokenExpired()) {
            $client->fetchAccessTokenWithRefreshToken($google->goo_refresh_token);
            $client->setAccessToken($client->getAccessToken());

            $google->goo_access_token = $client->getAccessToken()['access_token'];
            $google->goo_refresh_token = $client->getAccessToken()['refresh_token'];
            $google->goo_created = $client->getAccessToken()['created'];
            $google->goo_expires_in = $client->getAccessToken()['expires_in'];
            $google->save();
        }

        $product = $this->fillProductData($params, self::METHOD_TYPE_INSERT);
        $service = new ShoppingContent($client);
        $response = $service->products->insert($this->googleMerchantID, $product);

        return json_decode(json_encode($response), true);
    }

    /**
     * Summary of findById
     * @param mixed $productId
     * @return array
     */
    public function findById($productId): array
    {
        $google = $this->firstGoogle();
        $client = $this->getClient();
        $client->setAccessToken([
            'access_token' => $google->goo_access_token,
            'refresh_token' => $google->goo_refresh_token,
            'expires_in' => $google->goo_expires_in,
            'scope' => $google->goo_scope,
            'token_type' => $google->goo_token_type,
            'created' => $google->goo_created,
        ]);

        if ($client->isAccessTokenExpired()) {
            $client->fetchAccessTokenWithRefreshToken($google->goo_refresh_token);
            $client->setAccessToken($client->getAccessToken());

            $google->goo_access_token = $client->getAccessToken()['access_token'];
            $google->goo_refresh_token = $client->getAccessToken()['refresh_token'];
            $google->goo_created = $client->getAccessToken()['created'];
            $google->goo_expires_in = $client->getAccessToken()['expires_in'];
            $google->save();
        }

        $service = new ShoppingContent($client);
        $response = $service->products->get($this->googleMerchantID, $productId);

        return json_decode(json_encode($response), true);
    }

    /**
     * Summary of updateProduct
     * @param mixed $productId
     * @param mixed $params
     * @return array
     */
    public function updateProduct($productId, $params): array
    {
        $google = $this->firstGoogle();
        $client = $this->getClient();
        $client->setAccessToken([
            'access_token' => $google->goo_access_token,
            'refresh_token' => $google->goo_refresh_token,
            'expires_in' => $google->goo_expires_in,
            'scope' => $google->goo_scope,
            'token_type' => $google->goo_token_type,
            'created' => $google->goo_created,
        ]);

        if ($client->isAccessTokenExpired()) {
            $client->fetchAccessTokenWithRefreshToken($google->goo_refresh_token);
            $client->setAccessToken($client->getAccessToken());

            $google->goo_access_token = $client->getAccessToken()['access_token'];
            $google->goo_refresh_token = $client->getAccessToken()['refresh_token'];
            $google->goo_created = $client->getAccessToken()['created'];
            $google->goo_expires_in = $client->getAccessToken()['expires_in'];
            $google->save();
        }

        $product = $this->fillProductData($params, self::METHOD_TYPE_UPDATE);
        $service = new ShoppingContent($client);
        $response = $service->products->update($this->googleMerchantID, $productId, $product);

        return json_decode(json_encode($response), true);
    }

    /**
     * Summary of destroy
     * @param mixed $productId
     * @return array
     */
    public function destroy($productId): array
    {
        $google = $this->firstGoogle();
        $client = $this->getClient();
        $client->setAccessToken([
            'access_token' => $google->goo_access_token,
            'refresh_token' => $google->goo_refresh_token,
            'expires_in' => $google->goo_expires_in,
            'scope' => $google->goo_scope,
            'token_type' => $google->goo_token_type,
            'created' => $google->goo_created,
        ]);

        if ($client->isAccessTokenExpired()) {
            $client->fetchAccessTokenWithRefreshToken($google->goo_refresh_token);
            $client->setAccessToken($client->getAccessToken());

            $google->goo_access_token = $client->getAccessToken()['access_token'];
            $google->goo_refresh_token = $client->getAccessToken()['refresh_token'];
            $google->goo_created = $client->getAccessToken()['created'];
            $google->goo_expires_in = $client->getAccessToken()['expires_in'];
            $google->save();
        }

        $service = new ShoppingContent($client);
        $response = $service->products->delete($this->googleMerchantID, $productId);

        return json_decode(json_encode($response), true);
    }

    /**
     * Summary of createOrUpdateProducts
     * @param mixed $type
     * @param mixed $params
     * @return array
     */
    public function createOrUpdateProducts($type, $params): array
    {
        $google = $this->firstGoogle();
        $client = $this->getClient();
        $client->setAccessToken([
            'access_token' => $google->goo_access_token,
            'refresh_token' => $google->goo_refresh_token,
            'expires_in' => $google->goo_expires_in,
            'scope' => $google->goo_scope,
            'token_type' => $google->goo_token_type,
            'created' => $google->goo_created,
        ]);

        if ($client->isAccessTokenExpired()) {
            $client->fetchAccessTokenWithRefreshToken($google->goo_refresh_token);
            $client->setAccessToken($client->getAccessToken());

            $google->goo_access_token = $client->getAccessToken()['access_token'];
            $google->goo_refresh_token = $client->getAccessToken()['refresh_token'];
            $google->goo_created = $client->getAccessToken()['created'];
            $google->goo_expires_in = $client->getAccessToken()['expires_in'];
            $google->save();
        }

        $entries = [];
        foreach ($params as $productData) {
            $entry = new ProductsCustomBatchRequestEntry();
            $entry->setBatchId(randomNumber(1, 99999));
            $entry->setMerchantId($this->googleMerchantID);
            $entry->setMethod($type);
            $product = $this->fillProductData($productData, $type);
            $entry->setProduct($product);

            if ($type === self::METHOD_TYPE_UPDATE) {
                $entry->setProductId($productData['productId']);
            }

            $entries[] = $entry;
        }

        $customBatch = new ProductsCustomBatchRequest();
        $customBatch->setEntries($entries);

        $service = new ShoppingContent($client);
        $response = $service->products->custombatch($customBatch);

        return json_decode(json_encode($response), true);
    }

    /**
     * Summary of store
     * @param mixed $response
     * @return \App\Models\Google
     */
    private function store($response): Google
    {
        $params = [
            'goo_token_type' => $response['token_type'],
            'goo_expires_in' => $response['expires_in'],
            'goo_access_token' => $response['access_token'],
            'goo_created' => $response['created'],
            'goo_scope' => $response['scope'],
        ];
        if (isset($response['refresh_token'])) {
            $params['goo_refresh_token'] = $response['refresh_token'];
        }

        $google = $this->firstGoogle();

        return $google
            ? tap($google)->update($params)
            : $this->google->create($params);
    }

    /**
     * Summary of fillProductData
     * @param array $params
     * @param string $methodType
     * @return \Google\Service\ShoppingContent\Product
     */
    private function fillProductData(array $params, string $methodType): Product
    {
        $product = new Product();
        $product->setTitle($params['title']);
        $product->setDescription($params['description']);
        $product->setLink($params['link']);
        $product->setImageLink($params['imageLink']);
        $product->setAdditionalImageLinks($params['additionalImageLinks']);
        $price = new Price;
        $price->setValue($params['price']);
        $price->setCurrency('BRL');
        $product->setPrice($price);
        $product->setProductTypes([
            'Artigos de festa',
            'Lembrancinhas'
        ]);

        if ($methodType === self::METHOD_TYPE_INSERT) {
            $product->setOfferId($params['offerId']);
            $product->setContentLanguage('pt');
            $product->setTargetCountry('BR');
            $product->setFeedLabel('BR');
            $product->setChannel('online');
            $product->setAvailability('in stock');
            $product->setCondition('new');
            $shipping = new ProductShipping();
            $shipping->setCountry('BR');
            $product->setShipping([$shipping]);
        }

        return $product;
    }

    /**
     * Summary of getClient
     * @return \Google\Client
     */
    private function getClient(): Client
    {
        $client = new Client();
        $client->setClientId(config('google.client_id'));
        $client->setClientSecret(config('google.client_secret'));
        $client->setRedirectUri(config('google.redirect'));
        $client->setAccessType(config('google.access_type'));
        $client->setApprovalPrompt(config('google.approval_prompt'));
        $client->addScope(ShoppingContent::CONTENT);

        return $client;
    }
}
