<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    /**
     * Display a listing of audit logs
     */
    public function index(Request $request)
    {
        $query = Activity::with(['causer', 'subject'])
            ->latest();

        // Filter by user
        if ($request->filled('user_id')) {
            $query->where('causer_id', $request->user_id);
        }

        // Filter by model type
        if ($request->filled('subject_type')) {
            $query->where('subject_type', $request->subject_type);
        }

        // Filter by event (created, updated, deleted)
        if ($request->filled('event')) {
            $query->where('event', $request->event);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $logs = $query->paginate(50);

        // Get unique users and model types for filters
        $users = \App\Models\User::orderBy('name')->get();
        $modelTypes = Activity::select('subject_type')
            ->distinct()
            ->whereNotNull('subject_type')
            ->pluck('subject_type')
            ->map(function ($type) {
                return [
                    'value' => $type,
                    'label' => class_basename($type)
                ];
            });

        return view('admin.logs.index', compact('logs', 'users', 'modelTypes'));
    }

    /**
     * Display the specified audit log
     */
    public function show(Activity $log)
    {
        $log->load(['causer', 'subject']);
        return view('admin.logs.show', compact('log'));
    }

    /**
     * Clear old audit logs
     */
    public function clear(Request $request)
    {
        $request->validate([
            'days' => 'required|integer|min:1|max:365'
        ]);

        $date = now()->subDays($request->days);
        $count = Activity::where('created_at', '<', $date)->delete();

        return redirect()->route('admin.logs.index')
            ->with('success', "Deleted {$count} audit log(s) older than {$request->days} days.");
    }
}
