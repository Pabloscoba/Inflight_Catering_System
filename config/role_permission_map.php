<?php

return [
    // Role -> permissions mapping. Keep this file as the only place to assign
    // which permissions each role should receive. Permissions themselves are
    // defined in config/permissions.php (key: 'list').

    'Admin' => [
        'manage users', 'manage roles', 'manage permissions', 'manage products', 'manage categories',
        'view all requests', 'approve requests', 'manage flights', 'system settings', 'view activity logs', 'manage backups',
    ],

    'Inventory Personnel' => [
        'view products', 'create products', 'update products', 'delete products',
        'manage stock movements', 'create stock movements', 'add stock', 'issue stock', 'process returns', 'view stock levels',
        'generate stock movement reports',
    ],

    'Inventory Supervisor' => [
        'view products', 'approve stock movements', 'approve products', 'approve stock entries',
        'verify stock movement', 'view inventory reports', 'view stock levels', 'manage stock movements',
        'add stock', 'issue stock', 'create stock movements',
    ],

    'Catering Incharge' => [
        'view all catering requests', 'approve catering staff requests', 'receive products from inventory', 'approve product receipts',
        'oversee catering stock', 'request stock from PMU', 'view inventory usage', 'view dispatch reports', 'view product categories',
    ],

    'Catering Staff' => [
        'create catering request', 'view own catering requests', 'receive approved items', 'record items used', 'return unused items', 'view product list',
    ],

    'Ramp Dispatcher' => [
        'view approved orders', 'prepare dispatch manifest', 'mark items as dispatched', 'handover to flight crew', 'view dispatch reports',
    ],

    'Security Staff' => [
        'authenticate requests', 'authenticate orders', 'match request vs dispatch', 'approve final dispatch security check', 'view dispatch logs',
    ],

    'Cabin Crew' => [
        'receive goods from dispatcher', 'record items used during flight', 'record remaining items', 'submit usage report', 'view flight details assigned to them',
    ],

    'Flight Purser' => [
        'view flight schedule', 'view flight passenger capacity', 'view flight products assigned', 'approve cabin crew usage report', 'finalize flight report',
    ],

    'Flight Dispatcher' => [
        'view requests', 'inspect requests for errors', 'assess flight readiness', 'assess aircraft', 'approve flight departure', 'clear flight for operations',
        'forward requests to flight purser', 'view awaiting assessment requests', 'view flight requirements', 'comment on request',
    ],
];
