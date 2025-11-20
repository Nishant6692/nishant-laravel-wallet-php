<?php

namespace Nishant\Wallet\Traits;

use Nishant\Wallet\Models\Transaction;
use Nishant\Wallet\Enums\TransactionType;
use Illuminate\Support\Str;
use Exception;

trait Walletable
{
    /**
     * Get the wallet balance.
     *
     * @return float
     */
    public function getBalance(): float
    {
        return (float) $this->balance;
    }

    /**
     * Deposit amount to the wallet.
     *
     * @param float $amount
     * @param string|null $reference
     * @param string|null $description
     * @param array|null $meta
     * @return Transaction
     * @throws Exception
     */
    public function deposit(float $amount, ?string $reference = null, ?string $description = null, ?array $meta = null): Transaction
    {
        if ($amount <= 0) {
            throw new Exception('Deposit amount must be greater than zero.');
        }

        if (!$this->is_active) {
            throw new Exception('Cannot deposit to inactive wallet.');
        }

        $balanceBefore = $this->balance;
        $this->balance += $amount;
        $this->save();

        return $this->transactions()->create([
            'type' => TransactionType::DEPOSIT->value,
            'amount' => $amount,
            'balance_before' => $balanceBefore,
            'balance_after' => $this->balance,
            'reference' => $reference ?? Str::uuid()->toString(),
            'description' => $description,
            'meta' => $meta,
        ]);
    }

    /**
     * Withdraw amount from the wallet.
     *
     * @param float $amount
     * @param string|null $reference
     * @param string|null $description
     * @param array|null $meta
     * @return Transaction
     * @throws Exception
     */
    public function withdraw(float $amount, ?string $reference = null, ?string $description = null, ?array $meta = null): Transaction
    {
        if ($amount <= 0) {
            throw new Exception('Withdraw amount must be greater than zero.');
        }

        if (!$this->is_active) {
            throw new Exception('Cannot withdraw from inactive wallet.');
        }

        if (!$this->hasBalance($amount)) {
            throw new Exception('Insufficient balance in wallet.');
        }

        $balanceBefore = $this->balance;
        $this->balance -= $amount;
        $this->save();

        return $this->transactions()->create([
            'type' => TransactionType::WITHDRAW->value,
            'amount' => $amount,
            'balance_before' => $balanceBefore,
            'balance_after' => $this->balance,
            'reference' => $reference ?? Str::uuid()->toString(),
            'description' => $description,
            'meta' => $meta,
        ]);
    }

    /**
     * Check if wallet has sufficient balance.
     *
     * @param float $amount
     * @return bool
     */
    public function hasBalance(float $amount): bool
    {
        return $this->balance >= $amount;
    }
}

