<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\Product;

class TransactionController extends Controller
{
    /**
     * Display a listing of the user's transactions.
     */
    public function index(Request $request)
    {
        $status = $request->query('status');
        
        $query = Transaction::where('user_id', $request->user()->id)
            ->with('items.product');
        
        if ($status) {
            $query->where('status', $status);
        }
        
        $transactions = $query->orderBy('created_at', 'desc')->get();

        return response()->json($transactions);
    }

    /**
     * Display the specified transaction.
     */
    public function show(Request $request, $id)
    {
        $transaction = Transaction::where('user_id', $request->user()->id)
            ->where('id', $id)
            ->with('items.product')
            ->firstOrFail();

        return response()->json($transaction);
    }

    /**
     * Checkout - Create new transaction.
     */
    public function checkout(Request $request)
    {
        $data = $request->validate([
            'items' => 'required|array',
            'items.*.product_id' => 'required|integer',
            'items.*.qty' => 'required|integer|min:1',
            'pickup_date' => 'required|date|after_or_equal:today',
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

        // Simpan transaksi (gunakan user yang login)
        $transaction = Transaction::create([
            'user_id' => $request->user()->id,
            'total_amount' => $total,
            'status' => 'pending',
            'pickup_date' => $request->pickup_date,
        ]);

        // Simpan item transaksi
        foreach ($items as $i) {
            $i['transaction_id'] = $transaction->id;
            TransactionItem::create($i);
        }

        return response()->json([
            'message' => 'Checkout berhasil',
            'transaction' => $transaction->load('items.product')
        ], 201);
    }
    /**
     * Admin: Get all transactions
     */
    public function adminIndex(Request $request)
    {
        $status = $request->query('status');
        
        $query = Transaction::with(['items.product', 'user']);
        
        if ($status && $status !== 'all') {
            $query->where('status', $status);
        }
        
        $transactions = $query->orderBy('created_at', 'desc')->get();

        return response()->json($transactions);
    }

    /**
     * Admin: Update transaction status
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled'
        ]);

        $transaction = Transaction::findOrFail($id);
        $transaction->status = $request->status;
        $transaction->save();

        return response()->json([
            'message' => 'Status transaksi berhasil diupdate',
            'transaction' => $transaction
        ]);
    }
}
