<?php

namespace Nishant\Wallet\Traits;

use Nishant\Wallet\Models\Wallet;
use Nishant\Wallet\Models\Transaction;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasWallets
{
    /**
     * Get all wallets for the user.
     *
     * @return HasMany
     */
    public function wallets(): HasMany
    {
        return $this->hasMany(Wallet::class, 'user_id');
    }

    /**
     * Get active wallets for the user.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function activeWallets()
    {
        return $this->wallets()->active()->get();
    }

    /**
     * Get a wallet by name.
     *
     * @param string $name
     * @return Wallet|null
     */
    public function getWalletByName(string $name): ?Wallet
    {
        return $this->wallets()->where('name', $name)->first();
    }

    /**
     * Get a wallet by slug.
     *
     * @param string $slug
     * @return Wallet|null
     */
    public function getWalletBySlug(string $slug): ?Wallet
    {
        return $this->wallets()->where('slug', $slug)->first();
    }

    /**
     * Create a new wallet for the user.
     *
     * @param string $name
     * @param string $currency
     * @param string|null $description
     * @return Wallet
     */
    public function createWallet(string $name, string $currency = 'USD', ?string $description = null): Wallet
    {
        $slug = Str::slug($name);
        
        // Ensure unique slug
        $baseSlug = $slug;
        $counter = 1;
        while ($this->wallets()->where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        return $this->wallets()->create([
            'name' => $name,
            'slug' => $slug,
            'currency' => $currency,
            'balance' => 0,
            'is_active' => true,
            'description' => $description,
        ]);
    }

    /**
     * Get all transactions across all wallets.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllTransactions()
    {
        $walletIds = $this->wallets()->pluck('id');
        return Transaction::whereIn('wallet_id', $walletIds)->get();
    }

    /**
     * Get transactions by wallet name.
     *
     * @param string $walletName
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getTransactionsByWalletName(string $walletName)
    {
        $wallet = $this->getWalletByName($walletName);
        
        if (!$wallet) {
            return collect();
        }

        return $wallet->transactions;
    }

    /**
     * Get total balance across all wallets.
     *
     * @return float
     */
    public function getTotalBalance(): float
    {
        return (float) $this->wallets()->sum('balance');
    }

    /**
     * Get total balance for a specific currency.
     *
     * @param string $currency
     * @return float
     */
    public function getTotalBalanceByCurrency(string $currency): float
    {
        return (float) $this->wallets()
            ->where('currency', $currency)
            ->sum('balance');
    }
}

