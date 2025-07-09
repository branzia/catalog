<?php
namespace Branzia\Catalog\Support;

class VariantHelper
{
    public static function generate(array $attributes,$name,$sku,$price): array {  
        if (empty($attributes)) return [];

        // Load attribute and value names
        foreach ($attributes as $attributeId => $valueIds) {
            $attribute = \Branzia\Catalog\Models\Attribute::with('values')->find($attributeId);
            if (! $attribute) continue;

            foreach ($valueIds as $valueId) {
                $value = $attribute->values->firstWhere('id', $valueId);
                if ($value) {
                    $attributeNames[$attribute->name][$value->id] = $value->value;
                }
            }
        }

        // Generate all combinations
        $sets = array_values(array_map('array_values', $attributeNames));
        $combinations = [[]];

        foreach ($sets as $set) {
            $new = [];
            foreach ($combinations as $combo) {
                foreach ($set as $value) {
                    $new[] = array_merge($combo, [$value]);
                }
            }
            $combinations = $new;
        }

        // Return structured variant items
        return collect($combinations)->map(function ($combo) use ($name,$sku,$price) {
            return [
                'name' => $name.'-'.implode('-', $combo),
                'sku' => strtolower($sku.'-'.implode('-', $combo)),
                'price' => $price,
                'weight' => '',
                'product_type' => 'simple',
                'visibility' => 'not_visible',
                'attributes' => array_values($combo),
                
            ];
        })->toArray();
    }



}
