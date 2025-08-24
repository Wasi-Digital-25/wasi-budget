<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <div style="text-align:center;">
        @if(optional($quote->company)->logo_url)
            <img src="{{ $quote->company->logo_url }}" alt="Logo" height="80">
        @endif
        <h2>Quote {{ $quote->number }}</h2>
    </div>
    <p><strong>Client:</strong> {{ $quote->client_name }}<br>
       @if($quote->client_email){{ $quote->client_email }}<br>@endif
       @if($quote->client_phone){{ $quote->client_phone }}<br>@endif
    </p>
    <table>
        <thead>
            <tr>
                <th>Description</th>
                <th>Qty</th>
                <th>Unit Price</th>
                <th>Discount</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($quote->items as $item)
            <tr>
                <td>{{ $item->description }}</td>
                <td>{{ $item->quantity }}</td>
                <td>{{ number_format($item->unit_price_cents/100,2) }}</td>
                <td>{{ number_format($item->discount_cents/100,2) }}</td>
                <td>{{ number_format($item->line_total_cents/100,2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <p style="text-align:right;">
        <strong>Subtotal:</strong> {{ number_format($quote->subtotal_cents/100,2) }}<br>
        <strong>Discount:</strong> {{ number_format($quote->discount_cents/100,2) }}<br>
        <strong>Tax:</strong> {{ number_format($quote->tax_cents/100,2) }}<br>
        <strong>Total:</strong> {{ number_format($quote->total_cents/100,2) }}
    </p>
    @if($quote->notes)
    <p>{{ $quote->notes }}</p>
    @endif
</body>
</html>
