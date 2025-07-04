<?php

namespace Branzia\Catalog\Filament\Resources\ProductResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Branzia\Catalog\Filament\Resources\ProductResource;

class EditProduct extends EditRecord
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {     
        return [
            Actions\DeleteAction::make(),
        ];
    }
    
    

    protected function afterSave(): void
    {
        $product = $this->record;
        $formData = $this->form->getState();
        /*
        // Optional: Delete existing variants
        $product->variants()->delete();

        // Save new variants
        foreach ($formData['variants'] ?? [] as $variantData) {
            $product->variants()->create([
                ...$variantData,
                'parent_id' => $product->id,
                'slug' => \Str::slug($variantData['name']),
                'visibility' => 'not_visible',
                'is_active' => true,
                'product_type' => 'simple',
            ]);
        }*/
    }
}
