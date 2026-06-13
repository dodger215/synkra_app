<?php

namespace App\Services\Pos\Contracts;

interface PosDriverInterface
{
    /**
     * Establish a connection to the POS hardware (printer/drawer)
     */
    public function connect(): bool;

    /**
     * Disconnect from the POS hardware
     */
    public function disconnect(): void;

    /**
     * Send raw receipt data or formatted layout to be printed
     */
    public function printReceipt(array $data): bool;

    /**
     * Trigger the cash drawer to open
     */
    public function openDrawer(): bool;

    /**
     * Return detailed hardware status (paper low, cover open, etc.)
     */
    public function checkStatus(): array;
}
