<?php

namespace Nishant\Wallet\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Nishant\Wallet\Contracts\TransactionInterface;

class Transaction extends Model implements TransactionInterface
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'wallet_transactions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'wallet_id',
        'type',
        'amount',
        'balance_before',
        'balance_after',
        'reference',
        'description',
        'meta',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'amount' => 'decimal:2',
        'balance_before' => 'decimal:2',
        'balance_after' => 'decimal:2',
        'meta' => 'array',
    ];

    /**
     * Get the wallet that owns the transaction.
     *
     * @return BelongsTo
     */
    public function wallet(): BelongsTo
    {
        return $this->belongsTo(Wallet::class, 'wallet_id');
    }

    /**
     * Get the transaction type.
     *
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * Get the transaction amount.
     *
     * @return float
     */
    public function getAmount(): float
    {
        return (float) $this->amount;
    }

    /**
     * Get the balance before transaction.
     *
     * @return float
     */
    public function getBalanceBefore(): float
    {
        return (float) $this->balance_before;
    }

    /**
     * Get the balance after transaction.
     *
     * @return float
     */
    public function getBalanceAfter(): float
    {
        return (float) $this->balance_after;
    }

    /**
     * Get the transaction reference.
     *
     * @return string|null
     */
    public function getReference(): ?string
    {
        return $this->reference;
    }

    /**
     * Get the transaction description.
     *
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * Get the transaction meta data.
     *
     * @return array|null
     */
    public function getMeta(): ?array
    {
        return $this->meta;
    }

    /**
     * Scope to filter by type.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $type
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope to filter by wallet name.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $walletName
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByWalletName($query, string $walletName)
    {
        return $query->whereHas('wallet', function ($q) use ($walletName) {
            $q->where('name', $walletName);
        });
    }
}

