<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class HomeController extends Controller
{
    /**
     * Require admin auth for everything here.
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    /**
     * Resolve a blade view by the requested path if it exists, else 404 view.
     */
    public function index(Request $request)
    {
        $path = (string) $request->path();

        if (view()->exists($path)) {
            return view($path);
        }

        return view('errors.404');
    }

    /**
     * Root fallback.
     */
    public function root()
    {
        return view('index');
    }

    /**
     * Language switcher.
     */
    public function lang($locale)
    {
        if (!empty($locale)) {
            App::setLocale($locale);
            Session::put('lang', $locale);
            Session::save();

            return redirect()->back()->with('locale', $locale);
        }

        return redirect()->back();
    }

    /**
     * Update basic profile info (name, email, optional avatar).
     */
    public function updateProfile(Request $request, $id)
    {
        $request->validate([
            'name'   => ['required', 'string', 'max:255'],
            'email'  => ['required', 'string', 'email'],
            'avatar' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:1024'],

        $user = User::find($id);
        if (!$user) {
            Session::flash('message', 'User not found.');
            Session::flash('alert-class', 'alert-danger');
            return redirect()->back();
        }

        $user->name  = $request->string('name');
        $user->email = $request->string('email');

        if ($request->hasFile('avatar')) {
            $avatar     = $request->file('avatar');
            $avatarName = time() . '.' . $avatar->getClientOriginalExtension();
            $avatarPath = public_path('/images/');
            // this mirrors your current behavior (no storage changes)
            $avatar->move($avatarPath, $avatarName);
            $user->avatar = $avatarName;
        }

        if ($user->isDirty() && $user->save()) {
            Session::flash('message', 'User Details Updated successfully!');
            Session::flash('alert-class', 'alert-success');
            return redirect()->back();
        }

        Session::flash('message', 'Something went wrong!');
        Session::flash('alert-class', 'alert-danger');
        return redirect()->back();
    }

    /**
     * Update password (validates current, sets new).
     */
    public function updatePassword(Request $request, $id)
    {
        $request->validate([
            'current_password' => ['required', 'string'],
            'password'         => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $currentUser = Auth::user();
        if (!$currentUser || !Hash::check($request->get('current_password'), $currentUser->password)) {
            return response()->json([
                'isSuccess' => false,
                'Message'   => 'Your Current password does not matches with the password you provided. Please try again.',
            ], 200);
        }

        $user = User::find($id);
        if (!$user) {
            Session::flash('message', 'User not found!');
            Session::flash('alert-class', 'alert-danger');

            return response()->json([
                'isSuccess' => true,
                'Message'   => 'User not found!',
            ], 200);
        }

        $user->password = Hash::make($request->get('password'));

        if ($user->save()) {
            Session::flash('message', 'Password updated successfully!');
            Session::flash('alert-class', 'alert-success');

            return response()->json([
                'isSuccess' => true,
                'Message'   => 'Password updated successfully!',
            ], 200);
        }

        Session::flash('message', 'Something went wrong!');
        Session::flash('alert-class', 'alert-danger');

        return response()->json([
            'isSuccess' => true,
            'Message'   => 'Something went wrong!',
        ], 200);
    }
}
