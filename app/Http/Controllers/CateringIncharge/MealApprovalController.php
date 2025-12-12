<?php

namespace App\Http\Controllers\CateringIncharge;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class MealApprovalController extends Controller
{
    /**
     * Display pending meals awaiting approval
     */
    public function index()
    {
        $meals = Product::whereNotNull('meal_type')
            ->where('status', 'pending')
            ->with(['category', 'requester'])
            ->latest()
            ->paginate(15);

        return view('catering-incharge.meals.index', compact('meals'));
    }

    /**
     * Show meal details for approval
     */
    public function show(Product $meal)
    {
        $meal->load(['category', 'approvedBy', 'authenticatedBy', 'dispatchedBy', 'deliveredBy']);
        return view('catering-incharge.meals.show', compact('meal'));
    }

    /**
     * Approve a meal
     */
    public function approve(Request $request, Product $meal)
    {
        if ($meal->status !== 'pending') {
            return back()->with('error', 'Only pending meals can be approved.');
        }

        $meal->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
            'rejection_reason' => null,
        ]);

        activity()
            ->causedBy(auth()->user())
            ->performedOn($meal)
            ->log('Approved meal: ' . $meal->name);

        return redirect()->route('catering-incharge.meals.index')
            ->with('success', 'Meal approved successfully. Sent to Security for authentication.');
    }

    /**
     * Reject a meal
     */
    public function reject(Request $request, Product $meal)
    {
        if ($meal->status !== 'pending') {
            return back()->with('error', 'Only pending meals can be rejected.');
        }

        $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        $meal->update([
            'status' => 'rejected',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
            'rejection_reason' => $request->rejection_reason,
        ]);

        activity()
            ->causedBy(auth()->user())
            ->performedOn($meal)
            ->log('Rejected meal: ' . $meal->name . ' - Reason: ' . $request->rejection_reason);

        return redirect()->route('catering-incharge.meals.index')
            ->with('success', 'Meal rejected successfully.');
    }
}
