<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PromoCode;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Session;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class PromoCodeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    /**
     * Show promo code management page
     */
    public function index()
    {
        $promoCodes = PromoCode::orderBy('id', 'desc')->paginate(20);
        return view('admin.promo-codes.index', compact('promoCodes'));
    }

    /**
     * Show promo code details page with list of recipients
     */
    public function show($id)
    {
        try {
            $promoCode = PromoCode::findOrFail($id);
            
            // Get users assigned to this code
            $assignedUsers = $promoCode->users()->get();

            // Get custom emails that received this code
            $customEmails = $promoCode->sent_to_custom_emails ?? [];

            return view('admin.promo-codes.show', compact('promoCode', 'assignedUsers', 'customEmails'));
        } catch (\Exception $e) {
            return redirect()->route('admin.promo-codes.index')
                           ->with('error', 'Promo code not found: ' . $e->getMessage());
        }
    }

    /**
     * Get datatables for promo codes
     */
    public function datatables(Request $request)
    {
        $draw = $request->get('draw', 1);
        $start = $request->get('start', 0);
        $length = $request->get('length', 25);
        $search = $request->get('search');

        $query = PromoCode::query();

        // Search functionality
        if (!empty($search['value'])) {
            $searchTerm = $search['value'];
            $query->where('code', 'like', "%{$searchTerm}%")
                  ->orWhere('description', 'like', "%{$searchTerm}%");
        }

        // Get total count
        $recordsTotal = PromoCode::count();
        $recordsFiltered = $query->count();

        // Get paginated data
        $data = $query->orderBy('id', 'desc')
                      ->skip($start)
                      ->take($length)
                      ->get()
                      ->map(function ($code) {
                          return [
                              'id' => $code->id,
                              'code' => $code->code,
                              'discount_percentage' => $code->discount_percentage . '%',
                              'max_usage' => $code->max_usage,
                              'used_count' => $code->used_count,
                              'expires_at' => $code->expires_at ? $code->expires_at->format('M d, Y') : null,
                              'active' => $code->active,
                              'is_bulk' => $code->is_bulk,
                              'description' => $code->description,
                              'created_at' => $code->created_at->format('M d, Y - H:i'),
                          ];
                      });

        return response()->json([
            'draw' => $draw,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data,
        ]);
    }

    /**
     * Generate a new promo code for a user or bulk (AJAX endpoint)
     */
    public function generateForUser(Request $request)
    {
        try {
            $validated = $request->validate([
                'user_id' => 'nullable|exists:users,id',
                'discount_percentage' => 'required|numeric|min:0|max:100',
                'max_usage' => 'nullable|integer|min:1',
                'expires_at' => 'nullable|date|after:today',
                'description' => 'nullable|string|max:255',
                'is_bulk' => 'nullable|in:true,false,0,1',
            ]);

            $isBulk = $request->input('is_bulk', false);
            $maxUsage = $validated['max_usage'] ?? 1;

            // Generate unique promo code
            $code = $this->generateUniqueCode();

            // Create promo code
            $promoCode = PromoCode::create([
                'code' => $code,
                'discount_percentage' => $validated['discount_percentage'],
                'max_usage' => $maxUsage,
                'expires_at' => $validated['expires_at'] ? Carbon::parse($validated['expires_at']) : null,
                'active' => true,
                'is_bulk' => $isBulk,
                'description' => $validated['description'] ?? ($isBulk ? "Bulk promotional code" : "Promo code for user"),
            ]);

            // If not bulk, assign to specific user
            if (!$isBulk && isset($validated['user_id'])) {
                $user = User::find($validated['user_id']);
                $promoCode->users()->attach($user->id);

                return response()->json([
                    'success' => true,
                    'message' => 'Promo code generated successfully!',
                    'promo_code_id' => $promoCode->id,
                    'code' => $promoCode->code,
                    'user_email' => $user->email,
                    'user_id' => $user->id,
                    'discount_percentage' => $promoCode->discount_percentage,
                    'expires_at' => $promoCode->expires_at?->format('M d, Y'),
                ]);
            }

            // For bulk codes
            return response()->json([
                'success' => true,
                'message' => 'Promo code generated successfully!',
                'promo_code_id' => $promoCode->id,
                'code' => $promoCode->code,
                'discount_percentage' => $promoCode->discount_percentage,
                'expires_at' => $promoCode->expires_at?->format('M d, Y'),
                'max_usage' => $promoCode->max_usage,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error generating promo code: ' . $e->getMessage()
            ], 422);
        }
    }

    /**
     * Send promo code to user via email
     */
    public function sendToUser(Request $request)
    {
        try {
            $validated = $request->validate([
                'user_id' => 'required|exists:users,id',
                'promo_code_id' => 'required|exists:promo_codes,id',
                'include_unsubscribe' => 'boolean',
            ]);

            $user = User::find($validated['user_id']);
            $promoCode = PromoCode::find($validated['promo_code_id']);
            $includeUnsubscribe = $validated['include_unsubscribe'] ?? false;

            // Verify the user is assigned to this code
            if (!$promoCode->users()->where('user_id', $user->id)->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'This promo code is not assigned to this user.'
                ], 422);
            }

            // Generate unsubscribe token if needed
            $unsubscribeToken = null;
            if ($includeUnsubscribe) {
                $unsubscribeToken = base64_encode($user->id);
            }

            // Send email to user with proper error handling
            try {
                Mail::to($user->email)->send(new \App\Mail\SendPromoCodeMail($user, $promoCode, $unsubscribeToken));
                
                Log::info("Promo code email sent successfully", [
                    'user_id' => $user->id,
                    'user_email' => $user->email,
                    'promo_code' => $promoCode->code,
                    'include_unsubscribe' => $includeUnsubscribe,
                ]);
            } catch (\Exception $emailException) {
                Log::error("Failed to send promo code email", [
                    'user_id' => $user->id,
                    'user_email' => $user->email,
                    'promo_code' => $promoCode->code,
                    'error' => $emailException->getMessage(),
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to send email. Please check your email configuration or try again later.'
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => "Promo code email sent successfully to {$user->email}!",
            ]);
        } catch (\Exception $e) {
            Log::error('Error in sendToUser', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error sending promo code: ' . $e->getMessage()
            ], 422);
        }
    }

    /**
     * Send promo code to multiple users via email (batch operation)
     * Only sends to users with promotional_emails enabled
     */
    public function batchSendToUsers(Request $request)
    {
        try {
            $validated = $request->validate([
                'user_ids' => 'required|array|min:1',
                'user_ids.*' => 'required|exists:users,id',
                'promo_code_id' => 'required|exists:promo_codes,id',
                'include_unsubscribe' => 'boolean',
            ]);

            $promoCode = PromoCode::find($validated['promo_code_id']);
            $userIds = $validated['user_ids'];
            $includeUnsubscribe = $validated['include_unsubscribe'] ?? false;
            $successCount = 0;
            $failedCount = 0;
            $skippedCount = 0;
            $failedEmails = [];
            $sentUserIds = [];

            // Get users who have promotional_emails enabled
            $users = User::whereIn('id', $userIds)
                         ->where('promotional_emails', true)
                         ->get();

            $skippedCount = count($userIds) - $users->count();

            foreach ($users as $user) {
                try {
                    // Assign promo code to user if not already assigned
                    if (!$promoCode->users()->where('user_id', $user->id)->exists()) {
                        $promoCode->users()->attach($user->id);
                    }

                    // Generate unsubscribe token if needed
                    $unsubscribeToken = null;
                    if ($includeUnsubscribe) {
                        $unsubscribeToken = base64_encode($user->id);
                    }

                    // Send email to user with proper error handling
                    try {
                        Mail::to($user->email)->send(new \App\Mail\SendPromoCodeMail($user, $promoCode, $unsubscribeToken));
                        
                        Log::info("Batch: Promo code email sent successfully", [
                            'user_id' => $user->id,
                            'user_email' => $user->email,
                            'promo_code' => $promoCode->code,
                            'include_unsubscribe' => $includeUnsubscribe,
                        ]);
                        
                        $successCount++;
                        $sentUserIds[] = $user->id;
                    } catch (\Exception $emailException) {
                        Log::error("Batch: Failed to send promo code email", [
                            'user_id' => $user->id,
                            'user_email' => $user->email,
                            'promo_code' => $promoCode->code,
                            'error' => $emailException->getMessage(),
                        ]);
                        
                        $failedCount++;
                        $failedEmails[] = $user->email;
                    }
                } catch (\Exception $e) {
                    Log::error("Batch: Error processing user in batch send", [
                        'user_id' => $user->id,
                        'error' => $e->getMessage(),
                    ]);
                    
                    $failedCount++;
                }
            }

            // Save sent user IDs to the promo code
            if (!empty($sentUserIds)) {
                $currentSentUsers = $promoCode->sent_to_user_ids ?? [];
                $updatedSentUsers = array_unique(array_merge($currentSentUsers, $sentUserIds));
                $promoCode->update(['sent_to_user_ids' => $updatedSentUsers]);
            }

            // Prepare response message
            $message = "Promo code sent successfully to {$successCount} user(s)";
            if ($skippedCount > 0) {
                $message .= " ({$skippedCount} skipped - promotional emails disabled)";
            }
            if ($failedCount > 0) {
                $message .= " ({$failedCount} failed)";
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'sent_count' => $successCount,
                'skipped_count' => $skippedCount,
                'failed_count' => $failedCount,
                'failed_emails' => $failedEmails,
            ]);
        } catch (\Exception $e) {
            Log::error('Error in batchSendToUsers', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error sending promo codes: ' . $e->getMessage()
            ], 422);
        }
    }

    /**
     * Send multiple promo codes to selected recipients (platform/custom/all)
     * This is for the admin bulk send feature with recipient type selection
     */
    public function bulkSendPromos(Request $request)
    {
        try {
            $validated = $request->validate([
                'promo_code_ids' => 'required|array|min:1',
                'promo_code_ids.*' => 'required|exists:promo_codes,id',
                'recipient_type' => 'required|in:platform,custom,all',
                'include_unsubscribe' => 'boolean',
                'custom_emails' => 'nullable|array',
                'custom_emails.*' => 'nullable|email',
            ]);

            $promoCodeIds = $validated['promo_code_ids'];
            $recipientType = $validated['recipient_type'];
            $includeUnsubscribe = $validated['include_unsubscribe'] ?? false;
            $customEmails = $validated['custom_emails'] ?? [];

            // Prepare recipient lists based on type
            $platformUsers = collect();
            $externalEmails = [];
            $totalPlatformSent = 0;
            $totalCustomSent = 0;
            $totalFailed = 0;
            $failedEmails = [];

            // Get platform users based on recipient type
            if ($recipientType === 'platform' || $recipientType === 'all') {
                $platformUsers = User::where('promotional_emails', true)->get();
                
                if ($recipientType === 'platform' && $platformUsers->isEmpty()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'No platform users with promotional emails enabled found.'
                    ], 422);
                }
            }

            // Process custom emails based on recipient type
            if ($recipientType === 'custom' || $recipientType === 'all') {
                $externalEmails = array_filter($customEmails, function($email) {
                    return filter_var($email, FILTER_VALIDATE_EMAIL);
                });

                if ($recipientType === 'custom' && empty($externalEmails)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'No valid email addresses provided.'
                    ], 422);
                }
            }

            // Send to platform users (if applicable)
            if ($recipientType === 'platform' || $recipientType === 'all') {
                foreach ($promoCodeIds as $promoCodeId) {
                    try {
                        $promoCode = PromoCode::find($promoCodeId);
                        if (!$promoCode) {
                            continue;
                        }

                        $sentUserIds = [];

                        foreach ($platformUsers as $user) {
                            try {
                                // Assign promo code to user if not already assigned
                                if (!$promoCode->users()->where('user_id', $user->id)->exists()) {
                                    $promoCode->users()->attach($user->id);
                                }

                                // Generate unsubscribe token if needed
                                $unsubscribeToken = null;
                                if ($includeUnsubscribe) {
                                    $unsubscribeToken = base64_encode($user->id);
                                }

                                // Send email to platform user
                                try {
                                    Mail::to($user->email)->send(new \App\Mail\SendPromoCodeMail($user, $promoCode, $unsubscribeToken));
                                    
                                    Log::info("Bulk Send: Promo code email sent to platform user", [
                                        'user_id' => $user->id,
                                        'user_email' => $user->email,
                                        'promo_code' => $promoCode->code,
                                        'recipient_type' => $recipientType,
                                    ]);
                                    
                                    $totalPlatformSent++;
                                    $sentUserIds[] = $user->id;
                                } catch (\Exception $emailException) {
                                    Log::error("Bulk Send: Failed to send email to platform user", [
                                        'user_id' => $user->id,
                                        'user_email' => $user->email,
                                        'promo_code' => $promoCode->code,
                                        'error' => $emailException->getMessage(),
                                    ]);
                                    
                                    $totalFailed++;
                                    if (!in_array($user->email, $failedEmails)) {
                                        $failedEmails[] = $user->email;
                                    }
                                }
                            } catch (\Exception $e) {
                                Log::error("Bulk Send: Error processing platform user", [
                                    'user_id' => $user->id,
                                    'error' => $e->getMessage(),
                                ]);
                                
                                $totalFailed++;
                            }
                        }

                        // Save sent user IDs to the promo code
                        if (!empty($sentUserIds)) {
                            $currentSentUsers = $promoCode->sent_to_user_ids ?? [];
                            $updatedSentUsers = array_unique(array_merge($currentSentUsers, $sentUserIds));
                            $promoCode->update(['sent_to_user_ids' => $updatedSentUsers]);
                        }
                    } catch (\Exception $e) {
                        Log::error("Bulk Send: Error processing promo code for platform users", [
                            'promo_code_id' => $promoCodeId,
                            'error' => $e->getMessage(),
                        ]);
                    }
                }
            }

            // Send to custom/external emails (if applicable)
            if (($recipientType === 'custom' || $recipientType === 'all') && !empty($externalEmails)) {
                foreach ($promoCodeIds as $promoCodeId) {
                    try {
                        $promoCode = PromoCode::find($promoCodeId);
                        if (!$promoCode) {
                            continue;
                        }

                        $sentCustomEmails = [];

                        foreach ($externalEmails as $email) {
                            try {
                                // Send email to external/custom email using dedicated external user mail class
                                try {
                                    Mail::to($email)->send(new \App\Mail\PromoCodeExternalUserMail($promoCode, $email));
                                    
                                    Log::info("Bulk Send: Promo code email sent to custom email", [
                                        'email' => $email,
                                        'promo_code' => $promoCode->code,
                                        'recipient_type' => $recipientType,
                                    ]);
                                    
                                    $totalCustomSent++;
                                    $sentCustomEmails[] = $email;
                                } catch (\Exception $emailException) {
                                    Log::error("Bulk Send: Failed to send email to custom email", [
                                        'email' => $email,
                                        'promo_code' => $promoCode->code,
                                        'error' => $emailException->getMessage(),
                                    ]);
                                    
                                    $totalFailed++;
                                    if (!in_array($email, $failedEmails)) {
                                        $failedEmails[] = $email;
                                    }
                                }
                            } catch (\Exception $e) {
                                Log::error("Bulk Send: Error processing custom email", [
                                    'email' => $email,
                                    'error' => $e->getMessage(),
                                ]);
                                
                                $totalFailed++;
                            }
                        }

                        // Save sent custom emails to the promo code
                        if (!empty($sentCustomEmails)) {
                            $currentSentEmails = $promoCode->sent_to_custom_emails ?? [];
                            $updatedSentEmails = array_unique(array_merge($currentSentEmails, $sentCustomEmails));
                            $promoCode->update(['sent_to_custom_emails' => $updatedSentEmails]);
                        }
                    } catch (\Exception $e) {
                        Log::error("Bulk Send: Error processing promo code for custom emails", [
                            'promo_code_id' => $promoCodeId,
                            'error' => $e->getMessage(),
                        ]);
                    }
                }
            }

            // Calculate total sent
            $totalSent = $totalPlatformSent + $totalCustomSent;

            // Prepare response message
            $message = "Promo code(s) sent successfully";
            if ($totalFailed > 0) {
                $message .= " ({$totalFailed} failed)";
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'sent_count' => $totalSent,
                'platform_count' => $totalPlatformSent,
                'custom_count' => $totalCustomSent,
                'failed_count' => $totalFailed,
                'failed_emails' => $failedEmails,
            ]);
        } catch (\Exception $e) {
            Log::error('Error in bulkSendPromos', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error sending promo codes: ' . $e->getMessage()
            ], 422);
        }
    }

    /**
     * Deactivate a promo code
     */
    public function deactivate(Request $request, $id)
    {
        try {
            $promoCode = PromoCode::findOrFail($id);
            $promoCode->update(['active' => false]);

            return response()->json([
                'success' => true,
                'message' => 'Promo code deactivated successfully!',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deactivating promo code: ' . $e->getMessage()
            ], 422);
        }
    }

    /**
     * Activate a promo code
     */
    public function activate(Request $request, $id)
    {
        try {
            $promoCode = PromoCode::findOrFail($id);
            $promoCode->update(['active' => true]);

            return response()->json([
                'success' => true,
                'message' => 'Promo code activated successfully!',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error activating promo code: ' . $e->getMessage()
            ], 422);
        }
    }

    /**
     * Delete a promo code
     */
    public function destroy(Request $request, $id)
    {
        try {
            $promoCode = PromoCode::findOrFail($id);
            $promoCode->users()->detach(); // Remove user associations
            $promoCode->delete();

            return response()->json([
                'success' => true,
                'message' => 'Promo code deleted successfully!',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting promo code: ' . $e->getMessage()
            ], 422);
        }
    }

    /**
     * View user's assigned promo codes (AJAX)
     */
    public function userPromoCodes(Request $request, $userId)
    {
        try {
            $user = User::findOrFail($userId);
            $codes = $user->promoCodes()->get();

            return response()->json([
                'success' => true,
                'codes' => $codes->map(function ($code) {
                    return [
                        'id' => $code->id,
                        'code' => $code->code,
                        'discount_percentage' => $code->discount_percentage,
                        'expires_at' => $code->expires_at?->format('M d, Y'),
                        'used' => $code->pivot->used ? 'Yes' : 'No',
                        'used_at' => $code->pivot->used_at?->format('M d, Y H:i'),
                    ];
                })
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching promo codes: ' . $e->getMessage()
            ], 422);
        }
    }

    /**
     * Generate a unique promo code
     */
    private function generateUniqueCode(): string
    {
        do {
            // Generate code: XXXX-XXXX-XXXX (12 chars + 2 dashes)
            $code = strtoupper(
                substr(str_shuffle(str_repeat("0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ", 2)), 0, 4) . '-' .
                substr(str_shuffle(str_repeat("0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ", 2)), 0, 4) . '-' .
                substr(str_shuffle(str_repeat("0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ", 2)), 0, 4)
            );
        } while (PromoCode::where('code', $code)->exists());

        return $code;
    }
}
