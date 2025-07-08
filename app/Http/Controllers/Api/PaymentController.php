<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ricesales\Order;
use App\Models\Ricesales\Transaction;
use App\Services\CheckoutService;
use Illuminate\Http\Request;

use Midtrans\Notification;

class PaymentController extends Controller
{
    protected $checkoutService;

    public function __construct(CheckoutService $checkoutService)
    {
        $this->checkoutService = $checkoutService;
    }

    // public function checkout(Request $request)
    // {
    //     $order = Order::findOrFail($request->order_id);

    //     $result = $this->checkoutService->processCheckout($order);

    //     return response()->json([
    //         'snap_token' => $result['snap_token'],
    //         'transaction' => $result['transaction']
    //     ]);
    // }

    public function callback(Request $request)
    {
    // dd($request->transaction_status);
    //     $notif = new Notification();

        $order = Order::where('order_code', $request->order_id)->first();
        switch ($request->transaction_status) {
            case 'capture':
            case 'settlement':
                $order->status = 'pending';
                break;
            case 'pending':
                $order->status = 'pending';
                break;
            case 'deny':
            case 'expire':
            case 'cancel':
                $order->status = 'canceled';
                break;
        }

        if (in_array($request->transaction_status, ['capture', 'settlement'])) {
            $order->is_paid = true;
        }

        $order->save();
        return response()->json(['message' => 'Notification processed'], 200);
    }


    public function handleWebhook(Request $request)
    {
        // iniMengambil konfigurasi Server Key
        $serverKey = config('services.midtrans.server_key');

        // iniValidasi signature key dari Midtrans
        $signatureKey = hash("sha512",
            $request->order_id .
            $request->status_code .
            $request->gross_amount .
            $serverKey
        );

        if ($signatureKey !== $request->signature_key) {
            return response()->json(['message' => 'Invalid signature key'], 403);
        }

        // $transaction = Transaction::find($request->order_id);
        $transaction = Transaction::where('order_id', $request->order_id)->firstOrFail();

        if (!$transaction) {
            return response()->json(['message' => 'Transaction not found'], 404);
        }

        switch ($request->transaction_status) {
            case 'settlement': 
                $transaction->payment_status = 'paid';
                break;
            case 'pending':  
                $transaction->payment_status = 'pending';
                break;
            case 'expire':  
            case 'cancel':  
            case 'deny':    
                $transaction->payment_status = 'failed';
                break;
            case 'refund': 
                $transaction->payment_status = 'refunded';
                break;
        }


        $transaction->save();

        return response()->json(['message' => 'Webhook processed successfully']);
    }
}
