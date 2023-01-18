<?php

namespace App\Services;

use App\Models\MercadoLivreAnswer;
use Illuminate\Database\Eloquent\Collection;

class MercadoLivreAnswerService extends BaseService
{
    private $mercadoLivreAnswer;

    public function __construct(MercadoLivreAnswer $mercadoLivreAnswer)
    {
        $this->mercadoLivreAnswer = $mercadoLivreAnswer;
    }

    public function index(): Collection
    {
        return $this->mercadoLivreAnswer->all();
    }

    public function findById($request): MercadoLivreAnswer
    {
        return $this->mercadoLivreAnswer->findOrFail($request->input('id'));
    }

    public function store($request): MercadoLivreAnswer
    {
        $this->validateFields($request->all());
        $params = [
            'mea_description' => $request->input('description'),
        ];
        return $this->mercadoLivreAnswer->updateOrCreate([
            'mea_id' => $request->input('id'),
        ], $params);
    }

    public function destroy($request): bool
    {
        $mercadoLivreAnswer = $this->findById($request);
        return $mercadoLivreAnswer->delete();
    }

    private function validateFields($request): void
    {
        $rules = [
            'description' => 'required|string',
        ];
        $titles = [
            'description' => 'Descrição',
        ];
        $this->_validate($request, $rules, $titles);
    }
}
