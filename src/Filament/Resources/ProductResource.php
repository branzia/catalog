<?php

namespace Branzia\Catalog\Filament\Resources;

use Closure;
use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Branzia\Catalog\Models\Product;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Tabs;
use Branzia\Catalog\Models\Category;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Split;
use Branzia\Catalog\Models\Attribute;
use Filament\Forms\Components\HasOne;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Support\Enums\Alignment;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\Textarea;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\MultiSelect;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\MarkdownEditor;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Branzia\Catalog\Filament\Resources\ProductResource\Pages;
use Dvarilek\FilamentTableSelect\Components\Form\TableSelect;
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
                    TextInput::make('name')->required()->maxLength(255)->afterStateUpdated(fn (string $context, $state, callable $set) => $context === 'create' ? $set('slug', \Str::slug($state)) : null),
                    TableSelect::make('categories')->label('Assigned Categories')->relationship('categories', 'name') 
                    ->multiple()->placeholder('')
                    ->selectionTable(function (Table $table) {
                        return $table->heading('Categories')
                        ->columns([
                            Tables\Columns\TextColumn::make('name')->label('Category Name')->searchable()->sortable(),
                            Tables\Columns\TextColumn::make('parent.name')->label('Parent Category'),
                        ])->filters([
                            
                        ])->modifyQueryUsing(fn (Builder $query) => $query->where('is_active', 1));
                    })->selectionAction(function (Action $action) {
                        return $action
                            ->icon('heroicon-o-bars-3-bottom-right')
                            ->modalHeading('Select Categories')
                            ->label('Browse & Select Categories')
                            ->slideOver(true);
                    })->required(),
                    TextInput::make('sku')->required(),
                    TextInput::make('price')->required()->prefix('$')->numeric()->default(0),
                    Select::make('visibility')->label('Visibility')
                    ->options([
                        'not_visible'    => 'Not Visible Individually',
                        'catalog'        => 'Catalog Only',
                        'search'         => 'Search Only',
                        'catalog_search' => 'Catalog & Search',
                    ])
                    ->default('catalog_search')
                    ->required(),
                    Fieldset::make('Set Product as New')->schema([
                        DatePicker::make('new_from')->label('From'),
                        DatePicker::make('new_to')->label('To'), 
                    ]),
                    TextInput::make('weight')->label('Weight (kg)')->numeric()->step(0.001),
                    Fieldset::make('Product Dimensions')->schema([
                        TextInput::make('length')
                            ->label('Length (cm)')
                            ->numeric()
                            ->step(0.01),

                        TextInput::make('width')
                            ->label('Width (cm)')
                            ->numeric()
                            ->step(0.01),

                        TextInput::make('height')
                            ->label('Height (cm)')
                            ->numeric()
                            ->step(0.01),
                    ])->columns(3),
                    Forms\Components\Card::make()->schema([
                        Forms\Components\Toggle::make('is_active')->label('Active')->default(true),
                        Forms\Components\Toggle::make('is_featured')->label('Featured')->default(true),
                        Forms\Components\Toggle::make('is_giftable')->label('Giftable')->default(false),
                    ])->columns(3),
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
                            ->options(\Branzia\Shop\Models\Warehouse::all()->pluck('name', 'id'))->default(1)->required(),
                        TextInput::make('qty')
                            ->label('Quantity')
                            ->numeric()->default(0)
                            ->required(),
                        Select::make('stock_status')
                            ->label('Stock Status')
                            ->options([
                                'in_stock' => 'In Stock',
                                'out_of_stock' => 'Out of Stock',
                            ])->default('in_stock')
                            ->required(),
                    ])->defaultItems(1)->minItems(1)->addActionLabel('Add Inventory')->columns(3)->extraAttributes(['class' => 'bg-gray-100 rounded-lg p-4']),
                ]),
                Tab::make('Customizable Options')->schema([
                    Repeater::make('customizableOptions')->label('Custom options let customers choose the product variations they want.')->relationship('customizableOptions')
                        ->schema([
                            TextInput::make('title')->label('Option Title')->required(),
                            Select::make('type')->label('Option Type')
                                ->options([
                                    'text' => 'Text',
                                    'textarea' => 'Textarea',
                                    'file' => 'File',
                                    'select' => 'Select',
                                    'Multiple select' => 'Select',
                                    'radio' => 'Radio',
                                    'checkbox' => 'Checkbox',
                                    ])->reactive()->required()->afterStateUpdated(function (callable $set) {
                                        $set('values', []); 
                                    }),
                            Checkbox::make('is_required')->label('Required'),
                            Repeater::make('values')->label('')->relationship('values')->schema([
                                TextInput::make('label')->label('Title')->required(function (callable $get): bool {
                                    $type = $get('../../type');
                                    $singleValueTypes = ['text', 'textarea', 'file'];
                                    if (in_array($type, $singleValueTypes)) {
                                        return false;
                                    }
                                    return true;
                                })->hidden(function (callable $get): bool {
                                    $type = $get('../../type');
                                    $singleValueTypes = ['text', 'textarea', 'file'];
                                    if (in_array($type, $singleValueTypes)) {
                                        return true;
                                    }
                                    return false;
                                }),
                                TextInput::make('price')->label('Price')->prefix('$')->numeric()->default(0),
                                Select::make('price_type')->label('Price Type')
                                ->options([
                                    'fixed' => 'Fixed',
                                    'percent' => 'Percent'
                                ])->default('fixed'),
                                TextInput::make('sku'),
                                TextInput::make('compatible_extensions')->label('Compatible File Extensions')->required(function (callable $get): bool {
                                    $type = $get('../../type');
                                    if ($type === 'file') {
                                        return true;
                                    }
                                    return false;
                                })->hidden(function (callable $get): bool {
                                    $type = $get('../../type');
                                    if ($type === 'file') {
                                        return false;
                                    }
                                    return true;
                                })->helperText('Enter separated extensions, like: png, jpg, gif.'),
                                TextInput::make('max_characters')->hidden(function (callable $get): bool {
                                    $type = $get('../../type');
                                    $singleValueTypes = ['text', 'textarea'];
                                    if (in_array($type, $singleValueTypes)) {
                                        return false;
                                    }
                                    return true;
                                })->default(100),

                            ])->defaultItems(0)->columnSpanFull()->reactive()
                            ->columns(4)
                            ->addActionLabel('Add Value')
                            ->addActionAlignment(Alignment::Start)
                            ->orderColumn('sort_order')
                            ->addable(function (callable $get): bool {
                                $type = $get('type');
                                $values = $get('values') ?? [];
                                $singleValueTypes = ['text', 'textarea', 'file'];
                                if (in_array($type, $singleValueTypes) && count($values) >= 1) {
                                    return false;
                                }
                                return true;
                            }),
                        ])->orderColumn('sort_order')->itemLabel(fn (array $state): ?string => $state['title'] ?? null)
                        ->collapsible()
                        ->defaultItems(0)->columns(3)
                        ->columnSpanFull()
                        ->addActionLabel('Add Option')->reorderable(true)->extraAttributes([
                        'class' => 'bg-gray-100 rounded-lg p-4' 
                        ])
                ])
            ])->columnSpanFull(),   
            Forms\Components\Section::make('Product Attributes')->schema([
                Repeater::make('product_attributes')->relationship('productAttributes')->label('')->schema([
                        Select::make('attribute_id')
                            ->label('Attribute')
                            ->options(Attribute::pluck('name', 'id'))
                            ->reactive()
                            ->required()->afterStateUpdated(function (callable $get,callable $set) {
                                $set('attributeValues', null); 
                        }),
                        Select::make('attributeValues')->relationship('attributeValues')
                            ->label('Value')
                            ->options(function (callable $get): array {
                                $attributeId = $get('attribute_id');
                                if (!$attributeId) return [];
                                return \Branzia\Catalog\Models\AttributeValues::where('attribute_id', $attributeId)->orderByDesc('default')->orderBy('sort_order')->pluck('value', 'id')->toArray() ?? [];
                            })->multiple(function (callable $get): bool {
                                $attributeId = $get('attribute_id');
                                if (!$attributeId) return false;
                                $attribute = Attribute::find($attributeId);
                                return $attribute?->field_type === 'multiple';
                            })->live()->required()->statePath('attributeValues'),
                    ])->columns(2)->addActionLabel('Add Attribute')->extraAttributes(['class' => 'bg-gray-100 rounded-lg p-4']),
            ]),    
            Forms\Components\Section::make('Variant Configurations')->schema([ 
                Actions::make([
                    Action::make('build_variants')
                    ->label('Build Variants')
                    ->icon('heroicon-o-plus-circle')
                    ->stickyModalFooter()
                    ->form([
                        Fieldset::make('Attribute Variants')
                        ->schema(function () {
                            return \Branzia\Catalog\Models\Attribute::where('is_configurable', 1)
                                ->with('values')
                                ->get()
                                ->map(function ($attribute) {
                                    return CheckboxList::make("selected_attributes.{$attribute->id}")
                                        ->label($attribute->name)
                                        ->options($attribute->values->pluck('value', 'id')->toArray())
                                        ->searchable();
                                })
                                ->toArray();
                        }),
                    ])
                    ->action(function (array $data, \Filament\Forms\Set $set,\Filament\Forms\Get $get) {
                        $attributes = $data['selected_attributes'] ?? [];
                        $combinations = \Branzia\Catalog\Support\VariantHelper::generate($attributes);
                        // Fill the variant_products repeater
                        $existingVariants = $get('variants') ?? []; // ← Get current repeater data
                        $set('variants', array_merge($existingVariants, $combinations));
                        \Filament\Notifications\Notification::make()->title('Variants generated successfully.')->success()->send();
                    }),
                ]), 
                Repeater::make('variants')->relationship('variants')->label('Variant Products')->schema([
                    TextInput::make('name'),
                    TextInput::make('sku')->required(),
                    TextInput::make('price')->numeric()->default(0),
                    Hidden::make('attributes'),
                ])->columns(3)->columnSpanFull()->addable(false),
            ]),
            
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('sku')->searchable()->sortable(),
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
