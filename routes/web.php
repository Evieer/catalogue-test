<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CatalogueController;

Route::get('/', [CatalogueController::class, 'index'])->name('home');
Route::get('/group/{group}', [CatalogueController::class, 'group'])->name('group');
Route::get('/product/{product}', [CatalogueController::class, 'product'])->name('product');
