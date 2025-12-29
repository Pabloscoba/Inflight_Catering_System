<?php

namespace App\Observers;

use Spatie\Activitylog\Models\Activity;

class UserObserver
{
    public function created($user)
    {
        $activity = new Activity();
        $activity->log_name = 'user-management';
        $activity->description = "New user created: {$user->name}";
        $activity->subject_type = get_class($user);
        $activity->subject_id = $user->id;
        if (auth()->check()) {
            $activity->causer_type = get_class(auth()->user());
            $activity->causer_id = auth()->id();
        }
        $activity->properties = json_encode([
            'user_name' => $user->name,
            'user_email' => $user->email,
        ]);
        $activity->save();
    }

    public function updated($user)
    {
        $activity = new Activity();
        $activity->log_name = 'user-management';
        $activity->description = "User updated: {$user->name}";
        $activity->subject_type = get_class($user);
        $activity->subject_id = $user->id;
        if (auth()->check()) {
            $activity->causer_type = get_class(auth()->user());
            $activity->causer_id = auth()->id();
        }
        $activity->properties = json_encode([
            'user_name' => $user->name,
            'user_email' => $user->email,
            'changes' => $user->getChanges(),
        ]);
        $activity->save();
    }

    public function deleted($user)
    {
        $activity = new Activity();
        $activity->log_name = 'user-management';
        $activity->description = "User deleted: {$user->name}";
        $activity->subject_type = get_class($user);
        $activity->subject_id = $user->id;
        if (auth()->check()) {
            $activity->causer_type = get_class(auth()->user());
            $activity->causer_id = auth()->id();
        }
        $activity->properties = json_encode([
            'user_name' => $user->name,
            'user_email' => $user->email,
        ]);
        $activity->save();
    }
}
