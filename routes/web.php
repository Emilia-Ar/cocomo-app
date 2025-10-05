<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CocomoController;



Route::get('/', function () {
    return view('welcome');
});

Route::get('/cocomo', [CocomoController::class, 'index'])->name('cocomo.index');
Route::post('/cocomo/estimate', [CocomoController::class, 'estimate'])->name('cocomo.estimate');
Route::get('/cocomo/export', [CocomoController::class, 'exportCsv'])->name('cocomo.export');