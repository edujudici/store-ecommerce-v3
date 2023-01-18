<?php

namespace App\Services;

use App\Models\Feature;
use Illuminate\Database\Eloquent\Collection;

class FeatureService extends BaseService
{
    private $feature;

    public function __construct(Feature $feature)
    {
        $this->feature = $feature;
    }

    public function index(): Collection
    {
        return $this->feature->all();
    }

    public function findById($request): Feature
    {
        return $this->feature->findOrFail($request->input('id'));
    }

    public function store($request): Feature
    {
        $this->validateFields($request->all());
        $params = [
            'fea_title' => $request->input('title'),
            'fea_description' => $request->input('description'),
        ];
        uploadImage($request, $params, 'fea_image');
        return $this->feature->updateOrCreate([
            'fea_id' => $request->input('id'),
        ], $params);
    }

    public function destroy($request): bool
    {
        $feature = $this->findById($request);
        return $feature->delete();
    }

    private function validateFields($request): void
    {
        $rules = [
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'file' => 'image|mimes:jpeg,png,jpg,webp|max:2048',
        ];
        $titles = [
            'title' => 'Título',
            'description' => 'Descrição',
            'file' => 'Imagem',
        ];
        $this->_validate($request, $rules, $titles);
    }
}
