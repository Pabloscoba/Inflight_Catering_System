<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users Management</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; background: #f5f5f5; }
        
        .container { max-width: 1400px; margin: 0 auto; padding: 40px 20px; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        .header h1 { font-size: 28px; font-weight: 700; color: #000; }
        .header-actions { display: flex; gap: 12px; }
        .btn { padding: 10px 20px; border-radius: 6px; font-size: 14px; font-weight: 500; text-decoration: none; border: none; cursor: pointer; }
        .btn-primary { background: #0066cc; color: #fff; }
        .btn-primary:hover { background: #0052a3; }
        .btn-secondary { background: #6c757d; color: #fff; }
        .btn-secondary:hover { background: #5a6268; }
        .btn-danger { background: #dc3545; color: #fff; }
        .btn-danger:hover { background: #c82333; }
        .btn-success { background: #28a745; color: #fff; }
        .btn-success:hover { background: #218838; }
        
        .filters { background: #fff; border: 1px solid #ddd; border-radius: 8px; padding: 20px; margin-bottom: 24px; display: flex; gap: 12px; flex-wrap: wrap; }
        .filters input, .filters select { padding: 10px 14px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px; }
        .filters input { flex: 1; min-width: 250px; }
        .filters select { min-width: 180px; }
        .filters input:focus, .filters select:focus { outline: none; border-color: #0066cc; }
        
        .card { background: #fff; border: 1px solid #ddd; border-radius: 8px; overflow: hidden; }
        
        .table-container { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; }
        thead { background: #f8f9fa; border-bottom: 2px solid #dee2e6; }
        th { padding: 14px 16px; text-align: left; font-size: 13px; font-weight: 600; color: #495057; text-transform: uppercase; letter-spacing: 0.5px; }
        td { padding: 14px 16px; border-bottom: 1px solid #e9ecef; font-size: 14px; color: #495057; }
        tbody tr:hover { background: #f8f9fa; }
        
        .user-info { display: flex; align-items: center; gap: 12px; }
        .user-avatar { width: 40px; height: 40px; border-radius: 50%; background: #4dabf7; color: #fff; display: flex; align-items: center; justify-content: center; font-weight: 600; font-size: 16px; }
        .user-details h3 { font-size: 14px; font-weight: 600; color: #000; margin-bottom: 2px; }
        .user-details p { font-size: 13px; color: #6c757d; }
        
        .role-badge { display: inline-block; padding: 4px 10px; border-radius: 12px; font-size: 12px; font-weight: 500; }
        .role-admin { background: #fce4ec; color: #c2185b; }
        .role-staff { background: #e8f5e9; color: #2e7d32; }
        .role-supervisor { background: #e3f2fd; color: #1565c0; }
        .role-other { background: #fff3cd; color: #856404; }
        
        .actions { display: flex; gap: 8px; }
        .btn-sm { padding: 6px 12px; font-size: 13px; }
        
        .pagination { display: flex; justify-content: center; align-items: center; gap: 8px; padding: 20px; }
        .pagination a, .pagination span { padding: 8px 12px; border: 1px solid #ddd; border-radius: 4px; text-decoration: none; color: #495057; font-size: 14px; }
        .pagination a:hover { background: #e9ecef; }
        .pagination .active { background: #0066cc; color: #fff; border-color: #0066cc; }
        
        .empty-state { text-align: center; padding: 60px 20px; color: #6c757d; }
        .empty-state svg { width: 64px; height: 64px; margin: 0 auto 16px; opacity: 0.3; }
        
        .alert { padding: 12px 20px; border-radius: 6px; margin-bottom: 20px; font-size: 14px; }
        .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Users Management</h1>
            <div class="header-actions">
                <a href="{{ route('admin.users.create') }}" class="btn btn-primary">+ Create New User</a>
                <a href="{{ route('dashboard.index') }}" class="btn btn-secondary">← Back to Dashboard</a>
            </div>
        </div>
        
        @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        
        @if(session('error'))
        <div class="alert alert-error">{{ session('error') }}</div>
        @endif
        
        <form method="GET" action="{{ route('admin.users.index') }}" class="filters">
            <input type="text" name="search" placeholder="Search by name or email..." value="{{ request('search') }}">
            <select name="role">
                <option value="">All Roles</option>
                @foreach($roles as $role)
                <option value="{{ $role->name }}" {{ request('role') == $role->name ? 'selected' : '' }}>{{ $role->name }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-primary">Filter</button>
            @if(request('search') || request('role'))
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Clear</a>
            @endif
        </form>
        
        <div class="card">
            @if($users->count() > 0)
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Role</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr>
                            <td>
                                <div class="user-info">
                                    <div class="user-avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                                    <div class="user-details">
                                        <h3>{{ $user->name }}</h3>
                                        <p>{{ $user->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @forelse($user->roles as $role)
                                <span class="role-badge {{ 
                                    $role->name === 'Admin' ? 'role-admin' : 
                                    (str_contains($role->name, 'Supervisor') ? 'role-supervisor' : 
                                    (str_contains($role->name, 'Staff') ? 'role-staff' : 'role-other')) 
                                }}">{{ $role->name }}</span>
                                @empty
                                <span class="role-badge role-other">No Role</span>
                                @endforelse
                            </td>
                            <td>{{ $user->created_at->format('M d, Y') }}</td>
                            <td>
                                <div class="actions">
                                    <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-primary btn-sm">Edit</a>
                                    @if($user->id !== auth()->id())
                                    <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Are you sure you want to delete this user?');" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            @if($users->hasPages())
            <div class="pagination">
                @if($users->onFirstPage())
                <span>Previous</span>
                @else
                <a href="{{ $users->previousPageUrl() }}">Previous</a>
                @endif
                
                <span class="active">{{ $users->currentPage() }}</span>
                
                @if($users->hasMorePages())
                <a href="{{ $users->nextPageUrl() }}">Next</a>
                @else
                <span>Next</span>
                @endif
            </div>
            @endif
            @else
            <div class="empty-state">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
                <p>No users found. {{ request('search') || request('role') ? 'Try adjusting your filters.' : 'Create your first user!' }}</p>
            </div>
            @endif
        </div>
    </div>
</body>
</html>
