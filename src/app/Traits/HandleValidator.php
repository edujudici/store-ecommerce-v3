<?php

namespace App\Traits;

use App\Exceptions\BusinessError;
use Validator;

trait HandleValidator
{
    /**
     * system validator for throlling erros (use only in service context)
     * helper validator: https://github.com/LaravelLegends/pt-br-validator
     *
     * @param @data array with response data
     * @param @rules array of validation rules mandatory
     * @param @names array of bind :attibute param on string nom mandatory
     * @param @messages array of custom messages for overrides behaivoring
     *
     * @throws BusinessError a default sistem error throller
     */
    public function _validate(
        array $data,
        array $rules,
        array $names = [],
        array $messages = []
    ) {
        $validator = Validator::make($data, $rules, $messages, $names);
        if ($validator->fails()) {
            $erros = $validator->errors();
            foreach ($erros->all() as $message) {
                throw new BusinessError($message);
            }
        }
    }
}
