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
                Forms\Components\TextInput::make('name')->required()->maxLength(100)->afterStateUpdated(fn ($state, callable $set) => $set('code', \Str::slug($state))),
                Forms\Components\TextInput::make('code')->disabled()->dehydrated()->maxLength(50)->required()->unique(ignoreRecord: true),
            ]),
            Forms\Components\Select::make('field_type')->label('Field Type')->options(['single' => 'Single Select','multiple' => 'Multiple Select'])->default('single')->required(),
            Forms\Components\Toggle::make('is_comparable')->label('Comparable'),
            Forms\Components\Toggle::make('is_filterable')->label('Filterable'),
            Forms\Components\Toggle::make('is_visible_on_front')->label('Visible on Frontend'),
            ]),
            Section::make('Manage Values of Your Attribute')->schema([
                Forms\Components\Select::make('type')->label('Attribute Type')
                    ->options([
                        'dropdown' => 'Dropdown',
                        'visual_swatch' => 'Visual Swatch',
                        'text_swatch' => 'Text Swatch',
                    ])
                    ->required()
                    ->default('dropdown')
                    ->reactive(),
                Forms\Components\Toggle::make('use_product_image_for_swatch')->label('Use Product Image for Swatch if Possible')->visible(fn ($get) => $get('type') === 'visual_swatch'),                            
                Forms\Components\Repeater::make('values')
                    ->relationship()
                    ->label('Values')
                    ->defaultItems(1)
                    ->orderColumn('sort_order')
                    ->reorderable()
                    ->addActionLabel('Add Value')
                    ->collapsible()
                    ->columns(3)
                    ->schema([
                        Forms\Components\Toggle::make('default')->label('is Default')->inline(false),
                        Forms\Components\Hidden::make('swatch_type')
                            ->default('color')
                            ->visible(fn ($get, $livewire) =>
                                ($livewire->data['type'] ?? null) === 'visual_swatch'
                            )
                            ->reactive(),
                        Forms\Components\Hidden::make('swatch_type')->default('text')
                            ->visible(fn ($get, $livewire) =>
                                ($livewire->data['type'] ?? null) === 'text_swatch'
                            ),
                        Forms\Components\ColorPicker::make('swatch_value')->label('Swatch Color')
                            ->visible(fn ($get, $livewire) =>
                                ($livewire->data['type'] ?? null) === 'visual_swatch' &&
                                $get('swatch_type') === 'color'
                            ),
                        Forms\Components\TextInput::make('swatch_value')->label('Swatch Text')->visible(fn ($livewire) => ($livewire->data['type'] ?? null) === 'text_swatch'),
                        Forms\Components\TextInput::make('value')->required()->maxLength(255)->label('Value'),

                    ]),
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
            Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
            Tables\Columns\IconColumn::make('is_comparable')->boolean(),
            Tables\Columns\TextColumn::make('field_type'),
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
