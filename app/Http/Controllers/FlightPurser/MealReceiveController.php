<?php

namespace App\Http\Controllers\FlightPurser;

use App\Http\Controllers\Controller;
use App\Models\Meal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MealReceiveController extends Controller
{
    /**
     * Display a listing of meals ready to be received
     */
    public function index()
    {
        $meals = Meal::where('status', 'dispatched')
            ->orWhere('status', 'ready_for_loading')
            ->with(['createdBy', 'category'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('flight-purser.meals.index', compact('meals'));
    }

    /**
     * Display the specified meal
     */
    public function show(Meal $meal)
    {
        $meal->load(['createdBy', 'category', 'ingredients']);
        
        return view('flight-purser.meals.show', compact('meal'));
    }

    /**
     * Mark meal as received by Flight Purser
     */
    public function receive(Request $request, Meal $meal)
    {
        $request->validate([
            'received_quantity' => 'required|integer|min:1|max:' . $meal->quantity,
            'notes' => 'nullable|string|max:500',
            'condition' => 'required|in:good,damaged,incomplete',
        ]);

        DB::beginTransaction();
        try {
            // Update meal status
            $meal->update([
                'status' => 'loaded',
                'received_by' => auth()->id(),
                'received_at' => now(),
                'received_quantity' => $request->received_quantity,
                'receive_condition' => $request->condition,
                'receive_notes' => $request->notes,
            ]);

            // Log the activity
            activity()
                ->performedOn($meal)
                ->causedBy(auth()->user())
                ->withProperties([
                    'received_quantity' => $request->received_quantity,
                    'condition' => $request->condition,
                    'notes' => $request->notes,
                ])
                ->log('Meal received and loaded onto aircraft by Flight Purser');

            DB::commit();

            return redirect()
                ->route('flight-purser.meals.index')
                ->with('success', 'Meal successfully received and loaded onto aircraft');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()
                ->with('error', 'Failed to receive meal: ' . $e->getMessage())
                ->withInput();
        }
    }
}
