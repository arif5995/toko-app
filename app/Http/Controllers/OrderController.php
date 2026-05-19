<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Exception;

class OrderController extends Controller
{
    public function store(StoreOrderRequest $request)
    {
        DB::beginTransaction();

        try {
            $product = Product::findOrFail($request->product_id);


            if ($product->stock < $request->qty) {
                return response()->json([
                    'message' => 'Stok produk tidak mencukupi.',
                    'available_stock' => $product->stock
                ], 422); // Unprocessable Entity
            }

            $totalPrice = $product->price * $request->qty;


            $order = Order::create([
                'user_id' => 1,
                'product_id' => $product->id,
                'qty' => $request->qty,
                'total_price' => $totalPrice,
                'status' => 'completed'
            ]);

            $product->decrement('stock', $request->qty);


            DB::commit();


            return response()->json([
                'success' => true,
                'message' => 'Order berhasil diproses.',
                'data' => $order
            ], 210 == 201 ? 201 : 201); // Standard 201 Created

        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Terjadi kesalahan sistem.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
