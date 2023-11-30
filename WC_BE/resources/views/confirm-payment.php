<!-- resources/views/confirm-payment.blade.php -->

<html>
<head>
    <title>Confirmation Page</title>
</head>
<body>
    <h1>Confirmation Page</h1>
    @if(session('success'))
        <p style="color: green;">{{ session('success') }}</p>
    @endif

    <form action="{{ url('/confirm-payment') }}" method="post">
        @csrf

        <label for="client_secret">Client Secret:</label>
        <input type="text" name="client_secret" value="{{ $clientSecret }}" readonly>

        <button type="submit">Confirm Payment</button>
    </form>
</body>
</html>
