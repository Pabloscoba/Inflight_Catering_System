<?php
use Illuminate\Support\Str;

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Use Spatie models if available
$roleModel = '\Spatie\Permission\Models\Role';
$userModel = '\App\Models\User';

$aliases = [
    'flightops', 'flight ops', 'flight-ops', 'flight-operations-manager'
];
$canonical = 'Flight Operations Manager';

echo "Searching for roles matching aliases...\n";
$foundRoles = [];
foreach ($aliases as $a) {
    $r = $roleModel::whereRaw('LOWER(name) = ?', [Str::lower($a)])->first();
    if ($r) $foundRoles[] = $r;
}

// Also find case-insensitive matches that contain 'flight'
$others = $roleModel::where('name', 'LIKE', '%flight%')->get();
foreach ($others as $r) {
    if (!in_array($r, $foundRoles, true)) $foundRoles[] = $r;
}

if (empty($foundRoles)) {
    echo "No existing flight-related roles found.\n";
} else {
    echo "Found roles:\n";
    foreach ($foundRoles as $r) {
        echo " - [{$r->id}] {$r->name}\n";
    }
}

// Ensure canonical role exists
$canonicalRole = $roleModel::whereRaw('LOWER(name) = ?', [Str::lower($canonical)])->first();
if (!$canonicalRole) {
    echo "Creating canonical role: {$canonical}\n";
    $canonicalRole = $roleModel::create(['name' => $canonical, 'guard_name' => 'web']);
} else {
    echo "Canonical role exists: {$canonicalRole->name}\n";
}

// Find users that have any of the found roles
$usersAssigned = [];
if (!empty($foundRoles)) {
    $roleIds = array_map(function($r){ return $r->id; }, $foundRoles);
    $modelHasRoles = \DB::table('model_has_roles')->whereIn('role_id', $roleIds)->get();
    foreach ($modelHasRoles as $m) {
        $user = $userModel::find($m->model_id);
        if ($user) {
            $usersAssigned[$user->id] = $user;
        }
    }
}

if (empty($usersAssigned)) {
    echo "No users currently assigned to flight-related alias roles.\n";
} else {
    echo "Users with alias roles:\n";
    foreach ($usersAssigned as $u) {
        echo " - [{$u->id}] {$u->email} ({$u->name})\n";
    }

    // Assign canonical role to these users
    foreach ($usersAssigned as $u) {
        if (!$u->hasRole($canonical)) {
            $u->assignRole($canonicalRole->name);
            echo "Assigned canonical role to {$u->email}\n";
        } else {
            echo "User {$u->email} already has canonical role.\n";
        }
    }
}

echo "Done.\n";
