<?php

namespace App\Services\MerchantCenter;

use App\Models\MerchantCenter;
use App\Services\BaseService;
use Google\Client;
use Google\Service\ShoppingContent;
use Google\Service\ShoppingContent\Price;
use Google\Service\ShoppingContent\Product;
use Google\Service\ShoppingContent\ProductsCustomBatchRequest;
use Google\Service\ShoppingContent\ProductsCustomBatchRequestEntry;
use Google\Service\ShoppingContent\ProductShipping;

class MerchantCenterService extends BaseService
{
    private $merchantCenter;
    private $apiMerchantCenterID;

    private const METHOD_TYPE_INSERT = 'insert';
    private const METHOD_TYPE_UPDATE = 'update';

    public function __construct(
        MerchantCenter $merchantCenter
    ) {
        $this->merchantCenter = $merchantCenter;
        $this->apiMerchantCenterID = env('GOOGLE_MERCHANT_ID');
    }

    /**
     * Summary of firstMerchantCenter
     * @return \App\Models\MerchantCenter|null
     */
    public function firstMerchantCenter(): MerchantCenter|null
    {
        return $this->merchantCenter->first();
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
     * @return \App\Models\MerchantCenter
     */
    public function handleGoogleCallback($request): MerchantCenter
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
        $merchantCenter = $this->firstMerchantCenter();
        $client = $this->getClient();
        $client->setAccessToken([
            'access_token' => $merchantCenter->mec_access_token,
            'refresh_token' => $merchantCenter->mec_refresh_token,
            'expires_in' => $merchantCenter->mec_expires_in,
            'scope' => $merchantCenter->mec_scope,
            'token_type' => $merchantCenter->mec_token_type,
            'created' => $merchantCenter->mec_created,
        ]);

        if ($client->isAccessTokenExpired()) {
            $client->fetchAccessTokenWithRefreshToken($merchantCenter->mec_refresh_token);
            $client->setAccessToken($client->getAccessToken());

            $merchantCenter->mec_access_token = $client->getAccessToken()['access_token'];
            $merchantCenter->mec_refresh_token = $client->getAccessToken()['refresh_token'];
            $merchantCenter->mec_created = $client->getAccessToken()['created'];
            $merchantCenter->mec_expires_in = $client->getAccessToken()['expires_in'];
            $merchantCenter->save();
        }

        $service = new ShoppingContent($client);
        $response = $service->products->listProducts($this->apiMerchantCenterID);
        return $response->getResources();
    }

    /**
     * Summary of addProduct
     * @param mixed $params
     * @return array
     */
    public function addProduct($params): array
    {
        $merchantCenter = $this->firstMerchantCenter();
        $client = $this->getClient();
        $client->setAccessToken([
            'access_token' => $merchantCenter->mec_access_token,
            'refresh_token' => $merchantCenter->mec_refresh_token,
            'expires_in' => $merchantCenter->mec_expires_in,
            'scope' => $merchantCenter->mec_scope,
            'token_type' => $merchantCenter->mec_token_type,
            'created' => $merchantCenter->mec_created,
        ]);

        if ($client->isAccessTokenExpired()) {
            $client->fetchAccessTokenWithRefreshToken($merchantCenter->mec_refresh_token);
            $client->setAccessToken($client->getAccessToken());

            $merchantCenter->mec_access_token = $client->getAccessToken()['access_token'];
            $merchantCenter->mec_refresh_token = $client->getAccessToken()['refresh_token'];
            $merchantCenter->mec_created = $client->getAccessToken()['created'];
            $merchantCenter->mec_expires_in = $client->getAccessToken()['expires_in'];
            $merchantCenter->save();
        }

        $product = $this->fillProductData($params, self::METHOD_TYPE_INSERT);
        $service = new ShoppingContent($client);
        $response = $service->products->insert($this->apiMerchantCenterID, $product);

        return json_decode(json_encode($response), true);
    }

    /**
     * Summary of findById
     * @param mixed $productId
     * @return array
     */
    public function findById($productId): array
    {
        $merchantCenter = $this->firstMerchantCenter();
        $client = $this->getClient();
        $client->setAccessToken([
            'access_token' => $merchantCenter->mec_access_token,
            'refresh_token' => $merchantCenter->mec_refresh_token,
            'expires_in' => $merchantCenter->mec_expires_in,
            'scope' => $merchantCenter->mec_scope,
            'token_type' => $merchantCenter->mec_token_type,
            'created' => $merchantCenter->mec_created,
        ]);

        if ($client->isAccessTokenExpired()) {
            $client->fetchAccessTokenWithRefreshToken($merchantCenter->mec_refresh_token);
            $client->setAccessToken($client->getAccessToken());

            $merchantCenter->mec_access_token = $client->getAccessToken()['access_token'];
            $merchantCenter->mec_refresh_token = $client->getAccessToken()['refresh_token'];
            $merchantCenter->mec_created = $client->getAccessToken()['created'];
            $merchantCenter->mec_expires_in = $client->getAccessToken()['expires_in'];
            $merchantCenter->save();
        }

        $service = new ShoppingContent($client);
        $response = $service->products->get($this->apiMerchantCenterID, $productId);

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
        $merchantCenter = $this->firstMerchantCenter();
        $client = $this->getClient();
        $client->setAccessToken([
            'access_token' => $merchantCenter->mec_access_token,
            'refresh_token' => $merchantCenter->mec_refresh_token,
            'expires_in' => $merchantCenter->mec_expires_in,
            'scope' => $merchantCenter->mec_scope,
            'token_type' => $merchantCenter->mec_token_type,
            'created' => $merchantCenter->mec_created,
        ]);

        if ($client->isAccessTokenExpired()) {
            $client->fetchAccessTokenWithRefreshToken($merchantCenter->mec_refresh_token);
            $client->setAccessToken($client->getAccessToken());

            $merchantCenter->mec_access_token = $client->getAccessToken()['access_token'];
            $merchantCenter->mec_refresh_token = $client->getAccessToken()['refresh_token'];
            $merchantCenter->mec_created = $client->getAccessToken()['created'];
            $merchantCenter->mec_expires_in = $client->getAccessToken()['expires_in'];
            $merchantCenter->save();
        }

        $product = $this->fillProductData($params, self::METHOD_TYPE_UPDATE);
        $service = new ShoppingContent($client);
        $response = $service->products->update($this->apiMerchantCenterID, $productId, $product);

        return json_decode(json_encode($response), true);
    }

    /**
     * Summary of destroy
     * @param mixed $productId
     * @return array
     */
    public function destroy($productId): array
    {
        $merchantCenter = $this->firstMerchantCenter();
        $client = $this->getClient();
        $client->setAccessToken([
            'access_token' => $merchantCenter->mec_access_token,
            'refresh_token' => $merchantCenter->mec_refresh_token,
            'expires_in' => $merchantCenter->mec_expires_in,
            'scope' => $merchantCenter->mec_scope,
            'token_type' => $merchantCenter->mec_token_type,
            'created' => $merchantCenter->mec_created,
        ]);

        if ($client->isAccessTokenExpired()) {
            $client->fetchAccessTokenWithRefreshToken($merchantCenter->mec_refresh_token);
            $client->setAccessToken($client->getAccessToken());

            $merchantCenter->mec_access_token = $client->getAccessToken()['access_token'];
            $merchantCenter->mec_refresh_token = $client->getAccessToken()['refresh_token'];
            $merchantCenter->mec_created = $client->getAccessToken()['created'];
            $merchantCenter->mec_expires_in = $client->getAccessToken()['expires_in'];
            $merchantCenter->save();
        }

        $service = new ShoppingContent($client);
        $response = $service->products->delete($this->apiMerchantCenterID, $productId);

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
        $merchantCenter = $this->firstMerchantCenter();
        $client = $this->getClient();
        $client->setAccessToken([
            'access_token' => $merchantCenter->mec_access_token,
            'refresh_token' => $merchantCenter->mec_refresh_token,
            'expires_in' => $merchantCenter->mec_expires_in,
            'scope' => $merchantCenter->mec_scope,
            'token_type' => $merchantCenter->mec_token_type,
            'created' => $merchantCenter->mec_created,
        ]);

        if ($client->isAccessTokenExpired()) {
            $client->fetchAccessTokenWithRefreshToken($merchantCenter->mec_refresh_token);
            $client->setAccessToken($client->getAccessToken());

            $merchantCenter->mec_access_token = $client->getAccessToken()['access_token'];
            $merchantCenter->mec_refresh_token = $client->getAccessToken()['refresh_token'];
            $merchantCenter->mec_created = $client->getAccessToken()['created'];
            $merchantCenter->mec_expires_in = $client->getAccessToken()['expires_in'];
            $merchantCenter->save();
        }

        $entries = [];
        foreach ($params as $productData) {
            $entry = new ProductsCustomBatchRequestEntry();
            $entry->setBatchId(randomNumber(1, 99999));
            $entry->setMerchantId($this->apiMerchantCenterID);
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
     * @return \App\Models\MerchantCenter
     */
    private function store($response): MerchantCenter
    {
        $params = [
            'mec_token_type' => $response['token_type'],
            'mec_expires_in' => $response['expires_in'],
            'mec_access_token' => $response['access_token'],
            'mec_refresh_token' => $response['refresh_token'],
            'mec_created' => $response['created'],
            'mec_scope' => $response['scope'],
        ];

        $merchantCenter = $this->firstMerchantCenter();

        return $merchantCenter
            ? tap($merchantCenter)->update($params)
            : $this->merchantCenter->create($params);
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
