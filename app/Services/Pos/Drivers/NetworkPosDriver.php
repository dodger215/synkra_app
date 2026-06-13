<?php

namespace App\Services\Pos\Drivers;

use App\Services\Pos\Contracts\PosDriverInterface;
use App\Models\PosDevice;
use Illuminate\Support\Facades\Log;

class NetworkPosDriver implements PosDriverInterface
{
    protected $device;
    protected $connected = false;
    protected $socket = null;

    public function __construct(PosDevice $device)
    {
        $this->device = $device;
    }

    public function connect(): bool
    {
        // Use ip_address if available, fallback to serial_number if they use that field for IP temporarily
        $ip = $this->device->ip_address ?? $this->device->serial_number;
        $port = $this->device->port ?? 9100;

        Log::info("Attempting to connect to network POS device: {$this->device->device_name} at {$ip}:{$port}");

        if (empty($ip)) {
            Log::error("No IP address configured for device: {$this->device->device_name}");
            return false;
        }

        // Attempt socket connection with a 2-second timeout
        $this->socket = @fsockopen($ip, $port, $errno, $errstr, 2);

        if (!$this->socket) {
            Log::error("Failed to connect to device {$this->device->device_name}: {$errstr} ({$errno})");
            $this->connected = false;
            return false;
        }

        // Set stream timeout for reads/writes
        stream_set_timeout($this->socket, 2);
        
        $this->connected = true;
        Log::info("Successfully connected to device: {$this->device->device_name}");
        
        return true;
    }

    public function disconnect(): void
    {
        if ($this->socket) {
            fclose($this->socket);
            $this->socket = null;
        }
        $this->connected = false;
        Log::info("Disconnected from network POS device: {$this->device->device_name}");
    }

    public function printReceipt(array $data): bool
    {
        if (!$this->connected || !$this->socket) {
            return false;
        }

        // Basic ESC/POS implementation
        $initCommand = "\x1B\x40"; // ESC @ - Initialize printer
        $cutCommand = "\x1D\x56\x41\x10"; // GS V - Cut paper
        
        // Construct the raw bytes payload
        $content = $initCommand . ($data['content'] ?? '') . "\n\n\n\n" . $cutCommand;
        
        $written = fwrite($this->socket, $content);
        
        Log::info("Printing receipt on {$this->device->device_name}: bytes written " . ($written ?: 0));
        
        return $written !== false;
    }

    public function openDrawer(): bool
    {
        if (!$this->connected || !$this->socket) {
            return false;
        }

        // ESC p m t1 t2 - Generate pulse to open cash drawer
        // m=0 (pin 2), t1=25 (25*2ms=50ms pulse), t2=250 (250*2ms=500ms off)
        $drawerCommand = "\x1B\x70\x00\x19\xFA";
        
        $written = fwrite($this->socket, $drawerCommand);
        
        Log::info("Sending open drawer pulse to {$this->device->device_name}");
        
        return $written !== false;
    }

    public function checkStatus(): array
    {
        return [
            'is_online' => $this->connected,
            'paper_low' => false,
            'cover_open' => false,
            'drawer_open' => false
        ];
    }
}
