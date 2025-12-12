<?php

namespace App\Http\Controllers\CateringStaff;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MealController extends Controller
{
    /**
     * Display a listing of meals
     */
    public function index(Request $request)
    {
        $query = Product::with('category');
        
        // Filter by meal type
        if ($request->filled('meal_type')) {
            $query->where('meal_type', $request->meal_type);
        }
        
        // Filter by season
        if ($request->filled('season')) {
            $query->where('season', $request->season);
        }
        
        // Filter by route
        if ($request->filled('route')) {
            $query->where('route', $request->route);
        }
        
        // Filter special meals
        if ($request->filled('special')) {
            $query->where('is_special_meal', true);
        }
        
        // Filter by active menu (effective dates)
        if ($request->filled('active_menu')) {
            $today = now()->toDateString();
            $query->where(function($q) use ($today) {
                $q->whereDate('effective_start_date', '<=', $today)
                  ->whereDate('effective_end_date', '>=', $today);
            });
        }
        
        $meals = $query->latest()->paginate(15);
        
        return view('catering-staff.meals.index', compact('meals'));
    }

    /**
     * Show the form for creating a new meal
     */
    public function create()
    {
        $categories = Category::all();
        return view('catering-staff.meals.create', compact('categories'));
    }

    /**
     * Store a newly created meal
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|unique:products,sku',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'meal_type' => 'required|in:breakfast,lunch,dinner,snack,VIP_meal,special_meal',
            'ingredients' => 'nullable|string',
            'allergen_info' => 'nullable|string',
            'portion_size' => 'nullable|string',
            'season' => 'nullable|string',
            'route' => 'nullable|string',
            'is_special_meal' => 'boolean',
            'special_requirements' => 'nullable|string',
            'menu_version' => 'nullable|string',
            'effective_start_date' => 'nullable|date',
            'effective_end_date' => 'nullable|date|after_or_equal:effective_start_date',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'preparation_instructions' => 'nullable|string',
            'nutritional_info' => 'nullable|string',
            'unit_price' => 'nullable|numeric|min:0',
            'quantity_in_stock' => 'nullable|integer|min:0',
            'reorder_level' => 'nullable|integer|min:0',
            'unit' => 'nullable|string',
        ]);
        
        // Set default values for inventory fields
        $validated['unit_price'] = $validated['unit_price'] ?? 0;
        $validated['quantity_in_stock'] = $validated['quantity_in_stock'] ?? 0;
        $validated['reorder_level'] = $validated['reorder_level'] ?? 10;
        $validated['unit'] = $validated['unit'] ?? 'serving';
        
        // Handle photo upload
        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('meals', 'public');
        }
        
        $validated['status'] = 'pending';
        
        Product::create($validated);
        
        activity()
            ->causedBy(auth()->user())
            ->log('Created new meal: ' . $validated['name']);
        
        return redirect()->route('catering-staff.meals.index')
            ->with('success', 'Meal created successfully and sent for approval.');
    }

    /**
     * Display the specified meal
     */
    public function show(Product $meal)
    {
        return view('catering-staff.meals.show', compact('meal'));
    }

    /**
     * Show the form for editing the specified meal
     */
    public function edit(Product $meal)
    {
        $categories = Category::all();
        return view('catering-staff.meals.edit', compact('meal', 'categories'));
    }

    /**
     * Update the specified meal
     */
    public function update(Request $request, Product $meal)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'meal_type' => 'required|in:breakfast,lunch,dinner,snack,VIP_meal,special_meal',
            'ingredients' => 'nullable|string',
            'allergen_info' => 'nullable|string',
            'portion_size' => 'nullable|string',
            'season' => 'nullable|string',
            'route' => 'nullable|string',
            'is_special_meal' => 'boolean',
            'special_requirements' => 'nullable|string',
            'menu_version' => 'nullable|string',
            'effective_start_date' => 'nullable|date',
            'effective_end_date' => 'nullable|date|after_or_equal:effective_start_date',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'preparation_instructions' => 'nullable|string',
            'nutritional_info' => 'nullable|string',
            'unit_price' => 'nullable|numeric|min:0',
            'quantity_in_stock' => 'nullable|integer|min:0',
            'reorder_level' => 'nullable|integer|min:0',
            'unit' => 'nullable|string',
        ]);
        
        // Preserve existing values if not provided
        if (!isset($validated['unit_price'])) $validated['unit_price'] = $meal->unit_price ?? 0;
        if (!isset($validated['quantity_in_stock'])) $validated['quantity_in_stock'] = $meal->quantity_in_stock ?? 0;
        if (!isset($validated['reorder_level'])) $validated['reorder_level'] = $meal->reorder_level ?? 10;
        if (!isset($validated['unit'])) $validated['unit'] = $meal->unit ?? 'serving';
        
        // Handle photo upload
        if ($request->hasFile('photo')) {
            // Delete old photo
            if ($meal->photo) {
                Storage::disk('public')->delete($meal->photo);
            }
            $validated['photo'] = $request->file('photo')->store('meals', 'public');
        }
        
        // Set status to pending for approval workflow (same as create)
        $validated['status'] = 'pending';
        
        $meal->update($validated);
        
        activity()
            ->causedBy(auth()->user())
            ->performedOn($meal)
            ->log('Updated meal: ' . $meal->name);
        
        return redirect()->route('catering-staff.meals.index')
            ->with('success', 'Meal updated successfully and sent for approval.');
    }

    /**
     * Remove the specified meal
     */
    public function destroy(Product $meal)
    {
        if ($meal->photo) {
            Storage::disk('public')->delete($meal->photo);
        }
        
        activity()
            ->causedBy(auth()->user())
            ->performedOn($meal)
            ->log('Deleted meal: ' . $meal->name);
        
        $meal->delete();
        
        return redirect()->route('catering-staff.meals.index')
            ->with('success', 'Meal deleted successfully.');
    }
}
