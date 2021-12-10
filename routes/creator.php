<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Creator Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/podcasts', [App\Http\Controllers\Creator\HomeController::class, 'index'])->name('podcasts.index');
Route::get('/podcasts/create', [App\Http\Controllers\Creator\HomeController::class, 'create'])->name('podcasts.create');
Route::post('/podcasts/create', [App\Http\Controllers\Creator\HomeController::class, 'store'])->name('podcasts.store');
Route::get('/podcasts/{id}/edit', [App\Http\Controllers\Creator\HomeController::class, 'edit'])->name('podcasts.edit');
Route::put('/podcasts/{id}', [App\Http\Controllers\Creator\HomeController::class, 'update'])->name('podcasts.update');
Route::delete('/podcasts/{id}', [App\Http\Controllers\Creator\HomeController::class, 'destroy'])->name('podcasts.destroy');


Route::post('/episodes/create', [App\Http\Controllers\Creator\EpisodeController::class, 'store'])->name('episodes.store');
Route::put('/episodes/{id}', [App\Http\Controllers\Creator\EpisodeController::class, 'update'])->name('episodes.update');
Route::delete('/episodes/{id}', [App\Http\Controllers\Creator\EpisodeController::class, 'destroy'])->name('episodes.destroy');


