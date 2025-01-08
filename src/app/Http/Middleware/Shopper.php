<?php

namespace App\Http\Middleware;

use Closure;

class Shopper
{
    private $auth;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $this->auth = auth()->user()
            ? (auth()->user()->role === 'shopper')
            : false;

        if ($this->auth === true) {
            return $next($request);
        }

        if ($request->ajax()) {
            return response()->json([
                'status' => 0,
                'response' => null,
                'message' => 'Acesso negado. Efetue login para continuar',
            ]);
        }

        return redirect()->route('login')->with(
            'error',
            'Acesso negado. Efetue login para continuar'
        );
    }
}
