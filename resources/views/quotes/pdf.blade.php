<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Quote {{ $quote->number }}</title>
</head>
<body>
    <h1>Quote {{ $quote->number }}</h1>
    <p>Total: {{ $quote->total_cents }}</p>
    <table width="100%" border="1" cellspacing="0" cellpadding="4">
        <thead>
            <tr>
                <th>Description</th>
                <th>Qty</th>
                <th>Unit Cost</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($quote->items as $item)
                <tr>
                    <td>{{ $item->description }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ $item->unit_cost_cents }}</td>
                    <td>{{ $item->subtotal_cents }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
