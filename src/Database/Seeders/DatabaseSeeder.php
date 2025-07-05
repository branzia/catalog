<?php

namespace Branzia\Catalog\Database\Seeders;

use Illuminate\Database\Seeder;
use Branzia\Catalog\Database\Seeders\AttributesSeeder;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AttributesSeeder::class,
        ]);
    }
}
