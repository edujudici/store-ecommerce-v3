<?php

namespace App\Services;

use App\Models\Company;

class CompanyService extends BaseService
{
    private $company;

    public function __construct(Company $company)
    {
        $this->company = $company;
    }

    public function index(): ?Company
    {
        return $this->company->first();
    }

    public function store($request): Company
    {
        $this->validateFields($request->all());
        $params = [
            'com_title' => $request->input('title'),
            'com_description' => $request->input('description'),
            'com_phone' => $request->input('phone'),
            'com_work_hours' => $request->input('workHours'),
            'com_mail' => $request->input('mail'),
            'com_iframe' => $request->input('iframe'),
            'com_address' => $request->input('address'),
            'com_zipcode' => $request->input('zipcode'),
            'com_number' => $request->input('number'),
            'com_district' => $request->input('district'),
            'com_city' => $request->input('city'),
            'com_complement' => $request->input('complement'),
            'com_uf' => $request->input('uf'),
        ];
        uploadImage($request, $params, 'com_image');
        return $this->company->updateOrCreate([
            'com_id' => $request->input('id'),
        ], $params);
    }

    private function validateFields($request)
    {
        $rules = [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'phone' => 'string|max:255',
            'workHours' => 'string|max:255',
            'mail' => 'string|max:255',
            'iframe' => 'string',
            'file' => 'image|mimes:jpeg,png,jpg,webp|max:2048',
            'zipcode' => 'required|string|max:8',
            'address' => 'required|string|max:255',
            'number' => 'required|max:255',
            'district' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'complement' => 'string|max:255',
            'uf' => 'required|string|max:2',
        ];
        $titles = [
            'title' => 'Título',
            'description' => 'Descrição',
            'phone' => 'Telefone',
            'workHours' => 'Horário de funcionamento',
            'mail' => 'E-mail',
            'iframe' => 'Localização',
            'file' => 'Imagem',
            'zipcode' => 'CEP',
            'address' => 'Endereço',
            'number' => 'Número',
            'district' => 'Bairro',
            'city' => 'Cidade',
            'complement' => 'Complemento',
            'uf' => 'UF',
        ];
        $this->_validate($request, $rules, $titles);
    }
}
