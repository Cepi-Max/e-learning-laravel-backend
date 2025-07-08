<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ricesales\Order;
use App\Models\Ricesales\OrderItem;
use App\Models\Ricesales\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    // List semua order
    public function index()
    {
        $orders = Order::with('user')->latest()->paginate(10);
        return view('orders.index', compact('orders'));
    }

    // Form create order
    public function create()
    {
        $products = Product::all();
        return view('orders.create', compact('products'));
    }

    // Simpan order baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
        ]);

        DB::beginTransaction();
        try {
            $total = 0;
            $items = [];

            foreach ($request->products as $item) {
                $product = Product::findOrFail($item['product_id']);
                $subtotal = $product->price * $item['quantity'];
                $total += $subtotal;

                $items[] = [
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price' => $product->price,
                    'subtotal' => $subtotal,
                ];
            }

            $order = Order::create([
                'user_id' => Auth::id(),
                'order_code' => strtoupper(Str::random(8)),
                'total_price' => $total,
                'status' => 'pending',
            ]);

            foreach ($items as $item) {
                $item['order_id'] = $order->id;
                OrderItem::create($item);
            }

            DB::commit();
            return redirect()->route('orders.index')->with('success', 'Order berhasil dibuat');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Gagal membuat order: ' . $e->getMessage());
        }
    }

    // Detail order
    public function show($id)
    {
        $order = Order::with(['user', 'orderItems.product'])->findOrFail($id);
        return view('orders.show', compact('order'));
    }

    // Update status order
    public function updateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,paid,shipped,delivered,canceled',
        ]);

        $order = Order::findOrFail($id);
        $order->status = $validated['status'];
        $order->save();

        return redirect()->route('orders.index')->with('success', 'Status order diperbarui');
    }

    // Hapus order
    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        $order->delete();

        return redirect()->route('orders.index')->with('success', 'Order berhasil dihapus');
    }
}
