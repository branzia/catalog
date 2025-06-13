<?php

namespace Branzia\Catalog;
use Illuminate\Support\Facades\File;
use Branzia\Blueprint\BranziaServiceProvider;
class CatalogServiceProvider extends BranziaServiceProvider
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
    }

    public function register(): void
    {
        parent::register();
        $this->app->register(CatalogPanelProvider::class);
        if ($this->app->runningInConsole()) {
            $this->commands([\Branzia\Catalog\Console\InstallCommand::class]);
        }
    }
}

