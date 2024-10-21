<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTransactionProductRequest;
use App\Http\Requests\UpdateTransactionProductRequest;
use App\Models\TransactionProduct;
use App\Http\Controllers\Controller;
use App\Http\Requests\ExportTransactionProductRequest;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
        return response()->json(TransactionProduct::with(['product', 'transaction.user'])->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTransactionProductRequest $request)
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
        return response()->json($transactionProduct,200);
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
        $transactionProduct = TransactionProduct::whereHas('transaction', function ($query) use ($method) {
            $query->where('payment_method', $method);
        })->get();
        return response()->json($transactionProduct);
    }

    public function exportToCsv(ExportTransactionProductRequest $request)
    {
        $filter = $request->query('filter');

        switch($filter) {
            case 'last-30-days':
                
                $transactionProduct = TransactionProduct::with(['product:id,name', 'transaction:id,payment_method'])
                ->where('created_at', '>=', now()->subDays(30))
                ->get();
                break;

            case 'month-year':
                
                $month = $request->query('month');
                $year = $request->query('year');

                
                if (!is_numeric($month) || !is_numeric($year) || $month < 1 || $month > 12) {
                    return response()->json(['Error' => 'Invalid Month or Year'], 400);
                }

                $transactionProduct = TransactionProduct::whereYear('created_at', $year)
                    ->whereMonth('created_at', $month)
                    ->get();
                break;

            case 'all':
                
                $transactionProduct = TransactionProduct::all();
                break;

            default:
                
                return response()->json(['Error' => 'Invalid Filter'], 400);
        }

        $fileName = 'transaction_products.csv';

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        
        $columns = ['ID', 'Product Name', 'Amount', 'Payment Method', 'Created At'];

        $callback = function() use ($transactionProduct, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns); 

            foreach ($transactionProduct as $product) {
                
                $productName = $product->product ? $product->product->name : 'Unknown Product';
                $paymentMethod = $product->transaction ? $product->transaction->payment_method : 'Unknown Payment Method';
                
                fputcsv($file, [
                    $product->id,
                    $productName,  
                    number_format($product->amount, 2, ',', '.'),  
                    $paymentMethod,  
                    $product->created_at->format('Y-m-d H:i:s') 
                ]);
            }
        
            fclose($file);
        };

        
        return response()->stream($callback, 200, $headers);
    }

    public function getTotalDebit()
    {  
        $totalDebit = TransactionProduct::whereHas('transaction', function ($query) {
            $query->where('payment_method', 'DEBIT');
        })->sum('amount');

        return response()->json([
            'total_debit' => $totalDebit
        ]);
    }

    public function getTotalCredit()
    {
        $totalCredit = TransactionProduct::whereHas('transaction', function ($query) {
            $query->where('payment_method', 'CREDIT');
        })->sum('amount');

        return response()->json([
            'total_credit' => $totalCredit
        ]);
    }

    public function getTotalTransactions()
    {
        $totalCredit = TransactionProduct::whereHas('transaction', function ($query) {
            $query->where('payment_method', 'CREDIT');
        })->sum('amount');

        $totalDebit = TransactionProduct::whereHas('transaction', function ($query) {
            $query->where('payment_method', 'DEBIT');
        })->sum('amount');

        return response()->json([
            'total_debit' => $totalDebit,
            'total_credit' => $totalCredit,
            'total_transactions' => $totalDebit + $totalCredit 
        ]);
    }
}
