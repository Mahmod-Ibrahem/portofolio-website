<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PermissionController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\SettingController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Api\V1\ProfileController;
use App\Http\Controllers\Api\V1\ArticleController;
use App\Http\Controllers\Api\V1\VideoController;
use App\Http\Controllers\Api\V1\HomeController;
use App\Http\Controllers\Api\V1\StatsController;
use App\Http\Controllers\Api\GalleryController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| Public Routes (مسارات عامة)
|--------------------------------------------------------------------------
*/

Route::post('/login', [AuthController::class, 'login'])->name('auth.login');

/*
|--------------------------------------------------------------------------
| Portfolio API V1 Routes (مسارات البورتفوليو)
|--------------------------------------------------------------------------
*/

Route::prefix('v1')->group(function () {
    // Profile (single user - show/update)
    Route::get('profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');

    // Home Page Singleton
    Route::get('home-singleton', [HomeController::class, 'singleton']);

    // Public Article & Video routes
    Route::get('articles', [ArticleController::class, 'index']);
    Route::get('articles/{article}', [ArticleController::class, 'show']);
    Route::get('videos', [VideoController::class, 'index']);
    Route::get('videos/{video}', [VideoController::class, 'show']);

    // Public Gallery routes
    Route::get('galleries', [GalleryController::class, 'index']);
    Route::get('galleries/{gallery}', [GalleryController::class, 'show']);

    // Track site visit (public, no auth)
    Route::post('track-visit', [StatsController::class, 'trackVisit']);
});

Route::middleware('auth:sanctum')->group(function () {
    // Auth routes
    Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');
    Route::get('/me', [AuthController::class, 'me'])->name('auth.me');

    /*
|--------------------------------------------------------------------------
| User Management Routes (مسارات إدارة المستخدمين)
|--------------------------------------------------------------------------
*/
    Route::prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('users.index');
        Route::post('/', [UserController::class, 'store'])->name('users.store');
        Route::get('/{user}', [UserController::class, 'show'])->name('users.show');
        Route::put('/{user}', [UserController::class, 'update'])->name('users.update');
        Route::post('/{user}/toggle-active', [UserController::class, 'toggleActive'])->name('users.toggle-active');
        Route::delete('/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    });


    // Home Page Singleton Update (Auth)
    Route::post('home-singleton', [HomeController::class, 'updateSingleton']);

    // Dashboard Stats (Auth)
    Route::get('stats/dashboard', [StatsController::class, 'dashboard']);

    // Article & Video Management (Auth)
    Route::post('articles', [ArticleController::class, 'store']);
    Route::post('articles/{article}', [ArticleController::class, 'update']);
    Route::delete('articles/{article}', [ArticleController::class, 'destroy']);
    Route::post('videos', [VideoController::class, 'store']);
    Route::post('videos/{video}', [VideoController::class, 'update']);
    Route::delete('videos/{video}', [VideoController::class, 'destroy']);

    // Gallery Management (Auth)
    Route::post('galleries', [GalleryController::class, 'store']);
    Route::post('galleries/{gallery}', [GalleryController::class, 'update']);
    Route::delete('galleries/{gallery}', [GalleryController::class, 'destroy']);

    // RBAC Routes (Super Admin Only)
    Route::get('roles', [RoleController::class, 'index']); // Allow listing roles for other auth users (e.g. for filtering)
    Route::middleware(['auth:sanctum', 'role:مدير النظام'])->group(function () {
        Route::apiResource('roles', RoleController::class)->except(['index']);
        Route::post('roles/{role}/permissions', [RoleController::class, 'syncPermissions']);
        Route::apiResource('permissions', PermissionController::class);
        Route::post('setting/update', [SettingController::class, 'update'])->name('setting.update');
        Route::apiResource('setting', SettingController::class)->names('setting')->except(['update']);
    });
});
