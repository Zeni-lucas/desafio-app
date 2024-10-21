<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTransactionRequest;
use App\Http\Requests\UpdateTransactionRequest;
use App\Models\Transaction;
use App\Models\TransactionProduct;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Transaction::all(),200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTransactionRequest $request)
    {
        $data = $request->validated();

        $transaction = Transaction::create([
            'user_id' => $data['user_id'],
            'payment_method' => $data['payment_method'],
            'blocked' => $data['blocked'],
        ]);

        foreach ($data['products'] as $product){
            TransactionProduct::create([
                'transaction_id' => $transaction->id,
                'product_id' => $product['product_id'],
                'amount' => $product['quantity'],
            ]);
        }
        return response()->json($transaction->load('transactionProduct.product'),201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Transaction $transaction)
    {
        return $transaction;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTransactionRequest $request, Transaction $transaction)
    {
        $transaction ->update($request->validated());
        return response()->json($transaction);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaction $transaction)
    {
        $transaction->delete();
        return "Transaction Removed";
    }

    
}
