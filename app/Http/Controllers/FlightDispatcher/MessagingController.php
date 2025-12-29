<?php

namespace App\Http\Controllers\FlightDispatcher;

use App\Http\Controllers\Controller;
use App\Models\RequestMessage;
use App\Models\Request as RequestModel;
use App\Models\User;
use Illuminate\Http\Request;

class MessagingController extends Controller
{
    /**
     * Show all messages
     */
    public function index(Request $request)
    {
        $query = RequestMessage::with(['request.flight', 'sender'])
            ->forRole('Flight Dispatcher');

        // Filter by status
        if ($request->has('filter') && $request->filter === 'unread') {
            $query->unread();
        }

        // Filter by sender role
        if ($request->has('sender_role') && $request->sender_role !== 'all') {
            $query->where('sender_role', $request->sender_role);
        }

        $messages = $query->latest()->paginate(20);

        return view('flight-dispatcher.messages.index', compact('messages'));
    }

    /**
     * Show messages for a specific request
     */
    public function showRequest(RequestModel $requestModel)
    {
        $requestModel->load(['flight', 'requester', 'items.product']);

        $messages = RequestMessage::where('request_id', $requestModel->id)
            ->with(['sender'])
            ->latest()
            ->get();

        return view('flight-dispatcher.messages.show-request', compact('requestModel', 'messages'));
    }

    /**
     * Send a message
     */
    public function send(Request $request)
    {
        $validated = $request->validate([
            'request_id' => 'required|exists:requests,id',
            'recipient_role' => 'required|in:Cabin Crew,Ramp Dispatcher,Catering Staff,Catering Incharge,Flight Purser',
            'message' => 'required|string|max:1000',
            'message_type' => 'nullable|in:general,urgent,confirmation,query',
        ]);

        RequestMessage::create([
            'request_id' => $validated['request_id'],
            'sender_id' => auth()->id(),
            'sender_role' => 'Flight Dispatcher',
            'recipient_role' => $validated['recipient_role'],
            'message' => $validated['message'],
            'message_type' => $validated['message_type'] ?? 'general',
        ]);

        // Notify recipients
        $recipients = User::role($validated['recipient_role'])->get();
        foreach ($recipients as $recipient) {
            // Send notification (you can add notification logic here)
        }

        return back()->with('success', 'Message sent successfully to ' . $validated['recipient_role']);
    }

    /**
     * Mark message as read
     */
    public function markAsRead(RequestMessage $message)
    {
        if ($message->recipient_role === 'Flight Dispatcher') {
            $message->markAsRead();
        }

        return back()->with('success', 'Message marked as read.');
    }

    /**
     * Mark all messages as read
     */
    public function markAllAsRead()
    {
        RequestMessage::forRole('Flight Dispatcher')
            ->unread()
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

        return back()->with('success', 'All messages marked as read.');
    }

    /**
     * Add operational notes to request
     */
    public function addNote(Request $request, RequestModel $requestModel)
    {
        $validated = $request->validate([
            'note' => 'required|string|max:1000',
        ]);

        // Add note to request
        $currentNotes = $requestModel->notes ?? '';
        $timestamp = now()->format('Y-m-d H:i:s');
        $userName = auth()->user()->name;
        $newNote = "\n\n[{$timestamp}] Flight Dispatcher ({$userName}):\n{$validated['note']}";
        
        $requestModel->update([
            'notes' => $currentNotes . $newNote,
        ]);

        return back()->with('success', 'Operational note added successfully.');
    }

    /**
     * Send delay report
     */
    public function sendDelayReport(Request $request, RequestModel $requestModel)
    {
        $validated = $request->validate([
            'delay_reason' => 'required|string|max:1000',
            'estimated_delay_minutes' => 'nullable|integer|min:0',
            'notify_roles' => 'required|array',
            'notify_roles.*' => 'in:Cabin Crew,Ramp Dispatcher,Catering Staff,Catering Incharge,Flight Purser',
        ]);

        $delayMessage = "⚠️ DELAY REPORT\n";
        $delayMessage .= "Reason: {$validated['delay_reason']}\n";
        if (isset($validated['estimated_delay_minutes'])) {
            $delayMessage .= "Estimated Delay: {$validated['estimated_delay_minutes']} minutes\n";
        }
        $delayMessage .= "Reported by: " . auth()->user()->name . "\n";
        $delayMessage .= "Time: " . now()->format('Y-m-d H:i:s');

        // Send to all selected roles
        foreach ($validated['notify_roles'] as $role) {
            RequestMessage::create([
                'request_id' => $requestModel->id,
                'sender_id' => auth()->id(),
                'sender_role' => 'Flight Dispatcher',
                'recipient_role' => $role,
                'message' => $delayMessage,
                'message_type' => 'urgent',
            ]);
        }

        return back()->with('success', 'Delay report sent to selected teams.');
    }
}
