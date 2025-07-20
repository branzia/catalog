<?php 


namespace Branzia\Catalog\Filament\Settings;
use Filament\Forms\Components\Section;

use Filament\Forms\Components\TextInput;
use Branzia\Settings\Contracts\FormSchema;

class Product extends FormSchema 
{
    public static string $tab = 'Product';
    public static string $group = 'Catalog';
    public static int $sort = 1;

    public static function baseSchema(): array
    {
        return [
            Section::make('Rate limiting')->schema([
                TextInput::make('name')->label('Product'),
            ])        
        ];
    }
    
}