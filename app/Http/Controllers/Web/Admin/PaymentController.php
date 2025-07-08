<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ricesales\Payment;
use App\Models\Ricesales\Order;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    // Tampilkan semua payment
    public function index()
    {
        $payments = Payment::with(['order', 'user'])->latest()->paginate(10);
        return view('payments.index', compact('payments'));
    }

    // Form input pembayaran
    public function create()
    {
        $orders = Order::where('status', 'pending')->get();
        return view('payments.create', compact('orders'));
    }

    // Simpan pembayaran
    public function store(Request $request)
    {
        $validated = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'payment_method' => 'required|in:bank transfer,e-wallet,cod',
            'amount' => 'required|numeric|min:0',
            'transaction_id' => 'nullable|string',
        ]);

        Payment::create([
            'order_id' => $validated['order_id'],
            'user_id' => Auth::id(),
            'payment_method' => $validated['payment_method'],
            'payment_status' => 'paid',
            'transaction_id' => $validated['transaction_id'],
            'amount' => $validated['amount'],
        ]);

        // Update status order jadi paid
        $order = Order::findOrFail($validated['order_id']);
        $order->status = 'paid';
        $order->save();

        return redirect()->route('payments.index')->with('success', 'Pembayaran berhasil disimpan');
    }

    // Detail pembayaran
    public function show($id)
    {
        $payment = Payment::with(['order', 'user'])->findOrFail($id);
        return view('payments.show', compact('payment'));
    }

    // Ubah status pembayaran
    public function updateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'payment_status' => 'required|in:pending,paid,failed',
        ]);

        $payment = Payment::findOrFail($id);
        $payment->payment_status = $validated['payment_status'];
        $payment->save();

        return redirect()->route('payments.index')->with('success', 'Status pembayaran berhasil diupdate');
    }

    // Hapus pembayaran
    public function destroy($id)
    {
        $payment = Payment::findOrFail($id);
        $payment->delete();

        return redirect()->route('payments.index')->with('success', 'Pembayaran berhasil dihapus');
    }
}
