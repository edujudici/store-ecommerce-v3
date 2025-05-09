<?php

namespace App\Services\Communication;

use App\Events\NewsletterRegistered;
use App\Models\Newsletter;
use App\Services\BaseService;
use Illuminate\Database\Eloquent\Collection;

class NewsletterService extends BaseService
{
    private $newsletter;

    public function __construct(Newsletter $news)
    {
        $this->newsletter = $news;
    }

    public function index(): Collection
    {
        return $this->newsletter->orderBy('created_at', 'desc')->get();
    }

    public function findById($request): Newsletter
    {
        return $this->newsletter->findOrFail($request->input('id'));
    }

    public function store($request): Newsletter
    {
        $this->validateFields($request->all());
        $params = [
            'new_email' => $request->input('email'),
        ];
        $newsletter = $this->newsletter->create($params);
        NewsletterRegistered::dispatch($request->input('email'));
        return $newsletter;
    }

    public function destroy($request): bool
    {
        $newsletter = $this->findById($request);
        return $newsletter->delete();
    }

    private function validateFields($request): void
    {
        $rules = [
            'email' => 'required|email|unique:newsletters,new_email',
        ];
        $titles = [
            'email' => 'E-mail',
        ];
        $this->_validate($request, $rules, $titles);
    }
}
