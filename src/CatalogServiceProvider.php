<?php

namespace Branzia\Catalog;
use Illuminate\Support\Facades\File;
use Branzia\Blueprint\BranziaServiceProvider;
use Branzia\Blueprint\Contracts\ProvidesFilamentDiscovery;
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
    }

    public function register(): void
    {
        parent::register();
        if ($this->app->runningInConsole()) {
            $this->commands([\Branzia\Catalog\Console\InstallCommand::class]);
        }
    }
    public static function filamentDiscoveryPaths(): array
    {
        return [
            'resources' => [
                ['path' => __DIR__.'/Filament/Resources', 'namespace' => 'Branzia\\Catalog\\Filament\\Resources'],
            ],
            'pages' => [
                ['path' => __DIR__.'/Filament/Pages', 'namespace' => 'Branzia\\Catalog\\Filament\\Pages'],
            ],
            'clusters' => [
                ['path' => __DIR__.'/Filament/Clusters', 'namespace' => 'Branzia\\Catalog\\Filament\\Clusters'],
            ],
            'widgets' => [
                ['path' => __DIR__.'/Filament/Widgets', 'namespace' => 'Branzia\\Catalog\\Filament\\Widgets'],
            ],
        ];
    }
}

