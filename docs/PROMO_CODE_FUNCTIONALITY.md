# Promo Code Functionality - Complete Guide

## Overview
A comprehensive promo code system has been implemented that allows admins to generate and send promo codes to users with specific discount percentages and expiry dates. When users apply these codes during resume subscription checkout, they receive discounts on their subscription cost.

## Features
- ✅ Admin can generate unique promo codes with custom discount percentages
- ✅ Set expiry dates for promo codes (optional)
- ✅ Send codes to specific users via email
- ✅ Track code usage and limit maximum uses
- ✅ Apply discounts automatically during subscription checkout
- ✅ View discount details in subscription records
- ✅ Prevent code reuse (one code per user)
- ✅ Mark codes as active/inactive
- ✅ Full audit trail with timestamps

## Database Tables

### promo_codes
```sql
- id (Primary Key)
- code (Unique, e.g., "PROMO-XXXX-XXXX")
- discount_percentage (Decimal, 0-100)
- max_usage (Integer, default: 1)
- used_count (Integer, tracks usage)
- expires_at (DateTime, nullable for no expiry)
- active (Boolean, default: true)
- description (Text, nullable)
- created_at, updated_at
```

### promo_code_user (Pivot Table)
```sql
- id (Primary Key)
- promo_code_id (Foreign Key)
- user_id (Foreign Key)
- used (Boolean, tracks if user has used the code)
- used_at (DateTime, when user used the code)
- created_at, updated_at
```

### resume_subscriptions (Updated Columns)
```sql
- promo_code_id (Foreign Key, nullable)
- discount_amount (Decimal, amount saved)
- original_amount (Decimal, before discount)
```

## Models & Relationships

### PromoCode Model
- `->users()` - Get users assigned to this code (many-to-many)
- `->isValid()` - Check if code is currently valid
- `->canUserUse($user)` - Check if specific user can use the code
- `->applyToUser($user, $price)` - Calculate discount for user
- `->markUsedByUser($user)` - Mark code as used by user

### User Model (Updated)
- `->promoCodes()` - Get all promo codes assigned to user
- `->getActivePromoCodes()` - Get unused, non-expired codes
- `->canUsePromoCode($code)` - Check if user can use a specific code

### ResumeSubscription Model (Updated)
- `->promoCode()` - Get the promo code used for this subscription

## Admin Panel Usage

### 1. Access Promo Code Management
Navigate to: **Admin Dashboard → Users**

### 2. Generate Promo Code for User
1. Click the **"Send Promo"** button in the users table (gift icon)
2. Modal opens showing user details
3. Enter:
   - **Discount Percentage**: 0-100 (e.g., 10.50 for 10.50% off)
   - **Expiry Date** (Optional): Leave blank for no expiry
   - **Description** (Optional): Note about the code

4. Click **"Generate Code"** button
5. System generates unique code format: `XXXX-XXXX-XXXX`
6. Review the generated code details

### 3. Send Code to User
1. After generation, click **"Send to User"** button
2. Code is recorded in the system and logged
3. (Optional) Email sending can be implemented

### 4. View All Promo Codes
Navigate to: **Admin Dashboard → Promo Codes**
- View all generated codes
- See usage statistics
- Activate/Deactivate codes
- Delete expired codes

## User Experience

### 1. User Receives Promo Code
User receives the code (e.g., via email or promotion)
Example: `SUMMER-2024-SPECIAL`

### 2. Apply During Checkout
1. Go to Resume Builder subscription page
2. Select plan (Weekly/Monthly)
3. Enter promo code field (if shown)
4. System validates the code
5. Discount is applied automatically
6. Final price shown with breakdown:
   - Original Price: $X.XX
   - Discount (XX%): -$X.XX
   - **Final Price: $X.XX**

### 3. Subscription Created with Discount
- Subscription record stores:
  - Original amount
  - Discount amount
  - Final amount paid
  - Which promo code was used
  - Discount percentage

## Services & Helpers

### PromoCodeService
Located in: `app/Services/PromoCodeService.php`

```php
// Validate promo code
$result = promoCodeService->validatePromoCode($code, $user);

// Calculate discount
$discount = promoCodeService->calculateDiscount($amount, $promoCode);

// Apply promo code
$result = promoCodeService->applyPromoCode($promoCode, $user, $amount);

// Get user's active codes
$codes = promoCodeService->getUserActivePromoCodes($user);

// Check if code exists
$exists = promoCodeService->codeExists($code);

// Get promo code
$code = promoCodeService->getByCode($code);
```

### Helper Functions
Available globally via `Helpers` class:

```php
// Validate promo code
Helpers::validatePromoCode('CODE-123', $user);

// Calculate discount
Helpers::calculatePromoDiscount(100, $promoCode);

// Apply promo code
Helpers::applyPromoCode($promoCode, $user, 100);

// Check if code exists
Helpers::promoCodeExists('CODE-123');

// Get promo code
Helpers::getPromoCode('CODE-123');

// Get user's promo codes
Helpers::getUserPromoCodes($user);
```

## Routes

### Admin Routes
```
POST   /admin/promo-codes/generate-for-user - Generate code for user
POST   /admin/promo-codes/send-to-user - Send code to user
GET    /admin/promo-codes/datatables - Get codes data
POST   /admin/promo-codes/{id}/activate - Activate code
POST   /admin/promo-codes/{id}/deactivate - Deactivate code
POST   /admin/promo-codes/{id}/delete - Delete code
GET    /admin/promo-codes/user/{userId}/codes - Get user's codes
```

## Controller Methods

### PromoCodeController
- `index()` - List all promo codes
- `generateForUser()` - Generate new code for user (AJAX)
- `sendToUser()` - Send code to user (AJAX)
- `deactivate()` - Deactivate a code
- `activate()` - Activate a code
- `destroy()` - Delete a code
- `userPromoCodes()` - Get codes for specific user

## Validation Rules

### Generate Promo Code
- `user_id` - Required, must exist in users table
- `discount_percentage` - Required, numeric, 0-100
- `expires_at` - Optional, must be after today
- `description` - Optional, max 255 characters

### Apply Promo Code
Code is valid if:
- ✅ Code exists in database
- ✅ Code is marked as active
- ✅ Code hasn't expired (if expiry set)
- ✅ Code hasn't reached max usage limit
- ✅ User is assigned to this code
- ✅ User hasn't already used this code

## Examples

### Example 1: Create 20% Discount Code for VIP Users
1. Click "Send Promo" on user in admin panel
2. Set discount: 20
3. Leave expiry blank (permanent)
4. Set description: "VIP Customer Special"
5. Generate → Send to User

### Example 2: Limited Time Promotion
1. Click "Send Promo" on multiple users
2. Set discount: 15
3. Set expiry: 2026-03-31
4. Description: "Spring Sale - Expires March 31"
5. Generate → Send each user

### Example 3: Single-Use Referral Code
1. Click "Send Promo" on referred user
2. Set discount: 5
3. Leave expiry blank
4. Description: "Referral Bonus from [Referrer Name]"
5. Generate → Send to User
6. Code automatically marked as used after first purchase

## Code Generation Algorithm

Codes are generated in format: `XXXX-XXXX-XXXX`
- 12 alphanumeric characters
- 2 dashes separating groups of 4
- Automatically unique (checked against database)
- Example: `A7K9-M2P5-Q8R3`

## Database Migrations

Run migrations to set up the tables:
```bash
php artisan migrate
```

Migration files created:
1. `2026_03_04_create_promo_codes_table.php` - Creates promo_codes and promo_code_user tables
2. `2026_03_04_add_promo_code_to_resume_subscriptions.php` - Adds promo code columns to subscriptions

## Security Considerations

1. **Code Validation**: All codes validated on both client and server
2. **User Assignment**: Codes only valid for assigned users
3. **One-Time Use**: Each user can only use a code once
4. **Expiry Protection**: Expired codes automatically rejected
5. **Admin Only**: Code generation restricted to authenticated admins
6. **Audit Trail**: All usage tracked with timestamps

## Future Enhancements

- [ ] Email notification when code is sent
- [ ] SMS notifications for codes
- [ ] Bulk code generation for campaigns
- [ ] Code usage analytics and reporting
- [ ] Automatic code generation for referral tiers
- [ ] Tiered discount levels (5%, 10%, 15%, etc.)
- [ ] Combine multiple promo codes
- [ ] Seasonal/scheduled promotions
- [ ] A/B test different discount rates

## Testing

### Test Generate Promo Code
```php
$user = User::find(1);
$promoCode = PromoCode::create([
    'code' => 'TEST-001',
    'discount_percentage' => 15,
    'max_usage' => 1,
    'expires_at' => now()->addDays(30),
    'active' => true,
]);
$promoCode->users()->attach($user->id);
```

### Test Apply Discount
```php
$promoCode = PromoCode::where('code', 'TEST-001')->first();
$result = $promoCode->applyToUser($user, 100);
// Returns: final_price = 85 (15% discount)
```

### Test Validation
```php
$promoCode = PromoCode::where('code', 'TEST-001')->first();
$isValid = $promoCode->canUserUse($user); // true/false
```

## Troubleshooting

### Code Not Working
1. Check if code is active
2. Verify user is assigned to code
3. Check expiry date hasn't passed
4. Ensure user hasn't already used it

### Discount Not Applied
1. Verify discount_percentage is set correctly
2. Check code is marked as active
3. Ensure session is storing promo_code_id
4. Check StripePaymentService is handling metadata

### Migration Issues
```bash
# Rollback migrations
php artisan migrate:rollback

# Re-run migrations
php artisan migrate
```

## Support
For issues or questions, check the admin logs:
- Storage: `storage/logs/laravel.log`
- Look for PromoCode-related error messages
