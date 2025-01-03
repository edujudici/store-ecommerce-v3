<?php

namespace App\Http\Composers;

use App\Http\Controllers\API\AuthController;
use App\Services\Painel\CompanyService;
use App\Services\Product\FavoriteService;
use App\Traits\MakeRequest;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MasterComposer
{
    use MakeRequest;

    /**
     * Bind data to the view.
     */
    public function compose(View $view): void
    {
        $company = $this->_callService(CompanyService::class, 'index', []);
        $cartTotalItems = $this->cartTotalItems();
        $favoriteTotalItems = $this->favoriteTotalItems();
        $tokenApi = $this->apiAuth();

        $view
            ->with('company', json_encode($company['response']))
            ->with('tokenApi', $tokenApi)
            ->with('cartTotal', $cartTotalItems)
            ->with('favoriteTotal', $favoriteTotalItems);
    }

    private function apiAuth(): string
    {

        if (session('apiToken')) {
            return session('apiToken');
        }

        $request = new Request([
            'email' => env('SANCTUM_USER_EMAIL'),
            'password' => env('SANCTUM_USER_PASSWORD'),
        ]);
        $authController = new AuthController();
        $response = $authController->login($request);

        $token = $response->getData()->token;

        session(['apiToken' => $token]);

        return $token;
    }

    private function cartTotalItems()
    {
        $products = session('cart.products', []);
        return array_reduce($products, static function ($total, $product) {
            return $total += $product['amount'];
        }, 0);
    }

    private function favoriteTotalItems()
    {
        $products = session('favorite.products', []);
        if (! $products) {
            if (auth()->user()) {
                $data = $this->_callService(
                    FavoriteService::class,
                    'index',
                    auth()->user()->id
                );
                $products = $data['response'];
            }
        }
        return array_reduce($products, static function ($total, $product) {
            return $total += $product['amount'];
        }, 0);
    }
}
