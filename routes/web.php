<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LicensingController;
use App\Http\Controllers\LicenseController;
use App\Http\Controllers\LicenseRequirementController;
use App\Http\Controllers\LicensePaymentController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\PermitTypeController;
use App\Http\Controllers\PermitSubTypeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\AgencyController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;

// Guest Routes (non-authenticated users only)
Route::middleware('guest')->group(function () {
    Route::get('/', [AuthController::class, 'showLoginForm'])->name('auth.login');
    Route::post('/login', [AuthController::class, 'login'])->name('auth.login.post');
    Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('auth.register');
    Route::post('/register', [AuthController::class, 'register'])->name('auth.register.post');
});

// Logout route (must be authenticated)
Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout')->middleware('auth.user');

/*
|--------------------------------------------------------------------------
| Cron/Webhook Routes (External Cron Services)
|--------------------------------------------------------------------------
*/
Route::get('/cron/check-licenses/{secret}', function ($secret) {
    if ($secret !== config('app.cron_secret')) {
        abort(403, 'Invalid cron secret');
    }
    Artisan::call('licenses:check-expiration');
    return response()->json([
        'status' => 'success',
        'message' => 'License expiration check completed',
        'output' => Artisan::output(),
        'timestamp' => now()->toDateTimeString()
    ]);
});

// Protected Admin Routes (authenticated users only)
Route::prefix('admin')->middleware('auth.user')->group(function() {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::view('settings', 'files.settings')->name('admin.settings');
    Route::resource('licenses', LicenseController::class)->names('admin.licenses');
    Route::resource('agency', AgencyController::class)->names('admin.agency');
    Route::post('licenses/{license}/refresh-status', [LicenseController::class, 'refreshStatus'])->name('admin.licenses.refresh-status');
    Route::post('licenses/bulk-refresh', [LicenseController::class, 'bulkRefreshStatus'])->name('admin.licenses.bulk-refresh');
    Route::post('licenses/{license}/extend-expiration', [LicenseController::class, 'extendExpiration'])->name('admin.licenses.extend-expiration');
    Route::post('licenses/{license}/update-status', [LicenseController::class, 'updateStatus'])->name('admin.licenses.update-status');
    
    Route::prefix('licenses/{license}/requirements')->name('admin.licenses.requirements.')->group(function () {
        Route::get('/', [LicenseRequirementController::class, 'index'])->name('index');
        Route::post('/', [LicenseRequirementController::class, 'store'])->name('store');
        Route::post('/{requirement}/submit', [LicenseRequirementController::class, 'submit'])->name('submit');
        Route::post('/{requirement}/approve', [LicenseRequirementController::class, 'approve'])->name('approve');
        Route::post('/{requirement}/reject', [LicenseRequirementController::class, 'reject'])->name('reject');
        Route::delete('/{requirement}', [LicenseRequirementController::class, 'destroy'])->name('destroy');
        Route::post('/approve-license', [LicenseRequirementController::class, 'approveLicense'])->name('approve-license');
        Route::post('/reject-license', [LicenseRequirementController::class, 'rejectLicense'])->name('reject-license');
    });
    Route::prefix('licenses/{license}/payments')->name('admin.licenses.payments.')->group(function () {
        Route::get('/', [LicensePaymentController::class, 'show'])->name('show');
        Route::get('/create', [LicensePaymentController::class, 'create'])->name('create');
        Route::post('/', [LicensePaymentController::class, 'store'])->name('store');
        Route::post('/{payment}/add-item', [LicensePaymentController::class, 'addItem'])->name('add-item');
        Route::delete('/{payment}/items/{item}', [LicensePaymentController::class, 'removeItem'])->name('remove-item');
        Route::post('/{payment}/checkout', [LicensePaymentController::class, 'checkout'])->name('checkout');
        Route::get('/{payment}/success', [LicensePaymentController::class, 'success'])->name('success');
        Route::get('/{payment}/cancel', [LicensePaymentController::class, 'cancel'])->name('cancel');
        Route::post('/{payment}/pay-offline', [LicensePaymentController::class, 'payOffline'])->name('pay-offline');
        Route::post('/{payment}/override', [LicensePaymentController::class, 'override'])->name('override');
        Route::delete('/{payment}', [LicensePaymentController::class, 'destroy'])->name('destroy');
    });

    Route::resource('roles', RoleController::class)->names('admin.roles');
    Route::resource('permissions', PermissionController::class)->names('admin.permissions');
    Route::resource('permit-types', PermitTypeController::class)->names('admin.permit-types');
    Route::resource('permit-sub-types', PermitSubTypeController::class)->names('admin.permit-sub-types');
    Route::resource('users', UserController::class)->names('admin.users');
    Route::patch('users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('admin.users.toggle-status');
    Route::resource('modules', ModuleController::class)->names('admin.modules');
    Route::post('modules/reorder', [ModuleController::class, 'reorder'])->name('admin.modules.reorder');

    // Notifications Routes
    Route::prefix('notifications')->name('admin.notifications.')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('index');
        Route::get('/get', [NotificationController::class, 'getNotifications'])->name('get');
        Route::post('/{id}/read', [NotificationController::class, 'markAsRead'])->name('read');
        Route::post('/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('mark-all-read');
        Route::delete('/{id}', [NotificationController::class, 'destroy'])->name('destroy');
    });

    // Profile Routes
    Route::get('profile', [ProfileController::class, 'show'])->name('admin.profile');
    Route::get('profile/edit', [ProfileController::class, 'edit'])->name('admin.profile.edit');
    Route::put('profile', [ProfileController::class, 'update'])->name('admin.profile.update');
    Route::get('profile/password', [ProfileController::class, 'showChangePassword'])->name('admin.profile.password');
    Route::put('profile/password', [ProfileController::class, 'updatePassword'])->name('admin.profile.password.update');
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
