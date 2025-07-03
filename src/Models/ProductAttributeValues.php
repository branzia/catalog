<?php

namespace Branzia\Catalog\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductAttributeValues extends Model
{
    public $timestamps = false;

    protected $table = 'catalog_product_attribute_values';

    protected $fillable = [
        'product_attribute_id',
        'attribute_value_id',
    ];

    public function productAttribute(): BelongsTo
    {
        return $this->belongsTo(ProductAttribute::class, 'product_attribute_id');
    }

    public function attributeValue(): BelongsTo
    {
        return $this->belongsTo(AttributeValues::class, 'attribute_value_id');
    }
}
