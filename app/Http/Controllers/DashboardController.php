<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Http\Resources\OrderSummaryResource;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function summary()
    {
        // 1. Cek status apakah key 'dashboard_summary' sudah ada di memory cache
        $fromCache = Cache::has('dashboard_summary');

        // 2. Bungkus seluruh query ke dalam cache selama 300 detik sesuai petunjuk soal
        $analyticsData = Cache::remember('dashboard_summary', 300, function () {

            // A. Statistik Umum (Agregasi)
            $totalRevenue = Order::where('status', 'completed')->sum('total_price');
            $totalOrdersToday = Order::whereDate('created_at', Carbon::today())->count();
            $totalProductsActive = Product::where('is_active', 1)->count();
            $lowStockCount = Product::where('stock', '<', 5)->count();

            // B. Top 5 Produk Terlaris (Nested Eager Loading untuk cegah N+1 query)
            $topProducts = Order::selectRaw('product_id, SUM(qty) as total_sold')
                ->with(['product.category'])
                ->groupBy('product_id')
                ->orderByDesc('total_sold')
                ->take(5)
                ->get()
                ->map(function ($item) {
                    return [
                        'product_id' => $item->product_id,
                        'product_name' => $item->product->name ?? 'Unknown',
                        'category_name' => $item->product->category->name ?? 'Unknown',
                        'total_sold' => (int) $item->total_sold,
                    ];
                });

            // C. 10 Order Terbaru dengan Eager Loading mendalam
            $latestOrders = Order::with(['user', 'product.category'])
                ->orderByDesc('created_at')
                ->take(10)
                ->get();

            // Return data array asli yang akan disimpan di dalam cache
            return [
                'statistics' => [
                    'total_revenue' => 'Rp ' . number_format($totalRevenue, 0, ',', '.'),
                    'total_orders_today' => $totalOrdersToday,
                    'total_products_active' => $totalProductsActive,
                    'low_stock_count' => $lowStockCount,
                ],
                'top_five_products' => $topProducts,
                'latest_orders' => OrderSummaryResource::collection($latestOrders)
            ];
        }); // <-- Di sini kurung penutup fungsi Cache::remember() yang benar

        // 3. Kembalikan response JSON terstruktur beserta field from_cache dari soal
        return response()->json([
            'success' => true,
            'from_cache' => $fromCache,
            'message' => 'Data dashboard analytics berhasil dimuat.',
            'data' => $analyticsData
        ], 200);
    }

    // 4. Endpoint tambahan untuk membersihkan/flush cache secara manual
    public function flushCache()
    {
        Cache::forget('dashboard_summary');

        return response()->json([
            'success' => true,
            'message' => 'Cache dashboard_summary berhasil dihapus.'
        ], 200);
    }
}
