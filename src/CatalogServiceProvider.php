<?php

namespace Branzia\Catalog;
use Illuminate\Support\Facades\File;
use Branzia\Blueprint\BranziaServiceProvider;
use Branzia\Blueprint\Contracts\ProvidesFilamentDiscovery;
use Branzia\Settings\Services\SettingsRegistry;
class CatalogServiceProvider extends BranziaServiceProvider implements ProvidesFilamentDiscovery
{
    public function moduleName(): string
    {
        return 'Catalog';
    }
    public function moduleRootPath():string{
        return dirname(__DIR__);
    }
    
    public function boot():void
    {
        parent::boot();
        SettingsRegistry::push(\Branzia\Catalog\Filament\Settings\Category::class);
        SettingsRegistry::push(\Branzia\Catalog\Filament\Settings\Product::class);
    }

    public function register(): void
    {
        parent::register();
    }

}

