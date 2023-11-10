<?php

namespace App\Services\Freight;

use App\Api\ApiMelhorEnvio;
use App\Models\MelhorEnvio;
use App\Services\BaseService;

class MelhorEnvioService extends BaseService
{
    private $melhorEnvio;
    private $apiMelhorEnvio;

    public function __construct(
        MelhorEnvio $melhorEnvio,
        ApiMelhorEnvio $apiMelhorEnvio,
    ) {
        $this->melhorEnvio = $melhorEnvio;
        $this->apiMelhorEnvio = $apiMelhorEnvio;
    }

    public function index(): MelhorEnvio|null
    {
        return $this->melhorEnvio->first();
    }

    public function auth($request): MelhorEnvio
    {
        $code = $request->input('code');

        $response = $this->apiMelhorEnvio->accessToken($code);

        $params = [
            'mee_token_type' => $response->token_type,
            'mee_expires_in' => $response->expires_in,
            'mee_access_token' => $response->access_token,
            'mee_refresh_token' => $response->refresh_token,
        ];

        return $this->melhorEnvio->updateOrCreate([
            'mee_authorize_code' => $code,
        ], $params);
    }

    public function calculate($from, $to, $value): array
    {
        $melhorEnvio = $this->index();
        $token = $melhorEnvio->mee_access_token;

        $data = [
            'from' => $from,
            'to' => $to,
            'value' => $value,
            'services' => '1,2,3,4',
            'height' => 15,
            'width' => 20,
            'length' => 30,
            'weight' => 1,
        ];

        return $this->apiMelhorEnvio->calculate($data, $token);
    }
}
