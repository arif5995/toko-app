<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Http\Resources\ProductResource;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        // Query dasar: Hanya produk aktif (is_active = 1)
        $query = Product::with('category')->where('is_active', 1);

        // Filter pencarian berdasarkan nama (?search=...)
        if ($request->has('search') && $request->search != '') {
            $query->where('name', 'LIKE', '%' . $request->search . '%');
        }

        // Filter berdasarkan kategori (?category_id=...)
        if ($request->has('category_id') && $request->category_id != '') {
            $query->where('category_id', $request->category_id);
        }

        // Pagination 10 data per halaman
        $products = $query->paginate(10);

        return ProductResource::collection($products);
    }
}
