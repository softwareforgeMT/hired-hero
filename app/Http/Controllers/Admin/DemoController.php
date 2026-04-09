<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DemoSettings;
use App\Models\GeneralSetting;
use App\Models\SocialSetting;
use App\Models\Page;
use App\CentralLogics\Helpers;
use Illuminate\Http\Request;
use Session;

class DemoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    /**
     * Handle general settings update in demo mode
     */
    public function updateGeneralSettings(Request $request)
    {
        if (!Helpers::demo_mode()) {
            return redirect()->route('admin.generalsettings')->with('error', 'Demo mode is not active.');
        }

        try {
            // Get current demo data or use real settings as base
            $realSettings = GeneralSetting::find(1);
            $demoData = DemoSettings::getBySection('general_settings') ?? [];

            // Prepare data to save (keeping existing files/images)
            $updateData = [
                'name' => $request->input('name') ?? $realSettings->name,
                'slogan' => $request->input('slogan') ?? $realSettings->slogan,
            ];

            // Save to demo settings table
            DemoSettings::saveForSection('general_settings', $updateData);

            Session::flash('message', 'Demo mode: Settings saved to demo database (not live).');
            Session::flash('alert-class', 'alert-info');

            return redirect()->route('admin.generalsettings');
        } catch (\Exception $e) {
            Session::flash('error', 'Error: ' . $e->getMessage());
            Session::flash('alert-class', 'alert-danger');
            return redirect()->route('admin.generalsettings');
        }
    }

    /**
     * Handle social settings update in demo mode
     */
    public function updateSocialSettings(Request $request)
    {
        if (!Helpers::demo_mode()) {
            return redirect()->route('admin.social')->with('error', 'Demo mode is not active.');
        }

        try {
            $realSettings = SocialSetting::find(1);
            $demoData = DemoSettings::getBySection('social_settings') ?? [];

            // Prepare data to save
            $updateData = [
                'facebook' => $request->input('facebook') ?? $realSettings->facebook,
                'twitter' => $request->input('twitter') ?? $realSettings->twitter,
                'instagram' => $request->input('instagram') ?? $realSettings->instagram,
                'youtube' => $request->input('youtube') ?? $realSettings->youtube,
                'linkedin' => $request->input('linkedin') ?? $realSettings->linkedin,
            ];

            // Save to demo settings table
            DemoSettings::saveForSection('social_settings', $updateData);

            Session::flash('message', 'Demo mode: Social settings saved to demo database (not live).');
            Session::flash('alert-class', 'alert-info');

            return redirect()->route('admin.social');
        } catch (\Exception $e) {
            Session::flash('error', 'Error: ' . $e->getMessage());
            Session::flash('alert-class', 'alert-danger');
            return redirect()->route('admin.social');
        }
    }

    /**
     * Handle profile update in demo mode
     */
    public function updateProfileDemo(Request $request)
    {
        if (!Helpers::demo_mode()) {
            return redirect()->route('admin.profile')->with('error', 'Demo mode is not active.');
        }

        try {
            $demoData = DemoSettings::getBySection('profile_settings') ?? [];

            // Prepare data to save
            $updateData = [
                'name' => $request->input('name'),
                'email' => $request->input('email'),
            ];

            // Save to demo settings table
            DemoSettings::saveForSection('profile_settings', $updateData);

            Session::flash('message', 'Demo mode: Profile saved to demo database (not live).');
            Session::flash('alert-class', 'alert-info');

            return redirect()->route('admin.profile');
        } catch (\Exception $e) {
            Session::flash('error', 'Error: ' . $e->getMessage());
            Session::flash('alert-class', 'alert-danger');
            return redirect()->route('admin.profile');
        }
    }

    /**
     * Handle page creation in demo mode
     */
    public function storePageDemo(Request $request)
    {
        if (!Helpers::demo_mode()) {
            return redirect()->route('admin.custompage.index')->with('error', 'Demo mode is not active.');
        }

        try {
            $this->validate($request, [
                'slug' => 'required|string',
                'title' => 'required|string',
            ]);

            $pages = DemoSettings::getBySection('pages') ?? [];

            // Create new page entry
            $newPage = [
                'id' => (count($pages) > 0 ? max(array_keys($pages)) + 1 : 1),
                'slug' => $request->slug,
                'title' => $request->title,
                'details' => $request->details ?? '',
                'status' => 1,
            ];

            $pages[$newPage['id']] = $newPage;

            // Save to demo settings table
            DemoSettings::saveForSection('pages', $pages);

            Session::flash('message', 'Demo mode: Page created in demo database (not live).');
            Session::flash('alert-class', 'alert-info');

            return redirect()->route('admin.custompage.index');
        } catch (\Exception $e) {
            Session::flash('error', 'Error: ' . $e->getMessage());
            Session::flash('alert-class', 'alert-danger');
            return redirect()->back();
        }
    }

    /**
     * Handle page update in demo mode
     */
    public function updatePageDemo(Request $request, $id)
    {
        if (!Helpers::demo_mode()) {
            return redirect()->route('admin.custompage.index')->with('error', 'Demo mode is not active.');
        }

        try {
            $this->validate($request, [
                'slug' => 'required|string',
                'title' => 'required|string',
            ]);

            $pages = DemoSettings::getBySection('pages') ?? [];

            // Update page entry
            if (isset($pages[$id])) {
                $pages[$id]['slug'] = $request->slug;
                $pages[$id]['title'] = $request->title;
                $pages[$id]['details'] = $request->details ?? '';
            }

            // Save to demo settings table
            DemoSettings::saveForSection('pages', $pages);

            Session::flash('message', 'Demo mode: Page updated in demo database (not live).');
            Session::flash('alert-class', 'alert-info');

            return redirect()->route('admin.custompage.index');
        } catch (\Exception $e) {
            Session::flash('error', 'Error: ' . $e->getMessage());
            Session::flash('alert-class', 'alert-danger');
            return redirect()->back();
        }
    }

    /**
     * Handle page deletion in demo mode
     */
    public function deletePageDemo($id)
    {
        if (!Helpers::demo_mode()) {
            return redirect()->route('admin.custompage.index')->with('error', 'Demo mode is not active.');
        }

        try {
            $pages = DemoSettings::getBySection('pages') ?? [];

            // Delete page entry
            if (isset($pages[$id])) {
                unset($pages[$id]);
            }

            // Save to demo settings table
            DemoSettings::saveForSection('pages', $pages);

            Session::flash('message', 'Demo mode: Page deleted from demo database (not live).');
            Session::flash('alert-class', 'alert-info');

            return redirect()->route('admin.custompage.index');
        } catch (\Exception $e) {
            Session::flash('error', 'Error: ' . $e->getMessage());
            Session::flash('alert-class', 'alert-danger');
            return redirect()->back();
        }
    }

    /**
     * Handle page status change in demo mode
     */
    public function updatePageStatus($id, $status)
    {
        if (!Helpers::demo_mode()) {
            return redirect()->route('admin.custompage.index')->with('error', 'Demo mode is not active.');
        }

        try {
            $pages = DemoSettings::getBySection('pages') ?? [];

            // Update page status
            if (isset($pages[$id])) {
                $pages[$id]['status'] = (int) $status;
            }

            // Save to demo settings table
            DemoSettings::saveForSection('pages', $pages);

            Session::flash('message', 'Demo mode: Page status updated in demo database (not live).');
            Session::flash('alert-class', 'alert-info');

            return redirect()->route('admin.custompage.index');
        } catch (\Exception $e) {
            Session::flash('error', 'Error: ' . $e->getMessage());
            Session::flash('alert-class', 'alert-danger');
            return redirect()->back();
        }
    }
}
