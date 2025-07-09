<?php

namespace Branzia\Catalog\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductImage extends Model
{
    protected $table = 'catalog_product_images';
    protected $fillable = [
        'is_base_image',        
        'path',        // path or URL to the image
        'product_id',
        'position',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
