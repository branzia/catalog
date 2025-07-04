<?php

namespace Branzia\Catalog\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class CatalogProductCategory extends Pivot
{
    protected $table = 'catalog_product_categories';
    public $timestamps = false;
    public $incrementing = false; // Because you're using a composite primary key

    protected $fillable = [
        'product_id',
        'category_id',
    ];
}
