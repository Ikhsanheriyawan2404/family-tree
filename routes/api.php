<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\FamilyController;

Route::get('families', [FamilyController::class, 'allFamily']);
Route::get('families/anak-laki-laki/{id}', [FamilyController::class, 'sonBudi']);
Route::get('families/cucu-laki-laki/{id}', [FamilyController::class, 'grandsonBudi']);
Route::get('families/cucu-perempuan/{id}', [FamilyController::class, 'granddaughterBudi']);
Route::get('families/bibi-farah/{id}', [FamilyController::class, 'auntyFarah']);
Route::get('families/sepupu-laki-laki-hani/{id}', [FamilyController::class, 'maleCousinHani']);
