<?php

namespace App\Services\Pos;

use App\Models\PosDevice;
use App\Models\PosOrder;
use Illuminate\Support\Facades\Log;
use App\Services\Pos\Contracts\PosDriverInterface;
use App\Services\Pos\Drivers\NetworkPosDriver;
use App\Services\Pos\Drivers\UsbPosDriver;

class PosConnectionService
{
    /**
     * Attempt to automatically connect to a specific POS device.
     * Updates device status on success or failure.
     */
    public function autoConnect(PosDevice $device): bool
    {
        try {
            $driver = $this->resolveDriver($device);
            $isConnected = $driver->connect();

            if ($isConnected) {
                $device->update([
                    'status' => 'online',
                    'last_sync_at' => now(),
                ]);
                $driver->disconnect();
                return true;
            }

            $device->update(['status' => 'offline']);
            return false;
        } catch (\Exception $e) {
            Log::error("Failed to connect to POS Device {$device->id}: " . $e->getMessage());
            $device->update(['status' => 'offline']);
            return false;
        }
    }

    /**
     * Attempt to connect to ALL devices belonging to a tenant.
     * Returns an array of device IDs mapped to their connection result.
     */
    public function autoConnectAll(string $tenantId): array
    {
        $results = [];
        $devices = PosDevice::where('tenant_id', $tenantId)->get();

        foreach ($devices as $device) {
            $results[$device->id] = $this->autoConnect($device);
        }

        return $results;
    }

    /**
     * Send a print job (receipt) to the connected device.
     */
    public function printReceipt(PosDevice $device, array $receiptData): bool
    {
        $driver = $this->resolveDriver($device);

        if (!$driver->connect()) {
            $device->update(['status' => 'offline']);
            throw new \Exception("Cannot connect to device {$device->device_name} to print receipt.");
        }

        $success = $driver->printReceipt($receiptData);
        $driver->disconnect();

        return $success;
    }

    /**
     * Print a fully formatted receipt from a PosOrder model.
     */
    public function printOrderReceipt(PosDevice $device, PosOrder $order, string $storeName = 'flowexa POS'): bool
    {
        $formatter = new ReceiptFormatter();
        $rawContent = $formatter->format($order, $storeName);

        $driver = $this->resolveDriver($device);

        if (!$driver->connect()) {
            $device->update(['status' => 'offline']);
            throw new \Exception("Cannot connect to device {$device->device_name} to print order receipt.");
        }

        // Send the pre-formatted ESC/POS bytes directly
        $success = false;
        $socket = $this->getSocketFromDriver($driver);
        if ($socket) {
            $written = fwrite($socket, $rawContent);
            $success = $written !== false;
        }

        $driver->disconnect();

        return $success;
    }

    /**
     * Trigger the cash drawer to open on a device.
     */
    public function openCashDrawer(PosDevice $device): bool
    {
        $driver = $this->resolveDriver($device);

        if (!$driver->connect()) {
            $device->update(['status' => 'offline']);
            throw new \Exception("Cannot connect to device {$device->device_name} to open drawer.");
        }

        $success = $driver->openDrawer();
        $driver->disconnect();

        return $success;
    }

    /**
     * Check device health/status without keeping the connection open.
     */
    public function checkDeviceStatus(PosDevice $device): array
    {
        $driver = $this->resolveDriver($device);
        $connected = $driver->connect();

        $status = $driver->checkStatus();
        $status['device_name'] = $device->device_name;
        $status['connection_type'] = $device->connection_type ?? 'network';

        if ($connected) {
            $device->update(['status' => 'online', 'last_sync_at' => now()]);
            $driver->disconnect();
        } else {
            $device->update(['status' => 'offline']);
        }

        return $status;
    }

    /**
     * Resolve the appropriate driver based on device connection_type.
     */
    protected function resolveDriver(PosDevice $device): PosDriverInterface
    {
        $type = $device->connection_type ?? 'network';

        return match ($type) {
            'usb' => new UsbPosDriver($device),
            'network', 'ethernet', 'wifi' => new NetworkPosDriver($device),
            default => new NetworkPosDriver($device),
        };
    }

    /**
     * Reflectively access the raw socket/handle from the driver (for pre-formatted output).
     */
    protected function getSocketFromDriver(PosDriverInterface $driver)
    {
        if (property_exists($driver, 'socket') && $driver->socket) {
            return $driver->socket;
        }
        if (property_exists($driver, 'handle') && $driver->handle) {
            return $driver->handle;
        }
        return null;
    }
}
