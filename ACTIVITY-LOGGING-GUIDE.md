# Activity Logging Implementation Guide

## Overview
The Activity Logging system is now fully set up using Spatie Laravel Activity Log package. This guide shows how to automatically log user activities across the system.

## ✅ What's Already Set Up

### 1. Package Installation
- Spatie Laravel Activity Log ^4.10 installed
- Configuration published at `config/activitylog.php`
- Database table `activity_log` exists

### 2. Admin Interface
- **Activity Logs Dashboard**: `/admin/activity-logs`
- **Routes Created**:
  - `GET /admin/activity-logs` - List all activities with filters
  - `GET /admin/activity-logs/{activity}` - View activity details
  - `GET /admin/activity-logs-export` - Export to CSV
  - `DELETE /admin/activity-logs-delete-old` - Delete old logs

### 3. Features Available
✅ Advanced filtering (user, event type, date range, subject type, log name)
✅ Real-time statistics (total, today, this week, this month)
✅ CSV export with all filters applied
✅ Bulk delete old logs
✅ Detailed activity view with old/new values comparison
✅ User and role information tracking
✅ IP address and user agent capture

## 📝 How to Add Activity Logging to Controllers

### Basic Usage

#### 1. Log Model Creation
```php
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Product extends Model
{
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'quantity', 'price', 'category_id'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
```

#### 2. Manual Logging in Controllers

**Example: Request Creation (Catering Staff)**
```php
use Spatie\Activitylog\Contracts\Activity;

public function store(Request $request)
{
    $validated = $request->validate([...]);
    
    $cateringRequest = Request::create($validated);
    
    // Log the activity
    activity('request')
        ->causedBy(auth()->user())
        ->performedOn($cateringRequest)
        ->withProperties([
            'flight_number' => $cateringRequest->flight->flight_number,
            'items_count' => $cateringRequest->items->count(),
            'request_type' => $cateringRequest->request_type,
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ])
        ->log('Created new ' . $cateringRequest->request_type . ' request');
    
    return redirect()->route('catering-staff.requests.index')
        ->with('success', 'Request created successfully');
}
```

**Example: Request Approval (Catering Incharge)**
```php
public function approve(Request $request, $id)
{
    $cateringRequest = Request::findOrFail($id);
    
    $cateringRequest->update([
        'status' => 'approved',
        'approved_by' => auth()->id(),
        'approved_at' => now(),
    ]);
    
    // Log approval
    activity('request')
        ->causedBy(auth()->user())
        ->performedOn($cateringRequest)
        ->event('approved')
        ->withProperties([
            'old_status' => 'pending',
            'new_status' => 'approved',
            'flight_number' => $cateringRequest->flight->flight_number,
            'ip' => request()->ip(),
        ])
        ->log('Approved request for flight ' . $cateringRequest->flight->flight_number);
    
    return redirect()->back()->with('success', 'Request approved');
}
```

**Example: Stock Movement (Inventory Personnel)**
```php
public function storeIncoming(Request $request)
{
    $validated = $request->validate([...]);
    
    $movement = StockMovement::create($validated);
    
    // Update product quantity
    $product = Product::find($validated['product_id']);
    $product->increment('quantity', $validated['quantity']);
    
    // Log the activity
    activity('stock_movement')
        ->causedBy(auth()->user())
        ->performedOn($movement)
        ->withProperties([
            'product_name' => $product->name,
            'quantity' => $validated['quantity'],
            'movement_type' => 'incoming',
            'old_quantity' => $product->quantity - $validated['quantity'],
            'new_quantity' => $product->quantity,
            'ip' => request()->ip(),
        ])
        ->log('Added ' . $validated['quantity'] . ' units of ' . $product->name . ' to inventory');
    
    return redirect()->route('inventory-personnel.stock-movements.index')
        ->with('success', 'Incoming stock recorded');
}
```

**Example: Product Loading (Ramp Dispatcher)**
```php
public function markAsLoaded($id)
{
    $request = Request::findOrFail($id);
    
    $request->update([
        'status' => 'loaded',
        'loaded_by' => auth()->id(),
        'loaded_at' => now(),
    ]);
    
    // Log loading activity
    activity('request')
        ->causedBy(auth()->user())
        ->performedOn($request)
        ->event('loaded')
        ->withProperties([
            'flight_number' => $request->flight->flight_number,
            'departure_time' => $request->flight->departure_time,
            'items_count' => $request->items->count(),
            'ip' => request()->ip(),
        ])
        ->log('Loaded supplies onto aircraft for flight ' . $request->flight->flight_number);
    
    return redirect()->back()->with('success', 'Request marked as loaded');
}
```

**Example: Product Usage (Cabin Crew)**
```php
public function recordUsage(Request $request)
{
    $validated = $request->validate([
        'request_id' => 'required|exists:requests,id',
        'product_id' => 'required|exists:products,id',
        'quantity_used' => 'required|integer|min:1',
    ]);
    
    $usage = ProductUsage::create($validated + [
        'recorded_by' => auth()->id(),
        'recorded_at' => now(),
    ]);
    
    // Log usage
    activity('product_usage')
        ->causedBy(auth()->user())
        ->performedOn($usage)
        ->withProperties([
            'product_name' => $usage->product->name,
            'quantity_used' => $validated['quantity_used'],
            'flight_number' => $usage->request->flight->flight_number,
            'ip' => request()->ip(),
        ])
        ->log('Recorded usage of ' . $validated['quantity_used'] . ' units of ' . $usage->product->name);
    
    return redirect()->back()->with('success', 'Usage recorded');
}
```

**Example: Security Inspection (Security Staff)**
```php
public function inspectRequest($id)
{
    $request = Request::findOrFail($id);
    
    $request->update([
        'status' => 'authenticated',
        'inspected_by' => auth()->id(),
        'inspected_at' => now(),
    ]);
    
    // Log inspection
    activity('request')
        ->causedBy(auth()->user())
        ->performedOn($request)
        ->event('inspected')
        ->withProperties([
            'flight_number' => $request->flight->flight_number,
            'previous_status' => 'dispatched',
            'new_status' => 'authenticated',
            'items_inspected' => $request->items->count(),
            'ip' => request()->ip(),
        ])
        ->log('Inspected and authenticated request for flight ' . $request->flight->flight_number);
    
    return redirect()->back()->with('success', 'Request authenticated');
}
```

### Event Types
Use these standard event types:
- `created` - New records
- `updated` - Modified records
- `deleted` - Deleted records
- `approved` - Approvals
- `rejected` - Rejections
- `dispatched` - Dispatch actions
- `loaded` - Loading onto aircraft
- `delivered` - Delivery confirmations
- `served` - Service completion
- `inspected` - Security inspections
- `authenticated` - Authentication actions
- `login` - User logins
- `logout` - User logouts

### Log Names (Categories)
Organize logs by category:
- `request` - Request operations
- `product` - Product management
- `stock_movement` - Stock movements
- `flight` - Flight operations
- `user` - User management
- `product_usage` - Product usage tracking
- `meal` - Meal management
- `settings` - System settings changes

## 🔧 Configuration

### Edit `config/activitylog.php`

```php
return [
    // Enable/disable logging
    'enabled' => env('ACTIVITY_LOG_ENABLED', true),

    // Database connection
    'database_connection' => env('ACTIVITY_LOG_DB_CONNECTION'),

    // Table name
    'table_name' => 'activity_log',

    // Subject returns null
    'subject_returns_soft_deleted_models' => false,

    // Log all events by default
    'default_log_name' => 'default',

    // Automatically clean old logs
    'delete_records_older_than_days' => 365,

    // Which properties to log
    'submit_empty_logs' => false,
];
```

## 📊 Accessing Logs as Admin

### View All Activities
Navigate to: **Admin → Settings → Activity Logs**

### Filter Logs
- By User (dropdown)
- By Event Type (created, updated, deleted, etc.)
- By Log Name (request, product, stock_movement, etc.)
- By Subject Type (Request, Product, User, etc.)
- By Date Range (from-to)

### Export Logs
Click "Export Logs" button to download CSV with all current filters applied.

### Delete Old Logs
Click "Delete Old Logs" button and specify number of days (default: 90).

## 🎯 Next Steps

### Immediate Integration Tasks
1. **Add logging to Catering Staff controllers**:
   - `CateringStaffController::store()` - Request creation
   - `MealController::store()` - Meal creation
   - `MealController::update()` - Meal updates

2. **Add logging to Catering Incharge controllers**:
   - `CateringInchargeController::approve()` - Request approval
   - `CateringInchargeController::reject()` - Request rejection

3. **Add logging to Inventory Personnel controllers**:
   - `StockMovementController::storeIncoming()` - Stock received
   - `StockMovementController::storeIssue()` - Stock issued
   - `StockMovementController::storeReturns()` - Returns processed

4. **Add logging to Ramp Dispatcher controllers**:
   - `RampDispatcherController::dispatch()` - Request dispatch
   - `LoadController::markAsLoaded()` - Loading confirmation

5. **Add logging to Security Staff controllers**:
   - `SecurityStaffController::authenticate()` - Security inspection

6. **Add logging to Cabin Crew controllers**:
   - `ProductUsageController::recordUsage()` - Product usage
   - `ProductUsageController::recordReturns()` - Returns recording
   - `DeliveryController::markAsDelivered()` - Delivery confirmation

7. **Add logging to Flight Purser controllers**:
   - `FlightPurserController::markAsServed()` - Service completion

### Optional Enhancements
- Add activity log widget to admin dashboard
- Create role-specific activity views (show only their actions)
- Add real-time notifications for critical activities
- Implement activity log retention policies
- Add activity log search functionality

## 📖 Documentation Links
- [Spatie Activity Log Docs](https://spatie.be/docs/laravel-activitylog)
- Package Version: ^4.10
- Laravel Version: 11.x

---

**Created**: {{ date('Y-m-d') }}
**Status**: ✅ Ready for Integration
**Next Action**: Start adding activity() calls to existing controllers
