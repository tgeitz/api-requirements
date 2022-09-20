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
        // Filters from query string
        $category = $request->query('category');
        $price = intval($request->query('price'));
        // Filtering only by exact price doesn't seem like a good user experience, so
        //   let's offer min/max price filters as well.
        $priceMin = intval($request->query('price_min'));
        $priceMax = intval($request->query('price_max'));

        $products = Product::when($category, function ($query, $category) {
                // If the user filters by a category that doesn't exist, we want to
                //   show no results instead of skipping the filter altogether
                $categoryId = Category::where('name', $category)->first()->id ?? null;
                $query->where('category_id', $categoryId);
            })
            ->when($price, function ($query, $price) {
                $query->where('price', '=', $price);
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
