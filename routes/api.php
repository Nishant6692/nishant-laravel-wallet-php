<?php

use Illuminate\Support\Facades\Route;
use Nishant\Wallet\Http\Controllers\WalletController;
use Nishant\Wallet\Http\Controllers\TransactionController;

Route::middleware(['auth:sanctum'])->group(function () {
    // Wallet routes
    Route::get('/wallets', [WalletController::class, 'index']);
    Route::post('/wallets', [WalletController::class, 'store']);
    Route::get('/wallets/{id}', [WalletController::class, 'show']);
    Route::post('/wallets/{id}/deposit', [WalletController::class, 'deposit']);
    Route::post('/wallets/{id}/withdraw', [WalletController::class, 'withdraw']);
    Route::get('/wallets/{id}/transactions', [WalletController::class, 'transactions']);

    // Transaction routes by wallet name
    Route::get('/transactions/by-wallet-name', [TransactionController::class, 'byWalletName']);
    Route::post('/transactions/deposit-by-name', [TransactionController::class, 'depositByName']);
    Route::post('/transactions/withdraw-by-name', [TransactionController::class, 'withdrawByName']);
});

