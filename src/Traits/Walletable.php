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
    public function deposit(float $amount, ?string $reference = null, ?string $description = null, ?array $meta = null, bool $confirmed = true): Transaction
    {
        if ($amount <= 0) {
            throw new Exception('Deposit amount must be greater than zero.');
        }

        if (!$this->is_active) {
            throw new Exception('Cannot deposit to inactive wallet.');
        }

        $balanceBefore = $this->balance;

        if ($confirmed) {
            $this->balance += $amount;
            $this->save();
        }

        return $this->transactions()->create([
            'type' => TransactionType::DEPOSIT->value,
            'amount' => $amount,
            'balance_before' => $balanceBefore,
            'balance_after' => $confirmed ? $this->balance : $balanceBefore,
            'reference' => $reference ?? Str::uuid()->toString(),
            'description' => $description,
            'meta' => $meta,
            'confirmed' => $confirmed,
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
    public function withdraw(float $amount, ?string $reference = null, ?string $description = null, ?array $meta = null, bool $confirmed = true): Transaction
    {
        if ($amount <= 0) {
            throw new Exception('Withdraw amount must be greater than zero.');
        }

        if (!$this->is_active) {
            throw new Exception('Cannot withdraw from inactive wallet.');
        }

        if ($confirmed && !$this->hasBalance($amount)) {
            throw new Exception('Insufficient balance in wallet.');
        }

        $balanceBefore = $this->balance;
        if ($confirmed) {
            $this->balance -= $amount;
            $this->save();
        }

        return $this->transactions()->create([
            'type' => TransactionType::WITHDRAW->value,
            'amount' => $amount,
            'balance_before' => $balanceBefore,
            'balance_after' => $confirmed ? $this->balance : $balanceBefore,
            'reference' => $reference ?? Str::uuid()->toString(),
            'description' => $description,
            'meta' => $meta,
            'confirmed' => $confirmed,
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

    /**
     * Confirm a pending transaction and apply its effect to the wallet balance.
     *
     * @param Transaction $transaction
     * @return Transaction
     * @throws Exception
     */
    public function confirmTransaction(Transaction $transaction): Transaction
    {
        if ($transaction->confirmed) {
            return $transaction;
        }

        if ((int) $transaction->wallet_id !== (int) $this->id) {
            throw new Exception('Transaction does not belong to this wallet.');
        }

        $balanceBefore = $this->balance;

        if ($transaction->type === TransactionType::DEPOSIT->value) {
            $this->balance += $transaction->amount;
        } else {
            if (!$this->hasBalance($transaction->amount)) {
                throw new Exception('Insufficient balance to confirm withdrawal.');
            }
            $this->balance -= $transaction->amount;
        }

        $this->save();

        $transaction->update([
            'balance_before' => $balanceBefore,
            'balance_after' => $this->balance,
            'confirmed' => true,
        ]);

        return $transaction->refresh();
    }
}

