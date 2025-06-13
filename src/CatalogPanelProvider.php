<?php

namespace Branzia\Catalog;

use Branzia\Blueprint\BranziaPanelProvider;
use Filament\Panel;
use Filament\Pages;
use Filament\Widgets;
class CatalogPanelProvider extends BranziaPanelProvider
{
    public function panel(Panel $panel): Panel
    { 
        return $this->basePanel($panel)
            ->id('catalog')
            ->path('admin/catalog')
            ->discoverResources(in: __DIR__.'/Filament/Resources', for: 'Branzia\\Catalog\\Filament\\Resources')
            ->discoverPages(in: __DIR__.'/Filament/Pages', for: 'Branzia\\Catalog\\Filament\\Pages')
            ->discoverClusters(in: __DIR__.'/Filament/Clusters', for: 'Branzia\\Catalog\\Filament\\Clusters')
            ->discoverWidgets(in: __DIR__.'/Filament/Widgets', for: 'Branzia\\Catalog\\Filament\\Widgets')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->widgets([
                Widgets\AccountWidget::class,
            ]);
    }
}