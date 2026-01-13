<?php

if (!function_exists('getRolePrefix')) {
    /**
     * Get the route prefix for the current user based on their role.
     * This supports permission-based routing while maintaining role-specific route structures.
     * 
     * @return string The route prefix (e.g., 'admin', 'catering-staff', etc.)
     */
    function getRolePrefix()
    {
        if (!auth()->check()) {
            return '';
        }

        $user = auth()->user();

        // Check roles in order of priority
        if ($user->hasRole('Admin')) {
            return 'admin';
        }
        
        if ($user->hasAnyRole(['Flight Operations Manager', 'Flight Ops', 'flightops'])) {
            return 'flight-operations-manager';
        }
        
        if ($user->hasRole('Inventory Personnel')) {
            return 'inventory-personnel';
        }
        
        if ($user->hasRole('Inventory Supervisor')) {
            return 'inventory-supervisor';
        }
        
        if ($user->hasRole('Catering Incharge')) {
            return 'catering-incharge';
        }
        
        if ($user->hasRole('Catering Staff')) {
            return 'catering-staff';
        }
        
        if ($user->hasRole('Ramp Dispatcher')) {
            return 'ramp-dispatcher';
        }
        
        if ($user->hasRole('Security Staff')) {
            return 'security-staff';
        }
        
        if ($user->hasRole('Flight Dispatcher')) {
            return 'flight-dispatcher';
        }
        
        if ($user->hasRole('Flight Purser')) {
            return 'flight-purser';
        }
        
        if ($user->hasRole('Cabin Crew')) {
            return 'cabin-crew';
        }

        // Default to admin if no role matches
        return 'admin';
    }
}
