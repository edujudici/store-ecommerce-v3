<?php

namespace App\Traits;

use App\Api\Curl;
use RuntimeException;

trait MakeCurl
{
    /**
     * Curl to comunicate other services
     *
     * @param string $url
     * @param array $params
     *
     * @return string date formatted
     */
    protected function runCurl($url, $params = [])
    {
        try {
            $curl = new Curl($url, $params);
            return $curl->exec();
        } catch (RuntimeException $rEx) {
            debug([
                'line' => $rEx->getLine(),
                'code' => $rEx->getCode(),
                'erro' => $rEx->getMessage(),
                'trace' => $rEx->getTraceAsString(),
            ]);
        }
    }
}
