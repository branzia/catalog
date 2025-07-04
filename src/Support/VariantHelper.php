<?php
namespace Branzia\Catalog\Support;

class VariantHelper
{
    public static function generate(array $attributes): array {  
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
        return collect($combinations)->map(function ($combo) use ($attributeNames) {
            return [
                'sku' => implode('-', $combo),
                'name' => implode(' - ', $combo),
                'price' => 0,
                'attributes' => array_values($combo),
            ];
        })->toArray();
    }



}
