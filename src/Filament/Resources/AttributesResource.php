<?php

namespace Branzia\Catalog\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Resources\Pages\Page;
use Branzia\Catalog\Models\Attribute;
use Filament\Forms\Components\Section;
use Filament\Pages\SubNavigationPosition;
use Branzia\Bootstrap\Resource\ResourcePageExtensionManager;
use Branzia\Bootstrap\Resource\ResourceNavigationItemsManager;
use Branzia\Catalog\Filament\Resources\AttributesResource\Pages;

class AttributesResource extends Resource
{
    protected static ?string $model = Attribute::class;

    protected static ?string $navigationGroup = 'Catalog';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Attributes';
    protected static ?string $pluralModelLabel = 'Attributes';
    protected static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;
    public static function form(Form $form): Form
    {
        $baseSchema = [
            Section::make('Basic Information')->schema([
            Forms\Components\Fieldset::make('Attribute Info')->schema([
                Forms\Components\TextInput::make('code')
                ->required()
                ->unique(ignoreRecord: true)
                ->maxLength(50),

            Forms\Components\TextInput::make('label')
                ->required()
                ->maxLength(100),
            ]),
            Forms\Components\Toggle::make('is_required')->label('Required'),
            Forms\Components\Toggle::make('is_comparable')->label('Comparable'),
            Forms\Components\Toggle::make('is_unique')->label('Unique'),
            Forms\Components\Toggle::make('is_filterable')->label('Filterable'),
            Forms\Components\Toggle::make('is_visible_on_front')->label('Visible on Frontend'),
            ]),
            Section::make('Options')->schema([
            Forms\Components\Repeater::make('values')->relationship()->schema([
                Forms\Components\TextInput::make('value')->required()->maxLength(255),
            ])
            ->label('Values')
            ->defaultItems(1)
            ->orderColumn('sort_order')
            ->reorderable()
            ->addActionLabel('Add Value')
            ->collapsible()
            ->columnSpan('full'),
            ])
        ];
        return $form->schema(
            Form::withAdditionalField($baseSchema, static::class)
        );
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('code')->searchable()->sortable(),
            Tables\Columns\TextColumn::make('label')->searchable()->sortable(),
            Tables\Columns\IconColumn::make('is_required')->boolean(),
            Tables\Columns\IconColumn::make('is_comparable')->boolean(),
            Tables\Columns\IconColumn::make('is_unique')->boolean(),
            Tables\Columns\IconColumn::make('is_filterable')->boolean(),
            Tables\Columns\IconColumn::make('is_visible_on_front')->boolean(),
        ])
        ->filters([
            // Add filters if needed
        ])
        ->actions([
            Tables\Actions\EditAction::make(),
        ])
        ->bulkActions([
            Tables\Actions\DeleteBulkAction::make(),
        ]);
    }

    public static function getRelations(): array
    {
        return [
             
        ];
    }
    public static function getRecordSubNavigation(Page $page): array
    {
        $navigationItems = ResourceNavigationItemsManager::apply([
        ], static::class);
        return $page->generateNavigationItems($navigationItems);
    }    
    public static function getPages(): array
    {
        return ResourcePageExtensionManager::apply([
            'index' => Pages\ListAttributes::route('/'),
            'create' => Pages\CreateAttributes::route('/create'),
            'edit' => Pages\EditAttributes::route('/{record}/edit'),
         ], static::class);
    }
}
