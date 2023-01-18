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
            'com_address' => $request->input('address'),
            'com_phone' => $request->input('phone'),
            'com_work_hours' => $request->input('workHours'),
            'com_mail' => $request->input('mail'),
            'com_iframe' => $request->input('iframe'),
        ];
        uploadImage($request, $params, 'com_image');
        return $this->company->updateOrCreate([
            'com_id' => $request->input('id'),
        ], $params);
    }

    private function validateFields($request)
    {
        $rules = [
            'title' => 'required|string',
            'description' => 'required|string',
            'address' => 'string|max:255',
            'phone' => 'string|max:255',
            'workHours' => 'string|max:255',
            'mail' => 'string|max:255',
            'iframe' => 'string',
            'file' => 'image|mimes:jpeg,png,jpg,webp|max:2048',
        ];
        $titles = [
            'title' => 'Título', 'description' => 'Descrição',
            'address' => 'Endereço', 'phone' => 'Telefone',
            'workHours' => 'Horário de funcionamento',
            'mail' => 'E-mail',
            'iframe' => 'Localização',
            'file' => 'Imagem',
        ];
        $this->_validate($request, $rules, $titles);
    }
}
