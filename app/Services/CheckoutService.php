<?php

namespace App\Services;

use App\Models\Ricesales\OrderItem;
use App\Repositories\TransactionRepositoryInterface;
use Midtrans\Config;
use Midtrans\Snap;

class CheckoutService
{
    protected $transactionRepository;

    public function __construct(TransactionRepositoryInterface $transactionRepository)
    {
        $this->transactionRepository = $transactionRepository;

        // Konfigurasi Midtrans
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    public function processCheckout($order)
    {
        $transaction = $this->transactionRepository->createTransaction([
            'order_id' => $order->id,
            'user_id' => $order['user_id'],
            'payment_status' => 'pending'
        ]);
        
         // Data transaksi untuk Midtrans
         $transaction_details = [
            'order_id' => 'INV-' . $order->order_code,
            'gross_amount' => $order->total_price,
        ];

        $customer_details = [
            'first_name' => $order->user->name,
            'email' => $order->user->email,
        ];

        $item_details = [];
        foreach ($order->orderItems as $orderItem) {
            $item_details[] = [
                'id' => $orderItem->product->id,
                'price' => $orderItem->product->price,
                'quantity' => $orderItem->quantity,
                'name' => $orderItem->product->name,
            ];
        }

        $params = [
            'transaction_details' => $transaction_details,
            'customer_details' => $customer_details,
            // 'payment_type' => 'qris',
            'item_details' => $item_details,
        ];

        $snapToken = Snap::getSnapToken($params);

        return [
            'transaction' => $transaction,
            'snap_token' => "https://app.sandbox.midtrans.com/snap/v2/vtweb/$snapToken"
        ];
    }

}