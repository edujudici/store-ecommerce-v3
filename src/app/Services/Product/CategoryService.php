<?php

namespace App\Services\Product;

use App\Models\Category;
use App\Services\BaseService;
use Illuminate\Database\Eloquent\Collection;

class CategoryService extends BaseService
{
    private $category;

    public function __construct(Category $category)
    {
        $this->category = $category;
    }

    public function index(): Collection
    {
        return $this->category
            ->leftJoin('mercadolivre', function ($query) {
                $query
                    ->on('mercadolivre.mel_user_id', 'categories.cat_seller_id');
            })
            ->where(function ($query) {
                $query
                    ->where('mercadolivre.mel_enabled', true)
                    ->orWhereNull('categories.cat_seller_id');
            })
            ->orderBy('categories.cat_title')
            ->get();
    }

    public function findById($request): Category
    {
        return $this->category->findOrFail($request->input('id'));
    }

    public function store($request): Category
    {
        $this->validateFields($request->all());

        $params = $request->all();
        uploadImage($request, $params, 'cat_image');
        return $this->category->updateOrCreate([
            'cat_id' => $request->input('id'),
        ], $params);
    }

    public function destroy($request): bool
    {
        $category = $this->findById($request);
        return $category->delete();
    }

    private function validateFields($request)
    {
        $rules = [
            'cat_title' => 'required|string|max:255',
            'file' => 'image|mimes:jpeg,png,jpg,webp|max:2048',
        ];
        $titles = [
            'cat_title' => 'Título',
            'file' => 'Imagem',
        ];
        $this->_validate($request, $rules, $titles);
    }
}
