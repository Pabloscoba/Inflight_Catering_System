<?php

namespace App\Http\Controllers\FlightDispatcher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SettingsController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        return view('flight-dispatcher.settings.index', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . auth()->id(),
            'phone' => 'nullable|string|max:20',
        ]);

        $user = auth()->user();
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
        ]);

        activity()
            ->causedBy($user)
            ->log('Updated profile information');

        return redirect()->back()->with('success', 'Profile updated successfully!');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        $user = auth()->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        $user->update([
            'password' => Hash::make($request->new_password),
        ]);

        activity()
            ->causedBy($user)
            ->log('Changed password');

        return redirect()->back()->with('success', 'Password changed successfully!');
    }

    public function updatePreferences(Request $request)
    {
        $request->validate([
            'email_notifications' => 'boolean',
            'display_mode' => 'in:light,dark,auto',
            'items_per_page' => 'integer|min:10|max:100',
        ]);

        $user = auth()->user();
        $preferences = $user->preferences ?? [];
        
        $preferences['email_notifications'] = $request->has('email_notifications');
        $preferences['display_mode'] = $request->display_mode ?? 'light';
        $preferences['items_per_page'] = $request->items_per_page ?? 25;

        $user->update(['preferences' => $preferences]);

        activity()
            ->causedBy($user)
            ->log('Updated preferences');

        return redirect()->back()->with('success', 'Preferences updated successfully!');
    }
}
