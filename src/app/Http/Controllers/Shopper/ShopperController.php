<?php

namespace App\Http\Controllers\Shopper;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ShopperController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('shopper');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('shopper.home');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function dataIndex()
    {
        $data = auth()->user();
        return view('shopper.data')->with(compact('data'));
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function ordersIndex()
    {
        return view('shopper.orders');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function vouchersIndex()
    {
        return view('shopper.vouchers');
    }

    /**
     * Update user data.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function dataUpdate(Request $request)
    {
        $user = auth()->user();
        $user->update($request->all());
        return redirect()->route('shopper.data.index')
            ->with('success', 'Usuário atualizado com sucesso.');
    }

    /**
     * Update user password.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function passwordUpdate(Request $request)
    {
        $rules = [
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ];
        if (! session('login_socialite')) {
            $rules['current_password'] = 'required';
            $rules['password'][] = 'different:current_password';
        }
        $request->validate($rules, [], [
            'current_password' => 'Senha Atual',
            'password' => 'Nova Senha',
        ]);

        $user = auth()->user();

        if (! session('login_socialite')
            && ! Hash::check($request->current_password, $user->password)) {
            return back()->with('error', 'Senha atual não confere!');
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->route('shopper.data.index')
            ->with('success-password', 'Senha alterada com sucesso.');
    }
}
