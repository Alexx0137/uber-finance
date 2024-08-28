<?php

use App\Http\Controllers\RecordController;
use Illuminate\Support\Facades\Route;

Route::get('/', [RecordController::class, 'index'])->name('index');


//Route::resource('records', RecordController::class);
Route::get('/records/create', [RecordController::class, 'create'])->name('records.create');
Route::get('/records/{id}', [RecordController::class, 'edit'])->name('records.edit');
Route::post('/records', [RecordController::class, 'store'])->name('records.store');
Route::put('/records/{id}', [RecordController::class, 'update'])->name('records.update');
Route::delete('/records/{id}', [RecordController::class, 'destroy'])->name('records.destroy');
