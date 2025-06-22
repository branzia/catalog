<?php

namespace Branzia\Catalog\Filament\Pages;

use Illuminate\Support\Str;
use Kalnoy\Nestedset\QueryBuilder;
use Branzia\Catalog\Models\Category;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\IconEntry;
use Studio15\FilamentTree\Components\TreePage;

class Categories extends TreePage
{
    protected static ?string $navigationGroup = 'Catalog';

    public static function getModel(): string|QueryBuilder
    {
        return Category::class;
    }

    public static function getCreateForm(): array
    {
        return [
            TextInput::make('name')->required()->maxLength(255)->afterStateUpdated(fn (string $context, $state, callable $set) => $context === 'create' ? $set('slug', Str::slug($state)) : null),
            Toggle::make('is_active')->default(1),
            Textarea::make('description')->nullable(),
        ];
    }

    public static function getEditForm(): array
    {
        return [
            TextInput::make('name')->required(),
            TextInput::make('slug')->required()->unique(ignoreRecord: true),
            Toggle::make('is_active'),
            TextArea::make('description')->nullable(),
        ];
    }

    public static function getInfolistColumns(): array
    {
        return [
           IconEntry::make('is_active')->boolean()->label(''),
        ];
    }
}
