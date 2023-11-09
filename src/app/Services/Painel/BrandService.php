<?php

namespace App\Services\Painel;

use App\Models\Brand;
use App\Services\BaseService;
use Illuminate\Database\Eloquent\Collection;

class BrandService extends BaseService
{
    private $brand;

    public function __construct(Brand $brand)
    {
        $this->brand = $brand;
    }

    public function index(): Collection
    {
        return $this->brand->all();
    }

    public function findById($request): Brand
    {
        return $this->brand->findOrFail($request->input('id'));
    }

    public function store($request): Brand
    {
        $this->validateFields($request->all());
        $params = [
            'bra_title' => $request->input('title'),
        ];
        uploadImage($request, $params, 'bra_image');
        return $this->brand->updateOrCreate([
            'bra_id' => $request->input('id'),
        ], $params);
    }

    public function destroy($request): bool
    {
        $brand = $this->findById($request);
        return $brand->delete();
    }

    private function validateFields($request): void
    {
        $rules = [
            'title' => 'required|string|max:255',
            'file' => 'image|mimes:jpeg,png,jpg,webp|max:2048',
        ];
        $titles = [
            'title' => 'Título',
            'file' => 'Imagem',
        ];
        $this->_validate($request, $rules, $titles);
    }
}
