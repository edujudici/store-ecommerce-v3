<?php

namespace App\Traits;

use RuntimeException;

trait MakeCurl
{
    private static $curl;
    private static $time;

    /**
     * Init curl
     *
     * @param string $url
     * @param array $params
     */
    public function __construct()
    {
        self::$time = microtime(true);
    }

    /**
     * Curl to comunicate other services
     *
     * @param string $url
     * @param array $params
     *
     * @return string date formatted
     */
    protected static function runCurl($url, $params = [])
    {
        try {
            debug('=========>>>> CURL EXEC <<<<==========');
            debug($url);

            self::$curl = curl_init();

            curl_setopt_array(self::$curl, [
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_URL => $url,
                CURLOPT_USERAGENT => 'Codular Sample cURL Request',
            ]);

            if (isset($params['postFields'])) {
                curl_setopt(self::$curl, CURLOPT_POST, 1);
                curl_setopt(self::$curl, CURLOPT_POSTFIELDS, $params['postFields']);
            }
            if (isset($params['contentType']) || isset($params['bearerKey'])) {
                $header = [];
                if (isset($params['contentType'])) {
                    $header[] = 'Content-Type:' . $params['contentType'];
                }
                if (isset($params['bearerKey'])) {
                    $header[] = 'Authorization: Bearer ' . $params['bearerKey'];
                }
                curl_setopt(self::$curl, CURLOPT_HTTPHEADER, $header);
            }
            if (isset($params['customRequest'])) {
                curl_setopt(
                    self::$curl,
                    CURLOPT_CUSTOMREQUEST,
                    $params['customRequest']
                );
            }

            $response = curl_exec(self::$curl);
            debug(['debug curl response' => $response]);

            $error = curl_error(self::$curl);
            // debug(['debug curl error' => $error]);

            $errno = curl_errno(self::$curl);
            // debug(['debug curl errno' => $errno]);

            if (is_resource(self::$curl)) {
                curl_close(self::$curl);
            }
            $mctime = (microtime(true) - self::$time) * 1000;

            if ($errno !== 0) {
                debug('=====>>>>CURL EXECUTADO COM ERRO: ' . round($mctime) . ' ms');
                debug(['debug error' => $error]);
                debug(['debug errno' => $errno]);
                throw new RuntimeException($error, $errno);
            }
            debug('======>>>>CURL EXECUTADO COM SUCESSO: ' . round($mctime) . ' ms');
            return $response;
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
