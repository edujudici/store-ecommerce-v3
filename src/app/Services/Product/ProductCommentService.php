<?php

namespace App\Services\Product;

use App\Events\ProductCommentRegistered;
use App\Models\Product;
use App\Models\ProductComment;
use App\Services\BaseService;
use Illuminate\Database\Eloquent\Collection;

class ProductCommentService extends BaseService
{
    private $product;
    private $comment;

    public function __construct(
        Product $product,
        ProductComment $comment
    ) {
        $this->product = $product;
        $this->comment = $comment;
    }

    public function indexAll(): Collection
    {
        return $this->comment
            ->with('product')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function index($request): array
    {
        $product = $this->product
            ->where('pro_sku', $request->input('sku'))
            ->firstOrFail();
        return $product->comments()
            ->orderBy('created_at', 'desc')
            ->get()
            ->toArray();
    }

    public function findById($request): ProductComment
    {
        return $this->comment->findOrFail($request->input('id'));
    }

    public function store($request): array
    {
        $product = $this->product
            ->where('pro_sku', $request->input('sku'))
            ->firstOrFail();
        $product->comments()->updateOrCreate([
            'prc_id' => $request->input('prc_id'),
        ], $request->all());

        ProductCommentRegistered::dispatch($product);

        return $this->index($request);
    }

    public function destroy($request): bool
    {
        $comment = $this->findById($request);
        return $comment->delete();
    }
}
