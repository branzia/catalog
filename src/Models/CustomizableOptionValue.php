<?php
namespace Branzia\Catalog\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomizableOptionValue extends Model
{
    protected $table = 'catalog_product_customizable_options_values';

    protected $fillable = ['option_id', 'label', 'price','type','sort_order'];

    public function customOption(): BelongsTo
    {
        return $this->belongsTo(CustomizableOption::class, 'option_id');
    }
}
