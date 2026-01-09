<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LicensingController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\PermitTypeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ModuleController;

// Guest Routes (non-authenticated users only)
Route::middleware('guest')->group(function () {
    Route::get('/', [AuthController::class, 'showLoginForm'])->name('auth.login');
    Route::post('/login', [AuthController::class, 'login'])->name('auth.login.post');
    Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('auth.register');
    Route::post('/register', [AuthController::class, 'register'])->name('auth.register.post');
});

// Logout route (must be authenticated)
Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout')->middleware('auth.user');


// Protected Admin Routes (authenticated users only)
Route::prefix('admin')->middleware('auth.user')->group(function() {
    Route::controller(LicensingController::class)->group(function () {
        
    });

    Route::view('dashboard', 'files.dashboard')->name('admin.dashboard');
    Route::view('licensing', 'files.licensing')->name('admin.licensing');
    Route::view('add-new-license-user', 'files.add-new-license-user')->name('admin.add-new-license-user');
    Route::view('settings', 'files.settings')->name('admin.settings');

    // Roles CRUD
    Route::resource('roles', RoleController::class)->names('admin.roles');

    // Permissions CRUD
    Route::resource('permissions', PermissionController::class)->names('admin.permissions');

    // Permit Types CRUD
    Route::resource('permit-types', PermitTypeController::class)->names('admin.permit-types');
    Route::post('permit-types/{permitType}/add-sub-type', [PermitTypeController::class, 'addSubType'])->name('admin.permit-types.add-sub-type');
    Route::delete('permit-types/{permitType}/remove-sub-type', [PermitTypeController::class, 'removeSubType'])->name('admin.permit-types.remove-sub-type');

    // User Management CRUD
    Route::resource('users', UserController::class)->names('admin.users');
    Route::patch('users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('admin.users.toggle-status');

    // Modules CRUD
    Route::resource('modules', ModuleController::class)->names('admin.modules');
    Route::post('modules/reorder', [ModuleController::class, 'reorder'])->name('admin.modules.reorder');
});


// Admin Dashboard Routes
// Route::prefix('template')->group(function () {
//     // Dashboard
//     Route::get('/', function () {
//         return view('admin.dashboard');
//     })->name('admin.dashboard');

//     // Profile
//     Route::get('/profile', function () {
//         return view('admin.profile');
//     })->name('admin.profile');

//     // Forms
//     Route::get('/forms', function () {
//         return view('admin.forms');
//     })->name('admin.forms');

//     Route::get('/ui-components', function () {
//         return view('admin.ui-components');
//     })->name('admin.ui-components');
// });
