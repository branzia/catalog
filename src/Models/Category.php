<?php

namespace Branzia\Catalog\Models;

use Illuminate\Support\Str;
use Kalnoy\Nestedset\NodeTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Studio15\FilamentTree\Concerns\InteractsWithTree;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Category extends Model
{
    use NodeTrait;
    use InteractsWithTree;

     /**
     * Table name.
     *
     * @var string
     */
    protected $table = 'catalog_categories';


    protected $fillable = [
        'name',
        'slug',
        'parent_id',
        'description',
        'position',
        'is_active',
    ];

    public static function getTreeLabelAttribute(): string
    {
        return 'name';
    }
    public function getTreeCaption(): ?string
    {
        return $this->description;
    }

    protected static function booted()
    {
        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });
    }


    public function getIndentedNameAttribute(): string
    {
        return str_repeat('-- ', $this->depth) . $this->name;
    }
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function products(): BelongsToMany {
        return $this->belongsToMany(Product::class,'catalog_product_categories');
    }

}
