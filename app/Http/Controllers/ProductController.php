<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Http\Resources\ProductResource;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category')->where('is_active', 1);
        if ($request->has('search') && $request->search != '') {
            $query->where('name', 'LIKE', '%' . $request->search . '%');
        }


        if ($request->has('category_id') && $request->category_id != '') {
            $query->where('category_id', $request->category_id);
        }


        $products = $query->paginate(10);

        return ProductResource::collection($products);
    }
}
