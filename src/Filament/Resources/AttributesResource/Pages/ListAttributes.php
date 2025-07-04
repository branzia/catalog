<?php

namespace Branzia\Catalog\Filament\Resources\AttributesResource\Pages;

use Branzia\Catalog\Filament\Resources\AttributesResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAttributes extends ListRecords
{
    protected static string $resource = AttributesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
