<?php

namespace App\Models;

class Category extends BaseModel
{
    protected $table = 'categories';
    protected $primaryKey = 'cat_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'cat_title',
        'cat_image',
        'cat_id_secondary',
        'cat_seller_id',
    ];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'cat_id',
        'created_at',
        'update_at',
    ];

    /**
     * Get the products for the category.
     */
    public function products()
    {
        return $this->hasMany('App\Models\Product', 'cat_id', 'cat_id');
    }

    /**
     * Get the products for the category.
     */
    public function productsML()
    {
        return $this->hasMany(
            'App\Models\Product',
            'pro_category_id',
            'cat_id_secondary'
        );
    }
}
