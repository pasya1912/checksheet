<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChecksheetController;
use App\Http\Controllers\CheckareaController;
use App\Http\Controllers\CheckdataController;
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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::prefix('checksheet')->group(function () {
        Route::get('/',[ChecksheetController::class,'list'])->name('checksheet.list');
        Route::get('/{id}',[CheckareaController::class,'list'])->where('id', '[0-9]+')->name('checksheet.area');
        Route::get('/{idchecksheet}/checkarea/{idcheckarea}/form',[CheckdataController::class,'form'])->where('id', '[0-9]+')->where('idcheckarea', '[0-9]+')->name('checksheet.data');
        Route::post('/{idchecksheet}/checkarea/{idcheckarea}/form',[CheckdataController::class,'store'])->where('id', '[0-9]+')->where('idcheckarea', '[0-9]+')->name('checksheet.data.store');
        Route::get('/{idchecksheet}/checkarea/{idcheckarea}',[CheckdataController::class,'list'])->where('id', '[0-9]+')->where('idcheckarea', '[0-9]+')->name('checksheet.data.list');

    });







    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
