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

        // Get authenticated user
        $user = Auth::user();
        
        // Log successful login
        activity('authentication')
            ->causedBy($user)
            ->withProperties([
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'role' => $user->role?->name,
            ])
            ->log('User logged in successfully');
        
        // Store session security data
        session([
            'user_ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'last_regenerated' => now()->timestamp,
        ]);
        
        // Get intended URL and check if it's a notification endpoint
        $intended = session()->pull('url.intended', '');
        $isNotificationEndpoint = str_contains($intended, '/notifications/');
        
        // Determine redirect URL based on role
        $redirectUrl = null;
        if ($user->hasRole('Admin')) {
            $redirectUrl = route('admin.dashboard');
        } elseif ($user->hasRole('Inventory Personnel')) {
            $redirectUrl = route('inventory-personnel.dashboard');
        } elseif ($user->hasRole('Inventory Supervisor')) {
            $redirectUrl = route('inventory-supervisor.dashboard');
        } elseif ($user->hasRole('Catering Incharge')) {
            $redirectUrl = route('catering-incharge.dashboard');
        } elseif ($user->hasRole('Catering Staff')) {
            $redirectUrl = route('catering-staff.dashboard');
        } elseif ($user->hasRole('Ramp Dispatcher')) {
            $redirectUrl = route('ramp-dispatcher.dashboard');
        } elseif ($user->hasRole('Security Staff')) {
            $redirectUrl = route('security-staff.dashboard');
        } elseif ($user->hasRole('Cabin Crew')) {
            $redirectUrl = route('cabin-crew.dashboard');
        } elseif ($user->hasRole('Flight Purser')) {
            $redirectUrl = route('flight-purser.dashboard');
        } elseif ($user->hasRole('Flight Dispatcher')) {
            $redirectUrl = route('flight-dispatcher.dashboard');
        } elseif ($user->hasAnyRole(['Flight Operations Manager', 'Flight Ops', 'flightops'])) {
            $redirectUrl = route('flight-operations-manager.dashboard');
        } else {
            $redirectUrl = route('admin.dashboard');
        }
        
        // If intended URL points to admin area, but user lacks Admin role/permissions, ignore it
        if ($intended) {
            $intendedPath = parse_url($intended, PHP_URL_PATH) ?: '';
            if (str_starts_with($intendedPath, '/admin')) {
                $adminPermissions = [
                    'manage users', 'manage roles', 'manage products', 'manage categories',
                    'view all requests', 'approve requests', 'manage flights',
                    'manage system settings', 'view activity logs', 'manage backups'
                ];

                $canAccessAdmin = $user->hasRole('Admin');
                if (!$canAccessAdmin) {
                    foreach ($adminPermissions as $perm) {
                        if ($user->can($perm)) {
                            $canAccessAdmin = true;
                            break;
                        }
                    }
                }

                if (!$canAccessAdmin) {
                    $intended = '';
                }
            }
        }

        // If intended URL is not a notification endpoint, use it; otherwise use role-based URL
        if (!$isNotificationEndpoint && $intended) {
            return redirect($intended);
        }
        
        return redirect($redirectUrl);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        // Log logout before destroying session
        if (Auth::check()) {
            activity('authentication')
                ->causedBy(Auth::user())
                ->withProperties([
                    'ip' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ])
                ->log('User logged out');
        }
        
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
