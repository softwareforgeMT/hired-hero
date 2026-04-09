# Promo Code Bulk Send Enhancement

## Overview
The promo code management system has been enhanced to support sending promo codes to:
1. Platform users (existing users with promotional emails enabled)
2. Custom email addresses (external users not on the platform)
3. Both platform and custom users simultaneously

## Frontend Changes
Location: `resources/views/admin/promo-codes/index.blade.php`

### New Features Added
1. **Recipient Type Selection** - Three radio button options:
   - `Platform Users` - Send to all users on platform with promotional emails enabled
   - `Custom Emails` - Send only to manually entered email addresses
   - `All` - Send to both platform users and custom email addresses

2. **Custom Email Input** - Textarea to enter email addresses manually
   - One email per line
   - Shows 3-point instruction preview that external users will receive

3. **Instruction Preview** - Visual display of the 3-point onboarding instruction:
   - Create an Account
   - Subscribe to a Plan
   - Use the Promo Code

### JavaScript Changes
Updated AJAX calls to send additional parameters:
- `recipient_type` - Type of recipient (platform/custom/all)
- `custom_emails` - Array of custom email addresses
- `include_unsubscribe` - Whether to include unsubscribe link

## Backend Response Format

The backend should return a JSON response with the following structure:

```json
{
    "success": true,
    "message": "Promo codes sent successfully!",
    "sent_count": 150,
    "platform_count": 100,
    "custom_count": 50
}
```

- `sent_count`: Total number of emails sent
- `platform_count`: Number of platform users who received the email (only if recipient_type is 'platform' or 'all')
- `custom_count`: Number of custom emails sent (only if recipient_type is 'custom' or 'all')

### Controller: `app/Http/Controllers/Admin/PromoCodeController.php`

The `bulkSendPromos` method needs to be updated to:

```php
public function bulkSendPromos(Request $request)
{
    $promoCodeIds = $request->input('promo_code_ids', []);
    $recipientType = $request->input('recipient_type', 'platform'); // platform, custom, or all
    $customEmails = $request->input('custom_emails', []);
    $includeUnsubscribe = $request->input('include_unsubscribe', 0);

    // Validate inputs
    if (empty($promoCodeIds)) {
        return response()->json(['success' => false, 'message' => 'No promo codes selected'], 400);
    }

    // Prepare recipients based on type
    $platformRecipients = [];
    $customRecipients = [];
    $totalSent = 0;

    // Get platform users (if applicable)
    if (in_array($recipientType, ['platform', 'all'])) {
        $platformRecipients = User::where('promotional_emails', true)
            ->orWhere('promo_email_opt_in', true) // adjust based on your field name
            ->pluck('email')
            ->toArray();
    }

    // Get custom emails (if applicable)
    if (in_array($recipientType, ['custom', 'all'])) {
        // Validate and sanitize emails
        $validEmails = array_filter($customEmails, function($email) {
            return filter_var($email, FILTER_VALIDATE_EMAIL);
        });
        $customRecipients = array_values($validEmails); // Re-index array
    }

    // For 'custom' type, validate that we have emails
    if ($recipientType === 'custom' && empty($customRecipients)) {
        return response()->json([
            'success' => false, 
            'message' => 'No valid custom email addresses provided'
        ], 400);
    }

    // For 'all' type, require at least platform users or custom emails
    if ($recipientType === 'all' && empty($platformRecipients) && empty($customRecipients)) {
        return response()->json([
            'success' => false, 
            'message' => 'No valid recipients found'
        ], 400);
    }

    // Send emails for each promo code
    foreach ($promoCodeIds as $promoCodeId) {
        $promoCode = PromoCode::findOrFail($promoCodeId);
        
        // Send to platform users (if applicable)
        if (!empty($platformRecipients)) {
            foreach ($platformRecipients as $email) {
                Mail::to($email)->send(new PromoCodeMail(
                    $promoCode,
                    $includeUnsubscribe
                ));
                
                // Log the send action
                PromoCodeSend::create([
                    'promo_code_id' => $promoCodeId,
                    'email' => $email,
                    'user_id' => User::where('email', $email)->value('id'),
                    'is_external' => false,
                    'sent_at' => now(),
                ]);
                
                $totalSent++;
            }
        }
        
        // Send to custom emails (if applicable)
        if (!empty($customRecipients)) {
            foreach ($customRecipients as $email) {
                // Check if this is an external user
                $user = User::where('email', $email)->first();
                $isExternalUser = !$user;
                
                if ($isExternalUser) {
                    // Send external user email with 3-point instruction
                    Mail::to($email)->send(new PromoCodeExternalUserMail(
                        $promoCode,
                        $includeUnsubscribe
                    ));
                } else {
                    // Send platform user email (if they weren't already sent one)
                    Mail::to($email)->send(new PromoCodeMail(
                        $promoCode,
                        $includeUnsubscribe
                    ));
                }
                
                // Log the send action
                PromoCodeSend::create([
                    'promo_code_id' => $promoCodeId,
                    'email' => $email,
                    'user_id' => $user?->id,
                    'is_external' => $isExternalUser,
                    'sent_at' => now(),
                ]);
                
                $totalSent++;
            }
        }
    }

    // Return response with detailed counts
    return response()->json([
        'success' => true,
        'message' => 'Promo codes sent successfully!',
        'sent_count' => $totalSent,
        'platform_count' => count($platformRecipients),
        'custom_count' => count($customRecipients),
    ]);
}
```

### Email Templates Required

#### 1. `PromoCodeMail` - For Platform Users
Standard promo code email for existing users

#### 2. `PromoCodeExternalUserMail` - For External Users
Should include the 3-point instruction:

```
Subject: Exclusive Promo Code: [CODE] - Get [DISCOUNT]% Off!

---

Hello,

We have an exclusive promo code for you!

PROMO CODE: [CODE]
Discount: [DISCOUNT]%
Expires: [EXPIRY_DATE]

---

HOW TO USE OUR PROMO CODE:

1. CREATE AN ACCOUNT
   Visit our platform and create a free account using this email address.
   [SIGN UP LINK]

2. SUBSCRIBE TO A PLAN
   Browse our subscription plans and choose the one that suits your needs.
   [PRICING PAGE LINK]

3. USE THE PROMO CODE
   During checkout, enter the promo code [CODE] to apply your [DISCOUNT]% discount!

---

Questions? Contact our support team.

[UNSUBSCRIBE LINK - if include_unsubscribe = 1]
```

### Database Considerations

Optional: Create a `promo_code_sends` table to track all send operations:

```php
// Migration
Schema::create('promo_code_sends', function (Blueprint $table) {
    $table->id();
    $table->foreignId('promo_code_id')->constrained();
    $table->string('email');
    $table->foreignId('user_id')->nullable()->constrained();
    $table->boolean('is_external')->default(false);
    $table->timestamp('sent_at');
    $table->timestamps();
    $table->index(['promo_code_id', 'sent_at']);
});
```

## Validation Notes

1. **Email Validation**: All custom emails should be validated before sending
2. **Duplicates**: Remove duplicate email addresses from recipients list
3. **Error Handling**: Gracefully handle invalid emails and display appropriate messages
4. **Logging**: Log all send operations for audit purposes

## Feature Flags

Consider adding:
- Batch size limits for custom emails (e.g., max 100 at a time)
- Rate limiting to prevent abuse
- Email verification for custom email addresses before sending
- Send confirmation/preview before bulk send

## Testing Checklist

- [ ] Send to platform users only
- [ ] Send to custom emails only
- [ ] Send to both simultaneously
- [ ] Handle duplicate emails in custom email list
- [ ] Handle invalid email formats
- [ ] Verify external user template includes 3-point instruction
- [ ] Verify platform user template is appropriate
- [ ] Test unsubscribe link functionality
- [ ] Verify audit logging of sent emails
