<?php

namespace App\Services;

use App\Models\Faq;
use Illuminate\Database\Eloquent\Collection;

class FaqService extends BaseService
{
    private $faq;

    public function __construct(Faq $faq)
    {
        $this->faq = $faq;
    }

    public function index(): Collection
    {
        return $this->faq->all();
    }

    public function findById($request): Faq
    {
        return $this->faq->findOrFail($request->input('id'));
    }

    public function store($request): Faq
    {
        $this->validateFields($request->all());
        $params = [
            'faq_title' => $request->input('title'),
            'faq_description' => $request->input('description'),
        ];
        return $this->faq->updateOrCreate([
            'faq_id' => $request->input('id'),
        ], $params);
    }

    public function destroy($request): bool
    {
        $faq = $this->findById($request);
        return $faq->delete();
    }

    private function validateFields($request): void
    {
        $rules = [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
        ];
        $titles = [
            'title' => 'Título',
            'description' => 'Descrição',
        ];
        $this->_validate($request, $rules, $titles);
    }
}
