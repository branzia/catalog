<?php

namespace Branzia\Catalog\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Attribute extends Model
{
    protected $table = 'attributes';
    protected $fillable = [
        'code',
        'name',
        'type',
        'field_type',
        'use_product_image_for_swatch',
        'is_configurable',
        'is_comparable',
        'is_filterable',
        'is_visible_on_front',
    ];

    public function values(): HasMany
    {
        return $this->hasMany(AttributeValues::class, 'attribute_id');
    }

    protected static function booted(): void
    {
        static::saving(function ($attribute) {
            if (empty($attribute->code) && !empty($attribute->label)) {
                $attribute->code = \Str::slug($attribute->label);
            }
        });
    }
}
