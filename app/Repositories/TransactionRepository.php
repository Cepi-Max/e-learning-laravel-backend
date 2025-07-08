<?php

namespace App\Repositories;

use App\Models\Ricesales\Transaction;
use Illuminate\Http\Request;

class TransactionRepository implements TransactionRepositoryInterface
{
    public function createTransaction(array $order)
    {
        $existingTransaction = Transaction::where('order_id', $order['order_id'])
        ->whereIn('payment_status', ['pending', 'paid'])
        ->first();

        if ($existingTransaction) {
            return response()->json(['message' => 'Order sudah memiliki transaksi aktif'], 400);
        }
        
        return Transaction::create($order);
    }
}
