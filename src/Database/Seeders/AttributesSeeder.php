<?php

namespace Branzia\Catalog\Database\Seeders;

use Illuminate\Database\Seeder;
use Branzia\Catalog\Models\Attribute;
use Branzia\Catalog\Models\AttributeValues;

class AttributesSeeder extends Seeder
{
    public function run(): void
    {
        $attributes = [
            'color' => [
                'name' => 'Color',
                'type' => 'visual_swatch',
                'field_type' => 'multiple',
                'is_configurable' => true,
                'is_filterable' => true,
                'is_visible_on_front' => true,
                'values' => [
                    ['value' => 'Red', 'swatch_value' => '#ff0000'],
                    ['value' => 'Blue', 'swatch_value' => '#0000ff'],
                    ['value' => 'Green','swatch_value' => '#00ff00'],
                    ['value' => 'Black','swatch_value' => '#000000'],
                ],
            ],
            'size' => [
                'name' => 'Size',
                'type' => 'text_swatch',
                'field_type' => 'multiple',
                'is_configurable' => true,
                'is_filterable' => true,
                'is_visible_on_front' => true,
                'values' => [
                    ['value' => 'S','swatch_value' => 'Small'],
                    ['value' => 'M','swatch_value' => 'Medium'],
                    ['value' => 'L','swatch_value' => 'Large'],
                    ['value' => 'XL','swatch_value' => 'Extra Larage'],
                ],
            ],
            'manufacturer_country' => [
                'name' => 'Country of Manufacturer',
                'type' => 'dropdown',
                'field_type' => 'single',
                'is_configurable' => false,
                'is_filterable' => true,
                'is_visible_on_front' => false,
                'values' => [
                    ['value' => 'India'],
                    ['value' => 'USA'],
                    ['value' => 'Germany'],
                    ['value' => 'China'],
                ],
            ],
        ];

        foreach ($attributes as $code => $data) {
            $attribute = Attribute::firstOrCreate([
                'code' => $code,
            ], [
                'name' => $data['name'],
                'type' => $data['type'] ?? 'dropdown',
                'field_type' => $data['field_type'] ?? 'single',
                'use_product_image_for_swatch' => $data['use_product_image_for_swatch'] ?? false,
                'is_configurable' => $data['is_configurable'] ?? false,
                'is_comparable' => $data['is_comparable'] ?? false,
                'is_filterable' => $data['is_filterable'] ?? false,
                'is_visible_on_front' => $data['is_visible_on_front'] ?? false,
            ]);

            foreach ($data['values'] as $index => $val) {
                AttributeValues::firstOrCreate([
                    'attribute_id' => $attribute->id,
                    'value' => $val['value'],
                ], [
                    'swatch_value' => $val['swatch_value'] ?? null,
                    'sort_order' => $index,
                    'default' => $val['default'] ?? false,
                ]);
            }
        }
    }
}
