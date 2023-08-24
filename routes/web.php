<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SocailAuthController;
use Illuminate\Support\Facades\Auth;


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
