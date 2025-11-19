<?php

namespace Nishant\Wallet\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Nishant\Wallet\Services\WalletService;
use Nishant\Wallet\Models\Wallet;
use Exception;

class WalletController extends Controller
{
    /**
     * @var WalletService
     */
    protected $walletService;

    /**
     * WalletController constructor.
     *
     * @param WalletService $walletService
     */
    public function __construct(WalletService $walletService)
    {
        $this->walletService = $walletService;
    }

    /**
     * Get all wallets for the authenticated user.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            $wallets = $this->walletService->getUserWallets(auth()->id());
            return response()->json([
                'success' => true,
                'data' => $wallets,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Create a new wallet.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'currency' => 'nullable|string|size:3',
            'description' => 'nullable|string',
        ]);

        try {
            $wallet = $this->walletService->createWallet(
                auth()->id(),
                $request->name,
                $request->currency ?? 'USD',
                $request->description
            );

            return response()->json([
                'success' => true,
                'message' => 'Wallet created successfully',
                'data' => $wallet,
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get a specific wallet.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        try {
            $wallet = $this->walletService->getWallet($id);
            
            // Check if wallet belongs to authenticated user
            if ($wallet->user_id !== auth()->id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized',
                ], 403);
            }

            return response()->json([
                'success' => true,
                'data' => $wallet->load('transactions'),
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 404);
        }
    }

    /**
     * Deposit amount to a wallet.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function deposit(Request $request, int $id): JsonResponse
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'reference' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'meta' => 'nullable|array',
        ]);

        try {
            $wallet = $this->walletService->getWallet($id);
            
            // Check if wallet belongs to authenticated user
            if ($wallet->user_id !== auth()->id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized',
                ], 403);
            }

            $transaction = $this->walletService->deposit(
                $id,
                $request->amount,
                $request->reference,
                $request->description,
                $request->meta
            );

            return response()->json([
                'success' => true,
                'message' => 'Amount deposited successfully',
                'data' => $transaction,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Withdraw amount from a wallet.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function withdraw(Request $request, int $id): JsonResponse
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'reference' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'meta' => 'nullable|array',
        ]);

        try {
            $wallet = $this->walletService->getWallet($id);
            
            // Check if wallet belongs to authenticated user
            if ($wallet->user_id !== auth()->id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized',
                ], 403);
            }

            $transaction = $this->walletService->withdraw(
                $id,
                $request->amount,
                $request->reference,
                $request->description,
                $request->meta
            );

            return response()->json([
                'success' => true,
                'message' => 'Amount withdrawn successfully',
                'data' => $transaction,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Get transactions for a wallet.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function transactions(int $id): JsonResponse
    {
        try {
            $wallet = $this->walletService->getWallet($id);
            
            // Check if wallet belongs to authenticated user
            if ($wallet->user_id !== auth()->id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized',
                ], 403);
            }

            $transactions = $this->walletService->getWalletTransactions($id);

            return response()->json([
                'success' => true,
                'data' => $transactions,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 404);
        }
    }
}

