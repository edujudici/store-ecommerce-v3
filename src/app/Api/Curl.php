<?php

namespace App\Api;

class Curl
{
    private $curl;
    private $time;

    /**
     * Init curl
     *
     * @param string $url
     * @param array $params
     */
    public function __construct($url, $params)
    {
        $this->time = microtime(true);

        debug('=========>>>> CURL EXEC <<<<==========');
        debug($url);

        $this->curl = curl_init();

        curl_setopt_array($this->curl, [
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $url,
            CURLOPT_USERAGENT => 'Codular Sample cURL Request',
        ]);

        if (isset($params['postFields'])) {
            curl_setopt($this->curl, CURLOPT_POST, 1);
            curl_setopt($this->curl, CURLOPT_POSTFIELDS, $params['postFields']);
        }
        if (isset($params['contentType']) || isset($params['bearerKey'])) {
            $header = [];
            if (isset($params['contentType'])) {
                $header[] = 'Content-Type:' . $params['contentType'];
            }
            if (isset($params['bearerKey'])) {
                $header[] = 'Authorization: Bearer ' . $params['bearerKey'];
            }
            curl_setopt($this->curl, CURLOPT_HTTPHEADER, $header);
        }
        if (isset($params['customRequest'])) {
            curl_setopt(
                $this->curl,
                CURLOPT_CUSTOMREQUEST,
                $params['customRequest']
            );
        }
    }

    /**
     * Exec call using curl
     *
     * @return string
     *
     * @throws \RuntimeException On cURL error
     */
    public function exec()
    {
        $response = curl_exec($this->curl);
        debug(['debug curl response' => $response]);
        $error = curl_error($this->curl);
        // debug(['debug curl error' => $error]);
        $errno = curl_errno($this->curl);
        // debug(['debug curl errno' => $errno]);
        if (is_resource($this->curl)) {
            curl_close($this->curl);
        }
        $mctime = (microtime(true) - $this->time) * 1000;

        if ($errno !== 0) {
            debug('=====>>>>CURL EXECUTADO COM ERRO: ' . round($mctime) . ' ms');
            debug(['debug error' => $error]);
            debug(['debug errno' => $errno]);
            throw new \RuntimeException($error, $errno);
        }
        debug('======>>>>CURL EXECUTADO COM SUCESSO: ' . round($mctime) . ' ms');
        return $response;
    }
}
