<?php

namespace Branzia\Catalog\Filament\Resources\ProductResource\Pages;

use Branzia\Catalog\Filament\Resources\ProductResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateProduct extends CreateRecord
{
    protected static string $resource = ProductResource::class;

    public function generateVariants() {
        $attributeValues = $this->form->getState()['variant_builder']['attribute_values'] ?? [];
        $variants = \Branzia\Catalog\Support\VariantHelper::generate($attributeValues);
        $this->form->fill([
            'variant_combinations' => $variants,
        ]);
    }
}
