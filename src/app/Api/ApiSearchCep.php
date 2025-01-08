<?php

namespace App\Api;

use App\Exceptions\BusinessError;
use Claudsonm\CepPromise\Address;
use Claudsonm\CepPromise\CepPromise;
use Claudsonm\CepPromise\Exceptions\CepPromiseException;

class ApiSearchCep
{
    public function searchCep($cep): Address
    {
        try {
            return CepPromise::fetch($cep);
        } catch (CepPromiseException $e) {
            throw new BusinessError($e->getMessage());
        }
    }
}
