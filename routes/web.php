<?php

use App\Http\Controllers\Admin\RequestManagerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Student\RequestController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - Application de Gestion des Requêtes IME Douala
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Requêtes Étudiantes
    Route::get('/requests/create', [RequestController::class, 'create'])->name('requests.create');
    Route::post('/requests', [RequestController::class, 'store'])->name('requests.store');
    Route::get('/requests/{studentRequest}', [RequestController::class, 'show'])->name('requests.show');

    // Administration & Gestion
    Route::middleware(['role:gestionnaire,responsable_pedagogique,admin_systeme'])->group(function () {
        Route::patch('/requests/{studentRequest}/status', [RequestManagerController::class, 'updateStatus'])->name('requests.update-status');
        Route::patch('/requests/{studentRequest}/pedagogical-opinion', [RequestManagerController::class, 'submitPedagogicalOpinion'])->name('requests.submit-pedagogical-opinion');
    });
});
