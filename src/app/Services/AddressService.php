<?php

namespace App\Services;

use App\Models\Address;

class AddressService extends BaseService
{
    public function store($user, $request): Address
    {
        $this->validateFields($request);
        return $user->addresses()->updateOrCreate([
            'user_id' => $request['user_id'],
            'adr_type' => $request['adr_type'],
        ], $request);
    }

    private function validateFields($request): void
    {
        $rules = [
            'adr_name' => 'required|string|max:255',
            'adr_surname' => 'required|string|max:255',
            'adr_phone' => 'required|string|max:15',
            'adr_zipcode' => 'required|string|max:10',
            'adr_address' => 'required|string|max:255',
            'adr_number' => 'required|numeric',
            'adr_district' => 'required|string|max:100',
            'adr_city' => 'required|string|max:100',
            'adr_complement' => 'nullable|string|max:100',
            'adr_uf' => 'required|string|max:2',
        ];
        $titles = ['adr_name' => 'Nome', 'adr_surname' => 'Sobrenome',
            'adr_phone' => 'Telefone', 'adr_zipcode' => 'CEP',
            'adr_address' => 'Endereço', 'adr_number' => 'Número',
            'adr_district' => 'Bairro', 'adr_city' => 'Cidade',
            'adr_complement' => 'Complemento', 'adr_uf' => 'Estado',
        ];
        $this->_validate($request, $rules, $titles);
    }
}
