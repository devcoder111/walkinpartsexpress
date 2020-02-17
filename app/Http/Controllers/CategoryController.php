<?php

namespace App\Http\Controllers;

use App\WebCategory;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class CategoryController extends Controller
{
    public function show($categoryId)
    {
        $products = Product::with(['images'])
            ->where('web_category_id', (int)$categoryId)
            ->where('deleted', false)->get();

        return View::make('category')
            ->with('products', $products)
            ->with('category', WebCategory::find((int)$categoryId));
    }
}
