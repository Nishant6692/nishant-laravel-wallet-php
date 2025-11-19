<?php

namespace Nishant\Wallet\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Nishant\Wallet\Services\WalletService;
use Exception;

class TransactionController extends Controller
{
    /**
     * @var WalletService
     */
    protected $walletService;

    /**
     * TransactionController constructor.
     *
     * @param WalletService $walletService
     */
    public function __construct(WalletService $walletService)
    {
        $this->walletService = $walletService;
    }

    /**
     * Get transactions by wallet name.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function byWalletName(Request $request): JsonResponse
    {
        $request->validate([
            'wallet_name' => 'required|string',
        ]);

        try {
            $transactions = $this->walletService->getTransactionsByWalletName(
                auth()->id(),
                $request->wallet_name
            );

            return response()->json([
                'success' => true,
                'data' => $transactions,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Deposit to wallet by name.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function depositByName(Request $request): JsonResponse
    {
        $request->validate([
            'wallet_name' => 'required|string',
            'amount' => 'required|numeric|min:0.01',
            'reference' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'meta' => 'nullable|array',
        ]);

        try {
            $transaction = $this->walletService->depositByName(
                auth()->id(),
                $request->wallet_name,
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
     * Withdraw from wallet by name.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function withdrawByName(Request $request): JsonResponse
    {
        $request->validate([
            'wallet_name' => 'required|string',
            'amount' => 'required|numeric|min:0.01',
            'reference' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'meta' => 'nullable|array',
        ]);

        try {
            $transaction = $this->walletService->withdrawByName(
                auth()->id(),
                $request->wallet_name,
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
}

