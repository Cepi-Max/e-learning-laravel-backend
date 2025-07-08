<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ricesales\Order;
use App\Models\Ricesales\OrderItem;
use App\Models\Ricesales\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Untuk aggregation functions

class StatisticController extends Controller
{
    public function getDashboardStats(Request $request)
    {
        // Ambil data untuk user yang login, jika Anda ingin statistik spesifik per admin/penjual
        // $user = auth()->user();

        // Total Pendapatan (dari order yang 'completed' atau 'paid')
        $totalRevenue = Order::whereIn('status', ['completed', 'shipped', 'delivered']) // Sesuaikan status selesai/dibayar
                             ->sum('total_price');

        // Jumlah Pesanan Total
        $totalOrders = Order::count();

        // Jumlah Produk Terjual (dari order items yang terkait dengan order selesai)
        $totalProductsSold = OrderItem::whereHas('order', function ($query) {
                                 $query->whereIn('status', ['completed', 'shipped', 'delivered']);
                             })->sum('quantity');

        // Pesanan Berdasarkan Status
        $ordersByStatus = Order::select('status', DB::raw('count(*) as total'))
                               ->groupBy('status')
                               ->pluck('total', 'status')
                               ->toArray();

        // Produk Terlaris (Top 5 berdasarkan kuantitas terjual)
        $topSellingProducts = OrderItem::select('product_id', DB::raw('SUM(quantity) as total_quantity_sold'))
                                      ->whereHas('order', function ($query) {
                                          $query->whereIn('status', ['completed', 'shipped', 'delivered']);
                                      })
                                      ->groupBy('product_id')
                                      ->orderByDesc('total_quantity_sold')
                                      ->with('product') // Load detail produk
                                      ->limit(5)
                                      ->get();

        // Jumlah Produk di Sistem
        $totalProductsInSystem = Product::count();

        // Jumlah Pengguna Terdaftar
        $totalRegisteredUsers = User::count();

        // Anda bisa tambahkan logika untuk tren pendapatan harian/bulanan di sini
        // Contoh (sangat dasar, bisa lebih kompleks dengan date functions):
        $revenueLast7Days = Order::select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total_price) as total'))
                                  ->whereIn('status', ['completed', 'shipped', 'delivered'])
                                  ->where('created_at', '>=', now()->subDays(7))
                                  ->groupBy('date')
                                  ->orderBy('date')
                                  ->get();


        return response()->json([
            'status' => true,
            'message' => 'Statistik berhasil ditemukan',
            'data' => [
                'total_revenue' => $totalRevenue,
                'total_orders' => $totalOrders,
                'total_products_sold' => $totalProductsSold,
                'orders_by_status' => $ordersByStatus,
                'top_selling_products' => $topSellingProducts,
                'total_products_in_system' => $totalProductsInSystem,
                'total_registered_users' => $totalRegisteredUsers,
                'revenue_last_7_days' => $revenueLast7Days,
            ]
        ], 200);
    }
}