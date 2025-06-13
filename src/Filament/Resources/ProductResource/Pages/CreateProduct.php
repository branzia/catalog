<?php

namespace Branzia\Catalog\Filament\Resources\ProductResource\Pages;

use Branzia\Catalog\Filament\Resources\ProductResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateProduct extends CreateRecord
{
    protected static string $resource = ProductResource::class;
}
