<?php
namespace Branzia\Catalog\Console;

use Illuminate\Console\Command;

class InstallCommand extends Command
{
    protected $signature = 'catalog:install';
    protected $description = 'Install the Branzia Catalog plugin';

    public function handle()
    {
        $this->info('Running migrations...');
        $this->call('migrate');

        $this->info('Publishing config...');
        $this->call('vendor:publish', ['--tag' => 'config']);

        $this->info('Branzia Catalog installed successfully.');
    }
}
