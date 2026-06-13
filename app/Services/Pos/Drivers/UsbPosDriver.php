<?php

namespace App\Services\Pos\Drivers;

use App\Services\Pos\Contracts\PosDriverInterface;
use App\Models\PosDevice;
use Illuminate\Support\Facades\Log;

class UsbPosDriver implements PosDriverInterface
{
    protected $device;
    protected $connected = false;
    protected $handle = null;

    public function __construct(PosDevice $device)
    {
        $this->device = $device;
    }

    public function connect(): bool
    {
        $devicePath = $this->device->device_path ?? '/dev/usb/lp0';

        Log::info("Attempting to connect to USB POS device: {$this->device->device_name} at {$devicePath}");

        if (!file_exists($devicePath)) {
            Log::error("USB device path not found: {$devicePath}");
            return false;
        }

        $this->handle = @fopen($devicePath, 'w');

        if (!$this->handle) {
            Log::error("Failed to open USB device: {$devicePath}");
            $this->connected = false;
            return false;
        }

        $this->connected = true;
        Log::info("Successfully connected to USB device: {$this->device->device_name}");

        return true;
    }

    public function disconnect(): void
    {
        if ($this->handle) {
            fclose($this->handle);
            $this->handle = null;
        }
        $this->connected = false;
        Log::info("Disconnected from USB POS device: {$this->device->device_name}");
    }

    public function printReceipt(array $data): bool
    {
        if (!$this->connected || !$this->handle) {
            return false;
        }

        $initCommand = "\x1B\x40";
        $cutCommand = "\x1D\x56\x41\x10";

        $content = $initCommand . ($data['content'] ?? '') . "\n\n\n\n" . $cutCommand;

        $written = fwrite($this->handle, $content);

        Log::info("Printing receipt on USB {$this->device->device_name}: bytes written " . ($written ?: 0));

        return $written !== false;
    }

    public function openDrawer(): bool
    {
        if (!$this->connected || !$this->handle) {
            return false;
        }

        $drawerCommand = "\x1B\x70\x00\x19\xFA";

        $written = fwrite($this->handle, $drawerCommand);

        Log::info("Sending open drawer pulse to USB {$this->device->device_name}");

        return $written !== false;
    }

    public function checkStatus(): array
    {
        return [
            'is_online' => $this->connected,
            'paper_low' => false,
            'cover_open' => false,
            'drawer_open' => false,
        ];
    }
}
