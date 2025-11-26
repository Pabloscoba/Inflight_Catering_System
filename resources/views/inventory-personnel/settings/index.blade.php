@extends('layouts.app')

@section('page-title', 'Settings')
@section('page-description', 'Manage your account settings and preferences')

@section('content')
<style>
    .settings-container { max-width: 1000px; margin: 0 auto; padding: 24px; }
    .settings-card { background: white; border-radius: 12px; padding: 32px; margin-bottom: 24px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); }
    .section-title { font-size: 20px; font-weight: 600; color: #1f2937; margin-bottom: 20px; padding-bottom: 12px; border-bottom: 2px solid #e5e7eb; }
    .form-group { margin-bottom: 24px; }
    .form-group label { display: block; font-weight: 600; color: #374151; margin-bottom: 8px; font-size: 14px; }
    .form-group input, .form-group select { width: 100%; padding: 12px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px; }
    .form-group input:focus, .form-group select:focus { outline: none; border-color: #0b1a68; box-shadow: 0 0 0 3px rgba(11,26,104,0.1); }
    .btn { padding: 12px 24px; border-radius: 8px; font-weight: 600; cursor: pointer; border: none; transition: all 0.2s; }
    .btn-primary { background: #0b1a68; color: white; }
    .btn-primary:hover { background: #091352; }
    .btn-secondary { background: #f3f4f6; color: #374151; }
    .btn-danger { background: #dc2626; color: white; }
    .btn-danger:hover { background: #b91c1c; }
    .alert { padding: 16px; border-radius: 8px; margin-bottom: 24px; }
    .alert-success { background: #d1fae5; border: 1px solid #10b981; color: #065f46; }
    .alert-error { background: #fee2e2; border: 1px solid #ef4444; color: #991b1b; }
    .checkbox-group { display: flex; align-items: center; gap: 12px; }
    .checkbox-group input[type="checkbox"] { width: 20px; height: 20px; cursor: pointer; }
    .info-text { font-size: 13px; color: #6b7280; margin-top: 6px; }
</style>

<div class="settings-container">
    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    @if($errors->any())
    <div class="alert alert-error">
        <ul style="margin: 0; padding-left: 20px;">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- Profile Information -->
    <div class="settings-card">
        <h2 class="section-title">Profile Information</h2>
        <form action="{{ route(auth()->user()->hasRole('Catering Staff') ? 'catering-staff.settings.update-profile' : (auth()->user()->hasRole('Inventory Personnel') ? 'inventory-personnel.settings.update-profile' : (auth()->user()->hasRole('Inventory Supervisor') ? 'inventory-supervisor.settings.update-profile' : (auth()->user()->hasRole('Security Staff') ? 'security-staff.settings.update-profile' : (auth()->user()->hasRole('Catering Incharge') ? 'catering-incharge.settings.update-profile' : (auth()->user()->hasRole('Ramp Dispatcher') ? 'ramp-dispatcher.settings.update-profile' : (auth()->user()->hasRole('Flight Purser') ? 'flight-purser.settings.update-profile' : 'cabin-crew.settings.update-profile'))))))) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="form-group">
                <label for="name">Full Name</label>
                <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required>
            </div>

            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required>
            </div>

            <div class="form-group">
                <label for="phone">Phone Number</label>
                <input type="text" id="phone" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="+255 XXX XXX XXX">
            </div>

            <div style="display: flex; gap: 12px;">
                <button type="submit" class="btn btn-primary">Update Profile</button>
            </div>
        </form>
    </div>

    <!-- Change Password -->
    <div class="settings-card">
        <h2 class="section-title">Change Password</h2>
        <form action="{{ route(auth()->user()->hasRole('Catering Staff') ? 'catering-staff.settings.update-password' : (auth()->user()->hasRole('Inventory Personnel') ? 'inventory-personnel.settings.update-password' : (auth()->user()->hasRole('Inventory Supervisor') ? 'inventory-supervisor.settings.update-password' : (auth()->user()->hasRole('Security Staff') ? 'security-staff.settings.update-password' : (auth()->user()->hasRole('Catering Incharge') ? 'catering-incharge.settings.update-password' : (auth()->user()->hasRole('Ramp Dispatcher') ? 'ramp-dispatcher.settings.update-password' : (auth()->user()->hasRole('Flight Purser') ? 'flight-purser.settings.update-password' : 'cabin-crew.settings.update-password'))))))) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="form-group">
                <label for="current_password">Current Password</label>
                <input type="password" id="current_password" name="current_password" required>
            </div>

            <div class="form-group">
                <label for="new_password">New Password</label>
                <input type="password" id="new_password" name="new_password" required>
                <p class="info-text">Password must be at least 8 characters long</p>
            </div>

            <div class="form-group">
                <label for="new_password_confirmation">Confirm New Password</label>
                <input type="password" id="new_password_confirmation" name="new_password_confirmation" required>
            </div>

            <div style="display: flex; gap: 12px;">
                <button type="submit" class="btn btn-danger">Change Password</button>
            </div>
        </form>
    </div>

    <!-- Preferences -->
    <div class="settings-card">
        <h2 class="section-title">Preferences</h2>
        <form action="{{ route(auth()->user()->hasRole('Catering Staff') ? 'catering-staff.settings.update-preferences' : (auth()->user()->hasRole('Inventory Personnel') ? 'inventory-personnel.settings.update-preferences' : (auth()->user()->hasRole('Inventory Supervisor') ? 'inventory-supervisor.settings.update-preferences' : (auth()->user()->hasRole('Security Staff') ? 'security-staff.settings.update-preferences' : (auth()->user()->hasRole('Catering Incharge') ? 'catering-incharge.settings.update-preferences' : (auth()->user()->hasRole('Ramp Dispatcher') ? 'ramp-dispatcher.settings.update-preferences' : (auth()->user()->hasRole('Flight Purser') ? 'flight-purser.settings.update-preferences' : 'cabin-crew.settings.update-preferences'))))))) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="form-group">
                <div class="checkbox-group">
                    <input type="checkbox" id="email_notifications" name="email_notifications" value="1" {{ ($user->preferences['email_notifications'] ?? false) ? 'checked' : '' }}>
                    <label for="email_notifications" style="margin: 0; font-weight: normal;">Enable email notifications</label>
                </div>
                <p class="info-text">Receive email notifications for important updates</p>
            </div>

            <div class="form-group">
                <label for="display_mode">Display Mode</label>
                <select id="display_mode" name="display_mode">
                    <option value="light" {{ ($user->preferences['display_mode'] ?? 'light') == 'light' ? 'selected' : '' }}>Light</option>
                    <option value="dark" {{ ($user->preferences['display_mode'] ?? 'light') == 'dark' ? 'selected' : '' }}>Dark</option>
                    <option value="auto" {{ ($user->preferences['display_mode'] ?? 'light') == 'auto' ? 'selected' : '' }}>Auto (System)</option>
                </select>
            </div>

            <div class="form-group">
                <label for="items_per_page">Items Per Page</label>
                <select id="items_per_page" name="items_per_page">
                    <option value="10" {{ ($user->preferences['items_per_page'] ?? 25) == 10 ? 'selected' : '' }}>10</option>
                    <option value="25" {{ ($user->preferences['items_per_page'] ?? 25) == 25 ? 'selected' : '' }}>25</option>
                    <option value="50" {{ ($user->preferences['items_per_page'] ?? 25) == 50 ? 'selected' : '' }}>50</option>
                    <option value="100" {{ ($user->preferences['items_per_page'] ?? 25) == 100 ? 'selected' : '' }}>100</option>
                </select>
                <p class="info-text">Number of items to display per page in tables</p>
            </div>

            <div style="display: flex; gap: 12px;">
                <button type="submit" class="btn btn-primary">Save Preferences</button>
            </div>
        </form>
    </div>

    <!-- Account Information -->
    <div class="settings-card">
        <h2 class="section-title">Account Information</h2>
        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px;">
            <div>
                <p style="font-weight: 600; color: #6b7280; font-size: 13px; margin-bottom: 4px;">Role</p>
                <p style="font-size: 16px; color: #1f2937;">{{ $user->roles->first()->name ?? 'No Role' }}</p>
            </div>
            <div>
                <p style="font-weight: 600; color: #6b7280; font-size: 13px; margin-bottom: 4px;">Account Created</p>
                <p style="font-size: 16px; color: #1f2937;">{{ $user->created_at->format('M d, Y') }}</p>
            </div>
            <div>
                <p style="font-weight: 600; color: #6b7280; font-size: 13px; margin-bottom: 4px;">Last Updated</p>
                <p style="font-size: 16px; color: #1f2937;">{{ $user->updated_at->format('M d, Y') }}</p>
            </div>
            <div>
                <p style="font-weight: 600; color: #6b7280; font-size: 13px; margin-bottom: 4px;">User ID</p>
                <p style="font-size: 16px; color: #1f2937;">#{{ $user->id }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
