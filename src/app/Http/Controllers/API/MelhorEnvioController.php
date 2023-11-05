<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\Freight\MelhorEnvioService;
use Illuminate\Http\Request;

class MelhorEnvioController extends Controller
{
    public function auth(Request $request)
    {
        $this->_callService(
            MelhorEnvioService::class,
            'auth',
            $request
        );

        return redirect()->route('painel.melhorenvio.index')
            ->with('success', 'Autorizado com sucesso.');
    }
}
