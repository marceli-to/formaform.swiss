<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Create user
Route::middleware(['auth', 'superuser'])->group(function () {
    Route::post('/api/user', [UserController::class, 'store'])->name('api.users.store');
});

// Password reset
Route::post('/api/password-reset', [UserController::class, 'password'])->name('api.password.reset');
