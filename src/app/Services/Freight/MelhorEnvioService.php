<?php

namespace App\Services\Freight;

use App\Services\BaseService;
use App\Traits\MakeCurl;

class MelhorEnvioService extends BaseService
{
    use MakeCurl;

    private $baseUrl;
    private $clientId;
    private $secret;
    private $redirectUri;

    private const URI_SHIPMENT_CALCULATE = "/api/v2/me/shipment/calculate";
    private const URI_OAUTH_TOKEN = "/oauth/token";
    private const AUTHORIZATION_CODE = "authorization_code";
    private const REFRESH_TOKEN = "refresh_token";

    public function __construct()
    {
        $this->baseUrl = env('MELHOR_ENVIO_URL');
        $this->clientId = env('MELHOR_ENVIO_CLIENT_ID');
        $this->secret = env('MELHOR_ENVIO_SECRET');
        $this->redirectUri = env('MELHOR_ENVIO_REDIRECT_URI');
    }

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
     * Calculate freight on melhor envio service
     *
     * Payload example:
     * {
     *      "from": {"postal_code": "13503538"},
     *      "to": {"postal_code": "17013150"},
     *      "services": "1,2,3,4,7,11"
     *      "package": {
     *          "height": 15,
     *          "width": 20,
     *          "length": 30,
     *          "weight": 1
     *      },
     *      "options": {
     *          "insurance_value": 150,
     *          "receipt": false,
     *          "own_hand": false
     *      },
     * }
     * @param array $params
     * @param string $token
     * @return null|array
     */
    public function calculate($params, $token): ?array
    {
        $url = $this->baseUrl . self::URI_SHIPMENT_CALCULATE;
        $postFields = json_encode([
            'from' => [
                'postal_code' => $params['from'],
            ],
            'to' => [
                'postal_code' => $params['to'],
            ],
            'services' => $params['services'],
            'packages' => [
                'height' => $params['height'],
                'width' => $params['width'],
                'length' => $params['length'],
                'weight' => $params['weight'],
            ],
            'options' => [
                'insurance_value' => $params['value'],
                'receipt' => false,
                'own_hand' => false
            ]
        ]);

        return json_decode(self::runCurl($url, [
            'postFields' => $postFields,
            'bearerKey' => $token,
            'contentType' => 'application/json'
        ]), true);
    }
}
