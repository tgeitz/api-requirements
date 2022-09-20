<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $category = Category::where('name', $request->query('category'))->first();
        // Filtering by exact price doesn't seem like a good user experience, so
        //   let's offer min/max price filters instead
        $priceMin = intval($request->query('price_min'));
        $priceMax = intval($request->query('price_max'));

        $products = Product::when($category, function ($query, $category) {
                $query->where('category_id', $category->id);
            })
            ->when($priceMin, function ($query, $priceMin) {
                $query->where('price', '>=', $priceMin);
            })
            ->when($priceMax, function ($query, $priceMax) {
                $query->where('price', '<=', $priceMax);
            })
            ->get();

        return ProductResource::collection($products);
    }
}
