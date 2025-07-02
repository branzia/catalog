<?php

namespace Branzia\Catalog\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Attribute extends Model
{
    protected $table = 'attributes';
    protected $fillable = [
        'code',
        'label',
        'type',
        'use_product_image_for_swatch',
        'is_required',
        'is_comparable',
        'is_unique',
        'is_filterable',
        'is_visible_on_front',
    ];

    public function values(): HasMany
    {
        return $this->hasMany(AttributeValues::class);
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
