<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Redirecting to ConnectIPS</title>
</head>
<body onload="document.forms[0].submit()">
    <form method="POST" action="{{ $url }}">
        @foreach ($data as $key => $value)
            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
        @endforeach

        <noscript>
            <button type="submit">Continue to ConnectIPS</button>
        </noscript>
    </form>
</body>
</html>
