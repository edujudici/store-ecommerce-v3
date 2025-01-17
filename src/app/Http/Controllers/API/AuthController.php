<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\User\UserService;
use App\Traits\AuthApi;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    use AuthApi;

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        $user = $this->_callService(UserService::class, 'findByEmail', $credentials);
        if (!$user['response']) {
            return response()->json(['message' => 'Invalid login credentials'], 401);
        }

        auth()->login($user['response']);

        $token = $user['response']->createToken('API Token')->plainTextToken;

        return response()->json(['token' => $token], 200);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out'], 200);
    }

    public function keepAlive()
    {
        $token = $this->apiAuth();

        return response()->json(['token' => $token, 'status' => true], 200);
    }
}
