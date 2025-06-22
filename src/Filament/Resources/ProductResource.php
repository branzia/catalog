<?php

namespace Branzia\Catalog\Filament\Resources;


use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Branzia\Catalog\Models\Product;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Split;
use Filament\Forms\Components\HasOne;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Support\Enums\Alignment;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\Textarea;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\MarkdownEditor;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Branzia\Catalog\Filament\Resources\ProductResource\Pages;
use Branzia\Catalog\Filament\Resources\ProductResource\RelationManagers;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;
    protected static ?string $navigationGroup = 'Catalog';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?int $navigationSort = 1;
    public static function form(Form $form): Form
    {
        return $form->schema([
            Tabs::make('Heading')->tabs([
                Tab::make('General')->schema([
                    TextInput::make('name')->required()->maxLength(255)->afterStateUpdated(fn (string $context, $state, callable $set) => $context === 'create' ? $set('slug', Str::slug($state)) : null),
                    TextInput::make('sku')->required(),
                    TextInput::make('price')->required()->prefix('$')->numeric()->default(0),
                    Fieldset::make('Set Product as New')->schema([
                        DatePicker::make('new_from')->label('From'),
                        DatePicker::make('new_to')->label('To'), 
                    ]),
                ]),
                Tab::make('Description')->schema([
                    MarkdownEditor::make('short_description'),    
                    MarkdownEditor::make('description'),    
                ]),
                Tab::make('Advanced Pricing')->schema([
                    Fieldset::make('Special Price')->schema([
                        TextInput::make('special_price')->label('Price')->prefix('$')->numeric(),
                        DatePicker::make('special_price_from')->label('From'),
                        DatePicker::make('special_price_to')->label('To'), 
                    ])->columns(3),
                ]),
                Tab::make('Inventory')->schema([
                    Repeater::make('inventories')->label('')->relationship('inventories')->schema([
                        Select::make('warehouse_id')
                            ->label('Warehouse')
                            ->options(\Branzia\Shop\Models\Warehouse::all()->pluck('name', 'id'))
                            ->required(),
                        TextInput::make('qty')
                            ->label('Quantity')
                            ->numeric()
                            ->required(),
                        Select::make('stock_status')
                            ->label('Stock Status')
                            ->options([
                                'in_stock' => 'In Stock',
                                'out_of_stock' => 'Out of Stock',
                            ])
                            ->required(),
                    ])->defaultItems(1)->minItems(1)->addActionLabel('Add Inventory')->columns(3)->extraAttributes(['class' => 'bg-gray-100 rounded-lg p-4']),
                ]),
            ])->columnSpanFull(),    
            Forms\Components\Section::make('Customizable Options')->schema([
                    Repeater::make('CustomizableOptions')->label('')->relationship('CustomizableOptions')
                        ->schema([
                            TextInput::make('title')->label('Option Title')->required(),
                            Select::make('type')->label('Option Type')
                                ->options([
                                    'text' => 'Text',
                                    'textarea' => 'Textarea',
                                    'file' => 'File',
                                    'select' => 'Select',
                                    'radio' => 'Radio',
                                    'checkbox' => 'Checkbox',
                                ])->required(),
                            Checkbox::make('is_required')->label('Required'),
                            Repeater::make('values')->label('')->relationship('values')->schema([
                                TextInput::make('label')->label('Title')->required(),
                                TextInput::make('price')->label('Price')->prefix('$')->numeric()->default(0),
                                Select::make('type')->label('Price Type')
                                ->options([
                                    'fixed' => 'Fixed',
                                    'percent' => 'Percent'
                                ]),
                            ])->collapsible()->columnSpanFull()->defaultItems(0)->columns(3)->addActionLabel('Add Value')->addActionAlignment(Alignment::Start)->orderColumn('sort_order'),
                        ])->orderColumn('sort_order')
                        ->collapsible()
                        ->defaultItems(0)->columns(3)
                        ->columnSpanFull()
                        ->addActionLabel('Add Option')->reorderable(true)->collapsed()->extraAttributes([
                        'class' => 'bg-gray-100 rounded-lg p-4' 
                        ])
            ])            
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
    
}
