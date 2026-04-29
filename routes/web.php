<?php

use App\Http\Controllers\Web\ContactController;
use App\Http\Controllers\Web\CrechesController;
use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Web\LegalController;
use App\Http\Controllers\Web\PedagogyController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');
Route::get('/nos-creches', CrechesController::class)->name('creches.index');
Route::get('/projet-pedagogique', PedagogyController::class)->name('pedagogy.index');
Route::get('/contact', ContactController::class)->name('contact.index');
Route::get('/mentions-legales', LegalController::class)->name('legal.index');
