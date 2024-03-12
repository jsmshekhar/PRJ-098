<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to {{ env('APP_NAME') }}</title>
</head>

<body>
    <p>Dear {{ $details['name'] }},</p>

    <p>Welcome to our community! We are excited to have you on board.</p>

    <p>Your account details:</p>
    <ul>
        <li><strong>Name:</strong> {{ $details['name'] }}</li>
        <li><strong>Email:</strong> {{ $details['email'] }}</li>
        <li><strong>Password:</strong> {{ $details['password'] }}</li>
    </ul>

    <p>Thank you for joining us. If you have any questions or need assistance, feel free to reach out.</p>

    <p>Best regards,<br>
        {{ env('APP_NAME') }}
    </p>
</body>

</html>
