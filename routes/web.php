<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChecksheetController;
use App\Http\Controllers\CheckareaController;
use App\Http\Controllers\CheckdataController;
use App\Http\Controllers\DashboardController;
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




Route::middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class,'index'])->middleware('checkNotgood')->name('dashboard');
    Route::post('/line-status', [DashboardController::class,'getStatus'])->name('getStatus');
    Route::prefix('checksheet')->group(function () {
        Route::get('/getCode',[ChecksheetController::class,'getCode'])->middleware('checkNotgood')->name('checksheet.getCode');
        Route::get('/',[ChecksheetController::class,'list'])->middleware('checkNotgood')->name('checksheet.list');
        Route::get('/{id}',[CheckareaController::class,'list'])->where('id', '[0-9]+')->name('checksheet.area');
        Route::post('/{idchecksheet}/checkarea/{idcheckarea}',[CheckdataController::class,'store'])->where('id', '[0-9]+')->where('idcheckarea', '[0-9]+')->name('checksheet.data.store');
        Route::post('/{idchecksheet}/checkarea/{idcheckarea}/notes',[CheckdataController::class,'updateNotes'])->where('id', '[0-9]+')->where('idcheckarea', '[0-9]+')->name('checksheet.data.updateNotes');
        Route::get('/{idchecksheet}/checkarea/{idcheckarea}',[CheckdataController::class,'get'])->middleware('checkNotgood')->where('id', '[0-9]+')->where('idcheckarea', '[0-9]+')->name('checksheet.data.get');
        Route::get('/data',[CheckdataController::class,'list'])->middleware('checkNotgood')->name('checksheet.data');
        Route::get('/data/export',[CheckdataController::class,'export'])->name('checksheet.data.export');


    });

    Route::middleware('admin')->group(function () {
        Route::prefix('checksheet')->group(function () {
            Route::get('/data/approval',[AdminCheckdataController::class,'approval_page'])->middleware('checkNotgood')->name('checksheet.data.approval_page');
            Route::post('/data/approval',[AdminCheckdataController::class,'approval'])->name('checksheet.data.approval');
            Route::post('/data/{id}/status',[AdminCheckdataController::class,'updateStatus'])->where('id', '[0-9]+')->name('checksheet.data.changeStatus');
        });


});
});

require __DIR__.'/auth.php';
