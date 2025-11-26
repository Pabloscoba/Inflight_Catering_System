<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Roles & Permissions</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; background: #f5f5f5; }
        
        .container { max-width: 1200px; margin: 0 auto; padding: 40px 20px; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        .header h1 { font-size: 28px; font-weight: 700; color: #000; }
        .btn-back { padding: 10px 20px; background: #6c757d; color: #fff; text-decoration: none; border-radius: 6px; font-size: 14px; }
        .btn-back:hover { background: #5a6268; }
        
        .roles-grid { display: grid; gap: 20px; }
        .role-card { background: #fff; border: 1px solid #ddd; border-radius: 8px; padding: 24px; }
        .role-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; padding-bottom: 16px; border-bottom: 2px solid #e9ecef; }
        .role-name { font-size: 20px; font-weight: 600; color: #0b1a68; }
        .role-badge { padding: 6px 12px; background: #e3f2fd; color: #0066cc; border-radius: 20px; font-size: 13px; font-weight: 500; }
        
        .permissions-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 12px; }
        .permission-item { padding: 10px 14px; background: #f8f9fa; border: 1px solid #e9ecef; border-radius: 6px; font-size: 14px; color: #495057; display: flex; align-items: center; gap: 8px; }
        .permission-item svg { width: 16px; height: 16px; color: #28a745; flex-shrink: 0; }
        
        .btn-edit { padding: 8px 16px; background: #0066cc; color: #fff; border: none; border-radius: 6px; cursor: pointer; font-size: 14px; }
        .btn-edit:hover { background: #0052a3; }
        
        .empty-state { text-align: center; padding: 60px 20px; color: #6c757d; }
        .empty-state svg { width: 64px; height: 64px; margin: 0 auto 16px; opacity: 0.3; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Roles & Permissions Management</h1>
            <a href="{{ route('dashboard.index') }}" class="btn-back">← Back to Dashboard</a>
        </div>
        
        <div class="roles-grid">
            @forelse($roles as $role)
            <div class="role-card">
                <div class="role-header">
                    <div>
                        <div class="role-name">{{ $role->name }}</div>
                    </div>
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <span class="role-badge">{{ $role->permissions->count() }} Permissions</span>
                        <button class="btn-edit" onclick="editRole({{ $role->id }})">Edit</button>
                    </div>
                </div>
                
                @if($role->permissions->count() > 0)
                <div class="permissions-grid">
                    @foreach($role->permissions as $permission)
                    <div class="permission-item">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        {{ $permission->name }}
                    </div>
                    @endforeach
                </div>
                @else
                <div style="padding: 20px; text-align: center; color: #6c757d; font-size: 14px;">
                    No permissions assigned
                </div>
                @endif
            </div>
            @empty
            <div class="empty-state">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                </svg>
                <p>No roles found. Please run the seeder to create roles.</p>
            </div>
            @endforelse
        </div>
    </div>
    
    <script>
        function editRole(roleId) {
            // Redirect to edit page (to be created)
            window.location.href = `/admin/roles/${roleId}/edit`;
        }
    </script>
</body>
</html>
