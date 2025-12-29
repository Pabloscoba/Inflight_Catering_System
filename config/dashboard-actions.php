<?php

/**
 * Dashboard Actions Configuration
 * 
 * Maps permissions to dashboard UI elements (buttons/cards)
 * When a user has a permission, the corresponding action appears automatically
 * 
 * NO NEED TO EDIT BLADE FILES - just add permission via admin panel!
 */

return [
    
    /*
    |--------------------------------------------------------------------------
    | Permission-Based Dashboard Actions
    |--------------------------------------------------------------------------
    |
    | Each permission can have one or more dashboard actions
    | Format: 'permission_name' => [action config]
    |
    */
    
    'view activity logs' => [
        'title' => 'Activity Logs',
        'description' => 'View system activity',
        'icon' => '📋',
        'route' => 'admin.activity-logs.index',
        'color' => 'linear-gradient(135deg,#667eea 0%,#764ba2 100%)', // Purple
    ],
    
    'view audit logs' => [
        'title' => 'Audit Logs',
        'description' => 'View audit trail',
        'icon' => '🔍',
        'route' => 'admin.logs.index',
        'color' => 'linear-gradient(135deg,#f093fb 0%,#f5576c 100%)', // Pink
    ],
    
    'manage system settings' => [
        'title' => 'System Settings',
        'description' => 'Configure system',
        'icon' => '⚙️',
        'route' => 'admin.settings.general',
        'color' => 'linear-gradient(135deg,#4facfe 0%,#00f2fe 100%)', // Blue
    ],
    
    'manage backups' => [
        'title' => 'Backup & Restore',
        'description' => 'Database backups',
        'icon' => '💾',
        'route' => 'admin.backup.index',
        'color' => 'linear-gradient(135deg,#43e97b 0%,#38f9d7 100%)', // Green
    ],
    
    'create products' => [
        'title' => 'Add Product',
        'description' => 'Create new product',
        'icon' => '➕',
        'route' => 'cabin-crew.products.create', // Will be dynamic based on role
        'color' => 'linear-gradient(135deg,#667eea 0%,#764ba2 100%)', // Purple
        'dynamic_route' => true, // This route changes based on user role
    ],
    
    'view products' => [
        'title' => 'View Products',
        'description' => 'Browse product list',
        'icon' => '📦',
        'route' => 'cabin-crew.products.index',
        'color' => 'linear-gradient(135deg,#4facfe 0%,#00f2fe 100%)', // Blue
        'dynamic_route' => true,
    ],
    
    'manage users' => [
        'title' => 'Manage Users',
        'description' => 'User management',
        'icon' => '👥',
        'route' => 'admin.users.index',
        'color' => 'linear-gradient(135deg,#fa709a 0%,#fee140 100%)', // Orange
    ],
    
    'view reports' => [
        'title' => 'View Reports',
        'description' => 'System reports',
        'icon' => '📊',
        'route' => 'admin.reports.index',
        'color' => 'linear-gradient(135deg,#30cfd0 0%,#330867 100%)', // Teal
    ],
    
    'manage roles' => [
        'title' => 'Roles & Permissions',
        'description' => 'Configure access',
        'icon' => '🔐',
        'route' => 'admin.roles.index',
        'color' => 'linear-gradient(135deg,#a8edea 0%,#fed6e3 100%)', // Pastel
    ],
    
    'view stock levels' => [
        'title' => 'Stock Levels',
        'description' => 'Check inventory',
        'icon' => '📊',
        'route' => 'inventory-personnel.stock-movements.index',
        'color' => 'linear-gradient(135deg,#ffecd2 0%,#fcb69f 100%)', // Peach
        'dynamic_route' => true,
    ],
    
    'add stock' => [
        'title' => 'Add Stock',
        'description' => 'Receive inventory',
        'icon' => '📥',
        'route' => 'inventory-personnel.stock-movements.incoming',
        'color' => 'linear-gradient(135deg,#a8edea 0%,#fed6e3 100%)',
        'dynamic_route' => true,
    ],
    
    'issue stock' => [
        'title' => 'Issue Stock',
        'description' => 'Dispatch items',
        'icon' => '📤',
        'route' => 'inventory-personnel.stock-movements.issue',
        'color' => 'linear-gradient(135deg,#ff9a9e 0%,#fecfef 100%)',
        'dynamic_route' => true,
    ],
    
    'process returns' => [
        'title' => 'Process Returns',
        'description' => 'Handle returned items',
        'icon' => '↩️',
        'route' => 'inventory-personnel.stock-movements.returns',
        'color' => 'linear-gradient(135deg,#ffecd2 0%,#fcb69f 100%)',
        'dynamic_route' => true,
    ],
    
    // Add more permission mappings as needed
];
