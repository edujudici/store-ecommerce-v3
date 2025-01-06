<?php

namespace App\Traits;

use App\Http\Controllers\API\AuthController;
use Illuminate\Http\Request;

trait AuthApi
{
    public function apiAuth(): string
    {
        $request = new Request([
            'email' => env('SANCTUM_USER_EMAIL'),
            'password' => env('SANCTUM_USER_PASSWORD'),
        ]);
        $authController = new AuthController();
        $response = $authController->login($request);

        return $response->getData()->token;
    }
}
