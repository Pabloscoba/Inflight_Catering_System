@extends('layouts.app')

@section('content')
<style>
    .roles-container { max-width: 900px; margin: 0 auto; padding: 40px 20px; }
    .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
    .header h1 { font-size: 28px; font-weight: 700; color: #000; }
    .role-badge { display: inline-block; padding: 8px 16px; background: #e3f2fd; color: #0066cc; border-radius: 20px; font-size: 14px; font-weight: 500; margin-left: 12px; }
    .btn-back { padding: 10px 20px; background: #6c757d; color: #fff; text-decoration: none; border-radius: 6px; font-size: 14px; }
    .btn-back:hover { background: #5a6268; }
    
    .card { background: #fff; border: 1px solid #ddd; border-radius: 8px; padding: 30px; margin-bottom: 24px; }
    .section-title { font-size: 18px; font-weight: 600; color: #0b1a68; margin-bottom: 20px; padding-bottom: 12px; border-bottom: 2px solid #e9ecef; }
    
    .permissions-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 12px; }
    .permission-checkbox { display: flex; align-items: center; padding: 12px 16px; background: #f8f9fa; border: 1px solid #e9ecef; border-radius: 6px; cursor: pointer; transition: all 0.2s; }
    .permission-checkbox:hover { background: #e9ecef; }
    .permission-checkbox.selected { background: #d1f4e0; border-color: #28a745; }
    .permission-checkbox input[type="checkbox"] { width: 18px; height: 18px; margin-right: 12px; cursor: pointer; accent-color: #28a745; }
    .permission-checkbox label { cursor: pointer; font-size: 14px; color: #495057; flex: 1; user-select: none; }
    
    .actions { display: flex; gap: 12px; justify-content: flex-end; margin-top: 24px; }
    .btn-save { padding: 12px 24px; background: #28a745; color: #fff; border: none; border-radius: 6px; cursor: pointer; font-size: 14px; font-weight: 500; }
    .btn-save:hover { background: #218838; }
    .btn-cancel { padding: 12px 24px; background: #fff; color: #6c757d; border: 1px solid #ddd; border-radius: 6px; cursor: pointer; font-size: 14px; text-decoration: none; display: inline-block; }
    .btn-cancel:hover { background: #f8f9fa; }
    
    .select-all { margin-bottom: 16px; padding: 10px 16px; background: #fff3cd; border: 1px solid #ffc107; border-radius: 6px; display: flex; align-items: center; gap: 12px; }
    .select-all input { width: 18px; height: 18px; cursor: pointer; }
    .select-all label { cursor: pointer; font-size: 14px; font-weight: 500; color: #856404; user-select: none; }
    
    .info-box { background: #e7f3ff; border-left: 4px solid #2196f3; padding: 12px 16px; border-radius: 6px; margin-bottom: 20px; font-size: 14px; color: #0c5596; }
</style>

<div class="roles-container">
    <div class="header">
        <div>
            <h1>Edit Role Permissions</h1>
            <span class="role-badge">{{ $role->name }}</span>
        </div>
        <a href="{{ route('admin.roles.index') }}" class="btn-back">← Back to Roles</a>
    </div>
    
    <div class="info-box">
        💡 <strong>Tip:</strong> Changes will take effect immediately after saving. Users with this role will automatically get the updated permissions.
    </div>
    
    <form action="{{ route('admin.roles.update', $role) }}" method="POST" id="permissions-form">
        @csrf
        @method('PUT')
        
        <div class="card">
            <div class="select-all">
                <input type="checkbox" id="select-all" onchange="toggleAll(this)">
                <label for="select-all">Select/Deselect All Permissions</label>
            </div>
            
            <div class="section-title">
                Available Permissions 
                <span style="color: #6c757d; font-weight: 400; font-size: 14px;">
                    (<span id="selected-count">{{ count($rolePermissions) }}</span> of {{ $allPermissions->count() }} selected)
                </span>
            </div>
            
            <div class="permissions-grid">
                @foreach($allPermissions as $permission)
                <div class="permission-checkbox {{ in_array($permission->id, $rolePermissions) ? 'selected' : '' }}">
                    <input 
                        type="checkbox" 
                        name="permissions[]" 
                        value="{{ $permission->id }}" 
                        id="perm-{{ $permission->id }}"
                        {{ in_array($permission->id, $rolePermissions) ? 'checked' : '' }}
                        onchange="updateSelection(this)"
                    >
                    <label for="perm-{{ $permission->id }}">{{ $permission->name }}</label>
                </div>
                @endforeach
            </div>
        </div>
        
        <div class="actions">
            <a href="{{ route('admin.roles.index') }}" class="btn-cancel">Cancel</a>
            <button type="submit" class="btn-save">💾 Save Changes</button>
        </div>
    </form>
</div>

<script>
    function updateSelection(checkbox) {
        const div = checkbox.closest('.permission-checkbox');
        if (checkbox.checked) {
            div.classList.add('selected');
        } else {
            div.classList.remove('selected');
        }
        updateSelectAllState();
        updateSelectedCount();
    }
    
    function toggleAll(selectAllCheckbox) {
        const checkboxes = document.querySelectorAll('.permission-checkbox input[type="checkbox"]');
        checkboxes.forEach(checkbox => {
            checkbox.checked = selectAllCheckbox.checked;
            updateSelection(checkbox);
        });
    }
    
    function updateSelectAllState() {
        const allCheckboxes = document.querySelectorAll('.permission-checkbox input[type="checkbox"]');
        const selectAllCheckbox = document.getElementById('select-all');
        const checkedCount = Array.from(allCheckboxes).filter(cb => cb.checked).length;
        selectAllCheckbox.checked = checkedCount === allCheckboxes.length;
        selectAllCheckbox.indeterminate = checkedCount > 0 && checkedCount < allCheckboxes.length;
    }
    
    function updateSelectedCount() {
        const checkedCount = document.querySelectorAll('.permission-checkbox input[type="checkbox"]:checked').length;
        document.getElementById('selected-count').textContent = checkedCount;
    }
    
    // Initialize on page load
    updateSelectAllState();
    updateSelectedCount();
    
    // Confirm before submitting if no permissions selected
    document.getElementById('permissions-form').addEventListener('submit', function(e) {
        const checkedCount = document.querySelectorAll('.permission-checkbox input[type="checkbox"]:checked').length;
        if (checkedCount === 0) {
            if (!confirm('Are you sure you want to remove all permissions from this role? Users with this role will have no permissions.')) {
                e.preventDefault();
            }
        }
    });
</script>
@endsection
