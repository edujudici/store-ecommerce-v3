<?php

namespace App\Services\Freight;

use App\Api\ApiSearchCep;
use App\Services\BaseService;
use Claudsonm\CepPromise\Address;

class ZipcodeService extends BaseService
{
    private $apiSearchCep;

    public function __construct(
        ApiSearchCep $apiSearchCep,
    ) {
        $this->apiSearchCep = $apiSearchCep;
    }

    public function index($cep): Address
    {
        return $this->apiSearchCep->searchCep($cep);
    }
}
