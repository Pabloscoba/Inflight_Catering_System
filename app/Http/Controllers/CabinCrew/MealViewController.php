<?php

namespace App\Http\Controllers\CabinCrew;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class MealViewController extends Controller
{
    /**
     * Display all available meals for cabin crew reference
     */
    public function index(Request $request)
    {
        $query = Product::whereNotNull('meal_type')
            ->whereIn('status', ['approved', 'authenticated', 'dispatched', 'received'])
            ->with('category');

        // Filter by meal type
        if ($request->filled('meal_type')) {
            $query->where('meal_type', $request->meal_type);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by route
        if ($request->filled('route')) {
            $query->where('route', 'like', '%' . $request->route . '%');
        }

        $meals = $query->latest()->paginate(12);

        return view('cabin-crew.meals.index', compact('meals'));
    }

    /**
     * Show detailed meal information
     */
    public function show(Product $meal)
    {
        if (!$meal->meal_type) {
            abort(404, 'This is not a meal item.');
        }

        $meal->load('category');
        
        return view('cabin-crew.meals.show', compact('meal'));
    }
}
