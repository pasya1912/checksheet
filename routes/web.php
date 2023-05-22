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


Route::get('/', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::prefix('checksheet')->group(function () {
        Route::get('/getCode',[ChecksheetController::class,'getCode'])->name('checksheet.getCode');
        Route::get('/',[ChecksheetController::class,'list'])->name('checksheet.list');
        Route::get('/{id}',[CheckareaController::class,'list'])->where('id', '[0-9]+')->name('checksheet.area');
        Route::post('/{idchecksheet}/checkarea/{idcheckarea}',[CheckdataController::class,'store'])->where('id', '[0-9]+')->where('idcheckarea', '[0-9]+')->name('checksheet.data.store');
        Route::post('/{idchecksheet}/checkarea/{idcheckarea}/notes',[CheckdataController::class,'updateNotes'])->where('id', '[0-9]+')->where('idcheckarea', '[0-9]+')->name('checksheet.data.updateNotes');
        Route::get('/{idchecksheet}/checkarea/{idcheckarea}',[CheckdataController::class,'get'])->where('id', '[0-9]+')->where('idcheckarea', '[0-9]+')->name('checksheet.data.get');
        Route::get('/data',[CheckdataController::class,'list'])->name('checksheet.data');
        Route::get('/data/export',[CheckdataController::class,'export'])->name('checksheet.data.export');


    });

    Route::middleware('admin')->group(function () {
        Route::prefix('checksheet')->group(function () {
            Route::get('/data/approval',[AdminCheckdataController::class,'approval_page'])->name('checksheet.data.approval_page');
            Route::post('/data/approval',[AdminCheckdataController::class,'approval'])->name('checksheet.data.approval');
            Route::post('/data/{id}/status',[AdminCheckdataController::class,'updateStatus'])->where('id', '[0-9]+')->name('checksheet.data.changeStatus');
        });


});
});

require __DIR__.'/auth.php';
