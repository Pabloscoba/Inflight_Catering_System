<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assign Permissions to Users</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; background: #f5f5f5; }
        
        .container { max-width: 1200px; margin: 0 auto; padding: 40px 20px; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        .header h1 { font-size: 28px; font-weight: 700; color: #000; }
        .btn-back { padding: 10px 20px; background: #6c757d; color: #fff; text-decoration: none; border-radius: 6px; font-size: 14px; }
        .btn-back:hover { background: #5a6268; }
        
        .search-box { background: #fff; border: 1px solid #ddd; border-radius: 8px; padding: 20px; margin-bottom: 24px; }
        .search-box input { width: 100%; padding: 12px 16px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px; }
        .search-box input:focus { outline: none; border-color: #0066cc; }
        
        .users-grid { display: grid; gap: 16px; }
        .user-card { background: #fff; border: 1px solid #ddd; border-radius: 8px; padding: 20px; display: flex; justify-content: space-between; align-items: center; }
        .user-info { display: flex; align-items: center; gap: 16px; flex: 1; }
        .user-avatar { width: 48px; height: 48px; border-radius: 50%; background: #4dabf7; color: #fff; display: flex; align-items: center; justify-content: center; font-weight: 600; font-size: 18px; }
        .user-details h3 { font-size: 16px; font-weight: 600; color: #000; margin-bottom: 4px; }
        .user-details p { font-size: 13px; color: #6c757d; }
        
        .role-badges { display: flex; gap: 8px; flex-wrap: wrap; }
        .role-badge { padding: 6px 12px; background: #e3f2fd; color: #0066cc; border-radius: 20px; font-size: 12px; font-weight: 500; }
        .role-badge.admin { background: #fce4ec; color: #c2185b; }
        .role-badge.staff { background: #e8f5e9; color: #2e7d32; }
        
        .btn-assign { padding: 10px 20px; background: #0066cc; color: #fff; border: none; border-radius: 6px; cursor: pointer; font-size: 14px; font-weight: 500; }
        .btn-assign:hover { background: #0052a3; }
        
        .empty-state { text-align: center; padding: 60px 20px; color: #6c757d; background: #fff; border: 1px solid #ddd; border-radius: 8px; }
        
        /* Modal Styles */
        .modal { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center; }
        .modal.active { display: flex; }
        .modal-content { background: #fff; border-radius: 8px; padding: 30px; max-width: 600px; width: 90%; max-height: 80vh; overflow-y: auto; }
        .modal-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; padding-bottom: 16px; border-bottom: 2px solid #e9ecef; }
        .modal-header h2 { font-size: 22px; font-weight: 600; color: #000; }
        .btn-close { background: none; border: none; font-size: 24px; cursor: pointer; color: #6c757d; }
        .btn-close:hover { color: #000; }
        
        .roles-list { display: grid; gap: 12px; margin-bottom: 24px; }
        .role-option { display: flex; align-items: center; padding: 14px 16px; background: #f8f9fa; border: 1px solid #e9ecef; border-radius: 6px; cursor: pointer; transition: all 0.2s; }
        .role-option:hover { background: #e9ecef; }
        .role-option.selected { background: #d1f4e0; border-color: #28a745; }
        .role-option input[type="radio"] { width: 20px; height: 20px; margin-right: 12px; cursor: pointer; accent-color: #28a745; }
        .role-option label { cursor: pointer; flex: 1; font-size: 15px; font-weight: 500; color: #495057; }
        .role-description { font-size: 12px; color: #6c757d; margin-top: 4px; }
        
        .modal-actions { display: flex; gap: 12px; justify-content: flex-end; margin-top: 24px; }
        .btn-save { padding: 12px 24px; background: #28a745; color: #fff; border: none; border-radius: 6px; cursor: pointer; font-size: 14px; font-weight: 500; }
        .btn-save:hover { background: #218838; }
        .btn-cancel { padding: 12px 24px; background: #fff; color: #6c757d; border: 1px solid #ddd; border-radius: 6px; cursor: pointer; font-size: 14px; }
        .btn-cancel:hover { background: #f8f9fa; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Assign Roles & Permissions to Users</h1>
            <a href="{{ route('dashboard.index') }}" class="btn-back">← Back to Dashboard</a>
        </div>
        
        <div class="search-box">
            <input type="text" id="searchUser" placeholder="Search users by name or email..." onkeyup="filterUsers()">
        </div>
        
        <div class="users-grid" id="usersGrid">
            @forelse($users as $user)
            <div class="user-card" data-name="{{ strtolower($user->name) }}" data-email="{{ strtolower($user->email) }}">
                <div class="user-info">
                    <div class="user-avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                    <div class="user-details">
                        <h3>{{ $user->name }}</h3>
                        <p>{{ $user->email }}</p>
                    </div>
                </div>
                
                <div class="role-badges">
                    @forelse($user->roles as $role)
                        <span class="role-badge {{ $role->name === 'Admin' ? 'admin' : 'staff' }}">{{ $role->name }}</span>
                    @empty
                        <span class="role-badge" style="background: #ffeaa7; color: #d63031;">No Role</span>
                    @endforelse
                </div>
                
                <button class="btn-assign" onclick="openModal({{ $user->id }}, '{{ $user->name }}', {{ $user->roles->pluck('id')->toJson() }})">
                    Assign Role
                </button>
            </div>
            @empty
            <div class="empty-state">
                <p>No users found.</p>
            </div>
            @endforelse
        </div>
    </div>
    
    <!-- Modal for Assigning Role -->
    <div class="modal" id="assignModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Assign Role to <span id="modalUserName"></span></h2>
                <button class="btn-close" onclick="closeModal()">&times;</button>
            </div>
            
            <form id="assignRoleForm" method="POST">
                @csrf
                @method('PUT')
                
                <div class="roles-list">
                    @foreach($roles as $role)
                    <div class="role-option" onclick="selectRole(this, {{ $role->id }})">
                        <input type="radio" name="role_id" value="{{ $role->id }}" id="role-{{ $role->id }}">
                        <div style="flex: 1;">
                            <label for="role-{{ $role->id }}">{{ $role->name }}</label>
                            <div class="role-description">{{ $role->permissions->count() }} permissions assigned</div>
                        </div>
                    </div>
                    @endforeach
                </div>
                
                <div class="modal-actions">
                    <button type="button" class="btn-cancel" onclick="closeModal()">Cancel</button>
                    <button type="submit" class="btn-save">Assign Role</button>
                </div>
            </form>
        </div>
    </div>
    
    <script>
        let currentUserId = null;
        
        function openModal(userId, userName, currentRoles) {
            currentUserId = userId;
            document.getElementById('modalUserName').textContent = userName;
            document.getElementById('assignModal').classList.add('active');
            
            // Update form action
            document.getElementById('assignRoleForm').action = `/admin/users/${userId}/assign-role`;
            
            // Pre-select current role if exists
            if (currentRoles.length > 0) {
                const roleId = currentRoles[0];
                const radioButton = document.getElementById(`role-${roleId}`);
                if (radioButton) {
                    radioButton.checked = true;
                    radioButton.closest('.role-option').classList.add('selected');
                }
            }
        }
        
        function closeModal() {
            document.getElementById('assignModal').classList.remove('active');
            // Clear selections
            document.querySelectorAll('.role-option').forEach(opt => opt.classList.remove('selected'));
            document.querySelectorAll('input[name="role_id"]').forEach(radio => radio.checked = false);
        }
        
        function selectRole(div, roleId) {
            // Remove selected class from all
            document.querySelectorAll('.role-option').forEach(opt => opt.classList.remove('selected'));
            // Add to clicked
            div.classList.add('selected');
            // Check the radio
            document.getElementById(`role-${roleId}`).checked = true;
        }
        
        function filterUsers() {
            const searchTerm = document.getElementById('searchUser').value.toLowerCase();
            const userCards = document.querySelectorAll('.user-card');
            
            userCards.forEach(card => {
                const name = card.getAttribute('data-name');
                const email = card.getAttribute('data-email');
                
                if (name.includes(searchTerm) || email.includes(searchTerm)) {
                    card.style.display = 'flex';
                } else {
                    card.style.display = 'none';
                }
            });
        }
        
        // Close modal when clicking outside
        document.getElementById('assignModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });
    </script>
</body>
</html>
