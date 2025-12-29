<?php

namespace App\Observers;

use Spatie\Activitylog\Models\Activity;

class RequestObserver
{
    public function created($request)
    {
        $activity = new Activity();
        $activity->log_name = 'request-management';
        $activity->description = "New request created: REQ-{$request->id}";
        $activity->subject_type = get_class($request);
        $activity->subject_id = $request->id;
        if (auth()->check()) {
            $activity->causer_type = get_class(auth()->user());
            $activity->causer_id = auth()->id();
        }
        $activity->properties = json_encode([
            'request_id' => $request->id,
            'flight_id' => $request->flight_id,
            'status' => $request->status,
        ]);
        $activity->save();
    }

    public function updated($request)
    {
        $activity = new Activity();
        $activity->log_name = 'request-management';
        $description = "Request REQ-{$request->id} updated";
        
        // More descriptive messages based on status change
        if ($request->wasChanged('status')) {
            $description = "Request REQ-{$request->id} status changed to {$request->status}";
        }
        
        $activity->description = $description;
        $activity->subject_type = get_class($request);
        $activity->subject_id = $request->id;
        if (auth()->check()) {
            $activity->causer_type = get_class(auth()->user());
            $activity->causer_id = auth()->id();
        }
        $activity->properties = json_encode([
            'request_id' => $request->id,
            'status' => $request->status,
            'changes' => $request->getChanges(),
        ]);
        $activity->save();
    }
}
