<x-mail::message>
# Verify Your flowexa Account

Hi there!

Thank you for registering your workspace with flowexa. To ensure your account's security, please use the following 6-digit verification code to complete your registration.

<x-mail::panel>
<div style="text-align: center; font-size: 2rem; letter-spacing: 0.5rem; font-weight: bold; color: #4F46E5;">
{{ $code }}
</div>
</x-mail::panel>

This code is valid for the next 15 minutes. If you did not request this code, please ignore this email.

Thanks,<br>
{{ config('app.name') }} Security Team
</x-mail::message>
