<?php

use Illuminate\Support\Facades\Route;

use Branzia\Catalog\Http\Controllers\ProductController;

Route::get('catalog/products', [ProductController::class, 'index']);



