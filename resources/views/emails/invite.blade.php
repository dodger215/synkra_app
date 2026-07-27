<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>You're Invited to {{ $tenantName }}</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f5f5f7; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background-color: #f5f5f7;">
        <tr>
            <td align="center" style="padding: 40px 20px;">
                <table role="presentation" width="520" cellspacing="0" cellpadding="0" style="background-color: #ffffff; border-radius: 16px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06); overflow: hidden;">

                    {{-- Header with gradient --}}
                    <tr>
                        <td style="background: linear-gradient(135deg, #7B68EE 0%, #6C5CE7 50%, #5B4CD4 100%); padding: 40px 48px 32px; text-align: center;">
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td align="center">
                                        <div style="width: 48px; height: 48px; background-color: rgba(255, 255, 255, 0.2); border-radius: 12px; display: inline-block; line-height: 48px;">
                                            <span style="font-size: 24px; font-weight: 700; color: #ffffff;">S</span>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="center" style="padding-top: 20px;">
                                        <h1 style="margin: 0; color: #ffffff; font-size: 22px; font-weight: 600; line-height: 1.3;">
                                            You've been invited to join<br>
                                            <span style="font-size: 26px;">{{ $tenantName }}</span>
                                        </h1>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    {{-- Body --}}
                    <tr>
                        <td style="padding: 36px 48px;">
                            <p style="margin: 0 0 20px; color: #333333; font-size: 15px; line-height: 1.6;">
                                Hi there 👋
                            </p>
                            <p style="margin: 0 0 20px; color: #333333; font-size: 15px; line-height: 1.6;">
                                <strong>{{ $inviterName }}</strong> has invited you to collaborate on
                                <strong>{{ $tenantName }}</strong> as a <strong>{{ $roleName }}</strong>.
                            </p>

                            {{-- Role badge --}}
                            <table role="presentation" cellspacing="0" cellpadding="0" style="margin: 0 0 28px;">
                                <tr>
                                    <td style="background-color: #f0edff; border-radius: 8px; padding: 12px 20px;">
                                        <span style="color: #6C5CE7; font-size: 13px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">
                                            🛡️ Role: {{ $roleName }}
                                        </span>
                                    </td>
                                </tr>
                            </table>

                            <p style="margin: 0 0 32px; color: #666666; font-size: 14px; line-height: 1.6;">
                                Accept the invitation to get started. You can sign up with Google or create a password — it only takes a moment.
                            </p>

                            {{-- CTA Button --}}
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td align="center">
                                        <a href="{{ $acceptUrl }}"
                                           style="display: inline-block; background: linear-gradient(135deg, #7B68EE, #6C5CE7); color: #ffffff; text-decoration: none; font-size: 15px; font-weight: 600; padding: 14px 48px; border-radius: 10px; letter-spacing: 0.3px;">
                                            Accept Invitation
                                        </a>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    {{-- Divider --}}
                    <tr>
                        <td style="padding: 0 48px;">
                            <hr style="border: none; border-top: 1px solid #eeeeee; margin: 0;">
                        </td>
                    </tr>

                    {{-- Footer --}}
                    <tr>
                        <td style="padding: 24px 48px 32px;">
                            <p style="margin: 0 0 8px; color: #999999; font-size: 12px; line-height: 1.5; text-align: center;">
                                If you weren't expecting this invitation, you can safely ignore this email.
                            </p>
                            @if($invite->expires_at)
                            <p style="margin: 0 0 8px; color: #999999; font-size: 12px; line-height: 1.5; text-align: center;">
                                This invitation expires on {{ $invite->expires_at->format('M d, Y \a\t h:i A') }}.
                            </p>
                            @endif
                            <p style="margin: 0; color: #bbbbbb; font-size: 11px; line-height: 1.5; text-align: center;">
                                Powered by <strong style="color: #6C5CE7;">flowexa</strong>
                            </p>
                        </td>
                    </tr>
                </table>

                {{-- Below card text --}}
                <table role="presentation" width="520" cellspacing="0" cellpadding="0">
                    <tr>
                        <td style="padding: 20px 0; text-align: center;">
                            <p style="margin: 0; color: #aaaaaa; font-size: 11px; line-height: 1.5;">
                                Button not working? Copy and paste this link into your browser:<br>
                                <a href="{{ $acceptUrl }}" style="color: #6C5CE7; text-decoration: underline; word-break: break-all;">{{ $acceptUrl }}</a>
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
