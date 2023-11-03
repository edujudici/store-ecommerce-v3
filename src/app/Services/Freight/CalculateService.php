<?php

namespace App\Services\Freight;

use App\Models\MelhorEnvio;
use App\Services\BaseService;
use App\Services\CompanyService;
use App\Traits\FreightTransformable;
use Exception;

class CalculateService extends BaseService
{
    use FreightTransformable;

    private const MANUFACTURING_TIME_MIN = 3;
    private const MANUFACTURING_TIME_MAX = 4;

    private $melhorEnvioService;
    private $companyService;
    private $melhorEnvio;

    public function __construct(
        MelhorEnvioService $melhorEnvioService,
        CompanyService $companyService,
        MelhorEnvio $melhorEnvio
    ) {
        $this->melhorEnvioService = $melhorEnvioService;
        $this->companyService = $companyService;
        $this->melhorEnvio = $melhorEnvio;
    }

    public function calculate($request): ?array
    {
        $response = [];
        try {
            $this->validateFields($request->all());

            $melhorEnvio = $this->melhorEnvio->first();
            $company = $this->companyService->index();
            $token = $melhorEnvio->mee_access_token;

            $data = [
                'from' => $company->com_zipcode,
                'to' => $request->input('zipcode'),
                'services' => '1,2,3,4',
                'height' => 15,
                'width' => 20,
                'length' => 30,
                'weight' => 1,
                'value' => $request->input('value'),
            ];

            $freightList = $this->melhorEnvioService->calculate($data, $token);
            foreach ($freightList as $freight) {
                $transformabledData = self::prepareFreight($freight);
                if ($transformabledData['price'] !== null && $transformabledData['deliveryTime'] !== null) {
                    $response[] = $transformabledData;
                }
            }
        } catch (Exception $exception) {
            debug('Ocorreu um erro calcular frete: ' . $exception->getMessage());
        }

        $response[] = $this->addPickupHome();

        return $response;
    }

    private function validateFields($request)
    {
        $rules = [
            'zipcode' => 'required',
            'value' => 'required',
        ];
        $titles = [
            'zipcode' => 'CEP',
            'value' => 'Valor',
        ];
        $this->_validate($request, $rules, $titles);
    }

    private function addPickupHome()
    {
        $data = [
            'id' => -1,
            'name' => 'Retirar no Local',
            'price' => 0,
            'delivery_time' => self::MANUFACTURING_TIME_MAX,
            'delivery_range' => [
                'min' => self::MANUFACTURING_TIME_MIN,
                'max' => self::MANUFACTURING_TIME_MAX,
            ],
            'company' => [
                'id' => 0,
                'picture' => ''
            ],
        ];
        return $this->prepareFreight($data);
    }
}
