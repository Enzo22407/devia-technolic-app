<?php

use App\Http\Controllers\Admin\RequestManagerController;
use App\Http\Controllers\Admin\RequestTypeController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Student\ProfileController;
use App\Http\Controllers\Student\RequestController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - Application de Gestion des Requêtes IME Douala / Devia
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : redirect()->route('login');
});

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Protected Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Profil & Paramètres Utilisateur
    Route::get('/profile/settings', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile/settings', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

    // Requêtes Étudiantes
    Route::get('/requests/create', [RequestController::class, 'create'])->name('requests.create');
    Route::post('/requests', [RequestController::class, 'store'])->name('requests.store');
    Route::get('/requests/{studentRequest}', [RequestController::class, 'show'])->name('requests.show');

    // Administration & Gestion
    Route::middleware(['role:gestionnaire,responsable_pedagogique,admin_systeme'])->group(function () {
        Route::patch('/requests/{studentRequest}/status', [RequestManagerController::class, 'updateStatus'])->name('requests.update-status');
        Route::patch('/requests/{studentRequest}/pedagogical-opinion', [RequestManagerController::class, 'submitPedagogicalOpinion'])->name('requests.submit-pedagogical-opinion');
    });

    // Administration Système Uniquement (Admin Users & Request Types)
    Route::middleware(['role:admin_systeme'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::patch('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

        Route::get('/request-types', [RequestTypeController::class, 'index'])->name('request-types.index');
        Route::post('/request-types', [RequestTypeController::class, 'store'])->name('request-types.store');
        Route::patch('/request-types/{requestType}/toggle', [RequestTypeController::class, 'toggle'])->name('request-types.toggle');
    });
});
