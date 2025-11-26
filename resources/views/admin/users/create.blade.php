<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create New User</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; background: #f5f5f5; }
        
        .container { max-width: 700px; margin: 0 auto; padding: 40px 20px; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        .header h1 { font-size: 28px; font-weight: 700; color: #000; }
        .btn-back { padding: 10px 20px; background: #6c757d; color: #fff; text-decoration: none; border-radius: 6px; font-size: 14px; }
        .btn-back:hover { background: #5a6268; }
        
        .card { background: #fff; border: 1px solid #ddd; border-radius: 8px; padding: 30px; }
        
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; font-size: 14px; font-weight: 600; color: #495057; margin-bottom: 8px; }
        .form-group label .required { color: #dc3545; }
        .form-group input, .form-group select { width: 100%; padding: 12px 16px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px; }
        .form-group input:focus, .form-group select:focus { outline: none; border-color: #0066cc; }
        .form-group .help-text { font-size: 12px; color: #6c757d; margin-top: 6px; }
        .form-group .error { color: #dc3545; font-size: 13px; margin-top: 6px; }
        
        .form-actions { display: flex; gap: 12px; justify-content: flex-end; margin-top: 30px; padding-top: 20px; border-top: 1px solid #e9ecef; }
        .btn { padding: 12px 24px; border-radius: 6px; font-size: 14px; font-weight: 500; border: none; cursor: pointer; text-decoration: none; display: inline-block; }
        .btn-primary { background: #28a745; color: #fff; }
        .btn-primary:hover { background: #218838; }
        .btn-secondary { background: #fff; color: #6c757d; border: 1px solid #ddd; }
        .btn-secondary:hover { background: #f8f9fa; }
        
        .role-info { padding: 14px; background: #f8f9fa; border: 1px solid #e9ecef; border-radius: 6px; margin-top: 10px; }
        .role-info h4 { font-size: 13px; font-weight: 600; color: #495057; margin-bottom: 8px; }
        .role-info p { font-size: 12px; color: #6c757d; margin-bottom: 4px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Create New User</h1>
            <a href="{{ route('admin.users.index') }}" class="btn-back">← Back to Users</a>
        </div>
        
        <div class="card">
            <form method="POST" action="{{ route('admin.users.store') }}">
                @csrf
                
                <div class="form-group">
                    <label>Full Name <span class="required">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" required autofocus>
                    @error('name')
                    <div class="error">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label>Email Address <span class="required">*</span></label>
                    <input type="email" name="email" value="{{ old('email') }}" required>
                    @error('email')
                    <div class="error">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label>Password <span class="required">*</span></label>
                    <input type="password" name="password" required>
                    <div class="help-text">Minimum 8 characters</div>
                    @error('password')
                    <div class="error">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label>Confirm Password <span class="required">*</span></label>
                    <input type="password" name="password_confirmation" required>
                </div>
                
                <div class="form-group">
                    <label>Assign Role <span class="required">*</span></label>
                    <select name="role_id" id="roleSelect" required onchange="showRoleInfo()">
                        <option value="">-- Select Role --</option>
                        @foreach($roles as $role)
                        <option value="{{ $role->id }}" data-permissions="{{ $role->permissions->count() }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
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
                    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Create User</button>
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
        
        // Show role info if role is pre-selected (old value)
        window.addEventListener('load', showRoleInfo);
    </script>
</body>
</html>
