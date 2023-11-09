<?php

namespace App\Services\Freight;

use App\Exceptions\BusinessError;
use App\Services\BaseService;
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
