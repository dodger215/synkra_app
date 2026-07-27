<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt - {{ $order->order_number }}</title>
    <style>
        body { font-family: 'Courier New', Courier, monospace; width: 80mm; margin: 0 auto; padding: 10mm; background: #fff; color: #000; font-size: 12px; }
        .center { text-align: center; }
        .bold { font-weight: bold; }
        .line { border-top: 1px dashed #000; margin: 5px 0; }
        .table { width: 100%; border-collapse: collapse; }
        .table th { text-align: left; border-bottom: 1px solid #000; padding: 2px 0; }
        .table td { padding: 2px 0; vertical-align: top; }
        .right { text-align: right; }
        .total-row { font-size: 14px; font-weight: bold; }
        @media print {
            body { padding: 0; }
            @page { margin: 0; }
        }
    </style>
</head>
<body onload="window.print(); setTimeout(() => window.close(), 500);">
    <div class="center">
        <div class="bold" style="font-size: 18px;">flowexa POS</div>
        <div>Workspace: {{ auth()->user()->tenant->company_name ?? 'My Business' }}</div>
        <div class="line"></div>
    </div>

    <div>
        Order: {{ $order->order_number }}<br>
        Date:  {{ $order->completed_at->format('Y-m-d H:i') }}<br>
        Cashier: {{ $order->session->cashier->name }}<br>
        Customer: {{ $order->customer ? $order->customer->name : 'Walk-in Customer' }}
    </div>

    <div class="line"></div>

    <table class="table">
        <thead>
            <tr>
                <th>Item</th>
                <th class="right">Qty</th>
                <th class="right">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
                <tr>
                    <td>{{ $item->product->name }}</td>
                    <td class="right">{{ number_format($item->quantity, 0) }}</td>
                    <td class="right">GH₵ {{ number_format($item->total_price, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="line"></div>

    <table class="table">
        <tr>
            <td>Subtotal:</td>
            <td class="right">GH₵ {{ number_format($order->subtotal, 2) }}</td>
        </tr>
        @if($order->discount_amount > 0)
        <tr>
            <td>Discount:</td>
            <td class="right">-GH₵ {{ number_format($order->discount_amount, 2) }}</td>
        </tr>
        @endif
        <tr class="total-row">
            <td>TOTAL:</td>
            <td class="right">GH₵ {{ number_format($order->total_amount, 2) }}</td>
        </tr>
        <tr>
            <td>Paid ({{ ucfirst($order->payment_method) }}):</td>
            <td class="right">GH₵ {{ number_format($order->paid_amount, 2) }}</td>
        </tr>
        <tr>
            <td>Change:</td>
            <td class="right">GH₵ {{ number_format($order->change_amount, 2) }}</td>
        </tr>
    </table>

    <div class="line"></div>

    <div class="center" style="margin-top: 20px;">
        Thank you for your purchase!<br>
        Please visit us again.
    </div>
</body>
</html>
