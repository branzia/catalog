<?php
namespace Branzia\Catalog\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CustomizableOption extends Model
{
    protected $table = 'catalog_product_customizable_options';

    protected $fillable = ['product_id', 'title', 'type', 'is_required', 'sort_order'];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function values(): HasMany
    {
        return $this->hasMany(CustomizableOptionValue::class, 'option_id');
    }
}
