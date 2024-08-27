<?php

namespace App\Services\Freight;

use App\Api\ApiMelhorEnvio;
use App\Models\MelhorEnvio;
use App\Services\BaseService;
use Exception;

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

        return $this->store($response, $code);
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

        $response = $this->apiMelhorEnvio->calculate($data, $token);
        if ($this->isUnauthorized($response)) {
            return $this->refreshTokenAndRetry('calculate', $from, $to, $value);
        }

        return $response;
    }

    private function store($response, $code): MelhorEnvio
    {
        $params = [
            'mee_token_type' => $response->token_type,
            'mee_expires_in' => $response->expires_in,
            'mee_access_token' => $response->access_token,
            'mee_refresh_token' => $response->refresh_token,
            'mee_authorize_code' => $code,
        ];

        $melhorEnvio = $this->index();

        return $melhorEnvio
            ? tap($melhorEnvio)->update($params)
            : $this->melhorEnvio->create($params);
    }

    private function isUnauthorized(array $response): bool
    {
        return isset($response['message']) && $response['message'] === 'Unauthenticated';
    }

    private function refreshTokenAndRetry(string $methodName, ...$params): mixed
    {
        debug("Melhor Envio refresh token to method {$methodName}");
        $melhorEnvio = $this->index();
        $response = $this->apiMelhorEnvio->refreshToken($melhorEnvio->mee_refresh_token);

        if (isset($response->access_token)) {
            $this->melhorEnvio->update([
                'mee_token_type' => $response->token_type,
                'mee_expires_in' => $response->expires_in,
                'mee_access_token' => $response->access_token,
                'mee_refresh_token' => $response->refresh_token,
            ]);

            return call_user_func_array([$this, $methodName], $params);
        } else {
            throw new Exception('Failed to refresh Melhor Envio token');
        }
    }
}
