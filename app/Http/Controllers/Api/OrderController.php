<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ricesales\Order;
use App\Models\Ricesales\OrderItem;
use App\Models\Ricesales\Payment;
use App\Models\Ricesales\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Midtrans\Snap;

class OrderController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'user') {
            // Ambil order user sendiri + order items-nya
            // >>> PERBAIKAN: Tambahkan .user untuk memuat relasi penjual dari produk
            $orderdata = Order::with('orderItems.product.user', 'user')
                ->where('user_id', $user->id)
                ->latest()
                ->get();

        } else if ($user->role === 'admin') {
            $adminProductIds = Product::where('user_id', $user->id)->pluck('id');
            $orderIds = OrderItem::whereIn('product_id', $adminProductIds)
                ->pluck('order_id')
                ->unique();
            // >>> PERBAIKAN: Tambahkan .user untuk memuat relasi penjual dari produk
            $orderdata = Order::with(['orderItems.product.user', 'user'])
                ->whereIn('id', $orderIds)
                ->latest()
                ->get();

        } else {
            return response()->json([
                'status' => false,
                'message' => 'Role tidak dikenali'
            ], 403);
        }

        return response()->json([
            'status' => true,
            'message' => 'Data berhasil ditemukan',
            'data' => $orderdata
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        DB::beginTransaction();

        try {
            $total_price = 0;
            $productsToUpdate = [];

            // 1. Validasi dan kunci stok produk
            foreach ($request->items as $item) {
                $product = Product::lockForUpdate()->findOrFail($item['product_id']);

                if ($product->stock < $item['quantity']) {
                    throw new \Exception("Stok untuk produk {$product->name} tidak mencukupi.");
                }

                $total_price += $product->price * $item['quantity'];
                $productsToUpdate[] = [$product, $item['quantity']];
            }

            // 2. Buat order
            $order = Order::create([
                'user_id' => $request->user_id,
                'order_code' => 'ORD-' . now()->format('YmdHis'),
                'total_price' => $total_price,
                'status' => 'pending'
            ]);

            // 3. Buat order items dan kurangi stok
            foreach ($productsToUpdate as [$product, $qty]) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $qty,
                    'price' => $product->price,
                    'subtotal' => $product->price * $qty
                ]);

                $product->stock -= $qty;
                $product->save();
            }

            // 4. Bersihkan cart (jika ada)
            $userCart = \App\Models\Ricesales\Cart::where('user_id', $request->user_id)->first();
            if ($userCart) {
                $cartItemProductIds = collect($request->items)->pluck('product_id')->toArray();
                $userCart->items()->whereIn('product_id', $cartItemProductIds)->delete();
            }

            // 5. Midtrans setup
            \Midtrans\Config::$serverKey = config('services.midtrans.server_key');
            \Midtrans\Config::$isProduction = config('services.midtrans.is_production');
            \Midtrans\Config::$isSanitized = true;
            \Midtrans\Config::$is3ds = true;

            $user = \App\Models\User::findOrFail($request->user_id);

            $snapPayload = [
                'transaction_details' => [
                    'order_id' => $order->order_code,
                    'gross_amount' => $total_price,
                ],
                'customer_details' => [
                    'first_name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phone_number,
                ],
                'enabled_payments' => ['gopay', 'bank_transfer', 'qris'],
            ];

            $snapToken = Snap::getSnapToken($snapPayload);
            $order->midtrans_transaction_token = $snapToken;
            $order->save();

            DB::commit(); // ✅ commit di akhir

            return response()->json([
                'message' => 'Order created',
                'order' => $order,
                'snap_token' => $snapToken
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Gagal memproses order',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function show($id)
    {
        // >>> PERBAIKAN: Tambahkan .user untuk memuat relasi penjual dari produk
        $order = Order::with('orderItems.product.user', 'user')->find($id);
        if (!$order) {
            return response()->json(['error' => 'Order not found'], 404);
        }
        return response()->json($order, 200);
    }

    public function update(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        // kalo request hanya update status
        if ($request->has('status') && !$request->has('items')) {
            $validator = Validator::make($request->all(), [
                'status' => 'required|in:pending,shipped,delivered,completed,canceled'
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 422);
            }

            $order->update(['status' => $request->status]);

            return response()->json(['message' => 'Status pesanan berhasil diperbarui', 'order' => $order], 200);
        }

        if ($order->status !== 'pending') {
            return response()->json(['error' => 'Pesanan sudah diproses, tidak dapat mengubah item'], 400);
        }

        $validator = Validator::make($request->all(), [
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'status' => 'nullable|in:pending,paid,shipped,delivered,canceled'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        // Pastikan Anda ingin menghapus semua order item dan membuat yang baru.
        // Jika tidak, Anda bisa menggunakan metode updateOrCreate atau melakukan pengecekan.
        OrderItem::where('order_id', $id)->delete();

        $total_price = 0;
        foreach ($request->items as $item) {
            $product = Product::find($item['product_id']);
            if (!$product) {
                 return response()->json(['error' => 'Product with ID ' . $item['product_id'] . ' not found.'], 404);
            }
            $subtotal = $product->price * $item['quantity'];
            $total_price += $subtotal;

            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'price' => $product->price,
                'subtotal' => $subtotal,
            ]);
        }

        // Total harga ya bos
        $order->total_price = $total_price;
        if ($request->has('status')) {
            $order->status = $request->status;
        }
        $order->save();

        return response()->json(['message' => 'Order berhasil diperbarui', 'order' => $order], 200);
    }


    public function destroy(string $id)
    {
        $dataorder = Order::findOrFail($id);

        if (in_array($dataorder->status, ['paid', 'shipped', 'delivered'])) {
            return response()->json([
                'status' => false,
                'message' => 'Pesanan tidak dapat dihapus karena sudah diproses atau sedang dikirim.'
            ], 403);
        }

        $dataorder->delete();

        return response()->json([
            'status' => true,
            'message' => 'Data berhasil dihapus.'
        ], 200);
    }


    // Process payment
    public function makePayment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required|exists:orders,id',
            'user_id' => 'required|exists:users,id',
            'payment_method' => 'required|in:bank transfer,e-wallet,cod',
            'amount' => 'required|numeric|min:0'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $order = Order::find($request->order_id);
        if (!$order) {
            return response()->json(['error' => 'Order not found'], 404);
        }

        if ($order->total_price != $request->amount) {
            return response()->json(['error' => 'Invalid payment amount'], 400);
        }

        $payment = Payment::create([
            'order_id' => $order->id,
            'user_id' => $request->user_id,
            'payment_method' => $request->payment_method,
            'payment_status' => 'paid',
            'amount' => $request->amount,
            'transaction_id' => 'TXN-' . now()->format('YmdHis')
        ]);

        // Perbarui status pesanan menjadi 'paid' jika ini adalah metode pembayaran backend Anda
        // Perhatikan bahwa untuk pembayaran Midtrans, status 'is_paid' mungkin diperbarui oleh webhook
        $order->update(['status' => 'paid', 'is_paid' => true]);

        return response()->json(['message' => 'Payment successful', 'payment' => $payment], 200);
    }
}