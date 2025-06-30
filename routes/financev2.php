<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Financev2Controller\SetupController;
use App\Http\Controllers\Financev2Controller\LabFeeController;
use App\Http\Controllers\Financev2Controller\PaymentScheduleController;


//--------------------STUDENT ACCOUNTS---------------------//
Route::middleware(['auth'])->group(function () {
    // Route::view('/studentaccounts', 'finance_v2.pages.studentaccount');
    // Route::get('studentaccounts/getstudentaccounts', 'Financev2Controller\StudentAccountV2Controller@getStudentAccounts');
    // Route::get('studentaccounts/getStudentLedger', 'Financev2Controller\StudentAccountV2Controller@getStudentLedger');
    // Route::get('studentaccounts/studentLedgerHistory', 'Financev2Controller\StudentAccountV2Controller@adjustment_history');

    // Route::post('studentaccounts/saveAdjustment', 'Financev2Controller\StudentAccountV2Controller@saveAdjustment');
    // Route::post('studentaccounts/additems', 'Financev2Controller\StudentAccountV2Controller@additems');
    
});
Route::middleware(['auth'])->group(function () {
    // Route::view('/studentaccounts', 'finance_v2.pages.studentaccount');
// --------------------BOOK LIST---------------------
    // Route::view('/booklist', 'finance_v2/pages/book_list')->name('book_list');
    // Route::get('/setup/booklist/create', 'Financev2Controller\Booklistv2Controller@create_booklist');
    // Route::get('/setup/booklist/fetch', 'Financev2Controller\Booklistv2Controller@fetch_booklist');

    // Route::get('/setup/booklist/edit', 'Financev2Controller\Booklistv2Controller@edit_booklist');
    // Route::get('/setup/booklist/update', 'Financev2Controller\Booklistv2Controller@update_booklist');
    // Route::get('/setup/booklist/delete', 'Financev2Controller\Booklistv2Controller@delete_booklist');

// --------------------DISBURSEMENT---------------------
    // Route::view('/disbursement', 'finance_v2/pages/disbursement')->name('disbursement');
    // Route::get('/setup/disbursement_expenses/create', 'Financev2Controller\Disbursementv2Controller@create_disbursement_expenses');
    // Route::get('/setup/disbursement_students_refund/create', 'Financev2Controller\Disbursementv2Controller@create_disbursement_students_refund');
    // Route::get('/setup/disbursements/fetch', 'Financev2Controller\Disbursementv2Controller@fetch_disbursements');
    // Route::get('/setup/disbursements/delete', 'Financev2Controller\Disbursementv2Controller@delete_disbursements');
    // Route::get('/setup/disbursements_expenses/edit', 'Financev2Controller\Disbursementv2Controller@edit_disbursements_expenses');
    // Route::get('/setup/disbursement_expenses/update', 'Financev2Controller\Disbursementv2Controller@update_disbursement_expenses');
});


// --------------------SETUP---------------------
Route::middleware(['auth'])->group(function () {

    // --------------------CLASSIFICATION---------------------
    // Route::get('/setup/{page}', [SetupController::class, 'setup']);
    // Route::post('/classif/store', [SetupController::class, 'storeClassif'])->name('classif.store');
    // Route::get('/classif/fetch', [SetupController::class, 'fetchClassif'])->name('classif.fetch');
    // Route::post('/classif/destroy', [SetupController::class, 'destroyClassif'])->name('classif.destroy');
    // Route::post('/fees-item/store', [SetupController::class, 'storeFeesItem'])->name('fees-item.store');
    // Route::get('/fess-item/fetch', [SetupController::class, 'fetchFeesItem'])->name('fees-item.fetch');

    // // --------------------LAB FEES---------------------
    // Route::get('/lab-fees', [LabFeeController::class, 'index']);
    // Route::post('/lab-fees', [LabFeeController::class, 'store']);
    // Route::put('/lab-fees/{id}', [LabFeeController::class, 'update']);
    // Route::delete('/lab-fees/{id}', [LabFeeController::class, 'destroy']);
    // Route::get('/lab-fees/{id}', [LabFeeController::class, 'show']);


    // // --------------------PAYMENT SCHEDULE---------------------
    // Route::group(['middleware' => ['auth']], function() {
    //     Route::get('/payment-schedules', [PaymentScheduleController::class, 'index']);
    //     Route::post('/payment-schedules', [PaymentScheduleController::class, 'store']);
    //     Route::get('/payment-schedules/{id}', [PaymentScheduleController::class, 'show']);
    //     Route::put('/payment-schedules/{id}', [PaymentScheduleController::class, 'update']);
    //     Route::delete('/payment-schedules/{id}', [PaymentScheduleController::class, 'destroy']);
    // });
});