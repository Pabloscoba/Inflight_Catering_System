<?php

return [
    // Centralised list of all permissions used across the system.
    // Add new permission strings here so they are available to seeders and config maps.
    'list' => [
        // Admin/system
        'manage users', 'manage roles', 'manage permissions', 'system settings', 'manage backups', 'view logs',

        // Products & categories
        'view products', 'create products', 'update products', 'delete products', 'view product list', 'view product categories', 'edit product records',

        // Stock & inventory
        'manage stock', 'manage stock movements', 'create stock movements', 'add stock', 'issue stock', 'transfer stock', 'process returns',
        'view stock levels', 'generate stock movement reports', 'approve stock movements', 'approve stock entries', 'verify stock movement',
        'manage incoming stock', 'manage stock issues', 'manage stock returns', 'view inventory reports', 'approve products',

        // Requests & approvals
        'create catering request', 'view own catering requests', 'view all catering requests', 'view all requests', 'view approved requests',
        'approve requests', 'final approve requests', 'approve deny catering requests', 'view incoming requests from catering staff',

        // Catering operations
        'receive approved items', 'receive products from inventory', 'approve product receipts', 'oversee catering stock', 'request stock from PMU',
        'record items used', 'return unused items', 'submit usage report', 'record items used during flight', 'return receive items from flights',

        // Dispatch & ramp
        'view approved orders', 'prepare dispatch manifest', 'mark items as dispatched', 'handover to flight crew', 'view dispatch reports',
        'verify quantities before loading', 'dispatch flights', 'manage dispatches', 'manage messages',

        // Security
        'authenticate requests', 'authenticate orders', 'match request vs dispatch', 'approve final dispatch security check', 'block suspicious dispatch', 'view dispatch logs',

        // Flight & cabin
        'view flight schedule', 'view flight passenger capacity', 'view flight products assigned', 'view flight details assigned to them',
        'view assigned flights', 'manage cabin crew', 'approve cabin crew usage report', 'finalize flight report', 'submit final flight consumption',

        // Flight Dispatcher specific
        'view requests', 'inspect requests for errors', 'assess flight readiness', 'assess aircraft', 'approve flight departure', 'clear flight for operations',
        'forward requests to flight purser', 'view awaiting assessment requests', 'view flight requirements', 'comment on request', 'recommend dispatch to flight operations',

        // Misc
        'view activity logs', 'view audit logs', 'manage flights', 'manage catering operations', 'manage returns', 'view inventory usage',
    ],
];
