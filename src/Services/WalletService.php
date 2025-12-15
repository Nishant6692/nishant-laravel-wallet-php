<?php

namespace Nishant\Wallet\Services;

use Nishant\Wallet\Models\Wallet;
use Nishant\Wallet\Models\Transaction;
use Illuminate\Database\Eloquent\Collection;
use Exception;

class WalletService
{
    /**
     * Create a new wallet for a user.
     *
     * @param int $userId
     * @param string $name
     * @param string|null $description
     * @return Wallet
     */
    public function createWallet(int $userId, string $name, ?string $description = null): Wallet
    {
        $userModel = config('wallet.user_model', \App\Models\User::class);
        $user = $userModel::findOrFail($userId);

        if (!method_exists($user, 'createWallet')) {
            throw new Exception('User model must use HasWallets trait.');
        }

        return $user->createWallet($name, $description);
    }

    /**
     * Deposit amount to a wallet.
     *
     * @param int $walletId
     * @param float $amount
     * @param string|null $reference
     * @param string|null $description
     * @param array|null $meta
     * @return Transaction
     * @throws Exception
     */
    public function deposit(int $walletId, float $amount, ?string $reference = null, ?string $description = null, ?array $meta = null, bool $confirmed = true): Transaction
    {
        $wallet = Wallet::findOrFail($walletId);
        return $wallet->deposit($amount, $reference, $description, $meta, $confirmed);
    }

    /**
     * Deposit amount to a wallet by name.
     *
     * @param int $userId
     * @param string $walletName
     * @param float $amount
     * @param string|null $reference
     * @param string|null $description
     * @param array|null $meta
     * @return Transaction
     * @throws Exception
     */
    public function depositByName(int $userId, string $walletName, float $amount, ?string $reference = null, ?string $description = null, ?array $meta = null, bool $confirmed = true): Transaction
    {
        $userModel = config('wallet.user_model', \App\Models\User::class);
        $user = $userModel::findOrFail($userId);

        if (!method_exists($user, 'getWalletByName')) {
            throw new Exception('User model must use HasWallets trait.');
        }

        $wallet = $user->getWalletByName($walletName);

        if (!$wallet) {
            throw new Exception("Wallet '{$walletName}' not found for user.");
        }

        return $wallet->deposit($amount, $reference, $description, $meta, $confirmed);
    }

    /**
     * Withdraw amount from a wallet.
     *
     * @param int $walletId
     * @param float $amount
     * @param string|null $reference
     * @param string|null $description
     * @param array|null $meta
     * @return Transaction
     * @throws Exception
     */
    public function withdraw(int $walletId, float $amount, ?string $reference = null, ?string $description = null, ?array $meta = null, bool $confirmed = true): Transaction
    {
        $wallet = Wallet::findOrFail($walletId);
        return $wallet->withdraw($amount, $reference, $description, $meta, $confirmed);
    }

    /**
     * Withdraw amount from a wallet by name.
     *
     * @param int $userId
     * @param string $walletName
     * @param float $amount
     * @param string|null $reference
     * @param string|null $description
     * @param array|null $meta
     * @return Transaction
     * @throws Exception
     */
    public function withdrawByName(int $userId, string $walletName, float $amount, ?string $reference = null, ?string $description = null, ?array $meta = null, bool $confirmed = true): Transaction
    {
        $userModel = config('wallet.user_model', \App\Models\User::class);
        $user = $userModel::findOrFail($userId);

        if (!method_exists($user, 'getWalletByName')) {
            throw new Exception('User model must use HasWallets trait.');
        }

        $wallet = $user->getWalletByName($walletName);

        if (!$wallet) {
            throw new Exception("Wallet '{$walletName}' not found for user.");
        }

        return $wallet->withdraw($amount, $reference, $description, $meta, $confirmed);
    }

    /**
     * Get transactions by wallet name.
     *
     * @param int $userId
     * @param string $walletName
     * @return Collection
     */
    public function getTransactionsByWalletName(int $userId, string $walletName): Collection
    {
        $userModel = config('wallet.user_model', \App\Models\User::class);
        $user = $userModel::findOrFail($userId);

        if (!method_exists($user, 'getTransactionsByWalletName')) {
            throw new Exception('User model must use HasWallets trait.');
        }

        return $user->getTransactionsByWalletName($walletName);
    }

    /**
     * Get all transactions for a wallet.
     *
     * @param int $walletId
     * @return Collection
     */
    public function getWalletTransactions(int $walletId): Collection
    {
        $wallet = Wallet::findOrFail($walletId);
        return $wallet->transactions()->orderBy('created_at', 'desc')->get();
    }

    /**
     * Get wallet by ID.
     *
     * @param int $walletId
     * @return Wallet
     */
    public function getWallet(int $walletId): Wallet
    {
        return Wallet::findOrFail($walletId);
    }

    /**
     * Get wallet by name for a user.
     *
     * @param int $userId
     * @param string $walletName
     * @return Wallet|null
     */
    public function getWalletByName(int $userId, string $walletName): ?Wallet
    {
        $userModel = config('wallet.user_model', \App\Models\User::class);
        $user = $userModel::findOrFail($userId);

        if (!method_exists($user, 'getWalletByName')) {
            throw new Exception('User model must use HasWallets trait.');
        }

        return $user->getWalletByName($walletName);
    }

    /**
     * Confirm a pending transaction and apply it to the wallet.
     *
     * @param int $transactionId
     * @return Transaction
     * @throws Exception
     */
    public function confirmTransaction(int $transactionId): Transaction
    {
        $transaction = Transaction::findOrFail($transactionId);
        $wallet = $transaction->wallet;

        if (!$wallet) {
            throw new Exception('Wallet not found for transaction.');
        }

        return $wallet->confirmTransaction($transaction);
    }

    /**
     * Get all wallets for a user.
     *
     * @param int $userId
     * @return Collection
     */
    public function getUserWallets(int $userId): Collection
    {
        $userModel = config('wallet.user_model', \App\Models\User::class);
        $user = $userModel::findOrFail($userId);

        if (!method_exists($user, 'wallets')) {
            throw new Exception('User model must use HasWallets trait.');
        }

        return $user->wallets;
    }
}

