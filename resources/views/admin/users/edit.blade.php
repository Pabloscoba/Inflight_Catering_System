@extends('layouts.app')

@section('title', 'Edit User - {{ $user->name }}')

@section('content')
<style>
    body { background: #f5f5f5; }
        
        .container { max-width: 700px; margin: 0 auto; padding: 40px 20px; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        .header h1 { font-size: 28px; font-weight: 700; color: #000; }
        .user-badge { display: inline-block; padding: 6px 14px; background: #e3f2fd; color: #0066cc; border-radius: 16px; font-size: 13px; font-weight: 500; margin-left: 12px; }
        .btn-back { padding: 10px 20px; background: #6c757d; color: #fff; text-decoration: none; border-radius: 6px; font-size: 14px; }
        .btn-back:hover { background: #5a6268; }
        
        .card { background: #fff; border: 1px solid #ddd; border-radius: 8px; padding: 30px; margin-bottom: 20px; }
        
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; font-size: 14px; font-weight: 600; color: #495057; margin-bottom: 8px; }
        .form-group label .required { color: #dc3545; }
        .form-group input, .form-group select { width: 100%; padding: 12px 16px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px; }
        .form-group input:focus, .form-group select:focus { outline: none; border-color: #0066cc; }
        .form-group .help-text { font-size: 12px; color: #6c757d; margin-top: 6px; }
        .form-group .error { color: #dc3545; font-size: 13px; margin-top: 6px; }
        
        .password-section { background: #fff3cd; border: 1px solid #ffc107; border-radius: 6px; padding: 16px; margin-bottom: 20px; }
        .password-section h3 { font-size: 15px; font-weight: 600; color: #856404; margin-bottom: 8px; }
        .password-section p { font-size: 13px; color: #856404; margin-bottom: 12px; }
        
        .form-actions { display: flex; gap: 12px; justify-content: space-between; margin-top: 30px; padding-top: 20px; border-top: 1px solid #e9ecef; }
        .form-actions-right { display: flex; gap: 12px; }
        .btn { padding: 12px 24px; border-radius: 6px; font-size: 14px; font-weight: 500; border: none; cursor: pointer; text-decoration: none; display: inline-block; }
        .btn-primary { background: #28a745; color: #fff; }
        .btn-primary:hover { background: #218838; }
        .btn-secondary { background: #fff; color: #6c757d; border: 1px solid #ddd; }
        .btn-secondary:hover { background: #f8f9fa; }
        .btn-danger { background: #dc3545; color: #fff; }
        .btn-danger:hover { background: #c82333; }
        
        .role-info { padding: 14px; background: #f8f9fa; border: 1px solid #e9ecef; border-radius: 6px; margin-top: 10px; }
        .role-info h4 { font-size: 13px; font-weight: 600; color: #495057; margin-bottom: 8px; }
        .role-info p { font-size: 12px; color: #6c757d; margin-bottom: 4px; }
        
        .info-box { padding: 14px; background: #d1ecf1; border: 1px solid #bee5eb; border-radius: 6px; margin-bottom: 20px; }
        .info-box p { font-size: 13px; color: #0c5460; margin: 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div>
                <h1>Edit User</h1>
                <span class="user-badge">{{ $user->email }}</span>
            </div>
            <a href="{{ route('admin.users.index') }}" class="btn-back">← Back to Users</a>
        </div>
        
        <div class="info-box">
            <p><strong>User ID:</strong> {{ $user->id }} | <strong>Created:</strong> {{ $user->created_at->format('M d, Y H:i') }} | <strong>Last Updated:</strong> {{ $user->updated_at->format('M d, Y H:i') }}</p>
        </div>
        
        <div class="card">
            <form method="POST" action="{{ route('admin.users.update', $user) }}">
                @csrf
                @method('PUT')
                
                <div class="form-group">
                    <label>Full Name <span class="required">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required autofocus>
                    @error('name')
                    <div class="error">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label>Email Address <span class="required">*</span></label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required>
                    @error('email')
                    <div class="error">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="password-section">
                    <h3>Change Password</h3>
                    <p>Leave blank to keep the current password</p>
                    
                    <div class="form-group" style="margin-bottom: 12px;">
                        <label>New Password</label>
                        <input type="password" name="password">
                        <div class="help-text">Minimum 8 characters</div>
                        @error('password')
                        <div class="error">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group" style="margin-bottom: 0;">
                        <label>Confirm New Password</label>
                        <input type="password" name="password_confirmation">
                    </div>
                </div>
                
                <div class="form-group">
                    <label>Assign Role <span class="required">*</span></label>
                    <select name="role_id" id="roleSelect" required onchange="showRoleInfo()">
                        <option value="">-- Select Role --</option>
                        @foreach($roles as $role)
                        <option value="{{ $role->id }}" 
                            data-permissions="{{ $role->permissions->count() }}" 
                            {{ old('role_id', $userRole?->id) == $role->id ? 'selected' : '' }}>
                            {{ $role->name }}
                        </option>
                        @endforeach
                    </select>
                    @error('role_id')
                    <div class="error">{{ $message }}</div>
                    @enderror
                    
                    <div class="role-info" id="roleInfo" style="display: none;">
                        <h4>Role Information</h4>
                        <p id="rolePermissions"></p>
                    </div>
                </div>
                
                <div class="form-actions">
                    @if($user->id !== auth()->id())
                    <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Are you sure you want to delete this user? This action cannot be undone.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete User</button>
                    </form>
                    @else
                    <div></div>
                    @endif
                    
                    <div class="form-actions-right">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">Update User</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    <script>
        function showRoleInfo() {
            const select = document.getElementById('roleSelect');
            const roleInfo = document.getElementById('roleInfo');
            const rolePermissions = document.getElementById('rolePermissions');
            
            if (select.value) {
                const selectedOption = select.options[select.selectedIndex];
                const permissionsCount = selectedOption.getAttribute('data-permissions');
                const roleName = selectedOption.text;
                
                rolePermissions.textContent = `${roleName} role has ${permissionsCount} permissions assigned.`;
                roleInfo.style.display = 'block';
            } else {
                roleInfo.style.display = 'none';
            }
        }
        
        // Show role info on page load
        window.addEventListener('load', showRoleInfo);
    </script>
@endsection