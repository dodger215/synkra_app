<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #eee; border-radius: 10px; }
        .header { margin-bottom: 20px; text-align: center; }
        .status { font-weight: bold; padding: 5px 10px; border-radius: 5px; }
        .approved { background-color: #dcfce7; color: #166534; }
        .rejected { background-color: #fee2e2; color: #991b1b; }
        .footer { margin-top: 30px; font-size: 0.8rem; color: #777; text-align: center; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>flowexa Supply Chain</h2>
        </div>
        <p>Hello {{ $supplier->tenant->name }},</p>
        <p>The connection request you sent to <strong>{{ $supplier->supplierTenant->name }}</strong> has been
            <span class="status {{ $status === 'approved' ? 'approved' : 'rejected' }}">{{ strtoupper($status) }}</span>.
        </p>

        @if($status === 'rejected' && $reason)
            <div style="margin-top: 20px; padding: 15px; background: #f9fafb; border-left: 4px solid #991b1b;">
                <strong>Reason for rejection:</strong><br>
                {{ $reason }}
            </div>
        @endif

        <p>You can now manage your suppliers in your flowexa dashboard.</p>

        <div class="footer">
            &copy; {{ date('Y') }} flowexa. All rights reserved.
        </div>
    </div>
</body>
</html>
