<?php
namespace Branzia\Catalog\Support;

class VariantHelper
{
    public static function generate(array $selectedAttributes): array
{  
    if (empty($selectedAttributes)) return [];

    $keys = array_keys($selectedAttributes);
    $sets = array_values($selectedAttributes);

    // Safely sanitize
    $sets = array_map(function ($set) {
        return is_array($set) ? array_values(array_filter($set)) : [];
    }, $sets);
    // Remove empty sets to avoid incorrect combination
    if (empty(array_filter($sets))) return [];

    // Handle single attribute case
    echo count($sets);die('die');
    if (count($sets) === 1) {
        return collect($sets[0])->map(fn ($value) => [
            'sku' => '',
            'price' => 0,
            'stock' => 0,
            'attributes' => [$keys[0] => $value],
        ])->toArray();
    }

    // Cross join
    $combinations = array_reduce($sets, function ($carry, $set) {
        if (empty($carry)) {
            return collect($set)->map(fn ($item) => [$item]);
        }

        return $carry->flatMap(function ($existing) use ($set) {
            return collect($set)->map(function ($item) use ($existing) {
                return array_merge($existing, [$item]);
            });
        });
    }, collect());

    print_r($combinations->map(function ($values) use ($keys) {
        $attributes = array_combine($keys, $values);
        return [
            'sku' => '',
            'price' => 0,
            'stock' => 0,
            'attributes' => $attributes,
        ];
    })->toArray());
}



}
