<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
use App\Http\Controllers\BookkeeperController;
use App\Http\Controllers\BookkeeperControllers\FiscalYearController;
use App\Http\Controllers\BookkeeperControllers\EnrollmentSetupController;
use App\Http\Controllers\BookkeeperControllers\GeneralLedgerController;
use App\Http\Controllers\BookkeeperControllers\SubsidiaryLedgerController;
use App\Http\Controllers\BookkeeperControllers\SignatoryController;
use App\Http\Controllers\BookkeeperControllers\ExpensesItemController;
use App\Http\Controllers\BookkeeperControllers\TrialBalanceController;
use App\Http\Controllers\BookkeeperControllers\IncomeStatementController;
use App\Http\Controllers\BookkeeperControllers\BalanceSheetController;
use App\Http\Controllers\BookkeeperControllers\CostCenterController;
use Illuminate\Support\Facades\Route;

Route::view('/chart_of_accounts', 'bookkeeper.pages.chart_of_accounts')->name('chart_of_accounts');
Route::view('/enrollment_setup', 'bookkeeper.pages.enrollment_setup')->name('enrollment_setup');
Route::view('/fiscal_year', 'bookkeeper.pages.fiscal_year')->name('fiscal_year');

Route::view('/other_setup', 'bookkeeper.pages.other_setup')->name('other_setup');

Route::view('/fixedasset', 'bookkeeper.pages.fixed_asset');
Route::view('/expense_monitoring', 'bookkeeper.pages.expense_monitoring');
Route::view('/supplier', 'bookkeeper.pages.supplier');
Route::view('/purchase_order', 'bookkeeper.pages.purchase_order');
Route::view('/receiving', 'bookkeeper.pages.receiving');
Route::view('/stock_card', 'bookkeeper.pages.stock_card');
Route::view('/disbursement', 'bookkeeper.pages.disbursement');
Route::view('/income_statement', 'bookkeeper.pages.income_statement');
Route::view('/balance_sheet', 'bookkeeper.pages.balance_sheet');
Route::view('/cashflow_statement', 'bookkeeper.pages.cashflow_statement');
Route::view('/equity_statement', 'bookkeeper.pages.equity_statement');


Route::view('/general_ledger', 'bookkeeper.pages.general_ledger')->name('general_ledger');
Route::view('/subsidiary_ledger', 'bookkeeper.pages.subsidiary_ledger')->name('subsidiary_ledger');
Route::view('/trial_balance', 'bookkeeper.pages.trial_balance')->name('trial_balance');

Route::view('/setup', 'bookkeeper.pages.setup')->name('trial_balance');


// Add new coa
Route::post('/storecoa',[BookkeeperController::class,'storecoa']);
Route::get('/fetchcoa',[BookkeeperController::class,'fetchcoa']);
Route::post('/destroycoa',[BookkeeperController::class,'destroycoa']);
//v4
Route::get('/coa/edit',[BookkeeperController::class,'editcoa']);
Route::post('/coa/update',[BookkeeperController::class,'updatecoa']);

Route::get('/accounttype/edit',[BookkeeperController::class,'editacctype']);
Route::post('/accounttype/update',[BookkeeperController::class,'updateacctype']);

Route::get('/statementtype/edit',[BookkeeperController::class,'editstatementtype']);
Route::post('/statementtype/update',[BookkeeperController::class,'updatestatementtype']);

Route::get('/normalbalance/edit',[BookkeeperController::class,'editnormalbalance']);
Route::post('/normalbalance/update',[BookkeeperController::class,'updatenormalbalance']);

Route::get('/supplier/create',[BookkeeperController::class,'storesupplier']);
Route::get('/supplier/fetch',[BookkeeperController::class,'fetchsupplier']);
Route::get('/supplier/edit',[BookkeeperController::class,'editsupplier']);
Route::post('/supplier/update',[BookkeeperController::class,'updatesupplier']);
Route::get('/supplier/delete',[BookkeeperController::class,'deletesupplier']);

Route::get('/item/create',[BookkeeperController::class,'storeitem']);
Route::get('/item/fetch',[BookkeeperController::class,'fetchitem']);
Route::get('/selected_item/fetch',[BookkeeperController::class,'selectedItem']);
Route::get('/stockhistory/fetch',[BookkeeperController::class,'fetchstock_history']);

Route::get('/purchase_order/create',[BookkeeperController::class,'storePO']);
Route::get('/purchase_order/fetch',[BookkeeperController::class,'fetchPO']);
Route::get('/purchase_order/edit',[BookkeeperController::class,'editPO']);
Route::get('/purchase_order/delete',[BookkeeperController::class,'deletePO']);

Route::get('/purchase_order/update_approved',[BookkeeperController::class,'po_update_approved']);

Route::get('/receiving/update_received',[BookkeeperController::class,'receiving_update_received']);

Route::get('/expenses_monitoring/create',[BookkeeperController::class,'store_expenses_monitoring']);
Route::get('/expenses_monitoring/fetch',[BookkeeperController::class,'fetch_expenses_monitoring']);
Route::get('/expenses_monitoring/edit',[BookkeeperController::class,'edit_expenses_monitoring']);
Route::get('/expenses_monitoring/edit_selected',[BookkeeperController::class,'edit_selected_expenses_monitoring']);
Route::post('/expenses_monitoring/update_selected',[BookkeeperController::class,'update_selected_expenses_monitoring']);

Route::post('/expenses_monitoring/update',[BookkeeperController::class,'update_expenses_monitoring']);
Route::get('/expenses_monitoring/delete_selected',[BookkeeperController::class,'delete_selected_expenses_monitoring']);

Route::get('/expenses_monitoring/delete',[BookkeeperController::class,'delete_expenses_monitoring']);
Route::get('/expenses_monitoring_print', [BookkeeperController::class, 'print_expenses_monitoring']);


Route::get('/receiving/fetch',[BookkeeperController::class,'fetchReceiving']);
Route::get('/receiving/edit',[BookkeeperController::class,'editReceiving']);

Route::get('/fixed_asset/create',[BookkeeperController::class,'storefixed_asset']);
Route::get('/fixed_asset/fetch',[BookkeeperController::class,'fetchfixed_asset']);
Route::get('/fixed_asset/edit',[BookkeeperController::class,'editfixed_asset']);
Route::post('/fixed_asset/update',[BookkeeperController::class,'updatefixed_asset']);
Route::get('/fixed_asset/delete',[BookkeeperController::class,'deletefixed_asset']);

Route::get('/fixed_asset_print', [BookkeeperController::class, 'print_fixed_assets']);

// Route::get('/other_setup_cashierJE/create',[BookkeeperController::class,'storecashierJE']);
Route::get('/other_setup_cashierJE/setactive',[BookkeeperController::class,'setActive_cashierJE']);
Route::get('/other_setup_cashierJE/fetch_active',[BookkeeperController::class,'fetch_active_cashierJE']);

Route::get('/disbursement/create',[BookkeeperController::class,'store_disbursement']);
Route::get('/disbursement/fetch',[BookkeeperController::class,'fetch_disbursement']);
Route::get('/disbursement/edit',[BookkeeperController::class,'edit_disbursement']);
Route::post('/disbursement/items/update_selected',[BookkeeperController::class,'update_selected_disbursement_items']);
Route::get('/disbursement/items/edit_selected',[BookkeeperController::class,'edit_selected_disbursement_items']);
Route::post('/disbursement/update',[BookkeeperController::class,'update_disbursement']);
Route::get('/disbursement/delete',[BookkeeperController::class,'delete_disbursement']);
Route::get('/disbursement/delete_selected',[BookkeeperController::class,'delete_selected_disbursement_items']);

Route::get('/disbursement_print', [BookkeeperController::class, 'print_disbursements']);

Route::get('/fetch_cashflow_statement',[BookkeeperController::class,'fetch_cashflow_statement']);

Route::get('/equity-statement-fetch',[BookkeeperController::class,'fetch_equity_statement']);

Route::get('/cashflow_print', [BookkeeperController::class, 'print_cashflow_statement']);

Route::get('/equity_statement_print', [BookkeeperController::class, 'print_equity_statement']);

Route::get('/received_history/fetch',[BookkeeperController::class,'fetchreceived_history']);

Route::get('/receiving_history/edit',[BookkeeperController::class,'editReceivinghistory']);

Route::get('/sub_storecoa',[BookkeeperController::class,'storeSubCOA']);

Route::get('/subcoa/edit',[BookkeeperController::class,'edit_subcoa']);

Route::post('/subcoa/update',[BookkeeperController::class,'update_subcoa']);

Route::post('/destroy_subcoa',[BookkeeperController::class,'destroy_subcoa']);

Route::get('/coa/fetch_types',[BookkeeperController::class,'fetch_types']);


Route::get('/acctreceivable/filter/home', [BookkeeperController::class,'filter_acctreceivable'])->name('acctreceivablefilter_bookkeeper');

Route::get('/total_received_history', [BookkeeperController::class,'filter_total_received_history'])->name('filter_total_received_history');

Route::get('/cashflow_fiscal',[BookkeeperController::class,'cashflow_fiscal']);

Route::post('/depreciation',[BookkeeperController::class,'updateDepreciation']);

Route::get('/acctreceivable/filter/home_income_expenses', [BookkeeperController::class,'filter_acctreceivable_income_expenses'])->name('filter_acctreceivable_income_expenses');

Route::get('/filter/top_expenses', [BookkeeperController::class,'filter_top_expenses'])->name('filter_top_expenses');

Route::get('/all_expenses_items',[BookkeeperController::class,'all_expenses_items']);

Route::get('/enrollement_Setup/edit_enrollment_setup',[BookkeeperController::class,'edit_enrollment_setup']);

Route::post('/enrollement_Setup/edit_enrollment_setup_update', [BookkeeperController::class,'enrollment_setup_update']);

Route::get('/enrollement_Setup/delete',[BookkeeperController::class,'enrollment_setup_delete']); 

Route::get('/other_setup_cashierJE/setactive',[BookkeeperController::class,'setActive_cashierJE']);

Route::get('/other_setup_discountJE/setactive',[BookkeeperController::class,'setActive_discountJE']);
Route::get('/other_setup_discountJE/fetch_active',[BookkeeperController::class,'fetch_active_discountJE']);

Route::get('/other_setup_AccountJEdebit/setactive',[BookkeeperController::class,'setactive_AccountJEdebit']);
Route::get('/other_setup_AccountJE/fetch_active_adjustment',[BookkeeperController::class,'fetch_active_accountJE']);

Route::get('/disbursement/delete_selected_je',[BookkeeperController::class,'delete_selected_disbursement_je']);

Route::get('/item/item',[BookkeeperController::class,'fetch_active_discountJE']);

Route::get('/item/fetch/edit',[BookkeeperController::class,'editItem']);

Route::get('/item/assign_item',[BookkeeperController::class,'assignitem']);

Route::get('/purchase_order/po_update',[BookkeeperController::class,'po_update_new']);

Route::get('/purchase_order/delete_item',[BookkeeperController::class,'deletePO_item']);

Route::get('/item/fetch/edit_stock',[BookkeeperController::class,'editItem_stock']);

Route::post('/item/fetch/update_stock',[BookkeeperController::class,'updateItem_stock']);

Route::delete('/delete-expense-item_stock', [BookkeeperController::class, 'deleteExpenseItem_stock']);

Route::get('/loadFiscal',[BookkeeperController::class,'loadFiscal']);

Route::get('/purchase_order/get_next_refno', [BookkeeperController::class, 'getNextRefNo']);

Route::get('/v2/v2_voidtransactions', [BookkeeperController::class, 'v2_voidtransactions']);

Route::get('/stock_history_print', [BookkeeperController::class, 'print_stock_history']);

Route::post('/cls/update',[BookkeeperController::class,'updatecls']);

Route::get('/cls/edit',[BookkeeperController::class,'editcls']);

Route::get('/other_setup_discountJE/setactive_discount_discount',[BookkeeperController::class,'setActive_discountJE_discount']);

Route::get('/other_setup_discountJE/fetch_discountje', [BookkeeperController::class,'fetch_discountJE_discount']);

Route::get('/other_setup_discountJE/edit_discountje',[BookkeeperController::class,'edit_discountje']);

Route::post('/other_setup_discountJE/update_discountje',[BookkeeperController::class,'update_discountje']);

Route::get('/other_setup_discountJE/delete_discountje',[BookkeeperController::class,'delete_discountje']);

Route::get('/other_setup_debadjJE/setactive_debadj',[BookkeeperController::class,'setactive_debadj']);

Route::get('/other_setup_debadjJE/fetch_debadjje', [BookkeeperController::class,'fetch_debadj']);

Route::get('/other_setup_debadjJE/edit_debadj',[BookkeeperController::class,'edit_debadj']);

Route::post('/other_setup_debadjJE/update_debadj',[BookkeeperController::class,'update_debadj']);

Route::get('/other_setup_debadjJE/delete_debadj',[BookkeeperController::class,'delete_debadj']);

Route::get('/other_setup_credadjJE/setactive_credadj',[BookkeeperController::class,'setactive_credadj']);

Route::get('/other_setup_credadjJE/fetch_credadj', [BookkeeperController::class,'fetch_credadj']);

Route::get('/other_setup_credadjJE/edit_credadj',[BookkeeperController::class,'edit_credadj']);

Route::post('/other_setup_credadjJE/update_credadj',[BookkeeperController::class,'update_credadj']);

Route::get('/other_setup_credadjJE/delete_credadj',[BookkeeperController::class,'delete_credadj']);
/////////////////////////////////////////////////////////////////////

// Get Types
Route::get('/fetchtypes',[BookkeeperController::class,'fetchtypes']);
Route::post('/storetypes',[BookkeeperController::class,'storetypes']);
Route::post('/destroytype',[BookkeeperController::class,'destroytype']);

//Fiscal Year
Route::post('/save-fiscal-year', [FiscalYearController::class, 'saveFiscalYear']);
Route::get('/fiscal-year', [FiscalYearController::class, 'getFiscalYear']);
Route::get('/edit-fiscal-year/{id}', [FiscalYearController::class, 'getFiscalYearEdit']);
Route::delete('/delete-fiscal-year/{id}', [FiscalYearController::class, 'deletedFiscalYear']);
// Route::put('/update-fiscal-year/{id}', [FiscalYearController::class, 'updateFiscalYear']);
Route::put('/update-fiscal-year', [FiscalYearController::class, 'updateFiscalYear']);
Route::get('/closed/{id}', [FiscalYearController::class, 'getClosedInformation']);
Route::put('/update-status', [FiscalYearController::class, 'updateCloseFiscalYear']);
Route::post('/activate-fiscal-year', [FiscalYearController::class, 'activateCloseFiscalYear']);

//Enrollment Setup
Route::post('/save-classified', [EnrollmentSetupController::class, 'addAcountForm']);
Route::get('/enrollmentsetup', [EnrollmentSetupController::class, 'getClassifiedSetup']);
Route::post('/add-classification', [EnrollmentSetupController::class, 'addClassification']);
Route::get('/classification-list', [EnrollmentSetupController::class, 'updateclassification']);

//General Ledger
Route::get('/general-ledger', [GeneralLedgerController::class, 'getGeneralLedger']);
Route::get('/sync-ledger', [GeneralLedgerController::class, 'syncLedger']);
Route::post('/save-general-ledger', [GeneralLedgerController::class, 'saveGeneralLedger']);
Route::get('/display-general-ledger', [GeneralLedgerController::class, 'displayGeneralLedger']);
Route::get('/generate-voucher', [GeneralLedgerController::class, 'generateVoucherNumber']);
Route::get('/print-ledger', [GeneralLedgerController::class, 'printGeneralLedger']);

//Subsidiary Ledger
Route::get('/subsidiary-ledger', [SubsidiaryLedgerController::class, 'index']);
Route::post('/save-subsidiary-ledger', [SubsidiaryLedgerController::class, 'saveSubsidiaryLedger']);
Route::get('/subsidiary-ledger-print', [SubsidiaryLedgerController::class, 'printSubsidiaryLedger']);
Route::get('/subsidiary_account_fetch', [SubsidiaryLedgerController::class, 'subsidiary_account_fetch']);
Route::get('/subsidiary_costcenter_fetch', [SubsidiaryLedgerController::class, 'subsidiary_costcenter_fetch']);

//Signatories
Route::get('/signatories', [SignatoryController::class, 'getSignatories']);
Route::post('/save-signatories', [SignatoryController::class, 'addSignatories']);
Route::post('/add-signatory', [SignatoryController::class, 'addSignatory']);
Route::delete('/delete-signatory', [SignatoryController::class, 'deleteSignatory']);

//Cost Center
Route::get('/costcenter_create', [CostCenterController::class, 'costcenter_create']);
Route::get('/costcenter_read', [CostCenterController::class, 'costcenter_read']);

//Expense Items
Route::get('/expense-items', [ExpensesItemController::class, 'displayItems']);
Route::post('/add-expense-item', [ExpensesItemController::class, 'addExpenseItem']);
Route::delete('/delete-expense-item', [ExpensesItemController::class, 'deleteExpenseItem']);
Route::get('/edit-expense-item', [ExpensesItemController::class, 'editExpenseItem']);
Route::put('/update-expense-item', [ExpensesItemController::class, 'updateExpenseItem']);

//Trial Balance
Route::get('/trial-balance', [TrialBalanceController::class, 'displayTrialBalance']);
Route::get('/compare-trial-balance', [TrialBalanceController::class, 'displayTrialBalanceSummary']);
Route::get('/print/trialbalance', [TrialBalanceController::class, 'printTrialBalance']);

//Income Statement
Route::get('/income-statement', [IncomeStatementController::class, 'displayIncomeStatement']);
Route::get('/print/income-statement', [IncomeStatementController::class, 'printIncomeStatement']);

//Balance Sheet
Route::get('/balance-sheet', [BalanceSheetController::class, 'displayBalanceSheet']);
Route::get('/balance-sheet/pdf', [BalanceSheetController::class, 'generateBalanceSheetPDF']);



