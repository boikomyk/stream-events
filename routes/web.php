<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SocailAuthController;


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

// redirect not logged users to login page
Route::redirect('/', 'login');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

// social auth
Route::controller(SocailAuthController::class)->group(function(){
    Route::get('auth/{provider}', 'redirectToSocial')->name('auth.social');
    Route::get('auth/{provider}/callback', 'handleSocialCallback');
});

// dashboard
Route::controller(DashboardController::class)->group(function () {
    Route::get('dashboard/generate-fake-data', 'generateFakeData')->name('dashboard.generate-fake-data');
    Route::get('dashboard/toogle-notification-read', 'toogleNotificationRead')->name('dashboard.toogle-notification-read');
});
