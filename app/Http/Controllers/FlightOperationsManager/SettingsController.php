<?php

namespace App\Http\Controllers\FlightOperationsManager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        return view('flight-operations-manager.settings.index');
    }
}
