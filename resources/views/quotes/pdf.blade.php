<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 1rem; }
        th, td { border: 1px solid #ccc; padding: 4px; font-size: 12px; }
        th { background: #f0f0f0; }
        .totals { margin-top: 1rem; text-align: right; }
    </style>
</head>
<body>
    <h1>Quote {{ $quote->number }}</h1>
    <p>Client: {{ $quote->client_name }}</p>

    <table>
        <thead>
        <tr>
            <th>Item</th>
            <th class="text-right">Qty</th>
            <th class="text-right">Price</th>
            <th class="text-right">Total</th>
        </tr>
        </thead>
        <tbody>
        @foreach($quote->items as $item)
            <tr>
                <td>{{ $item->name }}</td>
                <td>{{ $item->quantity }}</td>
                <td>{{ number_format($item->unit_price_cents/100, 2) }}</td>
                <td>{{ number_format($item->line_total_cents/100, 2) }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <div class="totals">
        <p><strong>Total:</strong> {{ number_format($quote->total_cents/100, 2) }} {{ $quote->currency }}</p>
    </div>
</body>
</html>

