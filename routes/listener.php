<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Listener Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Explore Podcasts
Route::get('/podcasts', [App\Http\Controllers\Listener\HomeController::class, 'index'])->name('podcasts.index');
// Listen to a podcast
Route::get('/podcasts/{id}', [App\Http\Controllers\Listener\HomeController::class, 'show'])->name('podcasts.show');
