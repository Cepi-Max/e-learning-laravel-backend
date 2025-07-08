<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ricesales\Cart;
use App\Models\Ricesales\CartItem;
use App\Models\Ricesales\Product;
use Illuminate\Support\Facades\Auth;

class CartApiController extends Controller
{
    public function index()
    {
        $cart = Cart::with('items.product')->firstOrCreate([
            'user_id' => Auth::id()
        ]);

        return response()->json($cart);
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);

        $item = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $request->product_id)
            ->first();

        if ($item) {
            $item->quantity += $request->quantity;
            $item->save();
        } else {
            $item = CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $request->product_id,
                'quantity' => $request->quantity
            ]);
        }

        return response()->json(['message' => 'Produk ditambahkan ke keranjang', 'item' => $item]);
    }

    public function update(Request $request, $itemId)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $item = CartItem::findOrFail($itemId);
        $item->quantity = $request->quantity;
        $item->save();

        return response()->json(['message' => 'Jumlah diperbarui', 'item' => $item]);
    }

    public function destroy($itemId)
    {
        $item = CartItem::findOrFail($itemId);
        $item->delete();

        return response()->json(['message' => 'Item dihapus dari keranjang']);
    }

    public function destroyMany(Request $request)
    {
        $itemIds = $request->input('item_ids'); // array of IDs

        if (!is_array($itemIds)) {
            return response()->json(['message' => 'item_ids harus berupa array'], 400);
        }

        foreach ($itemIds as $id) {
            $item = CartItem::find($id);
            if ($item) {
                if ($item->quantity > 1) {
                    $item->quantity -= 1;
                    $item->save();
                } else {
                    $item->delete();
                }
            }
        }

        return response()->json(['message' => 'Barang di keranjang berhasil dikurangi.']);
    }

    public function clear()
    {
        $cart = Cart::where('user_id', Auth::id())->first();

        if ($cart) {
            $cart->items()->delete();
        }

        return response()->json(['message' => 'Keranjang dikosongkan']);
    }
}
