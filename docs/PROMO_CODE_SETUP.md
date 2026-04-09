# Promo Code Functionality - Quick Setup Guide

## Installation Steps

### 1. Run Database Migrations
```bash
php artisan migrate
```

This will create:
- `promo_codes` table
- `promo_code_user` pivot table
- Add columns to `resume_subscriptions` table

### 2. Clear Application Cache
```bash
php artisan optimize:clear
```

### 3. Test the Feature

#### Option A: Via Admin Panel
1. Go to Admin Dashboard → Users
2. Click the **"Send Promo"** button (gift icon) on any user
3. Enter discount percentage (e.g., 15)
4. Optionally set expiry date
5. Click **"Generate Code"**
6. Click **"Send to User"**

#### Option B: Via Command Line (Testing)
```bash
php artisan tinker

# Create a promo code
$code = App\Models\PromoCode::create([
    'code' => 'WELCOME10',
    'discount_percentage' => 10,
    'max_usage' => 5,
    'expires_at' => now()->addDays(30),
    'active' => true,
]);

# Assign to a user
$user = App\Models\User::find(1);
$code->users()->attach($user->id);

# Verify it works
$code->canUserUse($user); // Returns: true/false
```

## Files Created/Modified

### New Files
1. `app/Models/PromoCode.php` - PromoCode model
2. `app/Services/PromoCodeService.php` - PromoCodeService for validation/calculations
3. `app/Http/Controllers/Admin/PromoCodeController.php` - Admin controller
4. `database/migrations/2026_03_04_create_promo_codes_table.php` - Main migration
5. `database/migrations/2026_03_04_add_promo_code_to_resume_subscriptions.php` - Resume table update
6. `docs/PROMO_CODE_FUNCTIONALITY.md` - Complete documentation
7. `docs/PROMO_CODE_SETUP.md` - This setup guide

### Modified Files
1. `app/Models/User.php` - Added promo code relationships
2. `app/Models/ResumeSubscription.php` - Added promo code fields and relationship
3. `app/Http/Controllers/Placement/ResumeBuilderController.php` - Added promo code validation in checkout
4. `app/Services/Placement/StripePaymentService.php` - Added discount calculation for payments
5. `app/CentralLogics/helpers.php` - Added promo code helper functions
6. `routes/web.php` - Added promo code routes
7. `resources/views/admin/users/users.blade.php` - Added promo code button and modal

## Key Features

### Admin Panel
- **Users Table**: "Send Promo" button for each user
- **Promo Code Modal**: 
  - Generate unique codes with custom discount percentage
  - Set optional expiry dates
  - Auto-generated format: XXXX-XXXX-XXXX
  - Send code to user

### User Experience
- **During Checkout**: Apply promo code to get discount
- **Automatic Discount**: System calculates and applies discount
- **One-Time Use**: Code marked as used after first purchase
- **Subscription Record**: Stores discount details

### Discount Calculation
Example: User subscribes with 20% promo code on $50 plan
- Original Amount: $50.00
- Discount (20%): -$10.00
- **Final Amount: $40.00**

All stored in subscription record for audit trail.

## API Endpoints (Admin)

### Generate Code
```
POST /admin/promo-codes/generate-for-user
{
    "user_id": 1,
    "discount_percentage": 15,
    "expires_at": "2026-12-31",
    "description": "VIP Special Offer"
}
```

Response:
```json
{
    "success": true,
    "message": "Promo code generated successfully!",
    "promo_code_id": 5,
    "code": "PROMO-A7K9-M2P5",
    "discount_percentage": 15,
    "expires_at": "Dec 31, 2026"
}
```

### Send Code to User
```
POST /admin/promo-codes/send-to-user
{
    "user_id": 1,
    "promo_code_id": 5
}
```

## Validation Rules

### PromoCode Requirements
- Must be active (not disabled)
- Must not be expired (if expiry date set)
- Must not exceed max usage limit
- User must be assigned to code
- User must not have already used it

### Discount Percentage
- Minimum: 0%
- Maximum: 100%
- Supports decimals (e.g., 10.50%)

## Helper Functions Available

```php
// In any controller/blade file:

// Validate a code
Helpers::validatePromoCode('PROMO-123', $user);

// Calculate discount
Helpers::calculatePromoDiscount(100, $promoCode);

// Apply code with discount
Helpers::applyPromoCode($promoCode, $user, 100);

// Check if code exists
Helpers::promoCodeExists('PROMO-123');

// Get code details
Helpers::getPromoCode('PROMO-123');

// Get user's active codes
Helpers::getUserPromoCodes($user);
```

## Database Schema Overview

```
promo_codes
├── id (PK)
├── code (unique)
├── discount_percentage
├── max_usage
├── used_count
├── expires_at (nullable)
├── active
├── description
└── timestamps

promo_code_user (pivot)
├── id (PK)
├── promo_code_id (FK)
├── user_id (FK)
├── used (boolean)
├── used_at (timestamp)
└── timestamps

resume_subscriptions (updated)
├── promo_code_id (FK, nullable)
├── discount_amount (decimal, nullable)
├── original_amount (decimal, nullable)
└── ... existing fields
```

## Security Notes

✅ **Protected Routes**: All admin routes require authentication
✅ **Validation**: Server-side validation on all inputs
✅ **User Assignment**: Codes only work for assigned users
✅ **Usage Limit**: Built-in protection against reuse
✅ **Expiry Dates**: Automatic rejection of expired codes
✅ **Audit Trail**: All applications logged with timestamps

## Common Issues & Solutions

### Issue: Migration fails
**Solution**: Ensure database is accessible and migrations table exists
```bash
php artisan migrate:fresh --seed
```

### Issue: Promo code button not showing
**Solution**: Clear browser cache and reload
```bash
php artisan optimize:clear
```

### Issue: Code not applying discount
**Solution**: Check:
1. Code is active (`active = true`)
2. User is assigned to code
3. Code hasn't expired
4. User hasn't already used it

### Issue: Discount not showing in subscription
**Solution**: Verify `StripePaymentService::handleSuccessfulPayment()` is processing metadata correctly

## Next Steps

1. ✅ Run migrations (`php artisan migrate`)
2. ✅ Clear cache (`php artisan optimize:clear`)
3. ✅ Test promo code generation in admin panel
4. ✅ Test applying codes during checkout
5. ✅ Review documentation in `docs/PROMO_CODE_FUNCTIONALITY.md`
6. ✅ Customize email notifications (optional)
7. ✅ Set up automated job for expiring codes (optional)

## Support Resources

- **Full Documentation**: `docs/PROMO_CODE_FUNCTIONALITY.md`
- **Code Examples**: See relevant model classes
- **Database Schema**: Check migration files
- **API Endpoints**: See routes in `routes/web.php`

## Rollback (If Needed)

To remove promo code functionality:
```bash
php artisan migrate:rollback
```

Then delete these files:
- `app/Models/PromoCode.php`
- `app/Services/PromoCodeService.php`
- `app/Http/Controllers/Admin/PromoCodeController.php`
- `database/migrations/2026_03_04_*.php`

## Success Checklist

- [ ] Migrations completed successfully
- [ ] Cache cleared
- [ ] Admin can see "Send Promo" button on users
- [ ] Can generate promo codes
- [ ] Can send codes to users
- [ ] Users can apply codes during checkout
- [ ] Discounts show correctly
- [ ] Subscription records track discount details

Once all checkboxes are complete, your promo code system is fully operational! 🎉
