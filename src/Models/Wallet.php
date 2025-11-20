<?php

namespace Nishant\Wallet\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Nishant\Wallet\Contracts\WalletInterface;
use Nishant\Wallet\Enums\TransactionType;
use Nishant\Wallet\Traits\Walletable;

class Wallet extends Model implements WalletInterface
{
    use SoftDeletes, Walletable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'wallets';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'name',
        'balance',
        'is_active',
        'description',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'balance' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * Get the user that owns the wallet.
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        $userModel = config('wallet.user_model', \App\Models\User::class);
        return $this->belongsTo($userModel);
    }

    /**
     * Get all transactions for this wallet.
     *
     * @return HasMany
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'wallet_id');
    }

    /**
     * Get transactions by type.
     *
     * @param TransactionType|string $type
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getTransactionsByType(TransactionType|string $type)
    {
        if ($type instanceof TransactionType) {
            return $this->transactions()->where('type', $type->value)->get();
        }
        return $this->transactions()->where('type', $type)->get();
    }

    /**
     * Scope to get active wallets.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get wallets by name.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $name
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByName($query, string $name)
    {
        return $query->where('name', $name);
    }
}

