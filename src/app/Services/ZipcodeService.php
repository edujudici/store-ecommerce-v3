<?php

namespace App\Services;

use App\Exceptions\BusinessError;
use Claudsonm\CepPromise\Address;
use Claudsonm\CepPromise\CepPromise;
use Claudsonm\CepPromise\Exceptions\CepPromiseException;

class ZipcodeService extends BaseService
{
    public function index($cep): Address
    {
        try {
            return CepPromise::fetch($cep);
        } catch (CepPromiseException $e) {
            throw new BusinessError($e->getMessage());
        }
    }
}
