<?php

namespace App\Http\Controllers\SecurityStaff;

use App\Http\Controllers\Controller;
use App\Models\Request as RequestModel;
use App\Notifications\RequestAuthenticatedNotification;
use App\Notifications\RequestLoadedNotification;
use Illuminate\Http\Request;

class RequestController extends Controller
{
    /**
     * Security authenticates request (CORRECTED WORKFLOW - send to Ramp Dispatcher)
     */
    public function authenticateRequest(RequestModel $request)
    {
        // Check if awaiting security authentication
        if ($request->status !== 'catering_final_approved') {
            return back()->with('error', 'This request is not awaiting security authentication.');
        }

        \Illuminate\Support\Facades\DB::transaction(function () use ($request) {
            // Authenticate and send to Ramp Dispatcher
            $request->update([
                'status' => 'security_authenticated',
                'security_dispatched_by' => auth()->id(),
                'security_dispatched_at' => now(),
            ]);
            
            // Notify Ramp Dispatcher
            $rampDispatchers = \App\Models\User::role('Ramp Dispatcher')->get();
            foreach ($rampDispatchers as $dispatcher) {
                $dispatcher->notify(new RequestAuthenticatedNotification($request));
            }
        });

        return redirect()->route('security-staff.dashboard')->with('success', 'Request authenticated and forwarded to Ramp Dispatcher.');
    }

    // List requests awaiting security authentication
    public function index()
    {
        // Show requests awaiting security authentication (CORRECTED WORKFLOW)
        $requests = RequestModel::with(['flight','requester','items.product'])
            ->where('status', 'catering_final_approved')
            ->orderBy('created_at', 'asc')
            ->paginate(20);

        return view('security-staff.requests.awaiting-authentication', compact('requests'));
    }

    // View request details for security review
    public function show(RequestModel $request)
    {
        $request->load(['flight', 'requester', 'items.product.category', 'approver']);
        
        return view('security-staff.requests.show', compact('request'));
    }
}
