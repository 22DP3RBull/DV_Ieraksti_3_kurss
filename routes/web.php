<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\StudentController;

Route::get('/', function () {
    return Inertia::render('MainPage');
});

Route::get('/admin-login', function () {
    return Inertia::render('AdminLogin');
});

Route::get('/admin-dashboard', function () {
    return Inertia::render('AdminDashboard');
});

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::resource('students', StudentController::class)->except(['create', 'edit']);

Route::prefix('api')->group(function () {
    Route::get('/students', [StudentController::class, 'index']);
    Route::get('/rooms', [StudentController::class, 'getRooms']);
    Route::post('/students', [StudentController::class, 'store']);
    Route::put('/students/{id}', [StudentController::class, 'update']);
    Route::delete('/students/{id}', [StudentController::class, 'destroy']);
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
