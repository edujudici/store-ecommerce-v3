<?php

namespace App\Services;

use App\Models\Banner;
use Illuminate\Database\Eloquent\Collection;

class BannerService extends BaseService
{
    private $banner;

    public function __construct(Banner $banner)
    {
        $this->banner = $banner;
    }

    public function index(): Collection
    {
        return $this->banner->all();
    }

    public function findById($request): Banner
    {
        return $this->banner->findOrFail($request->input('id'));
    }

    public function store($request): Banner
    {
        $this->validateFields($request->all());
        $params = [
            'ban_title' => $request->input('title'),
            'ban_description' => $request->input('description'),
            'ban_url' => $request->input('url'),
        ];
        uploadImage($request, $params, 'ban_image');
        return $this->banner->updateOrCreate([
            'ban_id' => $request->input('id'),
        ], $params);
    }

    public function destroy($request): bool
    {
        $banner = $this->findById($request);
        return $banner->delete();
    }

    private function validateFields($request): void
    {
        $rules = [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
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
