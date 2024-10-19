<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTransactionProductRequest;
use App\Http\Requests\UpdateTransactionProductRequest;
use App\Models\TransactionProduct;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TransactionProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $transactionProduct = TransactionProduct::with('user', 'transaction')->paginate(10);
        return response()->json($transactionProduct);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTransactionProduct $request)
    {
        $data = $request->validated();
        $transactionProduct = TransactionProduct::create($data);
        return response()->json($transactionProduct, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(TransactionProduct $transactionProduct)
    {   
        return response()->json($transactionProduct);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTransactionProductRequest $request, TransactionProduct $transactionProduct)
    {
        $transactionProduct->update($request->validated());
        if(!$transactionProduct){
            return response()->json([
                'error' => "Transaction doesn't exists!"
            ]);
        }
        return response->json($transactionProduct,200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TransactionProduct $transactionProduct)
    {
        $transactionProduct->delete();
        return response()->json([
            'Message' => "Transaction deleted Successfully!"
        ]);
        
    }

    public function getProductsByPaymentMethod($method)
    {
        $transactionProduct = TransactionProduct::where('payment_method', $method)->get();
        return response()->json($transactionProduct);
    }

    public function exportToCsv(StoreTransactionProductRequest $request)
    {
        $filter = $request->query('filter');

        switch($filter){
            case 'last-30-days':
                $transactionProduct = TransactionProduct::where('created_at', '>=', now()->subdays(30))->get();
                break;
            case 'month-year':
                $month = $request->query('month');
                $year = $request->query('year');
                $transactionProduct = TransactionProduct::whereYear('created_at', $year)
                    ->whereMonth('created_at', $month)
                    ->get();
                break;
            case 'all':
                $transactionProduct = TransactionProduct::all();
                break;
            default:
                return response()->json([
                    'Error' => "Invalid Filter"
                ], 400);
        }

        $fileName = 'transaction_products.csv';

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['ID', 'Product Name', 'Amount', 'Payment Method', 'Created At'];

        $callback = function() use ($transactionProducts, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
    
            
            foreach ($transactionProducts as $product) {
                fputcsv($file, [
                    $product->id,
                    $product->product_name,
                    $product->amount,
                    $product->payment_method,
                    $product->created_at
                ]);
            }
            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }

    public function getTotalDebit()
    {
        $totalDebit = TransactionProduct::where('payment_method', 'DEBIT')->sum('amount');
        return response()->json([
            'total_debit' => $totalDebit
        ]);
    }

    public function getTotalCredit()
    {
        $totalCredit = TransactionProduct::where('payment_method', 'CREDIT')->sum('amount');
        return response()->json([
            'total_credit' => $totalCredit
        ]);
    }

    public function getTotalTransactions()
    {
        $total = TransactionProduct::sum('amount');
        return response()->json([
            'total' => $total
        ]);
    }
}
