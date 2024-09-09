<?php

namespace App\Services\Seller;

use App\Api\ApiMercadoLibre;
use App\Models\Seller;
use App\Services\BaseService;

class SellerService extends BaseService
{
    private $seller;
    private $apiMercadoLibre;

    public function __construct(
        Seller $seller,
        MercadoLibre $mercadoLibre
    ) {
        $this->seller = $seller;
        $this->apiMercadoLibre = $mercadoLibre;
    }

    public function index()
    {
        return $this->seller->all();
    }

    public function store($nickname): Seller
    {
        return $this->seller->updateOrCreate([
            'sel_nickname' => $nickname,
        ]);
    }

    public function destroy($request): bool
    {
        $seller = $this->seller->findOrFail($request->input('id'));
        return $seller->delete();
    }

    public function search($request)
    {
        $response = $this->apiMercadoLibre->getUserDetailsByNickname(
            $request->input('nickname')
        );
        if (! empty($response->seller->nickname)) {
            $this->store($response->seller->nickname);
        }
        return $response;
    }
}
