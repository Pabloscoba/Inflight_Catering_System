<?php

namespace App\Http\Controllers\SecurityStaff;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class MealAuthenticationController extends Controller
{
    /**
     * Display approved meals awaiting authentication
     */
    public function index()
    {
        $meals = Product::whereNotNull('meal_type')
            ->where('status', 'approved')
            ->with(['category', 'approvedBy'])
            ->latest('approved_at')
            ->paginate(15);

        return view('security-staff.meals.index', compact('meals'));
    }

    /**
     * Show meal details for authentication
     */
    public function show(Product $meal)
    {
        $meal->load(['category', 'approvedBy']);
        return view('security-staff.meals.show', compact('meal'));
    }

    /**
     * Authenticate a meal
     */
    public function authenticate(Request $request, Product $meal)
    {
        if ($meal->status !== 'approved') {
            return back()->with('error', 'Only approved meals can be authenticated.');
        }

        $meal->update([
            'status' => 'authenticated',
            'authenticated_by' => auth()->id(),
            'authenticated_at' => now(),
        ]);

        activity()
            ->causedBy(auth()->user())
            ->performedOn($meal)
            ->log('Authenticated meal: ' . $meal->name);

        return redirect()->route('security-staff.meals.index')
            ->with('success', 'Meal authenticated successfully. Sent to Ramp Dispatcher for dispatch.');
    }
}
