<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Promo Code</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f5f5f5;
        }

        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .header {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }

        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 700;
        }

        .content {
            padding: 40px 30px;
        }

        .greeting {
            font-size: 18px;
            font-weight: 600;
            color: #1a1a1a;
            margin-bottom: 15px;
        }

        .promo-text {
            color: #666;
            margin-bottom: 30px;
            font-size: 16px;
        }

        .promo-code-box {
            background: linear-gradient(135deg, #f0f9ff 0%, #eff6ff 100%);
            border: 2px solid #3b82f6;
            border-radius: 8px;
            padding: 25px;
            text-align: center;
            margin: 30px 0;
        }

        .promo-label {
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #3b82f6;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .promo-code {
            font-size: 32px;
            font-weight: 800;
            color: #1a1a1a;
            letter-spacing: 2px;
            font-family: 'Courier New', monospace;
            word-break: break-all;
        }

        .discount-badge {
            display: inline-block;
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 700;
            margin-top: 15px;
        }

        .pricing-table {
            margin: 30px 0;
            border-collapse: collapse;
            width: 100%;
        }

        .pricing-table th {
            background-color: #f9fafb;
            padding: 12px;
            text-align: left;
            font-weight: 700;
            color: #1a1a1a;
            border-bottom: 2px solid #e5e7eb;
        }

        .pricing-table td {
            padding: 12px;
            border-bottom: 1px solid #e5e7eb;
            color: #666;
        }

        .pricing-table .plan-name {
            font-weight: 600;
            color: #1a1a1a;
        }

        .original-price {
            text-decoration: line-through;
            color: #999;
        }

        .discounted-price {
            color: #10b981;
            font-weight: 700;
            font-size: 18px;
        }

        .cta-button {
            display: inline-block;
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: white;
            padding: 14px 32px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 700;
            font-size: 16px;
            margin: 20px 0;
        }

        .cta-button:hover {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
        }

        .expiry-info {
            background-color: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
            color: #92400e;
            font-size: 14px;
        }

        .footer {
            background-color: #f9fafb;
            padding: 25px;
            text-align: center;
            border-top: 1px solid #e5e7eb;
            font-size: 14px;
            color: #666;
        }

        .footer a {
            color: #3b82f6;
            text-decoration: none;
        }

        .divider {
            height: 1px;
            background-color: #e5e7eb;
            margin: 20px 0;
        }
    </style>
</head>

<body>
    <div class="email-container">
        <!-- Header -->
        <div class="header">
            <h1><img src="{{ URL::asset('assets/front/favicons/favicon-32x32.png') }}" alt=""> Exclusive Offer Inside!</h1>
        </div>

        <!-- Content -->
        <div class="content">
            <p class="greeting">Hi {{ $user->name }},</p>

            <p class="promo-text">
                We're excited to offer you an exclusive discount on our AI-Powered Resume Builder!
                This is a limited-time offer just for you. Use the promo code below to get your discount now.
            </p>

            <!-- Promo Code Box -->
            <div class="promo-code-box">
                <div class="promo-label">Your Promo Code</div>
                <div class="promo-code">{{ $promoCode->code }}</div>
                <div class="discount-badge">
                    Save {{ $promoCode->discount_percentage }}%
                </div>
            </div>

            <!-- About the Offer -->
            <p style="font-weight: 600; color: #1a1a1a; margin-top: 30px;">What You'll Get:</p>
            <p class="promo-text">
                ✓ 5 Professional Resume Templates<br>
                ✓ AI-Powered Content Suggestions<br>
                ✓ ATS-Optimized Resumes<br>
                ✓ Download as PDF<br>
                ✓ Unlimited Edits<br>
            </p>

            <!-- Expiry Information -->
            @if($promoCode->expires_at)
            <div class="expiry-info">
                ⏰ <strong>Offer Expires:</strong> {{ $promoCode->expires_at->format('F d, Y') }} at 11:59 PM<br>
                Don't miss out on this limited-time opportunity!
            </div>
            @endif

            <!-- CTA -->
            <div style="text-align: center;">
                <a href="{{ route('front.pricing') }}" class="cta-button">
                    View Our Plans Now
                </a>
            </div>

            <div class="divider"></div>

            <!-- How to Use -->
            <p style="color: #1a1a1a; font-weight: 600;">How to use your promo code:</p>
            <ol style="color: #666; line-height: 1.8;">
                <li>Go to the Resume Builder page</li>
                <li>Enter the promo code: <strong style="color: #1a1a1a; font-family: 'Courier New', monospace;">{{ $promoCode->code }}</strong></li>
                <li>Click "Validate Code" to see your discount</li>
                <li>Select your plan and checkout</li>
            </ol>

            <p style="color: #666; margin-top: 20px;">
                If you have any questions, feel free to reach out to our support team. We're here to help!
            </p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p style="margin: 0 0 10px 0;">
                © {{ date('Y') }} HiredHero. All rights reserved.
            </p>
            <p style="margin: 0;">
                Have questions? <a href="mailto:support@hiredhero.com">Contact our support team</a>
            </p>
            @if($unsubscribeToken)
            <div style="margin-top: 15px; padding-top: 15px; border-top: 1px solid #e5e7eb;">
                <p style="margin: 0; font-size: 12px; color: #999;">
                    <a href="{{ route('email.unsubscribe', ['token' => $unsubscribeToken]) }}" style="color: #999; text-decoration: underline;">
                        Unsubscribe from promotional emails
                    </a>
                </p>
            </div>
            @endif
        </div>
    </div>
</body>

</html>