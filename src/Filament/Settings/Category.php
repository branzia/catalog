<?php 


namespace Branzia\Catalog\Filament\Settings;
use Branzia\Settings\Contracts\FormSchema;

use Filament\Forms\Components\TextInput;
class Category extends FormSchema 
{
    public static string $tab = 'Category';
    public static string $group = 'Catalog';
    public static int $sort = 1;

    public static function baseSchema(): array
    {
        return [
            TextInput::make('name')->label('Category'),
        ];
    }
    
}