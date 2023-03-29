<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FamilyController;

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

Route::get('/', [FamilyController::class, 'index'])->name('families.index');

// Route::get('families/create', [FamilyController::class, 'create'])->name('families.create');
// Route::post('families', [FamilyController::class, 'store'])->name('families.store');
// Route::get('families/{family}', [FamilyController::class, 'show'])->name('families.show');

Route::resource('families', FamilyController::class);
