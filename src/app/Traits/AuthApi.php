<?php

namespace App\Traits;

use App\Http\Controllers\API\AuthController;
use Exception;
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

        try {
            return $response->getData()->token;
        } catch (Exception $e) {
            debug('Auth api error: ' . $e->getMessage());
            return '';
        }
    }
}
