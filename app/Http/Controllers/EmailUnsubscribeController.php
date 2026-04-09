<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EmailUnsubscribeController extends Controller
{
    /**
     * Handle email unsubscribe request
     * 
     * @param string $token - Encrypted user identifier
     * @return \Illuminate\View\View
     */
    public function handle($token)
    {
        try {
            // Decode the token to get user ID
            // The token format is: base64(user_id)
            $userId = base64_decode($token, true);
            
            if ($userId === false) {
                return view('email-unsubscribe', [
                    'success' => false,
                    'message' => 'Invalid unsubscribe link.',
                ]);
            }

            $user = User::find($userId);

            if (!$user) {
                return view('email-unsubscribe', [
                    'success' => false,
                    'message' => 'User not found.',
                ]);
            }

            // Update the user's promotional_emails preference
            $user->update(['promotional_emails' => false]);

            Log::info('User unsubscribed from promotional emails', [
                'user_id' => $user->id,
                'user_email' => $user->email,
            ]);

            return view('email-unsubscribe', [
                'success' => true,
                'message' => 'You have been successfully unsubscribed from promotional emails.',
                'email' => $user->email,
            ]);
        } catch (\Exception $e) {
            Log::error('Error in email unsubscribe', [
                'token' => $token,
                'error' => $e->getMessage(),
            ]);

            return view('email-unsubscribe', [
                'success' => false,
                'message' => 'An error occurred while processing your unsubscribe request.',
            ]);
        }
    }
}
