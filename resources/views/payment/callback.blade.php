<!DOCTYPE html>
<html>
<head>
    <title>Payment Callback</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        table { border-collapse: collapse; width: 100%; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        h2 { margin-top: 40px; }
    </style>
</head>
<body>
    <h1>Payment Callback Details</h1>

    @if(isset($data['data']) && is_array($data['data']))
        @foreach($data['data'] as $index => $payment)
            <h2>Payment #{{ $index + 1 }}</h2>
            <table>
                <tr><th>ID</th><td>{{ $payment['id'] }}</td></tr>
                <tr><th>Email</th><td>{{ $payment['email'] ?? 'N/A' }}</td></tr>
                <tr><th>Amount</th><td>{{ $payment['amount'] }} {{ strtoupper($payment['currency']) }}</td></tr>
                <tr><th>Status</th><td>{{ ucfirst($payment['status']) }}</td></tr>
                <tr><th>Purpose</th><td>{{ $payment['purpose'] ?? 'N/A' }}</td></tr>
                <tr><th>Created At</th><td>{{ $payment['created_at'] }}</td></tr>
                <tr><th>Checkout URL</th><td><a href="{{ $payment['url'] }}" target="_blank">{{ $payment['url'] }}</a></td></tr>
            </table>
        @endforeach
    @else
        <p>No payment data available.</p>
    @endif
</body>
</html>
