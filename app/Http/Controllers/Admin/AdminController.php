<?php

namespace App\Http\Controllers\Admin;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Models\GeneralSetting;
use App\Models\Product;
use App\Models\SocialSetting;
use App\Models\User;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Session;

class AdminController extends Controller
{
    protected $settings;

    public function __construct(GeneralSetting $settings)
    {
        $this->settings = $settings::first();
        $this->middleware('auth:admin');
    }

    public function index()
    {
        return view('admin.dashboard');
    }

    public function generalsettings(Request $request)
    {
        $data = GeneralSetting::find(1);
        return view('admin.generalsettings', compact('data'));
    }

    public function generalsettingsupdate(Request $request)
    {
        $input = $request->all();
        $data  = GeneralSetting::find(1);

        if ($file = $request->file('favicon')) {
            $data->favicon = Helpers::update('/', $data->favicon, config('fileformats.image'), $file);
        }

        if ($file = $request->file('logo_light')) {
            $data->logo_light = Helpers::update('/logo/', $data->logo_light, config('fileformats.image'), $file);
        }

        if ($file = $request->file('logo_dark')) {
            $data->logo_dark = Helpers::update('/logo/', $data->logo_dark, config('fileformats.image'), $file);
        }

        if ($file = $request->file('logo_light2')) {
            $data->logo_light2 = Helpers::update('/logo/', $data->logo_light2, config('fileformats.image'), $file);
        }

        if ($file = $request->file('logo_dark2')) {
            $data->logo_dark2 = Helpers::update('/logo/', $data->logo_dark2, config('fileformats.image'), $file);
        }

        if ($file = $request->file('intro_video')) {
            $name = time() . $file->getClientOriginalName();
            $file->move('assets/dynamic/images/', $name);

            if ($data->intro_video && file_exists(public_path('/assets/dynamic/images/' . $data->intro_video))) {
                @unlink(public_path('/assets/dynamic/images/' . $data->intro_video));
            }

            $data->intro_video = $name;
        }

        $data->name   = $request->name;
        $data->slogan = $request->slogan;
        $data->update();

        Session::flash('message', 'Successfully updated Data');
        Session::flash('alert-class', 'alert-success');
        return redirect()->back();
    }

    public function passwordreset()
    {
        return view('admin.cpassword');
    }

    public function changepass(Request $request)
    {
        $request->validate([
            'cpass'     => 'required|string|min:6',
            'newpass'   => 'required|string|min:8|different:cpass',
            'renewpass' => 'required|string|same:newpass',
        ], [
            'cpass.required' => 'Current password is required.',
            'cpass.min' => 'Current password must be at least 6 characters.',
            'newpass.required' => 'New password is required.',
            'newpass.min' => 'New password must be at least 8 characters.',
            'newpass.different' => 'New password must be different from current password.',
            'renewpass.required' => 'Password confirmation is required.',
            'renewpass.same' => 'Password confirmation does not match new password.',
        ]);

        $admin = Auth::guard('admin')->user();

        if (!Hash::check($request->cpass, $admin->password)) {
            return redirect()->back()->withInput()->withErrors([
                'cpass' => 'Current password does not match.',
            ]);
        }

        $admin->update(['password' => Hash::make($request->newpass)]);

        Session::flash('message', 'Password changed successfully!');
        Session::flash('alert-class', 'alert-success');
        return redirect()->back();
    }

    public function profile()
    {
        $data = Auth::guard('admin')->user();
        return view('admin.profile', compact('data'));
    }

    public function profileupdate(Request $request)
    {
        $request->validate([
            'name' => 'required|string|min:2|max:255',
            'email' => 'required|email|unique:admins,email,' . Auth::guard('admin')->user()->id,
            'photo' => 'nullable|mimes:jpeg,jpg,png,svg|max:2048',
        ], [
            'name.required' => 'Name field is required.',
            'name.min' => 'Name must be at least 2 characters.',
            'email.required' => 'Email field is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email is already in use.',
            'photo.mimes' => 'Photo must be a valid image file (jpeg, jpg, png, svg).',
            'photo.max' => 'Photo size must not exceed 2MB.',
        ]);

        $input = $request->all();
        $data  = Auth::guard('admin')->user();

        if ($file = $request->file('photo')) {
            $name = time() . $file->getClientOriginalName();
            $file->move('assets/dynamic/images/admins/', $name);

            if ($data->photo && file_exists(public_path('/assets/dynamic/images/admins/' . $data->photo))) {
                @unlink(public_path('/assets/dynamic/images/admins/' . $data->photo));
            }

            $input['photo'] = $name;
        }

        $data->fill($input)->save();

        // Clear any cache related to this admin
        \Illuminate\Support\Facades\Cache::forget('admin_profile_' . $data->id);

        Session::flash('message', 'Data Updated Successfully!');
        Session::flash('alert-class', 'alert-success');
        return redirect()->back();
    }

    public function social()
    {
        $data = SocialSetting::find(1);
        return view('admin.socialsettings', compact('data'));
    }

    public function socialupdate(Request $request)
    {
        $request->validate([
            'facebook' => 'nullable|url',
            'twitter' => 'nullable|url',
            'instagram' => 'nullable|url',
            'youtube' => 'nullable|url',
            'linkedin' => 'nullable|url',
        ], [
            'facebook.url' => 'Facebook URL must be a valid URL.',
            'twitter.url' => 'Twitter URL must be a valid URL.',
            'instagram.url' => 'Instagram URL must be a valid URL.',
            'youtube.url' => 'YouTube URL must be a valid URL.',
            'linkedin.url' => 'LinkedIn URL must be a valid URL.',
        ]);

        $input = $request->all();
        $data  = SocialSetting::find(1);

        $data->update($input);

        Session::flash('message', 'Social media links updated successfully!');
        Session::flash('alert-class', 'alert-success');
        return redirect()->back();
    }

    public function updateSocialLogin(Request $request)
    {
        $this->settings->facebook_login = $request->facebook_login;
        $this->settings->google_login   = $request->google_login;
        $this->settings->twitter_login  = $request->twitter_login;
        $this->settings->save();

        foreach ($request->except(['_token']) as $key => $value) {
            Helpers::envUpdate($key, $value);
        }

        Session::flash('message', 'Data Updated Successfully!');
        Session::flash('alert-class', 'alert-success');
        return redirect()->back();
    }

    // ==========================
    // REFERRALS (NEW)
    // ==========================

    public function referrals()
    {
        // Make sure this file exists:
        // resources/views/admin/referrals/index.blade.php
        return view('admin.referrals.index');
    }

    public function referralsDatatables(Request $request)
    {
        // Simple working JSON first (no joins, no datatables dependency)
        $rows = User::query()
            ->whereNotNull('affiliate_code')
            ->select([
                'id',
                'name',
                'email',
                'affiliate_code',
                'referred_by',
                'referral_discount_used',
            ])
            ->orderByDesc('id')
            ->limit(500)
            ->get();

        return response()->json($rows);
    }

    /**
     * Toggle demo mode
     */
    public function toggleDemoMode(Request $request)
    {
        // Only super admin can toggle demo mode (security check)
        if (!Auth::guard('admin')->check()) {
            return back()->with('error', 'Unauthorized access.');
        }

        try {
            $setting = GeneralSetting::find(1);
            if (!$setting) {
                return back()->with('error', 'Settings not found.');
            }

            // Toggle the demo mode
            $setting->demo_mode = !$setting->demo_mode;
            $setting->save();

            // Clear cache to ensure changes take effect immediately
            \Illuminate\Support\Facades\Cache::forget('general_settings');

            // If turning demo mode ON, populate initial dummy data
            if ($setting->demo_mode) {
                \App\Models\DemoSettings::populateInitialDemoData();
            }

            $mode = $setting->demo_mode ? 'DEMO' : 'LIVE';
            Session::flash('message', "Switch to $mode mode successfully!");
            Session::flash('alert-class', 'alert-success');

            return back();
        } catch (\Exception $e) {
            Session::flash('error', 'Failed to toggle demo mode: '.$e->getMessage());
            Session::flash('alert-class', 'alert-danger');
            return back();
        }
    }
}
