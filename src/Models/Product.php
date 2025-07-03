<?php

namespace Branzia\Catalog\Models;

use Branzia\Shop\Models\Inventory;
use Branzia\Shop\Models\Warehouse;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    /**
     * Table name.
     *
     * @var string
     */
    protected $table = 'catalog_products';

    /**
     * Fillable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'slug',
        'product_type',
        'parent_id',
        'sku',
        'price',
        'special_price',
        'special_price_from',
        'special_price_to',
        'qty',
        'stock_status',
        'visibility',
        'new_from',
        'new_to',
        'weight',
        'length',
        'width',
        'height',
        'is_active',
        'is_featured',
        'is_giftable',
        'tax_class_id',
        'attributes',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected $casts = [
        'special_price_from' => 'date',
        'special_price_to' => 'date',
        'new_from' => 'date',
        'new_to' => 'date',
        'attributes' => 'array',
        'is_giftable' => 'boolean',
        'price' => 'decimal:2',
        'special_price' => 'decimal:2',
        'weight' => 'decimal:3',
        'length' => 'decimal:2',
        'width' => 'decimal:2',
        'height' => 'decimal:2',
    ];

    /**
     * Parent product relationship (for variants or grouped products)
     */
    public function parent()
    {
        return $this->belongsTo(Product::class, 'parent_id');
    }

    /**
     * variants products (child)
     */
    public function variants()
    {
        return $this->hasMany(Product::class, 'parent_id');
    }

    /**
     * Tax class relationship
     */
    public function taxclass()
    {
        return $this->belongsTo(TaxClass::class, 'tax_class_id');
    }
    public function inventories(): HasMany
    {
        return $this->hasMany(\Branzia\Shop\Models\Inventory::class);
    }
    
    public function inventory():HasOne
    {
        return $this->hasOne(Inventory::class, 'product_id');
    }
    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }
    public function stockStatus(): string
    {
        return $this->inventory?->stock_status ?? 'out_of_stock';
    }

    public function availableQty(): int
    {
        return $this->inventory?->availableQty() ?? 0;
    }

    public function customizableOptions()
    {
        return $this->hasMany(CustomizableOption::class, 'product_id');
    }
    public function getAttributeSummaryAttribute(): string
    {
        return collect($this->attributes)->map(fn ($value, $key) => ucfirst($key) . ': ' . ucfirst($value))->implode(', ');
    }
    public function isConfigurable(): bool
    {
        return $this->product_type === 'configurable';
    }

    public function productAttributes()
    {
        return $this->hasMany(ProductAttribute::class, 'product_id');
    }
    public function allAttributesWithValues()
{
    return $this->productAttributes()->with('attribute', 'attributeValues')->get();
}
    protected static function booted(): void
    {
        static::creating(function (Product $product) {
            if (empty($product->slug)) {
                $product->slug = \Str::slug($product->name);
            }
            if ($product->parent_id && $product->attributes) {
                $product->attribute_hash = md5(json_encode($product->attributes));
            }
        });
   
    }
}
