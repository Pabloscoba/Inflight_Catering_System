@extends('layouts.app')

@section('page-title', 'General Settings')

@section('content')
<div style="padding: 32px;">
    <!-- Page Header -->
    <div style="margin-bottom: 32px;">
        <h1 style="font-size: 28px; font-weight: 700; color: #1a202c; margin-bottom: 8px;">General Settings</h1>
        <p style="color: #718096;">Configure general system settings</p>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
    <div style="background: #10b981; color: white; padding: 16px; border-radius: 8px; margin-bottom: 24px;">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div style="background: #ef4444; color: white; padding: 16px; border-radius: 8px; margin-bottom: 24px;">
        {{ session('error') }}
    </div>
    @endif

    <!-- Settings Form -->
    <div class="card" style="max-width: 1000px;">
        <form action="{{ route('admin.settings.update-general') }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Application Information -->
            <div style="margin-bottom: 32px;">
                <h2 style="font-size: 18px; font-weight: 600; color: #1f2937; margin-bottom: 16px; padding-bottom: 8px; border-bottom: 2px solid #e5e7eb;">
                    Application Information
                </h2>
                <div style="display: grid; gap: 20px;">
                    <!-- Application Name -->
                    <div>
                        <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 8px;">
                            Application Name <span style="color: #ef4444;">*</span>
                        </label>
                        <input 
                            type="text" 
                            name="app_name" 
                            value="{{ old('app_name', $settings['app_name']) }}"
                            required
                            placeholder="e.g., Inflight Catering System"
                            style="width: 100%; padding: 12px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px;"
                        >
                        <p style="color: #6b7280; font-size: 12px; margin-top: 4px;">System name shown in browser tabs and emails</p>
                        @error('app_name')
                        <p style="color: #ef4444; font-size: 12px; margin-top: 4px;">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Organization Name -->
                    <div>
                        <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 8px;">
                            Organization Name <span style="color: #ef4444;">*</span>
                        </label>
                        <input 
                            type="text" 
                            name="organization_name" 
                            value="{{ old('organization_name', $settings['organization_name']) }}"
                            required
                            placeholder="e.g., Tanzania Airports Authority"
                            style="width: 100%; padding: 12px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px;"
                        >
                        <p style="color: #6b7280; font-size: 12px; margin-top: 4px;">Your company or organization name</p>
                        @error('organization_name')
                        <p style="color: #ef4444; font-size: 12px; margin-top: 4px;">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- System URL -->
                    <div>
                        <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 8px;">
                            System URL <span style="color: #ef4444;">*</span>
                        </label>
                        <input 
                            type="url" 
                            name="app_url" 
                            value="{{ old('app_url', $settings['app_url']) }}"
                            required
                            placeholder="https://catering.example.com"
                            style="width: 100%; padding: 12px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px;"
                        >
                        <p style="color: #6b7280; font-size: 12px; margin-top: 4px;">Full URL where system is hosted</p>
                        @error('app_url')
                        <p style="color: #ef4444; font-size: 12px; margin-top: 4px;">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Timezone -->
                    <div>
                        <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 8px;">
                            Timezone <span style="color: #ef4444;">*</span>
                        </label>
                        <select 
                            name="app_timezone" 
                            required
                            style="width: 100%; padding: 12px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px;"
                        >
                            <option value="UTC" {{ old('app_timezone', $settings['app_timezone']) == 'UTC' ? 'selected' : '' }}>UTC (Universal Time)</option>
                            <option value="Africa/Nairobi" {{ old('app_timezone', $settings['app_timezone']) == 'Africa/Nairobi' ? 'selected' : '' }}>Africa/Nairobi (EAT - Kenya, Tanzania)</option>
                            <option value="Africa/Lagos" {{ old('app_timezone', $settings['app_timezone']) == 'Africa/Lagos' ? 'selected' : '' }}>Africa/Lagos (WAT - Nigeria)</option>
                            <option value="Africa/Cairo" {{ old('app_timezone', $settings['app_timezone']) == 'Africa/Cairo' ? 'selected' : '' }}>Africa/Cairo (EET - Egypt)</option>
                            <option value="Africa/Johannesburg" {{ old('app_timezone', $settings['app_timezone']) == 'Africa/Johannesburg' ? 'selected' : '' }}>Africa/Johannesburg (SAST - South Africa)</option>
                            <option value="America/New_York" {{ old('app_timezone', $settings['app_timezone']) == 'America/New_York' ? 'selected' : '' }}>America/New York (EST)</option>
                            <option value="Europe/London" {{ old('app_timezone', $settings['app_timezone']) == 'Europe/London' ? 'selected' : '' }}>Europe/London (GMT)</option>
                            <option value="Asia/Dubai" {{ old('app_timezone', $settings['app_timezone']) == 'Asia/Dubai' ? 'selected' : '' }}>Asia/Dubai (GST)</option>
                        </select>
                        <p style="color: #6b7280; font-size: 12px; margin-top: 4px;">Timezone for all timestamps and schedules</p>
                        @error('app_timezone')
                        <p style="color: #ef4444; font-size: 12px; margin-top: 4px;">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Contact Information -->
            <div style="margin-bottom: 32px;">
                <h2 style="font-size: 18px; font-weight: 600; color: #1f2937; margin-bottom: 16px; padding-bottom: 8px; border-bottom: 2px solid #e5e7eb;">
                    Contact Information
                </h2>
                <div style="display: grid; gap: 20px;">
                    <!-- Contact Email -->
                    <div>
                        <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 8px;">
                            Contact Email <span style="color: #ef4444;">*</span>
                        </label>
                        <input 
                            type="email" 
                            name="contact_email" 
                            value="{{ old('contact_email', $settings['contact_email']) }}"
                            required
                            placeholder="info@example.com"
                            style="width: 100%; padding: 12px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px;"
                        >
                        <p style="color: #6b7280; font-size: 12px; margin-top: 4px;">Primary contact email for support inquiries</p>
                        @error('contact_email')
                        <p style="color: #ef4444; font-size: 12px; margin-top: 4px;">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Contact Phone -->
                    <div>
                        <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 8px;">
                            Contact Phone <span style="color: #ef4444;">*</span>
                        </label>
                        <input 
                            type="text" 
                            name="contact_phone" 
                            value="{{ old('contact_phone', $settings['contact_phone']) }}"
                            required
                            placeholder="+255 123 456 789"
                            style="width: 100%; padding: 12px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px;"
                        >
                        <p style="color: #6b7280; font-size: 12px; margin-top: 4px;">Organization phone number with country code</p>
                        @error('contact_phone')
                        <p style="color: #ef4444; font-size: 12px; margin-top: 4px;">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Address -->
                    <div>
                        <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 8px;">
                            Address <span style="color: #ef4444;">*</span>
                        </label>
                        <textarea 
                            name="address" 
                            required
                            rows="3"
                            placeholder="Organization physical address"
                            style="width: 100%; padding: 12px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px; resize: vertical;"
                        >{{ old('address', $settings['address']) }}</textarea>
                        <p style="color: #6b7280; font-size: 12px; margin-top: 4px;">Physical office or organization address</p>
                        @error('address')
                        <p style="color: #ef4444; font-size: 12px; margin-top: 4px;">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Regional & Display Settings -->
            <div style="margin-bottom: 32px;">
                <h2 style="font-size: 18px; font-weight: 600; color: #1f2937; margin-bottom: 16px; padding-bottom: 8px; border-bottom: 2px solid #e5e7eb;">
                    Regional & Display Settings
                </h2>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <!-- Currency -->
                    <div>
                        <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 8px;">
                            Currency Code <span style="color: #ef4444;">*</span>
                        </label>
                        <select 
                            name="currency" 
                            required
                            style="width: 100%; padding: 12px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px;"
                        >
                            <option value="TZS" {{ old('currency', $settings['currency']) == 'TZS' ? 'selected' : '' }}>TZS - Tanzanian Shilling</option>
                            <option value="KES" {{ old('currency', $settings['currency']) == 'KES' ? 'selected' : '' }}>KES - Kenyan Shilling</option>
                            <option value="NGN" {{ old('currency', $settings['currency']) == 'NGN' ? 'selected' : '' }}>NGN - Nigerian Naira</option>
                            <option value="ZAR" {{ old('currency', $settings['currency']) == 'ZAR' ? 'selected' : '' }}>ZAR - South African Rand</option>
                            <option value="USD" {{ old('currency', $settings['currency']) == 'USD' ? 'selected' : '' }}>USD - US Dollar</option>
                            <option value="EUR" {{ old('currency', $settings['currency']) == 'EUR' ? 'selected' : '' }}>EUR - Euro</option>
                            <option value="GBP" {{ old('currency', $settings['currency']) == 'GBP' ? 'selected' : '' }}>GBP - British Pound</option>
                        </select>
                        @error('currency')
                        <p style="color: #ef4444; font-size: 12px; margin-top: 4px;">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Currency Symbol -->
                    <div>
                        <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 8px;">
                            Currency Symbol <span style="color: #ef4444;">*</span>
                        </label>
                        <input 
                            type="text" 
                            name="currency_symbol" 
                            value="{{ old('currency_symbol', $settings['currency_symbol']) }}"
                            required
                            placeholder="TZS, $, €, £"
                            maxlength="5"
                            style="width: 100%; padding: 12px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px;"
                        >
                        @error('currency_symbol')
                        <p style="color: #ef4444; font-size: 12px; margin-top: 4px;">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Date Format -->
                    <div>
                        <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 8px;">
                            Date Format <span style="color: #ef4444;">*</span>
                        </label>
                        <select 
                            name="date_format" 
                            required
                            style="width: 100%; padding: 12px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px;"
                        >
                            <option value="Y-m-d" {{ old('date_format', $settings['date_format']) == 'Y-m-d' ? 'selected' : '' }}>YYYY-MM-DD (2025-11-19)</option>
                            <option value="d/m/Y" {{ old('date_format', $settings['date_format']) == 'd/m/Y' ? 'selected' : '' }}>DD/MM/YYYY (19/11/2025)</option>
                            <option value="m/d/Y" {{ old('date_format', $settings['date_format']) == 'm/d/Y' ? 'selected' : '' }}>MM/DD/YYYY (11/19/2025)</option>
                            <option value="d-M-Y" {{ old('date_format', $settings['date_format']) == 'd-M-Y' ? 'selected' : '' }}>DD-Mon-YYYY (19-Nov-2025)</option>
                        </select>
                        @error('date_format')
                        <p style="color: #ef4444; font-size: 12px; margin-top: 4px;">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Records Per Page -->
                    <div>
                        <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 8px;">
                            Records Per Page <span style="color: #ef4444;">*</span>
                        </label>
                        <select 
                            name="records_per_page" 
                            required
                            style="width: 100%; padding: 12px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px;"
                        >
                            <option value="10" {{ old('records_per_page', $settings['records_per_page']) == '10' ? 'selected' : '' }}>10</option>
                            <option value="25" {{ old('records_per_page', $settings['records_per_page']) == '25' ? 'selected' : '' }}>25</option>
                            <option value="50" {{ old('records_per_page', $settings['records_per_page']) == '50' ? 'selected' : '' }}>50</option>
                            <option value="100" {{ old('records_per_page', $settings['records_per_page']) == '100' ? 'selected' : '' }}>100</option>
                        </select>
                        <p style="color: #6b7280; font-size: 12px; margin-top: 4px;">Number of items per page in tables</p>
                        @error('records_per_page')
                        <p style="color: #ef4444; font-size: 12px; margin-top: 4px;">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Email Configuration -->
            <div style="margin-bottom: 32px;">
                <h2 style="font-size: 18px; font-weight: 600; color: #1f2937; margin-bottom: 16px; padding-bottom: 8px; border-bottom: 2px solid #e5e7eb;">
                    Email Configuration
                </h2>
                <div style="display: grid; gap: 20px;">
                    <!-- Mail From Address -->
                    <div>
                        <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 8px;">
                            Mail From Address <span style="color: #ef4444;">*</span>
                        </label>
                        <input 
                            type="email" 
                            name="mail_from_address" 
                            value="{{ old('mail_from_address', $settings['mail_from_address']) }}"
                            required
                            placeholder="noreply@example.com"
                            style="width: 100%; padding: 12px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px;"
                        >
                        <p style="color: #6b7280; font-size: 12px; margin-top: 4px;">Email address used to send system notifications</p>
                        @error('mail_from_address')
                        <p style="color: #ef4444; font-size: 12px; margin-top: 4px;">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Mail From Name -->
                    <div>
                        <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 8px;">
                            Mail From Name <span style="color: #ef4444;">*</span>
                        </label>
                        <input 
                            type="text" 
                            name="mail_from_name" 
                            value="{{ old('mail_from_name', $settings['mail_from_name']) }}"
                            required
                            placeholder="Inflight Catering System"
                            style="width: 100%; padding: 12px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px;"
                        >
                        <p style="color: #6b7280; font-size: 12px; margin-top: 4px;">Display name for outgoing emails</p>
                        @error('mail_from_name')
                        <p style="color: #ef4444; font-size: 12px; margin-top: 4px;">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- System Maintenance -->
            <div style="margin-bottom: 32px;">
                <h2 style="font-size: 18px; font-weight: 600; color: #1f2937; margin-bottom: 16px; padding-bottom: 8px; border-bottom: 2px solid #e5e7eb;">
                    System Maintenance
                </h2>
                <div style="background: #fef3c7; border-left: 4px solid #f59e0b; padding: 16px; border-radius: 8px;">
                    <label style="display: flex; align-items: center; gap: 12px; cursor: pointer;">
                        <input 
                            type="checkbox" 
                            name="maintenance_mode" 
                            value="1"
                            {{ old('maintenance_mode', $settings['maintenance_mode']) ? 'checked' : '' }}
                            style="width: 20px; height: 20px; cursor: pointer;"
                        >
                        <div>
                            <span style="display: block; font-weight: 600; color: #92400e; font-size: 14px;">Enable Maintenance Mode</span>
                            <span style="display: block; color: #92400e; font-size: 12px; margin-top: 4px;">When enabled, only administrators can access the system. All other users will see a maintenance message.</span>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Action Buttons -->
            <div style="display: flex; gap: 12px; margin-top: 32px; padding-top: 24px; border-top: 1px solid #e5e7eb;">
                <button 
                    type="submit"
                    style="background: #0b1a68; color: white; padding: 12px 24px; border-radius: 6px; font-weight: 600; border: none; cursor: pointer;"
                >
                    Save Changes
                </button>
                <a 
                    href="{{ route('admin.dashboard') }}"
                    style="background: #f3f4f6; color: #374151; padding: 12px 24px; border-radius: 6px; font-weight: 600; text-decoration: none; display: inline-block;"
                >
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
