# Nishant Wallet - Multi-Wallet Management Package for Laravel 11

[![Latest Version on Packagist](https://img.shields.io/packagist/v/nishant/wallet.svg?style=flat-square)](https://packagist.org/packages/nishant/wallet)
[![Total Downloads](https://img.shields.io/packagist/dt/nishant/wallet.svg?style=flat-square)](https://packagist.org/packages/nishant/wallet)
[![License](https://img.shields.io/packagist/l/nishant/wallet.svg?style=flat-square)](https://packagist.org/packages/nishant/wallet)

A comprehensive Laravel package for managing multiple wallets per user with deposit, withdraw, and transaction tracking capabilities. Built for Laravel 11 and PHP 8.0+.

## Features

- ✅ Multiple wallets per user
- ✅ Deposit and withdraw functionality
- ✅ Transaction history tracking
- ✅ Get transactions by wallet name
- ✅ Interface-based architecture
- ✅ Trait-based implementation
- ✅ RESTful API endpoints
- ✅ Soft deletes support
- ✅ Currency support
- ✅ Balance validation

## Requirements

- PHP >= 8.0
- Laravel >= 11.0

## Installation

### Step 1: Install the Package

#### Option A: Install from Packagist (Production)

If the package is published to Packagist, use:

```bash
composer require nishant/wallet
```

**Note**: If you get a stability error, make sure the package has a stable version tag (v1.0.0) in the Git repository. You can also install a specific version:

```bash
composer require nishant/wallet:^1.0
```

Or if you need to allow dev stability temporarily:

```bash
composer require nishant/wallet --dev
```

#### Option B: Local Development Setup

If you're developing the package locally or testing it before publishing, add it to your Laravel project's `composer.json`:

```json
{
    "require": {
        "nishant/wallet": "*"
    },
    "repositories": [
        {
            "type": "path",
            "url": "./nishant-wallet-laravel"
        }
    ]
}
```

**Important Notes for Local Development:**
- The package folder (`nishant-wallet-laravel`) should be in the same directory as your Laravel project
- After adding this, run `composer update` to link the package
- Changes in the package will be immediately available (no need to reinstall)
- The package will be symlinked, so you can edit it directly
- For production, remove the `repositories` section and use `composer require nishant/wallet` instead

**Project Structure:**
```
your-laravel-project/
├── composer.json
├── app/
├── ...
└── nishant-wallet-laravel/    ← Package folder (same level)
    ├── composer.json
    ├── src/
    └── ...
```

After adding the repository configuration, run:
```bash
composer update nishant/wallet
```

### Step 2: Publish Configuration and Migrations

Publish the configuration file:

```bash
php artisan vendor:publish --tag=wallet-config
```

Publish the migrations:

```bash
php artisan vendor:publish --tag=wallet-migrations
```

### Step 3: Run Migrations

Run the database migrations:

```bash
php artisan migrate
```

### Step 4: Add Trait to User Model

Add the `HasWallets` trait to your User model:

```php
<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Nishant\Wallet\Traits\HasWallets;

class User extends Authenticatable
{
    use HasWallets;

    // ... rest of your User model
}
```

## Configuration

The configuration file is located at `config/wallet.php`. You can customize:

- **user_model**: The User model class (default: `App\Models\User`)
- **route_prefix**: API route prefix (default: `api/wallet`)
- **middleware**: Route middleware (default: `['api']`)
- **default_currency**: Default currency code (default: `USD`)
- **decimal_places**: Number of decimal places for balances (default: `2`)

## Usage

### Creating Wallets

#### Using the Trait (Recommended)

```php
use App\Models\User;

$user = User::find(1);

// Create a new wallet
$wallet = $user->createWallet('Main Wallet', 'USD', 'Primary wallet for transactions');

// Create another wallet
$savingsWallet = $user->createWallet('Savings', 'USD', 'Savings account');
```

#### Using the Service

```php
use Nishant\Wallet\Services\WalletService;

$walletService = app(WalletService::class);

$wallet = $walletService->createWallet(
    userId: 1,
    name: 'Main Wallet',
    currency: 'USD',
    description: 'Primary wallet'
);
```

### Depositing Amount

#### Using the Wallet Model

```php
$wallet = $user->getWalletByName('Main Wallet');

$transaction = $wallet->deposit(
    amount: 100.00,
    reference: 'DEP-001',
    description: 'Initial deposit',
    meta: ['source' => 'bank_transfer']
);
```

#### Using the Service

```php
// By wallet ID
$transaction = $walletService->deposit(
    walletId: $wallet->id,
    amount: 100.00,
    reference: 'DEP-001',
    description: 'Initial deposit'
);

// By wallet name
$transaction = $walletService->depositByName(
    userId: 1,
    walletName: 'Main Wallet',
    amount: 100.00
);
```

### Withdrawing Amount

#### Using the Wallet Model

```php
$wallet = $user->getWalletByName('Main Wallet');

try {
    $transaction = $wallet->withdraw(
        amount: 50.00,
        reference: 'WD-001',
        description: 'Payment for service',
        meta: ['recipient' => 'vendor-123']
    );
} catch (\Exception $e) {
    // Handle insufficient balance or other errors
    echo $e->getMessage();
}
```

#### Using the Service

```php
// By wallet ID
$transaction = $walletService->withdraw(
    walletId: $wallet->id,
    amount: 50.00
);

// By wallet name
$transaction = $walletService->withdrawByName(
    userId: 1,
    walletName: 'Main Wallet',
    amount: 50.00
);
```

### Getting Transactions

#### Get All Transactions for a Wallet

```php
$wallet = $user->getWalletByName('Main Wallet');
$transactions = $wallet->transactions;
```

#### Get Transactions by Wallet Name

```php
// Using the trait
$transactions = $user->getTransactionsByWalletName('Main Wallet');

// Using the service
$transactions = $walletService->getTransactionsByWalletName(
    userId: 1,
    walletName: 'Main Wallet'
);
```

#### Get Transactions by Type

```php
$deposits = $wallet->getTransactionsByType('deposit');
$withdrawals = $wallet->getTransactionsByType('withdraw');
```

### Getting Wallet Information

```php
// Get all wallets
$wallets = $user->wallets;

// Get active wallets only
$activeWallets = $user->activeWallets();

// Get wallet by name
$wallet = $user->getWalletByName('Main Wallet');

// Get wallet by slug
$wallet = $user->getWalletBySlug('main-wallet');

// Get wallet balance
$balance = $wallet->getBalance();

// Check if wallet has sufficient balance
if ($wallet->hasBalance(100.00)) {
    // Proceed with transaction
}

// Get total balance across all wallets
$totalBalance = $user->getTotalBalance();

// Get total balance by currency
$usdBalance = $user->getTotalBalanceByCurrency('USD');
```

## API Endpoints

The package provides RESTful API endpoints. Make sure you have authentication middleware configured.

### Wallet Endpoints

- `GET /api/wallet/wallets` - Get all wallets for authenticated user
- `POST /api/wallet/wallets` - Create a new wallet
- `GET /api/wallet/wallets/{id}` - Get a specific wallet with transactions
- `POST /api/wallet/wallets/{id}/deposit` - Deposit amount to wallet
- `POST /api/wallet/wallets/{id}/withdraw` - Withdraw amount from wallet
- `GET /api/wallet/wallets/{id}/transactions` - Get transactions for a wallet

### Transaction Endpoints

- `GET /api/wallet/transactions/by-wallet-name?wallet_name=Main Wallet` - Get transactions by wallet name
- `POST /api/wallet/transactions/deposit-by-name` - Deposit to wallet by name
- `POST /api/wallet/transactions/withdraw-by-name` - Withdraw from wallet by name

### API Request Examples

#### Create Wallet

```bash
POST /api/wallet/wallets
Content-Type: application/json
Authorization: Bearer {token}

{
    "name": "Main Wallet",
    "currency": "USD",
    "description": "Primary wallet"
}
```

#### Deposit Amount

```bash
POST /api/wallet/wallets/1/deposit
Content-Type: application/json
Authorization: Bearer {token}

{
    "amount": 100.00,
    "reference": "DEP-001",
    "description": "Initial deposit",
    "meta": {
        "source": "bank_transfer"
    }
}
```

#### Withdraw Amount

```bash
POST /api/wallet/wallets/1/withdraw
Content-Type: application/json
Authorization: Bearer {token}

{
    "amount": 50.00,
    "reference": "WD-001",
    "description": "Payment for service"
}
```

#### Get Transactions by Wallet Name

```bash
GET /api/wallet/transactions/by-wallet-name?wallet_name=Main Wallet
Authorization: Bearer {token}
```

## Interfaces

The package uses interfaces for better code organization:

### WalletInterface

```php
use Nishant\Wallet\Contracts\WalletInterface;

interface WalletInterface
{
    public function getBalance(): float;
    public function deposit(float $amount, ?string $reference = null, ?string $description = null, ?array $meta = null);
    public function withdraw(float $amount, ?string $reference = null, ?string $description = null, ?array $meta = null);
    public function hasBalance(float $amount): bool;
    public function transactions();
    public function getTransactionsByType(string $type);
}
```

### TransactionInterface

```php
use Nishant\Wallet\Contracts\TransactionInterface;

interface TransactionInterface
{
    public function getType(): string;
    public function getAmount(): float;
    public function getBalanceBefore(): float;
    public function getBalanceAfter(): float;
    public function getReference(): ?string;
    public function getDescription(): ?string;
    public function getMeta(): ?array;
}
```

## Traits

### HasWallets Trait

Add this trait to your User model to enable wallet functionality:

```php
use Nishant\Wallet\Traits\HasWallets;

class User extends Model
{
    use HasWallets;
}
```

This trait provides methods like:
- `wallets()` - Relationship to wallets
- `createWallet()` - Create a new wallet
- `getWalletByName()` - Get wallet by name
- `getTransactionsByWalletName()` - Get transactions by wallet name
- `getTotalBalance()` - Get total balance across all wallets

### Walletable Trait

This trait is automatically used by the Wallet model and provides:
- `deposit()` - Deposit amount
- `withdraw()` - Withdraw amount
- `hasBalance()` - Check balance
- `getBalance()` - Get current balance

## Database Schema

### Wallets Table

- `id` - Primary key
- `user_id` - Foreign key to users table
- `name` - Wallet name
- `slug` - Unique slug for wallet
- `currency` - Currency code (3 characters)
- `balance` - Current balance (decimal 15,2)
- `is_active` - Active status
- `description` - Optional description
- `timestamps` - Created/updated timestamps
- `deleted_at` - Soft delete timestamp

### Wallet Transactions Table

- `id` - Primary key
- `wallet_id` - Foreign key to wallets table
- `type` - Transaction type (deposit/withdraw)
- `amount` - Transaction amount
- `balance_before` - Balance before transaction
- `balance_after` - Balance after transaction
- `reference` - Optional reference number
- `description` - Optional description
- `meta` - JSON metadata
- `timestamps` - Created/updated timestamps

## Error Handling

The package throws exceptions for various scenarios:

- **Insufficient Balance**: When trying to withdraw more than available balance
- **Inactive Wallet**: When trying to deposit/withdraw from inactive wallet
- **Invalid Amount**: When amount is zero or negative
- **Wallet Not Found**: When wallet doesn't exist

Always wrap wallet operations in try-catch blocks:

```php
try {
    $transaction = $wallet->withdraw(100.00);
} catch (\Exception $e) {
    // Handle error
    logger()->error('Wallet operation failed: ' . $e->getMessage());
}
```

## Testing

To test the package, you can use Laravel's testing features:

```php
use Tests\TestCase;
use App\Models\User;
use Nishant\Wallet\Traits\HasWallets;

class WalletTest extends TestCase
{
    public function test_can_create_wallet()
    {
        $user = User::factory()->create();
        $wallet = $user->createWallet('Test Wallet');
        
        $this->assertNotNull($wallet);
        $this->assertEquals('Test Wallet', $wallet->name);
    }

    public function test_can_deposit_amount()
    {
        $user = User::factory()->create();
        $wallet = $user->createWallet('Test Wallet');
        
        $transaction = $wallet->deposit(100.00);
        
        $this->assertEquals(100.00, $wallet->fresh()->balance);
        $this->assertEquals('deposit', $transaction->type);
    }

    public function test_can_withdraw_amount()
    {
        $user = User::factory()->create();
        $wallet = $user->createWallet('Test Wallet');
        $wallet->deposit(100.00);
        
        $transaction = $wallet->withdraw(50.00);
        
        $this->assertEquals(50.00, $wallet->fresh()->balance);
        $this->assertEquals('withdraw', $transaction->type);
    }

    public function test_cannot_withdraw_insufficient_balance()
    {
        $user = User::factory()->create();
        $wallet = $user->createWallet('Test Wallet');
        $wallet->deposit(50.00);
        
        $this->expectException(\Exception::class);
        $wallet->withdraw(100.00);
    }
}
```

## License

This package is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## Support

For issues, questions, or contributions, please open an issue on the GitHub repository.

## Changelog

### Version 1.0.0
- Initial release
- Multiple wallets per user
- Deposit and withdraw functionality
- Transaction tracking
- Get transactions by wallet name
- Interface and trait implementation
- RESTful API endpoints

