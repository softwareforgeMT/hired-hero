<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ImpersonateController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    /**
     * Impersonate a user
     */
    public function impersonate($userId)
    {
        try {
            // Get the user to impersonate
            $user = User::findOrFail($userId);

            // Get current admin for reverting later
            $admin = Auth::guard('admin')->user();

            // Store impersonation data in session
            session([
                'impersonating' => true,
                'impersonating_by' => $admin->id,
                'impersonating_by_name' => $admin->name,
                'impersonating_by_guard' => 'admin',
            ]);

            // Log the impersonation
            Log::info('Admin impersonating user', [
                'admin_id' => $admin->id,
                'admin_email' => $admin->email,
                'user_id' => $user->id,
                'user_email' => $user->email,
                'timestamp' => now(),
            ]);

            // Login as the user (logout from admin first)
            Auth::guard('admin')->logout();
            Auth::guard('web')->login($user);

            // Redirect to user dashboard or home
            return redirect()->route('dashboard')->with('success', "Impersonating {$user->name} ({$user->email})");
        } catch (\Exception $e) {
            Log::error('Failed to impersonate user', [
                'user_id' => $userId,
                'error' => $e->getMessage(),
                'admin_id' => Auth::guard('admin')->id(),
            ]);

            return back()->withErrors(['error' => 'Failed to impersonate user. User might not exist.']);
        }
    }

    /**
     * Stop impersonating and return to admin account
     */
    public function stopImpersonate()
    {
        // Get the original admin ID from session
        $adminId = session('impersonating_by');
        $guard = session('impersonating_by_guard', 'admin');

        if (!$adminId) {
            return redirect()->route('admin.dashboard')->withErrors(['error' => 'No active impersonation found.']);
        }

        try {
            // Get the impersonated user info for logging
            $impersonatedUser = Auth::user();

            // Logout from user account
            Auth::guard('web')->logout();

            // Get admin back
            $admin = \App\Models\Admin::findOrFail($adminId);

            // Login as admin
            Auth::guard('admin')->login($admin);

            // Clear impersonation session data
            session()->forget(['impersonating', 'impersonating_by', 'impersonating_by_name', 'impersonating_by_guard']);

            // Log the revert
            Log::info('Admin stopped impersonating user', [
                'admin_id' => $admin->id,
                'admin_email' => $admin->email,
                'impersonated_user_id' => $impersonatedUser->id ?? null,
                'impersonated_user_email' => $impersonatedUser->email ?? null,
                'timestamp' => now(),
            ]);

            return redirect()->route('admin.users.index')->with('success', 'Successfully reverted to admin account.');
        } catch (\Exception $e) {
            Log::error('Failed to stop impersonation', [
                'error' => $e->getMessage(),
                'admin_id' => $adminId,
            ]);

            return redirect()->route('admin.dashboard')->withErrors(['error' => 'Failed to revert to admin account.']);
        }
    }

    /**
     * Check if currently impersonating
     */
    public static function isImpersonating(): bool
    {
        return session('impersonating', false) === true;
    }

    /**
     * Get the original admin ID
     */
    public static function getOriginalAdminId()
    {
        return session('impersonating_by');
    }

    /**
     * Get the original admin name
     */
    public static function getOriginalAdminName()
    {
        return session('impersonating_by_name');
    }
}
