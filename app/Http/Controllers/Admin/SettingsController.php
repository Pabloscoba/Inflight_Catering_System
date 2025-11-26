<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class SettingsController extends Controller
{
    public function general()
    {
        // Get settings from .env file
        $settings = [
            'app_name' => env('APP_NAME', 'Inflight Catering System'),
            'organization_name' => env('ORGANIZATION_NAME', 'Your Organization'),
            'app_url' => env('APP_URL', 'http://localhost'),
            'app_timezone' => env('APP_TIMEZONE', 'UTC'),
            'contact_email' => env('CONTACT_EMAIL', 'info@example.com'),
            'contact_phone' => env('CONTACT_PHONE', '+000 000 000 000'),
            'address' => env('ADDRESS', 'Organization Address'),
            'currency' => env('CURRENCY', 'USD'),
            'currency_symbol' => env('CURRENCY_SYMBOL', '$'),
            'date_format' => env('DATE_FORMAT', 'Y-m-d'),
            'records_per_page' => env('RECORDS_PER_PAGE', '50'),
            'mail_from_address' => env('MAIL_FROM_ADDRESS', 'noreply@example.com'),
            'mail_from_name' => env('MAIL_FROM_NAME', 'Inflight Catering System'),
            'maintenance_mode' => env('MAINTENANCE_MODE', false),
        ];

        return view('admin.settings.general', compact('settings'));
    }

    public function updateGeneral(Request $request)
    {
        $request->validate([
            'app_name' => 'required|string|max:255',
            'organization_name' => 'required|string|max:255',
            'app_url' => 'required|url',
            'app_timezone' => 'required|string',
            'contact_email' => 'required|email',
            'contact_phone' => 'required|string|max:50',
            'address' => 'required|string|max:500',
            'currency' => 'required|string|max:10',
            'currency_symbol' => 'required|string|max:5',
            'date_format' => 'required|string',
            'records_per_page' => 'required|integer|min:10|max:200',
            'mail_from_address' => 'required|email',
            'mail_from_name' => 'required|string|max:255',
        ]);

        // Update .env file
        $this->updateEnv([
            'APP_NAME' => $request->app_name,
            'ORGANIZATION_NAME' => $request->organization_name,
            'APP_URL' => $request->app_url,
            'APP_TIMEZONE' => $request->app_timezone,
            'CONTACT_EMAIL' => $request->contact_email,
            'CONTACT_PHONE' => $request->contact_phone,
            'ADDRESS' => $request->address,
            'CURRENCY' => $request->currency,
            'CURRENCY_SYMBOL' => $request->currency_symbol,
            'DATE_FORMAT' => $request->date_format,
            'RECORDS_PER_PAGE' => $request->records_per_page,
            'MAIL_FROM_ADDRESS' => $request->mail_from_address,
            'MAIL_FROM_NAME' => $request->mail_from_name,
            'MAINTENANCE_MODE' => $request->has('maintenance_mode') ? 'true' : 'false',
        ]);

        // Clear config cache
        Artisan::call('config:clear');

        activity()
            ->causedBy(auth()->user())
            ->log('Updated general system settings');

        return redirect()->back()->with('success', 'Settings updated successfully!');
    }

    private function updateEnv(array $data)
    {
        $envFile = base_path('.env');
        $env = file_get_contents($envFile);

        foreach ($data as $key => $value) {
            // Escape special characters and wrap in quotes
            $value = str_replace('"', '\\"', $value);
            $value = "\"$value\"";

            // Replace or add the key
            if (preg_match("/^{$key}=.*/m", $env)) {
                $env = preg_replace("/^{$key}=.*/m", "{$key}={$value}", $env);
            } else {
                $env .= "\n{$key}={$value}";
            }
        }

        file_put_contents($envFile, $env);
    }
}
