<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #eee; border-radius: 10px; }
        .header { margin-bottom: 20px; text-align: center; }
        .footer { margin-top: 30px; font-size: 0.8rem; color: #777; text-align: center; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>{{ Auth::user()->tenant->name }}</h2>
        </div>
        <p>Dear {{ $customer->first_name }},</p>

        <div style="margin: 20px 0; white-space: pre-wrap;">
            {!! nl2br(e($messageBody)) !!}
        </div>

        <p>Thank you for choosing us!</p>

        <div class="footer">
            &copy; {{ date('Y') }} {{ Auth::user()->tenant->name }}. Powered by flowexa.
        </div>
    </div>
</body>
</html>
