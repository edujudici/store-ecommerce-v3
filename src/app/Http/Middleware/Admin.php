<?php

namespace App\Http\Middleware;

use Closure;

class Admin
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
            ? (auth()->user()->role === 'admin')
            : false;

        if ($this->auth === true) {
            return $next($request);
        }

        return redirect()->route('painel.login.form')->with(
            'error',
            'Acesso negado. Efetue login para continuar'
        );
    }
}
