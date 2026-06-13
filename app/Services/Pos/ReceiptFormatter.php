<?php

namespace App\Services\Pos;

use App\Models\PosOrder;
use App\Models\PosOrderItem;

class ReceiptFormatter
{
    protected int $lineWidth;

    public function __construct(int $lineWidth = 48)
    {
        $this->lineWidth = $lineWidth;
    }

    /**
     * Build a full ESC/POS byte string for a given POS order.
     */
    public function format(PosOrder $order, string $storeName = 'Synkra POS'): string
    {
        $esc = "\x1B";
        $gs = "\x1D";

        $init = "{$esc}\x40";                       // ESC @ - Initialize
        $centerOn = "{$esc}\x61\x01";               // ESC a 1 - Center align
        $leftAlign = "{$esc}\x61\x00";              // ESC a 0 - Left align
        $boldOn = "{$esc}\x45\x01";                 // ESC E 1 - Bold on
        $boldOff = "{$esc}\x45\x00";                // ESC E 0 - Bold off
        $doubleHeight = "{$esc}\x21\x10";           // ESC ! 16 - Double height
        $normalSize = "{$esc}\x21\x00";             // ESC ! 0  - Normal size
        $cut = "{$gs}\x56\x41\x10";                 // GS V 65 16 - Partial cut

        $out = $init;

        // Header
        $out .= $centerOn;
        $out .= $doubleHeight . $boldOn;
        $out .= $storeName . "\n";
        $out .= $normalSize . $boldOff;
        $out .= str_repeat('-', $this->lineWidth) . "\n";

        // Order meta
        $out .= $leftAlign;
        $out .= "Order: {$order->order_number}\n";
        $out .= "Date:  {$order->completed_at?->format('Y-m-d H:i')}\n";

        if ($order->customer) {
            $out .= "Customer: {$order->customer->name}\n";
        }

        $cashierName = $order->session?->cashier?->name ?? 'N/A';
        $out .= "Cashier: {$cashierName}\n";
        $out .= str_repeat('-', $this->lineWidth) . "\n";

        // Column header
        $out .= $this->columns('Item', 'Qty', 'Price', 'Total') . "\n";
        $out .= str_repeat('-', $this->lineWidth) . "\n";

        // Items
        $order->loadMissing('items.product');
        foreach ($order->items as $item) {
            $name = mb_substr($item->product->name ?? 'Unknown', 0, 20);
            $out .= $this->columns(
                $name,
                (string) $item->quantity,
                number_format($item->unit_price, 2),
                number_format($item->total_price, 2)
            ) . "\n";
        }

        $out .= str_repeat('=', $this->lineWidth) . "\n";

        // Totals
        $out .= $this->rightAligned('Subtotal:', number_format($order->subtotal, 2)) . "\n";

        if ($order->discount_amount > 0) {
            $out .= $this->rightAligned('Discount:', '-' . number_format($order->discount_amount, 2)) . "\n";
        }

        if ($order->tax_amount > 0) {
            $out .= $this->rightAligned('Tax:', number_format($order->tax_amount, 2)) . "\n";
        }

        $out .= $boldOn;
        $out .= $this->rightAligned('TOTAL:', number_format($order->total_amount, 2)) . "\n";
        $out .= $boldOff;

        $out .= $this->rightAligned('Paid:', number_format($order->paid_amount, 2)) . "\n";
        $out .= $this->rightAligned('Change:', number_format($order->change_amount, 2)) . "\n";
        $out .= $this->rightAligned('Method:', ucfirst($order->payment_method)) . "\n";

        // Footer
        $out .= str_repeat('-', $this->lineWidth) . "\n";
        $out .= $centerOn;
        $out .= "Thank you for your purchase!\n";
        $out .= $leftAlign;

        // Feed and cut
        $out .= "\n\n\n\n" . $cut;

        return $out;
    }

    /**
     * Format a 4-column row for the items table.
     */
    protected function columns(string $col1, string $col2, string $col3, string $col4): string
    {
        $w1 = $this->lineWidth - 18; // item name
        $w2 = 4;   // qty
        $w3 = 7;   // price
        $w4 = 7;   // total

        return str_pad(mb_substr($col1, 0, $w1), $w1)
            . str_pad($col2, $w2, ' ', STR_PAD_LEFT)
            . str_pad($col3, $w3, ' ', STR_PAD_LEFT)
            . str_pad($col4, $w4, ' ', STR_PAD_LEFT);
    }

    /**
     * Right-align a label: value pair across the full line width.
     */
    protected function rightAligned(string $label, string $value): string
    {
        $gap = $this->lineWidth - mb_strlen($label) - mb_strlen($value);
        return $label . str_repeat(' ', max($gap, 1)) . $value;
    }
}
