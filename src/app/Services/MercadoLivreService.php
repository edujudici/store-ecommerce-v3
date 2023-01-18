<?php

namespace App\Services;

use App\Api\MercadoLibre;
use App\Jobs\LoadAccountML;
use App\Models\MercadoLivre;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class MercadoLivreService extends BaseService
{
    private $mercadoLivre;
    private $mercadoLibre;

    public function __construct(
        MercadoLivre $mercadoLivre,
        MercadoLibre $mercadoLibre
    ) {
        $this->mercadoLivre = $mercadoLivre;
        $this->mercadoLibre = $mercadoLibre;
    }

    public function dispatchAccount($request): void
    {
        $params = $request->all();
        debug(['debug mercado livre code' => $params]);
        LoadAccountML::dispatch($params)->onQueue('account');
    }

    public function newAccount($params): MercadoLivre
    {
        $request = Request::create('/', 'POST', [
            'mel_code_tg' => $params['code'],
        ]);

        $mercadoLivre = $this->store($request);

        $this->mercadoLibre->accessToken($mercadoLivre);

        $this->saveTitle($mercadoLivre);

        return $mercadoLivre;
    }

    public function index(): Collection
    {
        return $this->mercadoLivre->all();
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

    private function saveTitle($mercadoLivre): void
    {
        $usersData = $this->mercadoLibre->getUserDetails(
            $mercadoLivre->mel_user_id
        );

        $mercadoLivre->mel_title = $usersData->nickname;
        $mercadoLivre->save();
    }
}
