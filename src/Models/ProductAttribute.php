<?php

namespace Branzia\Catalog\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ProductAttribute extends Model
{
    protected $table = 'catalog_product_attributes';
    public $timestamps = false;
    protected $fillable = [
        'product_id',
        'attribute_id',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function attribute(): BelongsTo
    {
        return $this->belongsTo(Attribute::class, 'attribute_id');
    }
    public function attributeValueLinks(): HasMany
    {
        return $this->hasMany(ProductAttributeValues::class, 'product_attribute_id');
    }
    
    public function attributeValues(): BelongsToMany
    {
        return $this->belongsToMany(AttributeValues::class,'catalog_product_attribute_values','product_attribute_id','attribute_value_id');
    }
}
