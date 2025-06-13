<?php

namespace Branzia\Catalog\Http\Controllers;

use Illuminate\Routing\Controller;

class ProductController extends Controller
{
    public function index()
    {
         $products = [
        ['name' => 'Laptop', 'price' => 899.99],
        ['name' => 'Smartphone', 'price' => 499.00],
        ['name' => 'Headphones', 'price' => 149.95],
    ];

    return view('catalog::products', compact('products'));
    }
}
