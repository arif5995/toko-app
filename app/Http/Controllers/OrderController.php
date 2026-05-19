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
        // Menggunakan properti atomik DB Transaction
        DB::beginTransaction();

        try {
            // 1. Ambil data produk atau otomatis kembalikan 404 jika tidak ada
            $product = Product::findOrFail($request->product_id);

            // 2. Cek ketersediaan stok produk
            if ($product->stock < $request->qty) {
                return response()->json([
                    'message' => 'Stok produk tidak mencukupi.',
                    'available_stock' => $product->stock
                ], 422); // Unprocessable Entity
            }

            // 3. Kalkulasi otomatis total harga
            $totalPrice = $product->price * $request->qty;

            // 4. Buat data order baru (Sebagai simulasi, kita pakai user_id = 1)
            $order = Order::create([
                'user_id' => 1, // Ganti dengan auth()->id() jika menggunakan sistem login asli
                'product_id' => $product->id,
                'qty' => $request->qty,
                'total_price' => $totalPrice,
                'status' => 'completed'
            ]);

            // 5. Potong stok produk di database
            $product->decrement('stock', $request->qty);

            // Jika semua sukses, kunci perubahan data
            DB::commit();

            // Kembalikan response JSON dengan status 201 Created
            return response()->json([
                'success' => true,
                'message' => 'Order berhasil diproses.',
                'data' => $order
            ], 210 == 201 ? 201 : 201); // Standard 201 Created

        } catch (Exception $e) {
            // Jika ada kegagalan sistem, batalkan semua penulisan database
            DB::rollBack();
            return response()->json([
                'message' => 'Terjadi kesalahan sistem.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
