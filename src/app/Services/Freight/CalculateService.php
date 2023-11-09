<?php

namespace App\Services\Freight;

use App\Services\BaseService;
use App\Services\Painel\CompanyService;
use App\Traits\FreightTransformable;
use Exception;

class CalculateService extends BaseService
{
    use FreightTransformable;

    private const MANUFACTURING_TIME_MIN = 3;
    private const MANUFACTURING_TIME_MAX = 4;

    private $companyService;
    private $melhorEnvioService;

    public function __construct(
        CompanyService $companyService,
        MelhorEnvioService $melhorEnvioService
    ) {
        $this->companyService = $companyService;
        $this->melhorEnvioService = $melhorEnvioService;
    }

    public function calculate($request): ?array
    {
        $response = [];
        try {
            $this->validateFields($request->all());
            $company = $this->companyService->index();

            $freightList = $this->melhorEnvioService->calculate(
                $company->com_zipcode,
                $request->input('zipcode'),
                $request->input('value')
            );
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
            'custom_price' => 0,
            'custom_delivery_time' => self::MANUFACTURING_TIME_MAX,
            'custom_delivery_range' => [
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
