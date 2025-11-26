<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        // Redirect based on user role
        $user = Auth::user();
        
        if ($user->hasRole('Admin')) {
            return redirect()->intended(route('admin.dashboard'));
        } elseif ($user->hasRole('Inventory Personnel')) {
            return redirect()->intended(route('inventory-personnel.dashboard'));
        } elseif ($user->hasRole('Inventory Supervisor')) {
            return redirect()->intended(route('inventory-supervisor.dashboard'));
        } elseif ($user->hasRole('Catering Incharge')) {
            return redirect()->intended(route('catering-incharge.dashboard'));
        } elseif ($user->hasRole('Catering Staff')) {
            return redirect()->intended(route('catering-staff.dashboard'));
        } elseif ($user->hasRole('Ramp Dispatcher')) {
            return redirect()->intended(route('ramp-dispatcher.dashboard'));
        } elseif ($user->hasRole('Security Staff')) {
            return redirect()->intended(route('security-staff.dashboard'));
        } elseif ($user->hasRole('Cabin Crew')) {
            return redirect()->intended(route('cabin-crew.dashboard'));
        } elseif ($user->hasRole('Flight Purser')) {
            return redirect()->intended(route('flight-purser.dashboard'));
        }
        
        // Default fallback to admin dashboard
        return redirect()->intended(route('admin.dashboard'));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
