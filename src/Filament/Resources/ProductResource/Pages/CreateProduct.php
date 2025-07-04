<?php

namespace Branzia\Catalog\Filament\Resources\ProductResource\Pages;

use Branzia\Catalog\Filament\Resources\ProductResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Actions\Action;
class CreateProduct extends CreateRecord
{
    protected static string $resource = ProductResource::class;

    protected function getFormActions(): array
    {
        return [
            ...parent::getFormActions(),
            Action::make('close')->action('createAndClose'),
        ];
    }
}
