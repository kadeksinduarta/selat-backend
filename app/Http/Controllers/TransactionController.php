<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function checkout(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'required|integer',
            'items' => 'required|array',
            'items.*.product_id' => 'required|integer',
            'items.*.qty' => 'required|integer|min:1'
        ]);

        $total = 0;
        $items = [];

        foreach ($data['items'] as $item) {
            $product = Product::findOrFail($item['product_id']);

            if ($product->stock < $item['qty']) {
                return response()->json([
                    'message' => 'Stok tidak cukup untuk produk ' . $product->name
                ], 400);
            }

            $price = $product->price * $item['qty'];
            $total += $price;

            $items[] = [
                'product_id' => $product->id,
                'qty' => $item['qty'],
                'price' => $product->price
            ];

            // Kurangi stok
            $product->stock -= $item['qty'];
            $product->save();
        }

        // Simpan transaksi
        $transaction = Transaction::create([
            'user_id' => $data['user_id'],
            'total_amount' => $total,
            'status' => 'pending'
        ]);

        // Simpan item transaksi
        foreach ($items as $i) {
            $i['transaction_id'] = $transaction->id;
            TransactionItem::create($i);
        }

        return [
            'message' => 'Checkout berhasil',
            'transaction' => $transaction->load('items.product')
        ];
    }

}
