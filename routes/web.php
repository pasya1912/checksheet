<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CheckareaController;
use App\Http\Controllers\CheckdataController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ChecksheetController;
use App\Http\Controllers\Admin\CheckdataController as AdminCheckdataController;
use App\Http\Controllers\Admin\SettingController as AdminSettingController;
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
        Route::get('/data',[CheckdataController::class,'list'])->middleware('checkNotgood')->name('checksheet.data');
        Route::get('/data/export',[CheckdataController::class,'export'])->name('checksheet.data.export');
        Route::middleware('checkJP')->group(function () {
        Route::get('/{id}',[CheckareaController::class,'list'])->middleware('checkNotgood')->where('id', '[0-9]+')->name('checksheet.area');
        Route::post('/{idchecksheet}/checkarea/{idcheckarea}',[CheckdataController::class,'store'])->where('id', '[0-9]+')->where('idcheckarea', '[0-9]+')->name('checksheet.data.store');
        Route::post('/{idchecksheet}/checkarea/{idcheckarea}/notes',[CheckdataController::class,'updateNotes'])->where('id', '[0-9]+')->where('idcheckarea', '[0-9]+')->name('checksheet.data.updateNotes');
        Route::get('/{idchecksheet}/checkarea/{idcheckarea}',[CheckdataController::class,'get'])->middleware('checkNotgood')->where('id', '[0-9]+')->where('idcheckarea', '[0-9]+')->name('checksheet.data.get');

        });

    });
    Route::post('/set-jp',[ChecksheetController::class,'setJP'])->name('checksheet.setJP');

    Route::middleware('admin')->group(function () {
        Route::prefix('checksheet')->group(function () {
            Route::get('/data/approval',[AdminCheckdataController::class,'approval_page'])->middleware('checkNotgood')->name('checksheet.data.approval_page');
            Route::post('/data/approval',[AdminCheckdataController::class,'approval'])->name('checksheet.data.approval');
            Route::group(['middleware' => ['editSetting']], function () {
                Route::get('/setting',[AdminSettingController::class,'index'])->name('checksheet.setting');
                Route::get('/setting/{id}',[AdminSettingController::class,'area'])->where('id', '[0-9]+')->name('checksheet.setting.area');
                Route::get('/setting/{idchecksheet}/checkarea/{idcheckarea}',[AdminSettingController::class,'areaEdit'])->where('id', '[0-9]+')->where('idcheckarea', '[0-9]+')->name('checksheet.setting.area.edit');
                Route::post('/setting/{idchecksheet}/checkarea/{idcheckarea}',[AdminSettingController::class,'areaEditAction'])->where('id', '[0-9]+')->where('idcheckarea', '[0-9]+')->name('checksheet.setting.area.editAction');


                Route::get('/setting/approval',[AdminSettingController::class,'approvalList'])->name('checksheet.setting.approval');
                Route::get('/setting/approval/{id}',[AdminSettingController::class,'approvalDetail'])->whereNumber('id')->name('checksheet.setting.approval.detail');
                Route::post('/setting/approval/{id}',[AdminSettingController::class,'approvalAction'])->whereNumber('id')->name('checksheet.setting.approval.action');

            });

        });


});
});

require __DIR__.'/auth.php';
