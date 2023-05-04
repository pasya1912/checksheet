<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChecksheetController;
use App\Http\Controllers\CheckareaController;
use App\Http\Controllers\CheckdataController;
use App\Http\Controllers\Admin\CheckdataController as AdminCheckdataController;
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
    if(!auth()->check()){
        return redirect()->route('login');
    }
});


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::prefix('checksheet')->group(function () {
        Route::get('/',[ChecksheetController::class,'list'])->name('checksheet.list');
        Route::get('/{id}',[CheckareaController::class,'list'])->where('id', '[0-9]+')->name('checksheet.area');
        Route::post('/{idchecksheet}/checkarea/{idcheckarea}',[CheckdataController::class,'store'])->where('id', '[0-9]+')->where('idcheckarea', '[0-9]+')->name('checksheet.data.store');


    });

    Route::middleware('admin')->group(function () {
        Route::prefix('checksheet')->group(function () {
            Route::get('/data',[AdminCheckdataController::class,'list'])->name('checksheet.data');
            Route::post('/data/{id}/status',[AdminCheckdataController::class,'changeStatus'])->where('id', '[0-9]+')->name('checksheet.data.changeStatus');
        });

});








    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
