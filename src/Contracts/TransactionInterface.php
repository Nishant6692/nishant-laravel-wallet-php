<?php

namespace Nishant\Wallet\Contracts;

interface TransactionInterface
{
    /**
     * Get the transaction type.
     *
     * @return string
     */
    public function getType(): string;

    /**
     * Get the transaction amount.
     *
     * @return float
     */
    public function getAmount(): float;

    /**
     * Get the balance before transaction.
     *
     * @return float
     */
    public function getBalanceBefore(): float;

    /**
     * Get the balance after transaction.
     *
     * @return float
     */
    public function getBalanceAfter(): float;

    /**
     * Get the transaction reference.
     *
     * @return string|null
     */
    public function getReference(): ?string;

    /**
     * Get the transaction description.
     *
     * @return string|null
     */
    public function getDescription(): ?string;

    /**
     * Get the transaction meta data.
     *
     * @return array|null
     */
    public function getMeta(): ?array;
}

