<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Services\Payment\PayResponseService;
use Illuminate\Http\Request;

class PayController extends Controller
{
    public function confirmation($status, Request $request)
    {
        if ($status === 'success' || $status === 'pending') {
            $data = json_encode($this->_callService(
                PayResponseService::class,
                'confirmation',
                $request
            ));
            $message = $status === 'success'
                ? 'Obrigado, seu pedido foi concluído com sucesso'
                : 'Obrigado, seu pedido esta em processo de aprovação';
            $request->session()->flash('status', $message);
            $request->session()->forget('cart.products');
            $request->session()->forget('cart.voucher');
            $request->session()->forget('cart.voucherValue');

            return view('site.confirmation')->with(compact('data'));
        }
        return redirect('/checkout')->with(
            'status',
            'Ocorreu uma falha no seu pagamento, por favor tente novamente!'
        );
    }
}
