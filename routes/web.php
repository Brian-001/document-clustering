<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DocumentController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/', [DocumentController::class, 'index'])->name('documentz.index');
Route::post('/upload', [DocumentController::class, 'upload'])->name('documentz.upload');