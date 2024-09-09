<?php

namespace App\Services\Seller;

use App\Api\ApiMercadoLibre;
use App\Models\MercadoLivre;
use App\Services\BaseService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class MercadoLivreService extends BaseService
{
    private $mercadoLivre;
    private $apiMercadoLibre;

    public function __construct(
        MercadoLivre $mercadoLivre,
        ApiMercadoLibre $apiMercadoLibre
    ) {
        $this->mercadoLivre = $mercadoLivre;
        $this->apiMercadoLibre = $apiMercadoLibre;
    }

    public function auth($request): void
    {
        $newRequest = Request::create('/', 'POST', [
            'mel_code_tg' => $request->input('code'),
        ]);

        $mercadoLivre = $this->store($newRequest);

        $this->apiMercadoLibre->accessToken($mercadoLivre);

        $this->saveTitle($mercadoLivre);
    }

    public function index($request = null): Collection
    {
        $query = $this->mercadoLivre;
        if ($request !== null && $request->has('mel_enabled')) {
            $query = $query->where('mel_enabled', filter_var($request->input('mel_enabled'), FILTER_VALIDATE_BOOLEAN));
        }
        return $query->get();
    }

    public function findById($request): MercadoLivre
    {
        return $this->mercadoLivre->findOrFail($request->input('id'));
    }

    public function store($request): MercadoLivre
    {
        $params = $request->all();
        return $this->mercadoLivre->updateOrCreate([
            'mel_id' => $request->input('id'),
        ], $params);
    }

    public function destroy($request): bool
    {
        $mercadoLivre = $this->findById($request);
        return $mercadoLivre->delete();
    }

    public function getMyInfoData($request)
    {
        $mercadoLivre = $this->findById($request);
        return $this->apiMercadoLibre->getMyUserDetails($mercadoLivre);
    }

    private function saveTitle($mercadoLivre): void
    {
        $usersData = $this->apiMercadoLibre->getUserDetails(
            $mercadoLivre->mel_user_id
        );

        $mercadoLivre->mel_title = $usersData->nickname;
        $mercadoLivre->save();
    }
}
