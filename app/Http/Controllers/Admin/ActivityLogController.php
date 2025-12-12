<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;
use App\Models\User;

class ActivityLogController extends Controller
{
    /**
     * Display activity logs
     */
    public function index(Request $request)
    {
        $query = Activity::with(['causer', 'subject'])
            ->latest();
        
        // Filter by user/role
        if ($request->filled('user_id')) {
            $query->where('causer_id', $request->user_id);
        }
        
        // Filter by event type
        if ($request->filled('event')) {
            $query->where('event', $request->event);
        }
        
        // Filter by log name
        if ($request->filled('log_name')) {
            $query->where('log_name', $request->log_name);
        }
        
        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        // Filter by subject type (model)
        if ($request->filled('subject_type')) {
            $query->where('subject_type', $request->subject_type);
        }
        
        $activities = $query->paginate(50);
        
        // Get all users for filter dropdown
        $users = User::with('roles')->orderBy('name')->get();
        
        // Get unique event types
        $eventTypes = Activity::select('event')->distinct()->pluck('event');
        
        // Get unique log names
        $logNames = Activity::select('log_name')->distinct()->pluck('log_name');
        
        // Get unique subject types
        $subjectTypes = Activity::select('subject_type')
            ->distinct()
            ->whereNotNull('subject_type')
            ->pluck('subject_type')
            ->map(function($type) {
                return class_basename($type);
            });
        
        // Statistics
        $stats = [
            'total_activities' => Activity::count(),
            'today_activities' => Activity::whereDate('created_at', today())->count(),
            'this_week' => Activity::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'this_month' => Activity::whereMonth('created_at', now()->month)->count(),
        ];
        
        return view('admin.activity-logs.index', compact(
            'activities', 
            'users', 
            'eventTypes', 
            'logNames', 
            'subjectTypes',
            'stats'
        ));
    }
    
    /**
     * Show detailed activity log
     */
    public function show(Activity $activity)
    {
        $activity->load(['causer', 'subject']);
        
        return view('admin.activity-logs.show', compact('activity'));
    }
    
    /**
     * Delete old activity logs
     */
    public function deleteOld(Request $request)
    {
        $request->validate([
            'days' => 'required|integer|min:1|max:365'
        ]);
        
        $count = Activity::where('created_at', '<', now()->subDays($request->days))->delete();
        
        activity()
            ->causedBy(auth()->user())
            ->log("Deleted {$count} activity logs older than {$request->days} days");
        
        return redirect()->route('admin.activity-logs.index')
            ->with('success', "Successfully deleted {$count} old activity logs.");
    }
    
    /**
     * Export activity logs
     */
    public function export(Request $request)
    {
        $query = Activity::with(['causer', 'subject'])->latest();
        
        // Apply same filters as index
        if ($request->filled('user_id')) {
            $query->where('causer_id', $request->user_id);
        }
        
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        $activities = $query->get();
        
        $filename = 'activity_logs_' . now()->format('Y-m-d_His') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}",
        ];
        
        $callback = function() use ($activities) {
            $file = fopen('php://output', 'w');
            
            // Header row
            fputcsv($file, [
                'ID',
                'Date/Time',
                'User',
                'Role',
                'Event',
                'Description',
                'Subject Type',
                'Subject ID',
                'IP Address',
            ]);
            
            // Data rows
            foreach ($activities as $activity) {
                fputcsv($file, [
                    $activity->id,
                    $activity->created_at->format('Y-m-d H:i:s'),
                    $activity->causer?->name ?? 'System',
                    $activity->causer?->roles->pluck('name')->implode(', ') ?? 'N/A',
                    $activity->event ?? 'N/A',
                    $activity->description,
                    class_basename($activity->subject_type ?? 'N/A'),
                    $activity->subject_id ?? 'N/A',
                    $activity->properties['ip'] ?? 'N/A',
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
}
