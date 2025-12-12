<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CRMController;
use App\Http\Controllers\CustomerController;

Route::get('/', function () {
    return view('home');
});

Route::get('/', [CRMController::class, 'home'])->name('home');
Route::get('customers/filter', [CustomerController::class, 'filter'])->name('customers.filter');
Route::get('customers/export/pdf', [CustomerController::class, 'exportPdf'])->name('customers.export.pdf');
Route::get('customers/export/excel', [CustomerController::class, 'exportExcel'])->name('customers.export.excel');
Route::resource('customers', CustomerController::class);
