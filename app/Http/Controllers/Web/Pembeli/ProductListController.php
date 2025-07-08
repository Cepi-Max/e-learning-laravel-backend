<?php

namespace App\Http\Controllers\Web\Pembeli;

use App\Http\Controllers\Controller;
use App\Models\Ricesales\Product;
use Illuminate\Http\Request;

class ProductListController extends Controller
{
    //
    public function productList()
    {
        $products = Product::get();
        return view('pembeli.products.index', compact('products'));
    }
}
