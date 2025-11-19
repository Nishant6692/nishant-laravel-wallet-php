<?php

namespace Nishant\Wallet\Contracts;

interface WalletInterface
{
    /**
     * Get the wallet balance.
     *
     * @return float
     */
    public function getBalance(): float;

    /**
     * Deposit amount to the wallet.
     *
     * @param float $amount
     * @param string|null $reference
     * @param string|null $description
     * @param array|null $meta
     * @return \Nishant\Wallet\Models\Transaction
     */
    public function deposit(float $amount, ?string $reference = null, ?string $description = null, ?array $meta = null);

    /**
     * Withdraw amount from the wallet.
     *
     * @param float $amount
     * @param string|null $reference
     * @param string|null $description
     * @param array|null $meta
     * @return \Nishant\Wallet\Models\Transaction
     * @throws \Exception
     */
    public function withdraw(float $amount, ?string $reference = null, ?string $description = null, ?array $meta = null);

    /**
     * Check if wallet has sufficient balance.
     *
     * @param float $amount
     * @return bool
     */
    public function hasBalance(float $amount): bool;

    /**
     * Get all transactions for this wallet.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function transactions();

    /**
     * Get transactions by type.
     *
     * @param string $type
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getTransactionsByType(string $type);
}

