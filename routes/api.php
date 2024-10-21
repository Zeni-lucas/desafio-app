<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\TransactionProductController;
use App\Http\Controllers\UserController;
use App\Models\Transaction;
use App\Models\TransactionProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::apiResource('users', UserController::class);
Route::apiResource('products', ProductController::class);
Route::apiResource('transactions',TransactionController::class);
Route::apiResource('transactionsProducts',TransactionProductController::class);

//Rotas extras para Transactions

Route::get("/transactions/getproducts/{method}", [TransactionProductController::class, 'getProductsByPaymentMethod']);
Route::get("/exportcsv", [TransactionProductController::class, 'exportToCsv']);
Route::get("/totaldebit", [TransactionProductController::class, 'getTotalDebit']);
Route::get("/totalcredit", [TransactionProductController::class, 'getTotalCredit']);
Route::get("/totaltransactions", [TransactionProductController::class, 'getTotalTransactions']);
