<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});
require __DIR__.'/auth.php';

// Dashboard Route - Redirects based on role
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        $user = auth()->user();
        
        // Redirect to role-specific dashboard
        if ($user->hasRole('Admin')) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->hasRole('Inventory Personnel')) {
            return redirect()->route('inventory-personnel.dashboard');
        } elseif ($user->hasRole('Inventory Supervisor')) {
            return redirect()->route('inventory-supervisor.dashboard');
        } elseif ($user->hasRole('Catering Incharge')) {
            return redirect()->route('catering-incharge.dashboard');
        } elseif ($user->hasRole('Catering Staff')) {
            return redirect()->route('catering-staff.dashboard');
        } elseif ($user->hasRole('Ramp Dispatcher')) {
            return redirect()->route('ramp-dispatcher.dashboard');
        } elseif ($user->hasRole('Security Staff')) {
            return redirect()->route('security-staff.dashboard');
        } elseif ($user->hasRole('Cabin Crew')) {
            return redirect()->route('cabin-crew.dashboard');
        } elseif ($user->hasRole('Flight Purser')) {
            return redirect()->route('flight-purser.dashboard');
        }
        
        // Default to admin dashboard if no role found
        return redirect()->route('admin.dashboard');
    })->name('dashboard.index');
    
    // Flight Schedule - Accessible to all authenticated users
    Route::get('/flights', [App\Http\Controllers\Admin\FlightController::class, 'index'])->name('flights.schedule');
    
    // Notifications Routes - Accessible to all authenticated users
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [App\Http\Controllers\NotificationController::class, 'index'])->name('index');
        Route::get('/recent', [App\Http\Controllers\NotificationController::class, 'recent'])->name('recent');
        Route::get('/unread-count', [App\Http\Controllers\NotificationController::class, 'unreadCount'])->name('unread-count');
        Route::post('/{id}/read', [App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('read');
        Route::post('/mark-all-read', [App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('mark-all-read');
        Route::delete('/{id}', [App\Http\Controllers\NotificationController::class, 'destroy'])->name('destroy');
        Route::post('/clear-read', [App\Http\Controllers\NotificationController::class, 'clearRead'])->name('clear-read');
    });
});

// Admin Routes - Protected by auth and role:Admin middleware
Route::middleware(['auth', 'role:Admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

    // Users Management
    Route::resource('users', App\Http\Controllers\Admin\UserController::class)->except(['show']);
    Route::patch('/users/{user}/toggle-status', [App\Http\Controllers\Admin\UserController::class, 'toggleStatus'])->name('users.toggle-status');

    // Roles & Permissions
    Route::get('/roles', [App\Http\Controllers\Admin\RoleController::class, 'index'])->name('roles.index');
    Route::get('/roles/assign', [App\Http\Controllers\Admin\RoleController::class, 'assignForm'])->name('roles.assign');
    Route::get('/roles/{role}/edit', [App\Http\Controllers\Admin\RoleController::class, 'edit'])->name('roles.edit');
    Route::put('/roles/{role}', [App\Http\Controllers\Admin\RoleController::class, 'update'])->name('roles.update');
    Route::put('/users/{user}/assign-role', [App\Http\Controllers\Admin\RoleController::class, 'assignRole'])->name('users.assignRole');

    // Products Management
    Route::resource('products', App\Http\Controllers\Admin\ProductController::class)->except(['show']);
    Route::patch('/products/{product}/toggle-status', [App\Http\Controllers\Admin\ProductController::class, 'toggleStatus'])->name('products.toggle-status');
    
    Route::resource('categories', App\Http\Controllers\Admin\CategoryController::class)->except(['show']);
    Route::patch('/categories/{category}/toggle-status', [App\Http\Controllers\Admin\CategoryController::class, 'toggleStatus'])->name('categories.toggle-status');

    // Stock Movements
    Route::get('/stock-movements', [App\Http\Controllers\Admin\StockMovementController::class, 'index'])->name('stock-movements.index');
    Route::get('/stock-movements/incoming', [App\Http\Controllers\Admin\StockMovementController::class, 'incomingForm'])->name('stock-movements.incoming');
    Route::post('/stock-movements/incoming', [App\Http\Controllers\Admin\StockMovementController::class, 'storeIncoming'])->name('stock-movements.store-incoming');
    Route::get('/stock-movements/issue', [App\Http\Controllers\Admin\StockMovementController::class, 'issueForm'])->name('stock-movements.issue');
    Route::post('/stock-movements/issue', [App\Http\Controllers\Admin\StockMovementController::class, 'storeIssue'])->name('stock-movements.store-issue');
    Route::get('/stock-movements/returns', [App\Http\Controllers\Admin\StockMovementController::class, 'returnsForm'])->name('stock-movements.returns');
    Route::post('/stock-movements/returns', [App\Http\Controllers\Admin\StockMovementController::class, 'storeReturns'])->name('stock-movements.store-returns');

    // Flights Management
    Route::resource('flights', App\Http\Controllers\Admin\FlightController::class)->except(['show']);

    // Requests Management (Admin or Inventory Supervisor with permissions)
    Route::get('/requests', [App\Http\Controllers\Admin\RequestController::class, 'index'])->name('requests.index')->middleware('permission:view all requests');
    Route::get('/requests/create', [App\Http\Controllers\Admin\RequestController::class, 'create'])->name('requests.create');
    Route::post('/requests', [App\Http\Controllers\Admin\RequestController::class, 'store'])->name('requests.store');
    Route::get('/requests/pending', [App\Http\Controllers\Admin\RequestController::class, 'pending'])->name('requests.pending')->middleware('permission:view pending requests');
    Route::get('/requests/approved', [App\Http\Controllers\Admin\RequestController::class, 'approved'])->name('requests.approved')->middleware('permission:view approved requests');
    Route::get('/requests/rejected', [App\Http\Controllers\Admin\RequestController::class, 'rejected'])->name('requests.rejected')->middleware('permission:view rejected requests');
    Route::get('/requests/{request}', [App\Http\Controllers\Admin\RequestController::class, 'show'])->name('requests.show')->middleware('permission:view all requests');
    Route::get('/requests/{request}/approve', [App\Http\Controllers\Admin\RequestController::class, 'approveForm'])->name('requests.approve-form');
    Route::post('/requests/{request}/approve', [App\Http\Controllers\Admin\RequestController::class, 'approve'])->name('requests.approve');
    Route::post('/requests/{request}/reject', [App\Http\Controllers\Admin\RequestController::class, 'reject'])->name('requests.reject');
    Route::delete('/requests/{request}', [App\Http\Controllers\Admin\RequestController::class, 'destroy'])->name('requests.destroy');

    // Audit Logs (Admin Only)
    Route::middleware(['role:Admin'])->group(function () {
        Route::get('/logs', [App\Http\Controllers\Admin\AuditLogController::class, 'index'])->name('logs.index');
        Route::get('/logs/{log}', [App\Http\Controllers\Admin\AuditLogController::class, 'show'])->name('logs.show');
        Route::post('/logs/clear', [App\Http\Controllers\Admin\AuditLogController::class, 'clear'])->name('logs.clear');
    });

    // Activity Logs (Admin Only)
    Route::get('/activity-logs', [App\Http\Controllers\Admin\ActivityLogController::class, 'index'])->name('activity-logs.index');
    Route::get('/activity-logs/{activity}', [App\Http\Controllers\Admin\ActivityLogController::class, 'show'])->name('activity-logs.show');
    Route::get('/activity-logs-export', [App\Http\Controllers\Admin\ActivityLogController::class, 'export'])->name('activity-logs.export');
    Route::delete('/activity-logs-delete-old', [App\Http\Controllers\Admin\ActivityLogController::class, 'deleteOld'])->name('activity-logs.delete-old');

    // System Settings
    Route::get('/settings/general', [App\Http\Controllers\Admin\SettingsController::class, 'general'])->name('settings.general');
    Route::put('/settings/general', [App\Http\Controllers\Admin\SettingsController::class, 'updateGeneral'])->name('settings.update-general');
    
    // Database Backup (Admin Only)
    Route::middleware(['role:Admin'])->group(function () {
        Route::get('/backup', [App\Http\Controllers\Admin\BackupController::class, 'index'])->name('backup.index');
        Route::post('/backup/create', [App\Http\Controllers\Admin\BackupController::class, 'create'])->name('backup.create');
        Route::get('/backup/download/{filename}', [App\Http\Controllers\Admin\BackupController::class, 'download'])->name('backup.download');
        Route::delete('/backup/delete/{filename}', [App\Http\Controllers\Admin\BackupController::class, 'delete'])->name('backup.delete');
    });
});

// ============================================
// INVENTORY PERSONNEL ROUTES
// ============================================
Route::middleware(['auth', 'role:Inventory Personnel'])->prefix('inventory-personnel')->name('inventory-personnel.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\InventoryPersonnel\DashboardController::class, 'index'])->name('dashboard');
    
    // Products Management
    Route::get('/products', [App\Http\Controllers\InventoryPersonnel\ProductController::class, 'index'])->name('products.index')->middleware('permission:view products');
    Route::get('/products/create', [App\Http\Controllers\InventoryPersonnel\ProductController::class, 'create'])->name('products.create')->middleware('permission:create products');
    Route::post('/products', [App\Http\Controllers\InventoryPersonnel\ProductController::class, 'store'])->name('products.store')->middleware('permission:create products');
    Route::get('/products/{product}/edit', [App\Http\Controllers\InventoryPersonnel\ProductController::class, 'edit'])->name('products.edit')->middleware('permission:update products');
    Route::put('/products/{product}', [App\Http\Controllers\InventoryPersonnel\ProductController::class, 'update'])->name('products.update')->middleware('permission:update products');
    Route::delete('/products/{product}', [App\Http\Controllers\InventoryPersonnel\ProductController::class, 'destroy'])->name('products.destroy')->middleware('permission:update products');
    
    // Stock Movements
    Route::get('/stock-movements', [App\Http\Controllers\InventoryPersonnel\StockMovementController::class, 'index'])->name('stock-movements.index')->middleware('permission:view stock levels');
    Route::get('/stock-movements/export-pdf', [App\Http\Controllers\InventoryPersonnel\StockMovementController::class, 'exportPDF'])->name('stock-movements.export-pdf')->middleware('permission:view stock levels');
    Route::get('/stock-movements/incoming', [App\Http\Controllers\InventoryPersonnel\StockMovementController::class, 'incomingForm'])->name('stock-movements.incoming')->middleware('permission:add stock');
    Route::post('/stock-movements/incoming', [App\Http\Controllers\InventoryPersonnel\StockMovementController::class, 'storeIncoming'])->name('stock-movements.store-incoming')->middleware('permission:add stock');
    Route::get('/stock-movements/issue', [App\Http\Controllers\InventoryPersonnel\StockMovementController::class, 'issueForm'])->name('stock-movements.issue')->middleware('permission:issue stock');
    Route::post('/stock-movements/issue', [App\Http\Controllers\InventoryPersonnel\StockMovementController::class, 'storeIssue'])->name('stock-movements.store-issue')->middleware('permission:issue stock');
    Route::get('/stock-movements/returns', [App\Http\Controllers\InventoryPersonnel\StockMovementController::class, 'returnsForm'])->name('stock-movements.returns')->middleware('permission:process returns');
    Route::post('/stock-movements/returns', [App\Http\Controllers\InventoryPersonnel\StockMovementController::class, 'storeReturns'])->name('stock-movements.store-returns')->middleware('permission:process returns');
    Route::get('/stock-movements/transfer-to-catering', [App\Http\Controllers\InventoryPersonnel\StockMovementController::class, 'transferToCateringForm'])->name('stock-movements.transfer-to-catering')->middleware('permission:add stock');
    Route::post('/stock-movements/transfer-to-catering', [App\Http\Controllers\InventoryPersonnel\StockMovementController::class, 'storeTransferToCatering'])->name('stock-movements.store-transfer-to-catering')->middleware('permission:add stock');

    // Pending requests from Catering Staff (Inventory Personnel reviews first)
    Route::get('/requests/pending', [App\Http\Controllers\InventoryPersonnel\RequestController::class, 'pendingRequests'])->name('requests.pending');
    Route::post('/requests/{request}/forward-to-supervisor', [App\Http\Controllers\InventoryPersonnel\RequestController::class, 'forwardToSupervisor'])->name('requests.forward-to-supervisor');

    // View supervisor-approved requests (Inventory Personnel)
    Route::get('/requests/supervisor-approved', [App\Http\Controllers\InventoryPersonnel\RequestController::class, 'supervisorApproved'])->name('requests.supervisor-approved')->middleware('permission:issue stock');
    
    // Forward supervisor-approved requests to Security
    Route::post('/requests/{request}/forward-to-security', [App\Http\Controllers\Admin\RequestController::class, 'forwardToSecurity'])->name('requests.forward-to-security')->middleware('permission:issue stock');
    
    // Settings
    Route::get('/settings', [App\Http\Controllers\InventoryPersonnel\SettingsController::class, 'index'])->name('settings');
    Route::put('/settings/profile', [App\Http\Controllers\InventoryPersonnel\SettingsController::class, 'updateProfile'])->name('settings.update-profile');
    Route::put('/settings/password', [App\Http\Controllers\InventoryPersonnel\SettingsController::class, 'updatePassword'])->name('settings.update-password');
    Route::put('/settings/preferences', [App\Http\Controllers\InventoryPersonnel\SettingsController::class, 'updatePreferences'])->name('settings.update-preferences');
});

// ============================================
// INVENTORY SUPERVISOR ROUTES
// ============================================
Route::middleware(['auth', 'role:Inventory Supervisor'])->prefix('inventory-supervisor')->name('inventory-supervisor.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\InventorySupervisor\DashboardController::class, 'index'])->name('dashboard');
    
    // Product approval routes
    Route::get('/products', [App\Http\Controllers\InventorySupervisor\ProductController::class, 'index'])->name('products.index')->middleware('permission:approve products');
    Route::patch('/products/{product}/approve', [App\Http\Controllers\InventorySupervisor\ProductController::class, 'approve'])->name('products.approve')->middleware('permission:approve products');
    Route::patch('/products/{product}/reject', [App\Http\Controllers\InventorySupervisor\ProductController::class, 'reject'])->name('products.reject')->middleware('permission:approve products');
    
    // Approval routes (legacy)
    Route::get('/approvals/products', [App\Http\Controllers\InventorySupervisor\ApprovalController::class, 'pendingProducts'])->name('approvals.products')->middleware('permission:approve products');
    Route::post('/approvals/products/{product}/approve', [App\Http\Controllers\InventorySupervisor\ApprovalController::class, 'approveProduct'])->name('approvals.products.approve')->middleware('permission:approve products');
    Route::post('/approvals/products/{product}/reject', [App\Http\Controllers\InventorySupervisor\ApprovalController::class, 'rejectProduct'])->name('approvals.products.reject')->middleware('permission:approve products');
    
    Route::get('/approvals/movements', [App\Http\Controllers\InventorySupervisor\ApprovalController::class, 'pendingMovements'])->name('approvals.movements')->middleware('permission:approve stock movements');
    Route::post('/approvals/movements/{movement}/approve', [App\Http\Controllers\InventorySupervisor\ApprovalController::class, 'approveMovement'])->name('approvals.movements.approve')->middleware('permission:approve stock movements');
    Route::post('/approvals/movements/{movement}/reject', [App\Http\Controllers\InventorySupervisor\ApprovalController::class, 'rejectMovement'])->name('approvals.movements.reject')->middleware('permission:approve stock movements');
    
    // Stock Movements history (view only)
    Route::get('/stock-movements', [App\Http\Controllers\Admin\StockMovementController::class, 'index'])->middleware('permission:view stock levels')->name('stock-movements.index');

    // Inventory Supervisor can view and approve requests awaiting supervisor approval
    Route::get('/requests/pending', [App\Http\Controllers\Admin\RequestController::class, 'pending'])->name('requests.pending')->middleware('permission:approve deny catering requests');
    Route::get('/requests/{request}', [App\Http\Controllers\Admin\RequestController::class, 'show'])->name('requests.show')->middleware('permission:view incoming requests from catering staff');
    Route::get('/requests/{request}/approve', [App\Http\Controllers\Admin\RequestController::class, 'approveForm'])->name('requests.approve-form')->middleware('permission:approve deny catering requests');
    Route::post('/requests/{request}/approve', [App\Http\Controllers\Admin\RequestController::class, 'approve'])->name('requests.approve')->middleware('permission:approve deny catering requests');
    
    // Settings
    Route::get('/settings', [App\Http\Controllers\InventorySupervisor\SettingsController::class, 'index'])->name('settings');
    Route::put('/settings/profile', [App\Http\Controllers\InventorySupervisor\SettingsController::class, 'updateProfile'])->name('settings.update-profile');
    Route::put('/settings/password', [App\Http\Controllers\InventorySupervisor\SettingsController::class, 'updatePassword'])->name('settings.update-password');
    Route::put('/settings/preferences', [App\Http\Controllers\InventorySupervisor\SettingsController::class, 'updatePreferences'])->name('settings.update-preferences');
});

// ============================================
// CATERING INCHARGE ROUTES
// ============================================
Route::middleware(['auth', 'role:Catering Incharge'])->prefix('catering-incharge')->name('catering-incharge.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\CateringIncharge\DashboardController::class, 'index'])->name('dashboard');
    
    // Product Receipt Approvals (from Inventory Personnel)
    Route::get('/receipts/pending', [App\Http\Controllers\CateringIncharge\ProductReceiptController::class, 'pendingReceipts'])->name('receipts.pending')->middleware('permission:receive products from inventory');
    Route::post('/receipts/{receipt}/approve', [App\Http\Controllers\CateringIncharge\ProductReceiptController::class, 'approveReceipt'])->name('receipts.approve')->middleware('permission:approve product receipts');
    Route::post('/receipts/{receipt}/reject', [App\Http\Controllers\CateringIncharge\ProductReceiptController::class, 'rejectReceipt'])->name('receipts.reject')->middleware('permission:approve product receipts');
    Route::get('/receipts/stock-overview', [App\Http\Controllers\CateringIncharge\ProductReceiptController::class, 'stockOverview'])->name('receipts.stock-overview')->middleware('permission:oversee catering stock');
    
    // Request Approvals (from Catering Staff)
    Route::get('/requests/pending', [App\Http\Controllers\CateringIncharge\RequestApprovalController::class, 'pendingRequests'])->name('requests.pending')->middleware('permission:view all catering requests');
    Route::post('/requests/{requestModel}/approve', [App\Http\Controllers\CateringIncharge\RequestApprovalController::class, 'approveRequest'])->name('requests.approve')->middleware('permission:approve catering staff requests');
    Route::post('/requests/{requestModel}/reject', [App\Http\Controllers\CateringIncharge\RequestApprovalController::class, 'rejectRequest'])->name('requests.reject')->middleware('permission:approve catering staff requests');
    Route::get('/requests/approved', [App\Http\Controllers\CateringIncharge\RequestApprovalController::class, 'approvedRequests'])->name('requests.approved')->middleware('permission:view all catering requests');
    
    // Meal Approvals
    Route::get('/meals', [App\Http\Controllers\CateringIncharge\MealApprovalController::class, 'index'])->name('meals.index')->middleware('permission:approve catering staff requests');
    Route::get('/meals/{meal}', [App\Http\Controllers\CateringIncharge\MealApprovalController::class, 'show'])->name('meals.show')->middleware('permission:approve catering staff requests');
    Route::post('/meals/{meal}/approve', [App\Http\Controllers\CateringIncharge\MealApprovalController::class, 'approve'])->name('meals.approve')->middleware('permission:approve catering staff requests');
    Route::post('/meals/{meal}/reject', [App\Http\Controllers\CateringIncharge\MealApprovalController::class, 'reject'])->name('meals.reject')->middleware('permission:approve catering staff requests');
    
    // Settings
    Route::get('/settings', [App\Http\Controllers\CateringIncharge\SettingsController::class, 'index'])->name('settings');
    Route::put('/settings/profile', [App\Http\Controllers\CateringIncharge\SettingsController::class, 'updateProfile'])->name('settings.update-profile');
    Route::put('/settings/password', [App\Http\Controllers\CateringIncharge\SettingsController::class, 'updatePassword'])->name('settings.update-password');
    Route::put('/settings/preferences', [App\Http\Controllers\CateringIncharge\SettingsController::class, 'updatePreferences'])->name('settings.update-preferences');
});

// ============================================
// CATERING STAFF ROUTES
// ============================================
Route::middleware(['auth', 'role:Catering Staff'])->prefix('catering-staff')->name('catering-staff.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\CateringStaff\DashboardController::class, 'index'])->name('dashboard');
    // Requests by Catering Staff
    Route::get('/requests', [App\Http\Controllers\CateringStaff\RequestController::class, 'index'])->name('requests.index')->middleware('permission:view own catering requests');
    Route::get('/requests/create', [App\Http\Controllers\CateringStaff\RequestController::class, 'create'])->name('requests.create')->middleware('permission:create catering request');
    Route::post('/requests', [App\Http\Controllers\CateringStaff\RequestController::class, 'store'])->name('requests.store')->middleware('permission:create catering request');
    Route::get('/requests/{requestModel}', [App\Http\Controllers\CateringStaff\RequestController::class, 'show'])->name('requests.show')->middleware('permission:view own catering requests');

    // Mark received (after Catering Incharge approves)
    Route::post('/requests/{requestModel}/received', [App\Http\Controllers\CateringStaff\RequestController::class, 'markReceived'])->name('requests.received')->middleware('permission:receive approved items');

    // Send approved request to Ramp Dispatcher
    Route::post('/requests/{requestModel}/send-to-ramp', [App\Http\Controllers\CateringStaff\RequestController::class, 'sendToRamp'])->name('requests.send-to-ramp')->middleware('permission:create catering request');

    // Flight management
    Route::get('/flights/create', [App\Http\Controllers\CateringStaff\FlightController::class, 'create'])->name('flights.create')->middleware('permission:create catering request');
    Route::post('/flights', [App\Http\Controllers\CateringStaff\FlightController::class, 'store'])->name('flights.store')->middleware('permission:create catering request');

    // Record usage and return items
    Route::post('/requests/{requestModel}/record-usage', [App\Http\Controllers\CateringStaff\RequestController::class, 'recordUsage'])->name('requests.record-usage')->middleware('permission:record items used');
    Route::post('/requests/{requestModel}/return-items', [App\Http\Controllers\CateringStaff\RequestController::class, 'returnItems'])->name('requests.return-items')->middleware('permission:return unused items');
    
    // Additional product requests from Cabin Crew
    Route::get('/additional-requests', [App\Http\Controllers\CateringStaff\AdditionalRequestController::class, 'index'])->name('additional-requests.index');
    Route::post('/additional-requests/{additionalRequest}/approve', [App\Http\Controllers\CateringStaff\AdditionalRequestController::class, 'approve'])->name('additional-requests.approve');
    
    // Meal Management
    Route::resource('meals', App\Http\Controllers\CateringStaff\MealController::class);
    Route::post('/additional-requests/{additionalRequest}/reject', [App\Http\Controllers\CateringStaff\AdditionalRequestController::class, 'reject'])->name('additional-requests.reject');
    Route::post('/additional-requests/{additionalRequest}/delivered', [App\Http\Controllers\CateringStaff\AdditionalRequestController::class, 'markDelivered'])->name('additional-requests.delivered');
    
    // Settings
    Route::get('/settings', [App\Http\Controllers\CateringStaff\SettingsController::class, 'index'])->name('settings');
    Route::put('/settings/profile', [App\Http\Controllers\CateringStaff\SettingsController::class, 'updateProfile'])->name('settings.update-profile');
    Route::put('/settings/password', [App\Http\Controllers\CateringStaff\SettingsController::class, 'updatePassword'])->name('settings.update-password');
    Route::put('/settings/preferences', [App\Http\Controllers\CateringStaff\SettingsController::class, 'updatePreferences'])->name('settings.update-preferences');
});

// ============================================
// RAMP DISPATCHER ROUTES
// ============================================
Route::middleware(['auth', 'role:Ramp Dispatcher'])->prefix('ramp-dispatcher')->name('ramp-dispatcher.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\RampDispatcher\DashboardController::class, 'index'])->name('dashboard');
    Route::post('/requests/{request}/dispatch', [App\Http\Controllers\RampDispatcher\DispatchController::class, 'markDispatched'])->name('requests.dispatch');
    Route::get('/dispatched', [App\Http\Controllers\RampDispatcher\DispatchController::class, 'dispatched'])->name('dispatched');
    
    // Meal Dispatch (manual action)
    Route::post('/meals/{meal}/dispatch', [App\Http\Controllers\RampDispatcher\DispatchController::class, 'dispatchMeal'])->name('meals.dispatch');
    
    // Returns management (NEW WORKFLOW)
    Route::get('/returns', [App\Http\Controllers\RampDispatcher\ReturnController::class, 'index'])->name('returns.index');
    Route::post('/returns/{return}/receive', [App\Http\Controllers\RampDispatcher\ReturnController::class, 'receive'])->name('returns.receive');
    Route::post('/returns/bulk-receive', [App\Http\Controllers\RampDispatcher\ReturnController::class, 'bulkReceive'])->name('returns.bulk-receive');
    
    // Settings
    Route::get('/settings', [App\Http\Controllers\RampDispatcher\SettingsController::class, 'index'])->name('settings');
    Route::put('/settings/profile', [App\Http\Controllers\RampDispatcher\SettingsController::class, 'updateProfile'])->name('settings.update-profile');
    Route::put('/settings/password', [App\Http\Controllers\RampDispatcher\SettingsController::class, 'updatePassword'])->name('settings.update-password');
    Route::put('/settings/preferences', [App\Http\Controllers\RampDispatcher\SettingsController::class, 'updatePreferences'])->name('settings.update-preferences');
});

// ============================================
// SECURITY STAFF ROUTES
// ============================================
Route::middleware(['auth', 'role:Security Staff'])->prefix('security-staff')->name('security-staff.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\SecurityStaff\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/requests/awaiting-authentication', [App\Http\Controllers\SecurityStaff\RequestController::class, 'index'])->name('requests.awaiting-authentication')->middleware('permission:authenticate requests');
    Route::get('/requests/{request}', [App\Http\Controllers\SecurityStaff\RequestController::class, 'show'])->name('requests.show')->middleware('permission:authenticate requests');
    Route::post('/requests/{request}/authenticate', [App\Http\Controllers\SecurityStaff\RequestController::class, 'authenticateRequest'])->name('requests.authenticate')->middleware('permission:authenticate requests');
    
    // Meal Authentication
    Route::get('/meals', [App\Http\Controllers\SecurityStaff\MealAuthenticationController::class, 'index'])->name('meals.index')->middleware('permission:authenticate requests');
    Route::get('/meals/{meal}', [App\Http\Controllers\SecurityStaff\MealAuthenticationController::class, 'show'])->name('meals.show')->middleware('permission:authenticate requests');
    Route::post('/meals/{meal}/authenticate', [App\Http\Controllers\SecurityStaff\MealAuthenticationController::class, 'authenticate'])->name('meals.authenticate')->middleware('permission:authenticate requests');
    
    // Returns authentication (NEW WORKFLOW)
    Route::get('/returns', [App\Http\Controllers\SecurityStaff\ReturnController::class, 'index'])->name('returns.index');
    Route::post('/returns/{return}/authenticate', [App\Http\Controllers\SecurityStaff\ReturnController::class, 'authenticate'])->name('returns.authenticate');
    Route::post('/returns/{return}/reject', [App\Http\Controllers\SecurityStaff\ReturnController::class, 'reject'])->name('returns.reject');
    Route::post('/returns/bulk-authenticate', [App\Http\Controllers\SecurityStaff\ReturnController::class, 'bulkAuthenticate'])->name('returns.bulk-authenticate');
    
    // Settings
    Route::get('/settings', [App\Http\Controllers\SecurityStaff\SettingsController::class, 'index'])->name('settings');
    Route::put('/settings/profile', [App\Http\Controllers\SecurityStaff\SettingsController::class, 'updateProfile'])->name('settings.update-profile');
    Route::put('/settings/password', [App\Http\Controllers\SecurityStaff\SettingsController::class, 'updatePassword'])->name('settings.update-password');
    Route::put('/settings/preferences', [App\Http\Controllers\SecurityStaff\SettingsController::class, 'updatePreferences'])->name('settings.update-preferences');
});

// ============================================
// CABIN CREW ROUTES
// ============================================
Route::middleware(['auth', 'role:Cabin Crew'])->prefix('cabin-crew')->name('cabin-crew.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\CabinCrew\DashboardController::class, 'index'])->name('dashboard');
    
    // Mark requests as delivered
    Route::post('/requests/{request}/deliver', [App\Http\Controllers\CabinCrew\DeliveryController::class, 'markDelivered'])->name('requests.deliver');
    Route::get('/delivered', [App\Http\Controllers\CabinCrew\DeliveryController::class, 'delivered'])->name('delivered');
    
    // Comprehensive served form with usage tracking
    Route::get('/requests/{request}/served-form', [App\Http\Controllers\CabinCrew\DeliveryController::class, 'showServedForm'])->name('served.form');
    Route::post('/requests/{request}/served', [App\Http\Controllers\CabinCrew\DeliveryController::class, 'submitServed'])->name('served.submit');
    
    // Product usage management
    Route::get('/requests/{request}/products', [App\Http\Controllers\CabinCrew\ProductUsageController::class, 'viewProducts'])->name('products.view');
    Route::post('/items/{item}/mark-used', [App\Http\Controllers\CabinCrew\ProductUsageController::class, 'markAsUsed'])->name('items.mark-used');
    Route::post('/items/{item}/record-defect', [App\Http\Controllers\CabinCrew\ProductUsageController::class, 'recordDefect'])->name('items.record-defect');
    
    // Usage tracking index
    Route::get('/usage', [App\Http\Controllers\CabinCrew\ProductUsageController::class, 'index'])->name('usage.index');
    
    // Additional product requests
    Route::get('/requests/{request}/request-additional', [App\Http\Controllers\CabinCrew\ProductUsageController::class, 'requestAdditional'])->name('products.request-additional');
    Route::post('/requests/{request}/request-additional', [App\Http\Controllers\CabinCrew\ProductUsageController::class, 'storeAdditionalRequest'])->name('products.store-additional');
    
    // Returns management (NEW WORKFLOW)
    Route::get('/returns', [App\Http\Controllers\CabinCrew\ReturnController::class, 'index'])->name('returns.index');
    Route::get('/returns/create/{request}', [App\Http\Controllers\CabinCrew\ReturnController::class, 'create'])->name('returns.create');
    Route::post('/returns/{request}', [App\Http\Controllers\CabinCrew\ReturnController::class, 'store'])->name('returns.store');
    Route::get('/returns/{return}/show', [App\Http\Controllers\CabinCrew\ReturnController::class, 'show'])->name('returns.show');
    
    // Usage report
    Route::get('/requests/{request}/report', [App\Http\Controllers\CabinCrew\ProductUsageController::class, 'generateReport'])->name('products.report');
    
    // Meal reference (for better service)
    Route::get('/meals', [App\Http\Controllers\CabinCrew\MealViewController::class, 'index'])->name('meals.index');
    Route::get('/meals/{meal}', [App\Http\Controllers\CabinCrew\MealViewController::class, 'show'])->name('meals.show');
    
    // Settings
    Route::get('/settings', [App\Http\Controllers\CabinCrew\SettingsController::class, 'index'])->name('settings');
    Route::put('/settings/profile', [App\Http\Controllers\CabinCrew\SettingsController::class, 'updateProfile'])->name('settings.update-profile');
    Route::put('/settings/password', [App\Http\Controllers\CabinCrew\SettingsController::class, 'updatePassword'])->name('settings.update-password');
    Route::put('/settings/preferences', [App\Http\Controllers\CabinCrew\SettingsController::class, 'updatePreferences'])->name('settings.update-preferences');
});

// ============================================
// FLIGHT PURSER ROUTES
// ============================================
Route::middleware(['auth', 'role:Flight Purser'])->prefix('flight-purser')->name('flight-purser.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\FlightPurser\DashboardController::class, 'index'])->name('dashboard');
    
    // Load requests onto aircraft
    Route::get('/requests/{request}', [App\Http\Controllers\FlightPurser\LoadController::class, 'show'])->name('requests.show');
    Route::post('/requests/{request}/load', [App\Http\Controllers\FlightPurser\LoadController::class, 'markLoaded'])->name('requests.load');
    Route::get('/loaded', [App\Http\Controllers\FlightPurser\LoadController::class, 'loaded'])->name('loaded');
    
    // Load Meals (manual action)
    Route::get('/meals', [App\Http\Controllers\FlightPurser\MealReceiveController::class, 'index'])->name('meals.index');
    Route::get('/meals/{meal}', [App\Http\Controllers\FlightPurser\MealReceiveController::class, 'show'])->name('meals.show');
    Route::post('/meals/{meal}/receive', [App\Http\Controllers\FlightPurser\MealReceiveController::class, 'receive'])->name('meals.receive');
    
    // Settings
    Route::get('/settings', [App\Http\Controllers\FlightPurser\SettingsController::class, 'index'])->name('settings');
    Route::put('/settings/profile', [App\Http\Controllers\FlightPurser\SettingsController::class, 'updateProfile'])->name('settings.update-profile');
    Route::put('/settings/password', [App\Http\Controllers\FlightPurser\SettingsController::class, 'updatePassword'])->name('settings.update-password');
    Route::put('/settings/preferences', [App\Http\Controllers\FlightPurser\SettingsController::class, 'updatePreferences'])->name('settings.update-preferences');
});

