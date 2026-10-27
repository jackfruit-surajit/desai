<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Product Assignment Notification</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f4f6f9; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #333333;">
    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #f4f6f9; padding: 30px 15px;">
        <tr>
            <td align="center">
                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 10px rgba(0,0,0,0.06);">
                    <!-- Header -->
                    <tr>
                        <td align="center" style="background: linear-gradient(135deg, #04A9F5 0%, #005a9e 100%); padding: 30px 20px;">
                            <h1 style="color: #ffffff; margin: 0; font-size: 24px; font-weight: 700; letter-spacing: 0.5px;">
                                {{ config('app.name', 'Desai') }}
                            </h1>
                            <p style="color: #e0f2fe; margin: 8px 0 0 0; font-size: 14px;">
                                Product Assignment Notification
                            </p>
                        </td>
                    </tr>

                    <!-- Body Content -->
                    <tr>
                        <td style="padding: 30px 25px;">
                            <p style="font-size: 16px; margin: 0 0 15px 0; color: #1e293b;">
                                Hello <strong>{{ $data['warehouse_name'] ?? 'Warehouse Manager' }}</strong>,
                            </p>
                            <p style="font-size: 14px; line-height: 1.6; margin: 0 0 20px 0; color: #475569;">
                                New product(s) have been assigned to your warehouse by the manufacturer. Please review the details below:
                            </p>

                            <!-- Meta Details Box -->
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #f8fafc; border-left: 4px solid #04A9F5; border-radius: 4px; padding: 12px 16px; margin-bottom: 25px;">
                                <tr>
                                    <td style="font-size: 13px; color: #64748b; padding: 4px 0;">
                                        <strong>Assigned By:</strong> {{ $data['manufacturer_name'] ?? 'Manufacturer' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="font-size: 13px; color: #64748b; padding: 4px 0;">
                                        <strong>Date & Time:</strong> {{ $data['date'] ?? date('d M, Y h:i A') }}
                                    </td>
                                </tr>
                            </table>

                            <!-- Products Table -->
                            <h3 style="font-size: 15px; margin: 0 0 12px 0; color: #0f172a; text-transform: uppercase; letter-spacing: 0.5px;">
                                Assigned Product Details
                            </h3>
                            <table border="0" cellpadding="10" cellspacing="0" width="100%" style="border-collapse: collapse; margin-bottom: 25px;">
                                <thead>
                                    <tr style="background-color: #04A9F5; color: #ffffff;">
                                        <th align="center" style="font-size: 13px; font-weight: 600; border: 1px solid #04A9F5; width: 10%;">#</th>
                                        <th align="left" style="font-size: 13px; font-weight: 600; border: 1px solid #04A9F5;">Product Name</th>
                                        <th align="center" style="font-size: 13px; font-weight: 600; border: 1px solid #04A9F5; width: 35%;">Per Case Quantity</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(!empty($data['products']) && count($data['products']) > 0)
                                        @foreach($data['products'] as $index => $item)
                                            <tr style="background-color: {{ $index % 2 == 0 ? '#ffffff' : '#f8fafc' }};">
                                                <td align="center" style="font-size: 13px; border: 1px solid #e2e8f0; color: #334155;">
                                                    {{ $index + 1 }}
                                                </td>
                                                <td align="left" style="font-size: 13px; border: 1px solid #e2e8f0; color: #334155; font-weight: 500;">
                                                    {{ $item['name'] ?? 'N/A' }}
                                                </td>
                                                <td align="center" style="font-size: 13px; border: 1px solid #e2e8f0; color: #334155;">
                                                    <strong>{{ $item['per_case_quantity'] ?? 0 }}</strong> Cases
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="3" align="center" style="font-size: 13px; border: 1px solid #e2e8f0; color: #94a3b8; padding: 15px;">
                                                No product details available.
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>

                            <!-- Call to Action Button -->
                            @if(!empty($data['login_url']))
                                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="margin: 25px 0;">
                                    <tr>
                                        <td align="center">
                                            <a href="{{ $data['login_url'] }}" target="_blank" style="display: inline-block; background-color: #04A9F5; color: #ffffff; text-decoration: none; padding: 12px 28px; border-radius: 6px; font-size: 14px; font-weight: 600; letter-spacing: 0.3px; box-shadow: 0 2px 6px rgba(4, 169, 245, 0.4);">
                                                Login to Warehouse Portal
                                            </a>
                                        </td>
                                    </tr>
                                </table>
                            @endif

                            <p style="font-size: 13px; line-height: 1.5; color: #64748b; margin: 20px 0 0 0;">
                                Please log in to your warehouse dashboard to verify and manage these products. If you have any inquiries, feel free to contact the administrator.
                            </p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td align="center" style="background-color: #f1f5f9; padding: 20px; border-top: 1px solid #e2e8f0;">
                            <p style="font-size: 12px; color: #94a3b8; margin: 0;">
                                This is an automated email notification from {{ config('app.name', 'Desai') }} system. Please do not reply directly to this email.
                            </p>
                            <p style="font-size: 12px; color: #94a3b8; margin: 6px 0 0 0;">
                                &copy; {{ date('Y') }} {{ config('app.name', 'Desai') }}. All rights reserved.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>

