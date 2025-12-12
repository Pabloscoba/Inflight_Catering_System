# 🔒 Comprehensive Security Implementation Guide
## Inflight Catering System - Security Hardening

**Date**: December 3, 2025  
**Status**: Production Security Checklist

---

## 🛡️ 1. AUTHENTICATION & ACCESS CONTROL (CRITICAL)

### A. Strong Password Policy
```php
// config/auth.php - Add password rules
'password_timeout' => 10800, // 3 hours

// app/Rules/StrongPassword.php - Create custom rule
<?php
namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class StrongPassword implements Rule
{
    public function passes($attribute, $value)
    {
        // Minimum 12 characters
        // At least 1 uppercase, 1 lowercase, 1 number, 1 special char
        return strlen($value) >= 12 &&
               preg_match('/[A-Z]/', $value) &&
               preg_match('/[a-z]/', $value) &&
               preg_match('/[0-9]/', $value) &&
               preg_match('/[@$!%*#?&]/', $value);
    }

    public function message()
    {
        return 'Password must be at least 12 characters with uppercase, lowercase, number, and special character.';
    }
}

// Use in registration/password change
'password' => ['required', new StrongPassword, 'confirmed'],
```

### B. Two-Factor Authentication (2FA)
```bash
composer require pragmarx/google2fa-laravel
php artisan vendor:publish --provider="PragmaRx\Google2FALaravel\ServiceProvider"
```

```php
// app/Http/Controllers/Auth/TwoFactorController.php
<?php
namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use PragmaRx\Google2FA\Google2FA;

class TwoFactorController extends Controller
{
    public function enable(Request $request)
    {
        $user = $request->user();
        $google2fa = new Google2FA();
        
        $user->google2fa_secret = $google2fa->generateSecretKey();
        $user->save();

        $qrCodeUrl = $google2fa->getQRCodeUrl(
            config('app.name'),
            $user->email,
            $user->google2fa_secret
        );

        return view('auth.2fa-setup', ['qrCodeUrl' => $qrCodeUrl]);
    }

    public function verify(Request $request)
    {
        $request->validate(['one_time_password' => 'required']);
        
        $google2fa = new Google2FA();
        $valid = $google2fa->verifyKey(
            $request->user()->google2fa_secret,
            $request->one_time_password
        );

        if ($valid) {
            $request->session()->put('2fa_verified', true);
            return redirect()->intended('dashboard');
        }

        return back()->withErrors(['one_time_password' => 'Invalid code']);
    }
}
```

### C. Account Lockout After Failed Attempts
```php
// app/Http/Controllers/Auth/LoginController.php
use Illuminate\Support\Facades\RateLimiter;

protected function attemptLogin(Request $request)
{
    $throttleKey = strtolower($request->input('email')).'|'.$request->ip();
    
    // Lock account after 5 failed attempts for 15 minutes
    if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
        $seconds = RateLimiter::availableIn($throttleKey);
        
        // Log suspicious activity
        activity('security')
            ->withProperties([
                'email' => $request->input('email'),
                'ip' => $request->ip(),
                'attempts' => RateLimiter::attempts($throttleKey),
                'locked_until' => now()->addSeconds($seconds),
            ])
            ->log('Account lockout - too many failed login attempts');
        
        return $this->sendLockoutResponse($request, $seconds);
    }

    if ($this->guard()->attempt(
        $request->only('email', 'password'),
        $request->filled('remember')
    )) {
        RateLimiter::clear($throttleKey);
        return true;
    }

    RateLimiter::hit($throttleKey, 900); // 15 minutes
    return false;
}
```

---

## 🔐 2. SESSION & COOKIE SECURITY

### A. Secure Session Configuration
```php
// config/session.php
return [
    'driver' => 'database', // Store in database, not files
    'lifetime' => 120, // 2 hours
    'expire_on_close' => true,
    'encrypt' => true,
    'secure' => true, // HTTPS only
    'http_only' => true, // Prevent JavaScript access
    'same_site' => 'strict', // CSRF protection
    'domain' => env('SESSION_DOMAIN', null),
];
```

### B. Session Hijacking Prevention
```php
// app/Http/Middleware/ValidateSession.php
<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ValidateSession
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check()) {
            $user = auth()->user();
            
            // Check if IP changed (possible hijacking)
            if (session('user_ip') && session('user_ip') !== $request->ip()) {
                activity('security')
                    ->causedBy($user)
                    ->withProperties([
                        'old_ip' => session('user_ip'),
                        'new_ip' => $request->ip(),
                        'user_agent' => $request->userAgent(),
                    ])
                    ->log('Suspicious IP change detected - session terminated');
                
                auth()->logout();
                $request->session()->invalidate();
                return redirect()->route('login')->with('error', 'Session terminated for security reasons.');
            }
            
            // Store current IP
            session(['user_ip' => $request->ip()]);
            
            // Check if user agent changed
            if (session('user_agent') && session('user_agent') !== $request->userAgent()) {
                activity('security')
                    ->causedBy($user)
                    ->withProperties([
                        'old_agent' => session('user_agent'),
                        'new_agent' => $request->userAgent(),
                    ])
                    ->log('User agent change detected');
            }
            
            session(['user_agent' => $request->userAgent()]);
        }

        return $next($request);
    }
}

// Register in app/Http/Kernel.php
protected $middlewareGroups = [
    'web' => [
        // ... other middleware
        \App\Http\Middleware\ValidateSession::class,
    ],
];
```

---

## 🚫 3. SQL INJECTION PREVENTION

### A. Always Use Eloquent ORM or Query Builder
```php
// ❌ NEVER DO THIS (Vulnerable to SQL Injection)
$results = DB::select("SELECT * FROM users WHERE email = '".$request->email."'");

// ✅ ALWAYS DO THIS (Safe - Uses Parameter Binding)
$results = DB::table('users')
    ->where('email', $request->email)
    ->get();

// ✅ OR THIS (Eloquent)
$user = User::where('email', $request->email)->first();

// ✅ OR THIS (Raw with bindings)
$results = DB::select('SELECT * FROM users WHERE email = ?', [$request->email]);
```

### B. Validate All Input
```php
// app/Http/Controllers/Admin/ProductController.php
public function store(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255|regex:/^[a-zA-Z0-9\s\-]+$/',
        'quantity' => 'required|integer|min:0|max:999999',
        'price' => 'required|numeric|min:0|max:9999999.99',
        'category_id' => 'required|exists:categories,id',
        'description' => 'nullable|string|max:1000',
    ]);

    Product::create($validated);
}
```

---

## 🛑 4. XSS (CROSS-SITE SCRIPTING) PREVENTION

### A. Blade Automatic Escaping
```blade
{{-- ✅ SAFE - Auto-escaped --}}
<p>{{ $user->name }}</p>
<p>{{ $product->description }}</p>

{{-- ❌ DANGEROUS - Not escaped --}}
<p>{!! $user->name !!}</p>

{{-- Only use {!! !!} for trusted HTML from admin --}}
@role('Admin')
    <div>{!! $trustedAdminContent !!}</div>
@endrole
```

### B. Content Security Policy (CSP)
```php
// app/Http/Middleware/SetSecurityHeaders.php
<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SetSecurityHeaders
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        $response->headers->set('Content-Security-Policy', 
            "default-src 'self'; " .
            "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.jsdelivr.net; " .
            "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com; " .
            "font-src 'self' https://fonts.gstatic.com; " .
            "img-src 'self' data: https:; " .
            "connect-src 'self';"
        );

        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Permissions-Policy', 'geolocation=(), microphone=(), camera=()');

        return $response;
    }
}

// Register in app/Http/Kernel.php
protected $middleware = [
    // ... other middleware
    \App\Http\Middleware\SetSecurityHeaders::class,
];
```

---

## 🔄 5. CSRF PROTECTION

### A. Verify CSRF Tokens (Already Enabled in Laravel)
```blade
{{-- All forms must include CSRF token --}}
<form method="POST" action="{{ route('admin.products.store') }}">
    @csrf
    <!-- form fields -->
</form>

{{-- For AJAX requests --}}
<script>
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
</script>
```

### B. SameSite Cookie Attribute (Already configured above)

---

## 📁 6. FILE UPLOAD SECURITY

### A. Validate File Uploads Strictly
```php
// app/Http/Controllers/Admin/ProductController.php
public function uploadImage(Request $request)
{
    $request->validate([
        'image' => [
            'required',
            'file',
            'mimes:jpeg,png,jpg,webp', // Only images
            'max:2048', // Max 2MB
            'dimensions:min_width=100,min_height=100,max_width=4000,max_height=4000',
        ],
    ]);

    $file = $request->file('image');
    
    // Generate random filename (prevent path traversal)
    $filename = uniqid() . '_' . time() . '.' . $file->getClientOriginalExtension();
    
    // Store in private directory (not publicly accessible)
    $path = $file->storeAs('product-images', $filename, 'private');
    
    // Scan for malware (if available)
    // $this->scanFileForMalware(storage_path('app/' . $path));
    
    return $path;
}

// Never allow PHP, executable, or script files
private function isAllowedExtension($extension)
{
    $blocked = ['php', 'exe', 'sh', 'bat', 'js', 'html', 'svg'];
    return !in_array(strtolower($extension), $blocked);
}
```

### B. Store Files Outside Web Root
```php
// config/filesystems.php
'disks' => [
    'private' => [
        'driver' => 'local',
        'root' => storage_path('app/private'),
        'visibility' => 'private',
    ],
],

// Serve files through controller
public function downloadFile($id)
{
    $file = File::findOrFail($id);
    
    // Check permission
    if (!auth()->user()->can('download', $file)) {
        abort(403);
    }
    
    return Storage::disk('private')->download($file->path);
}
```

---

## 🔍 7. AUTHORIZATION & PERMISSIONS

### A. Use Gates and Policies
```php
// app/Policies/RequestPolicy.php
<?php
namespace App\Policies;

use App\Models\User;
use App\Models\Request;

class RequestPolicy
{
    public function view(User $user, Request $request)
    {
        // Admin can view all
        if ($user->hasRole('Admin')) {
            return true;
        }
        
        // User can view their own requests
        return $request->created_by === $user->id;
    }

    public function approve(User $user, Request $request)
    {
        // Only Catering Incharge can approve
        return $user->hasRole('Catering Incharge') 
            && $request->status === 'pending';
    }

    public function delete(User $user, Request $request)
    {
        // Only creator or admin can delete
        return $user->hasRole('Admin') 
            || $request->created_by === $user->id;
    }
}

// Use in controllers
public function show($id)
{
    $request = Request::findOrFail($id);
    $this->authorize('view', $request);
    
    return view('requests.show', compact('request'));
}
```

---

## 🚨 8. RATE LIMITING & BRUTE FORCE PROTECTION

### A. API Rate Limiting
```php
// app/Http/Kernel.php
protected $middlewareGroups = [
    'api' => [
        'throttle:60,1', // 60 requests per minute
    ],
];

// Custom rate limits for sensitive endpoints
Route::middleware(['throttle:5,1'])->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
});

// For admin actions
Route::middleware(['throttle:100,1', 'role:Admin'])->group(function () {
    Route::resource('users', UserController::class);
});
```

---

## 🔒 9. ENCRYPTION & DATA PROTECTION

### A. Encrypt Sensitive Data
```php
// app/Models/User.php
use Illuminate\Database\Eloquent\Casts\Attribute;

protected function creditCardNumber(): Attribute
{
    return Attribute::make(
        get: fn ($value) => decrypt($value),
        set: fn ($value) => encrypt($value),
    );
}

// Or use casts
protected $casts = [
    'sensitive_data' => 'encrypted',
];
```

### B. Secure Environment Variables
```bash
# .env - NEVER commit to Git
APP_KEY=base64:STRONG_RANDOM_KEY_HERE
DB_PASSWORD=complex_password_123!@#
JWT_SECRET=another_strong_secret

# Strong APP_KEY generation
php artisan key:generate --force
```

---

## 🔐 10. DATABASE SECURITY

### A. Use Prepared Statements (Already done with Eloquent)

### B. Database User Permissions
```sql
-- Create separate user with limited permissions
CREATE USER 'catering_app'@'localhost' IDENTIFIED BY 'strong_password_here';
GRANT SELECT, INSERT, UPDATE, DELETE ON catering_db.* TO 'catering_app'@'localhost';
FLUSH PRIVILEGES;

-- Remove DROP, CREATE, ALTER permissions in production
```

### C. Encrypted Database Connection
```php
// config/database.php
'mysql' => [
    'driver' => 'mysql',
    'host' => env('DB_HOST', '127.0.0.1'),
    'port' => env('DB_PORT', '3306'),
    'database' => env('DB_DATABASE', 'forge'),
    'username' => env('DB_USERNAME', 'forge'),
    'password' => env('DB_PASSWORD', ''),
    'options' => [
        PDO::MYSQL_ATTR_SSL_CA => env('DB_SSL_CA'),
        PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false,
    ],
],
```

---

## 🛡️ 11. LOGGING & MONITORING

### A. Log Security Events
```php
// app/Http/Middleware/LogSecurityEvents.php
<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class LogSecurityEvents
{
    public function handle(Request $request, Closure $next)
    {
        // Log sensitive actions
        if (in_array($request->method(), ['POST', 'PUT', 'DELETE', 'PATCH'])) {
            activity('security')
                ->causedBy(auth()->user())
                ->withProperties([
                    'method' => $request->method(),
                    'url' => $request->fullUrl(),
                    'ip' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'input' => $request->except(['password', 'password_confirmation']),
                ])
                ->log('Sensitive action performed');
        }

        return $next($request);
    }
}
```

### B. Monitor Failed Login Attempts
```php
// app/Listeners/LogFailedLogin.php
<?php
namespace App\Listeners;

use Illuminate\Auth\Events\Failed;

class LogFailedLogin
{
    public function handle(Failed $event)
    {
        activity('security')
            ->withProperties([
                'email' => $event->credentials['email'] ?? 'unknown',
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ])
            ->log('Failed login attempt');
    }
}

// Register in EventServiceProvider
protected $listen = [
    'Illuminate\Auth\Events\Failed' => [
        'App\Listeners\LogFailedLogin',
    ],
];
```

---

## 🔧 12. DEPENDENCY SECURITY

### A. Regular Updates
```bash
# Update all dependencies
composer update --with-all-dependencies

# Check for security vulnerabilities
composer audit

# Update npm packages
npm audit fix
```

### B. Remove Unused Packages
```bash
composer remove package/name
npm uninstall package-name
```

---

## 🌐 13. HTTPS & SSL/TLS

### A. Force HTTPS
```php
// app/Providers/AppServiceProvider.php
public function boot()
{
    if (config('app.env') === 'production') {
        URL::forceScheme('https');
    }
}

// Or in .htaccess
RewriteEngine On
RewriteCond %{HTTPS} off
RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
```

---

## 🔥 14. FIREWALL & SERVER SECURITY

### A. Configure Firewall Rules
```bash
# Allow only necessary ports
sudo ufw allow 80/tcp   # HTTP
sudo ufw allow 443/tcp  # HTTPS
sudo ufw allow 22/tcp   # SSH (from specific IP only)
sudo ufw enable
```

### B. Disable Directory Listing
```apache
# .htaccess
Options -Indexes

# Or in Apache config
<Directory /var/www/html>
    Options -Indexes
</Directory>
```

---

## 🎯 15. SECURITY CHECKLIST FOR PRODUCTION

### Before Deployment:
- [ ] Set `APP_DEBUG=false` in `.env`
- [ ] Set `APP_ENV=production` in `.env`
- [ ] Change all default passwords
- [ ] Enable HTTPS/SSL
- [ ] Configure firewall
- [ ] Set strong `APP_KEY`
- [ ] Remove development tools (`composer install --no-dev`)
- [ ] Clear config cache (`php artisan config:cache`)
- [ ] Clear route cache (`php artisan route:cache`)
- [ ] Clear view cache (`php artisan view:cache`)
- [ ] Set proper file permissions (755 for directories, 644 for files)
- [ ] Restrict database user permissions
- [ ] Enable 2FA for admin accounts
- [ ] Set up automated backups
- [ ] Configure rate limiting
- [ ] Add security headers middleware
- [ ] Test all authentication flows
- [ ] Review all file upload endpoints
- [ ] Verify CSRF protection on all forms
- [ ] Check for SQL injection vulnerabilities
- [ ] Test XSS protection
- [ ] Enable activity logging
- [ ] Set up monitoring and alerts
- [ ] Document security procedures
- [ ] Train staff on security best practices

---

## 🚀 QUICK IMPLEMENTATION PRIORITY

### **CRITICAL (Do Immediately):**
1. Strong password policy
2. Account lockout after failed attempts
3. HTTPS enforcement
4. Security headers middleware
5. Session security configuration
6. File upload validation
7. Rate limiting on login

### **HIGH (Do This Week):**
1. Two-factor authentication
2. Session hijacking prevention
3. Authorization policies
4. Security event logging
5. Database encryption
6. Content Security Policy

### **MEDIUM (Do This Month):**
1. IP whitelisting for admin
2. Automated security audits
3. Penetration testing
4. Security monitoring dashboard
5. Intrusion detection system

---

## 📚 Additional Resources
- [OWASP Top 10](https://owasp.org/www-project-top-ten/)
- [Laravel Security Best Practices](https://laravel.com/docs/security)
- [PHP Security Cheat Sheet](https://cheatsheetseries.owasp.org/cheatsheets/PHP_Configuration_Cheat_Sheet.html)

**Remember**: Security is a continuous process, not a one-time task!
