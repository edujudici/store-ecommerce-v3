<?php

namespace App\Services;

use App\Api\CalculaFrete;
use App\Api\ServicosCorreios;
use ReflectionClass;

class FreightService extends BaseService
{
    private const ZIPCODE_FROM = '13503538';
    private const MANUFACTURING_TIME = 4;

    private $apiFreight;

    public function __construct(CalculaFrete $calculaFrete)
    {
        $this->apiFreight = $calculaFrete;
    }

    public function getCodes(): array
    {
        $refl = new ReflectionClass('App\Api\ServicosCorreios');
        return $refl->getConstants();
    }

    public function calculate($request): ?array
    {
        $this->validateFields($request->all());
        $this->fillCommomArgs($request);

        $code = $request->input('serviceCode');
        $this->apiFreight->nCdServico = $code;
        $data = $this->apiFreight->request();
        if ((int) $data['Erro'] === 0 || (int) $data['Erro'] === 1) {
            $data['ServicoNome'] = ServicosCorreios::getDescription($code);
            $data['PrazoEntrega'] += self::MANUFACTURING_TIME;
            return $data;
        }

        debug('error freight code: ' . $code);
        debug('error freight response: ' . $data['Erro']);
        // debug('error freight response message: ' . $data['MsgErro']);
        return null;
    }

    private function fillCommomArgs($request): void
    {
        $_args = [
            'sCepOrigem' => self::ZIPCODE_FROM,
            'sCepDestino' => $request->input('zipcode'),
            'nVlPeso' => '1',
            'nVlComprimento' => 30,
            'nVlAltura' => 15,
            'nVlLargura' => 20,
        ];
        $this->apiFreight->init($_args);
    }

    private function validateFields($request)
    {
        $rules = [
            'zipcode' => 'required',
            'serviceCode' => 'required',
        ];
        $titles = [
            'zipcode' => 'CEP',
            'serviceCode' => 'Código Correios',
        ];
        $this->_validate($request, $rules, $titles);
    }
}
