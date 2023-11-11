<?php

namespace App\Models;

class Product extends BaseModel
{
    protected const ASC = 'asc';
    protected const DESC = 'desc';
    protected const SOLD = 'sold';
    protected const RECENT = 'recent';

    protected $table = 'products';
    protected $primaryKey = 'pro_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'cat_id', // default
        'pro_price', // default
        'pro_oldprice', // default
        'pro_title', // default
        'pro_description', // default
        'pro_description_long', // default
        'pro_image', // default
        'pro_sku', // default
        'pro_inventory', // default
        'pro_seller_id',
        'pro_category_id',
        'pro_condition',
        'pro_permalink',
        'pro_thumbnail',
        'pro_secure_thumbnail',
        'pro_accepts_merc_pago',
        'pro_load_date',
        'pro_sold_quantity',
        'pro_external',
        'pro_enabled',
    ];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'pro_id',
        'created_at',
        'update_at',
    ];

    /**
     * Get the category that owns the product.
     */
    public function category()
    {
        return $this->belongsTo(
            'App\Models\Category',
            'cat_id',
            'cat_id'
        );
    }

    /**
     * Get the category that owns the product.
     */
    public function categoryML()
    {
        return $this->belongsTo(
            'App\Models\Category',
            'pro_category_id',
            'cat_id_secondary'
        );
    }

    /**
     * Get the exclusive deal record associated with the product.
     */
    public function exclusiveDeal()
    {
        return $this->hasOne(
            'App\Models\ProductExclusive',
            'pro_sku',
            'pro_sku'
        );
    }

    /**
     * Get the products related for the product.
     */
    public function productsRelated()
    {
        return $this->hasMany(
            'App\Models\ProductRelated',
            'pro_sku',
            'pro_sku'
        );
    }

    /**
     * Get the specifications for the product.
     */
    public function specifications()
    {
        return $this->hasMany(
            'App\Models\ProductSpecification',
            'pro_sku',
            'pro_sku'
        );
    }

    /**
     * Get the pictures for the product.
     */
    public function pictures()
    {
        return $this->hasMany('App\Models\Picture', 'pro_sku', 'pro_sku');
    }

    /**
     * Get the comments for the product.
     */
    public function comments()
    {
        return $this->hasMany(
            'App\Models\ProductComment',
            'pro_sku',
            'pro_sku'
        );
    }

    /**
     * Scope a query to only include filters.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearch($query, $request)
    {
        $this->filterEnabled($query, $request);
        $this->filterSellerEnabled($query, $request);
        $this->filterCategory($query, $request);
        $this->filterOrder($query, $request);
        $this->filterPrice($query, $request);

        if (
            $request->has('search')
            && !is_null($request->input('search'))
        ) {
            $search = $request->input('search');
            $query->where(function ($query) use ($search) {
                $query
                    ->where('products.pro_title', 'like', '%' . $search . '%')
                    ->orWhere('products.pro_description', 'like', '%' . $search . '%')
                    ->orWhere('products.pro_description_long', 'like', '%' . $search . '%');
            });
        }
        return $query;
    }

    private function filterEnabled(&$query, $request)
    {
        $query->whereIn('products.pro_enabled', [
            true,
            filter_var($request->input('enabled'), FILTER_VALIDATE_BOOLEAN)
        ]);
    }

    private function filterSellerEnabled(&$query, $request)
    {
        $query
            ->leftJoin('mercadolivre', function ($query) {
                $query
                    ->on('mercadolivre.mel_user_id', 'products.pro_seller_id');
            })
            ->where(function ($query) {
                $query
                    ->where('mercadolivre.mel_enabled', true)
                    ->orWhereNull('products.pro_seller_id');
            });
    }

    private function filterCategory(&$query, $request)
    {
        if ($request->has('category')) {
            $query
                ->join('categories', function ($query) {
                    $query
                        ->on('categories.cat_id', 'products.cat_id')
                        ->orOn(
                            'categories.cat_id_secondary',
                            'products.pro_category_id'
                        );
                })
                ->where(function ($query) use ($request) {
                    $category = $request->input('category');
                    $query
                        ->where('categories.cat_id', $category)
                        ->orWhere('categories.cat_id_secondary', $category);
                });
        }
    }

    private function filterOrder(&$query, $request)
    {
        if ($request->has('order')) {
            $order = $request->input('order');
            switch ($order) {
                case self::SOLD:
                    $query->orderBy('products.pro_sold_quantity', self::DESC);
                    break;
                case self::ASC:
                case self::DESC:
                    $query->orderBy('products.pro_price', $order);
                    break;
                case self::RECENT:
                    $query->orderBy('products.created_at', self::DESC);
                    break;
                default:
                    $query->orderBy('products.created_at', self::DESC);
                    break;
            }
        }
    }

    private function filterPrice(&$query, $request)
    {
        if ($request->has('price')) {
            if ($request->input('price') === '-1') {
                $query->where('products.pro_price', '>=', 1);
            } else {
                $query->where('products.pro_price', '<=', $request->input('price'));
            }
        }
    }
}
