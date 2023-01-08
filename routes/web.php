<?php
// use Illuminate\Routing\Route;
// use Illuminate\Support\Facades\Artisan;
// use Illuminate\Support\Facades\Auth;

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
/* Application Routes */


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\HomeAjaxController;

Route::group(['prefix'=>'2fa'], function(){
    Route::get('/','LoginSecurityController@show2faForm');
    Route::post('/generateSecret','LoginSecurityController@generate2faSecret')->name('generate2faSecret');
    Route::post('/enable2fa','LoginSecurityController@enable2fa')->name('enable2fa');
    Route::post('/disable2fa','LoginSecurityController@disable2fa')->name('disable2fa');
    // 2fa middleware
    Route::post('/2faVerify', function () {
        return redirect(URL()->previous());
    })->name('2faVerify')->middleware('2fa');
});
Route::get('/', 'IndexController@index');
	Route::get('/sync_sale-invoices','MyobInvoiceController@uplaodMyobInvoices')->name('sale.invoices.sync');
	Route::get('/sync-purchase-invoices','MyobInvoiceController@uplaodMyobPurchaseInvoices')->name('purchase.invoices.sync');
Auth::routes();
Route::group(['middleware' => ['auth', '2fa']], function() {

  	Route::get('/dashboard','HomeController@index');

    Route::get('/overall-stats','HomeController@overallStats')->name('overall.stats');

    /* Activity Logs */
	Route::get('/logs', 'LogsController@index')->name('logs.index');
	Route::get('/logs/{username}', 'LogsController@show')->name('logs.show');

  	/* Dashboard Filters */
  	Route::get('/monthly-report/{series}/{month}','DashboardController@monthlyReport');
  	Route::get('/monthly-report-filter/{series}/{month}/{day}','DashboardController@monthlyReportFilter');
  	Route::get('/month-report/{series}/{month}','DashboardController@monthReport');
  	Route::get('/overdue-sales-report/{encrypt}/{date}','DashboardController@overdueSalesReport');
	Route::get('/search-monthly-report', 'DashboardController@searchMonthlyReport');
  	Route::get('/search-purchase-monthly-report', 'DashboardController@searchPurchaseMonthlyReport');
  	Route::get('/supplier-company-report/{series}/{company}', 'DashboardController@supplierCompany');
  	Route::get('/main-company-report/{company}', 'DashboardController@mainCompany');
  	Route::get('/total-deliveries','DashboardController@totalDeliveries');
    Route::get('/sub-company-report/{company}', 'DashboardController@subCompany');
  	Route::view('/all-pending-records','admin.dashboard_filters.pending_records');
  	Route::view('/balance-amount-report','admin.dashboard_filters.balance_amount_report');
  	Route::view('/all-paid-invoices','admin.dashboard_filters.paid_records');
  	Route::view('/all-overdue-invoices','admin.dashboard_filters.overdue_records');
    Route::view('/dashboard-unpaid-invoices','admin.dashboard_filters.unpaid_records');


  	/* Settings & Configurations */
  	Route::post('/delete-bill-of-lading', 'AccountantController@deleteBillOfLading')->name('delete-bill-of-lading');
  	Route::post('/delete-delivery-docket', 'AccountantController@deleteDeliveryDocket')->name('delete-delivery-docket');
    Route::post('/update-payment-record', 'PurchaseController@updatePaymentRecord')->name('update.payment.record.store');
	Route::get('/home', 'HomeController@index')->name('home');
  	Route::get('/trash-box','UsersController@trashBox')->name('trash.box');
  	Route::post('/filter','HomeController@filter');
  	Route::post('/sales-filter','HomeController@salesFilter');
  	Route::post('/add-invoice-mass-match','HomeController@addInvoiceMassMatch');

    // ajaxcontroller
    Route::get('/ajaxcallHome',[HomeAjaxController::class,'index'])->name('ajaxtopstat');
    Route::get('/activitylogs',[HomeAjaxController::class,'Activitylogs'])->name('Activitylogs');
    Route::get('/SlineChart',[HomeAjaxController::class,'SlineChart'])->name('SlineChart');
    Route::get('/supplierchart',[HomeAjaxController::class,'supplierchart'])->name('supplierchart');
    Route::get('/SlineChartMonthly',[HomeAjaxController::class,'SlineChartMonthly'])->name('SlineChartMonthly');
    Route::get('/linechart',[HomeAjaxController::class,'linechart'])->name('linechart');
    Route::get('/sidechart',[HomeAjaxController::class,'sidechart'])->name('sidechart');
    Route::get('/monthltyReport',[HomeAjaxController::class,'monthltyReport'])->name('monthltyReport');

  //manual massmatch
  Route::get('/add-manual-mass-match','ManualMassmatchController@addManualMassMatch')->name('add.manual.mass.match');
  Route::post('/add-purchase-invoice-mass-match','HomeController@addPurchaseInvoiceMassMatch');

	Route::resource('categories','CategoriesController');
  	Route::resource('companies','CompaniesController');
    Route::resource('subcompanies','SubCompanyController');
    Route::resource('/roles','RolesController');
    Route::resource('/users','UsersController');
    Route::resource('profile', 'ProfileController');
    Route::resource('change-password', 'ChangePasswordController');
    Route::resource('supplier-companies', 'SupplierCompaniesController');
    Route::resource('/records','RecordsController');
    //load more route for loading sales records
    Route::get('load-record','RecordsController@load')->name('load-record');
  	Route::post('/delete-company-email','CompaniesController@deleteEmail');
    Route::post('/delete-subcompany-email','SubCompanyController@deleteEmail');
  	Route::post('/change-company-status','CompaniesController@changeStatus')->name('companies.change.status');
    Route::post('/change-subcompany-status','SubCompanyController@changeStatus')->name('subcompanies.change.status');
  	Route::post('/change-category-status','CategoriesController@changeStatus')->name('categories.change.status');
  	Route::post('/validate-username','UsersController@validateUsername');
  	Route::post('/validate-edit-username','UsersController@validateEditUsername');
  	Route::post('/ban-user','UsersController@banUser');
  	Route::post('/trash-user','UsersController@trashUser');
  	Route::post('/user-attachment-delete','UsersController@deleteAttachment');
    Route::resource('products','ProductController');
	//sync data
    Route::get('product-sync','ProductController@syncproduct')->name('product.sync');
	Route::get('subcompany-sync','SubCompanyController@syncSubCompany')->name('subcompany.sync');
	Route::get('suppliercompany-sync','SupplierCompaniesController@syncSupplierCompany')->name('supplier.sync');
	Route::get('supplier-coa-sync','SupplierCompaniesController@syncSupplierAccount')->name('supplier.coa.sync');
	Route::get('customer-coa-sync','SubCompanyController@syncCustomerAccount')->name('customer.coa.sync');
	// Route::get('supplieraccount-sync','SupplierCompaniesController@syncSupplierAccount');
	Route::get('customeraccount-sync','SubCompanyController@syncCustomerAccount');

	//inoice sync
	Route::get('purchase-invoice-sync','PurchaseController@syncPurchaseRecorduid');
	Route::get('sales-invoice-sync','AccountantController@syncSalesinvoiceduid');

	// Invoice - Bill Sync
	// Route::get('purchase-sync','PurchaseController@syncPurchaseuid');
	// Route::get('sale-sync','AccountantController@syncSalesuid');

   	Route::post('/products-change-status','ProductController@changeStatus');
  	Route::post('/update-category','SubCompanyController@updateCategory');
    Route::post('/record-sub-companies-prices','RecordsController@fetchCompanyRates')->name('record.sub.companies.prices');
  	Route::get('/delete-sale/{id}','RecordsController@deleteSale')->name('records.deleteSale');


  	/* Trash */
  	Route::get('/trash','TrashController@index')->name('trash');
  	Route::get('/trash/{id}','TrashController@show')->name('trash.details');
  	Route::get('/trash-permanent-delete/{id}','TrashController@permanentDelete')->name('trash.permanent.delete');
  	Route::get('/trash-restore-delete/{id}','TrashController@restoreDelete')->name('trash.restore.delete');

  	/* Invoices */
  	Route::get('/invoice/{id}','InvoiceController@invoice')->name('invoice');
	Route::get('/all-invoices','InvoiceController@allInvoices')->name('all.invoices');
	Route::get('/paid-invoices','InvoiceController@paidInvoices')->name('paid.invoices');
	Route::get('/unpaid-invoices','InvoiceController@unpaidInvoices')->name('unpaid.invoices');
	Route::get('/overdue-invoices','InvoiceController@overdueInvoice')->name('overdue.invoices');
  	Route::get('/invoice/{id}/details','InvoiceController@invoiceDetails')->name('invoice.details');
  	Route::post('/pay-invoice-payment','InvoiceController@payInvoicePayment')->name('pay.invoice');
  	Route::post('/send-invoice-mail','InvoiceController@sendMail')->name('send.invoice.mail');

  	Route::post('/allInvoices-filter','InvoiceController@allInvoicesFilter');
  	Route::post('/paidInvoices-filter','InvoiceController@paidInvoicesFilter');
    Route::post('/unpaidInvoices-filter','InvoiceController@unPaidInvoicesFilter');
    Route::post('/overdueInvoices-filter','InvoiceController@overdueInvoicesFilter');
    Route::post('/invoice-follows-note','InvoiceController@invoiceFollowsNote');

      Route::get('/tax-code','DeleteController@getTaxcode')->name('tax.code');

     //excel
Route::get('export', 'RecordsController@export')->name('export');
Route::get('sales-invoices', 'InvoiceController@exportallinvoices')->name('export.invoices');
Route::get('purchase_export', 'PurchaseController@export')->name('purchase.export');
Route::get('exportallinvoices', 'InvoiceController@exportallinvoices')->name('exportallinvoices.export');
Route::get('paidinvoices', 'InvoiceController@paidexport')->name('paidinvoices.export');
Route::get('unpaidexport', 'InvoiceController@unpaidexport')->name('unpaidexport.export');
Route::get('purchaseallInvoicesexport', 'InvoiceController@purchaseallInvoicesexport')->name('purchaseallInvoicesexport.export');
Route::get('purchasepaidinvoicesex', 'InvoiceController@purchasepaidinvoicesex')->name('purchasepaidinvoicesex.export');
Route::get('purchaseunpaidinvoicesex', 'InvoiceController@purchaseunpaidinvoicesex')->name('purchaseunpaidinvoicesex.export');
  	/*Purchase Invoices */
	Route::get('/purchase-all-invoices','InvoiceController@purchaseallInvoices')->name('purchase.all.invoices');
	Route::get('/purchase-paid-invoices','InvoiceController@purchasepaidInvoices')->name('purchase.paid.invoices');
	Route::get('/purchase-unpaid-invoices','InvoiceController@purchaseunpaidInvoices')->name('purchase.unpaid.invoices');


	/* Purchase vs Sales Manual */

    Route::get('/manual-purchases-vs-sales','PurchasesVsSalesController@manualindex')->name('manual.purchases.vs.sales');
    Route::get('/manual-purchases-vs-sales/{id}','PurchasesVsSalesController@manualshow')->name('manual.purchases.vs.sales.details');
    Route::get('/manual-purchases-vs-sales-company','PurchasesVsSalesController@manualpurchasesVsSalesCompany')->name('manual.purchases.vs.sales.company');



   	/* Purchase */
  	Route::get('/add/purchase','PurchaseController@create')->name('purchase.create');
  	Route::post('/submit/purchase','PurchaseController@submit')->name('purchase.submit');
    Route::get('/purchases','PurchaseController@index')->name('purchases');
  	Route::get('/purchase/{id}/edit','PurchaseController@edit')->name('purchases.edit');
    Route::get('/purchase/{id}','PurchaseController@show')->name('purchases.show');
  	Route::post('/update/purchase','PurchaseController@update')->name('purchase.update');
  	Route::post('/purchase/destroy/{id}','PurchaseController@destroy')->name('purchase.destroy');

  	/* Mass Match */
  	Route::resource('/mass-match','MassMatchController');
    Route::get('/manual-mass-match','ManualMassmatchController@show')->name('manual-mass-match.show');

    Route::post('/manual-mass-match-reconsile','ManualMassmatchController@reConsile');
  	/* Accounts */
    Route::get('add-account', 'AccountsController@addAccount')->name('add.account');
    Route::post('add-account', 'AccountsController@storeAccount')->name('add_account.store');
    Route::get('edit-account/{id}', 'AccountsController@editAccount')->name('edit.account');
    Route::post('update-bank-account', 'AccountsController@updateAccount')->name('bank.account.show');
    Route::get('manage-account', 'AccountsController@manageAccount')->name('manage.accounts');
    Route::post('delete-bank-account', 'AccountsController@deleteAccount')->name('delete.accounts');
  	Route::get('/manage-accounts','AccountsController@manageAccounts')->name('manage_accounts');

    /* chart of accounts */
 	Route::get('/manage-chart-accounts','AccountsController@manageChartsAccounts')->name('manage.chart.accounts');
    Route::get('/create-chart-account','AccountsController@createChartAccount')->name('create.chart.account');
    Route::post('/store-chart-account','AccountsController@StoreChartAccount')->name('store.chart.account');
    Route::get('/edit-chart-account/{id}','AccountsController@editChartAccount')->name('edit.chart.account');
    Route::post('/update-chart-account/{id}','AccountsController@updateChartAccount')->name('update.chart.account');
    Route::post('/delete-chart-account','AccountsController@deleteChartAccount')->name('delete.chart.account');
  //sync accounts
	Route::get('/sync-all-accounts','AccountsController@syncAccounts');

	//payment route
    /* chart of account types */
    Route::get('/manage-account-type','ChartAccountTypeController@manageChartAccountType')->name('manage.chart.account_type');
    Route::get('/create-chart-account-type','ChartAccountTypeController@createChartAccountType')->name('create.chart.account_type');
    Route::post('/store-chart-account-type','ChartAccountTypeController@StoreChartAccountType')->name('store.chart.account_type');
    Route::get('/edit-chart-account-type/{id}','ChartAccountTypeController@editChartAccountType')->name('edit.chart.account_type');
    Route::post('/update-chart-account-type/{id}','ChartAccountTypeController@updateChartAccountType')->name('update.chart.account_type');
    Route::post('/delete-chart-account-type','ChartAccountTypeController@deleteChartAccountType')->name('delete.chart.account_type');

    /* sub accounts */
    Route::get('/manage-subAccounts','SubAccountController@manageSubAccount');
    Route::get('/create-subaccount','SubAccountController@createSubAccount')->name('create.subaccount');
    Route::post('/store-subaccount','SubAccountController@StoreSubAccount')->name('store.subaccount');
    Route::get('/edit-subaccount/{id}','SubAccountController@editSubAccount')->name('edit.subaccount');
    Route::post('/update-subaccount/{id}','SubAccountController@updateSubAccount')->name('update.subaccount');
    Route::post('/delete-subaccount','SubAccountController@deleteSubAccount')->name('delete.subaccount');
  	Route::get('/transactions','AccountsController@transaction')->name('transactions');
  	Route::post('/transactions','AccountsController@transactionUpload');
  	Route::get('/fetch-subaccounts','AccountsController@fetchSubAccounts');
  	Route::get('/fetch-invoices','AccountsController@fetchInvoices');
  	Route::post('/allocate-transaction','AccountsController@allocateToMe');

  /* Supervisor */
  	Route::group(['prefix' => 'supervisor'], function () {
      Route::get('/dashboard','SupervisorController@dashboard')->name('supervisor.dashboard');
      Route::get('/record-details/{id}','SupervisorController@record')->name('supervisor.record.details');
      Route::post('/record-fuel-delivery/{id}','SupervisorController@fuelDelivery')->name('supervisor.record.fuel.delivery');
      Route::get('/record-fuel-delivery/{id}/update','SupervisorController@fuelDeliveryUpdate')->name('supervisor.record.fuel.delivery.update');
      Route::post('/record-fuel-delivery/form/update','SupervisorController@fuelDeliveryFormUpdate')->name('supervisor.record.fuel.delivery.form.update');
      Route::get('/record-details/{id}/delete','SupervisorController@deleteApplication')->name('supervisor.record.delete');

  	  Route::post('/record-fuel-delivery','SupervisorController@submitFuelDelivery')->name('submit.fuel.delivery');
      Route::get('/get-companies','SupervisorController@getCompanies')->name('companies.get');
      Route::get('/get-sub-companies','SupervisorController@getSubCompanies')->name('sub.companies.get');
      Route::post('/sub-companies-prices','SupervisorController@fetchCompanyRates')->name('sub.companies.prices');
    });

  	/* Assign Controller */
  	Route::get('/invoice-manual-insertion/{id}','AssignInvoicesController@manualInsertion')->name('manual.insertion');
  	Route::post('invoice-manual-insertion','AssignInvoicesController@submitManulInsertion');
    Route::post('mass-match-manual-insertion','AssignInvoicesController@submitMassMatchManulInsertion');
  	  /* Accountant */
      Route::get('/accountant/dashboard','AccountantController@dashboard')->name('accountant.dashboard');
  	  Route::group(['prefix' => 'accountant'], function () {
      Route::get('/record-details/{id}','AccountantController@record')->name('accountant.record.details');
      Route::get('/record-details/{id}/update','AccountantController@recordUpdate')->name('accountant.record.details.update');
      Route::post('/record-details/update','AccountantController@recordUpdation')->name('accountant.record.details.updation');
      Route::post('/record-details/updation','AccountantController@disApprove')->name('accountant.record.details.cancel');
      Route::get('/record/approve/{id}','AccountantController@approveApplication')->name('accountant.approve.application');
	  //upload invoices
	  Route::post('/record/approve/email','AccountantController@approveApplicationWithemail')->name('accountant.approve.withemail');
      Route::post('/accountant-sub-companies-prices','AccountantController@fetchCompanyRates')->name('accountant.sub.companies.prices');
    });
	//upload invoice route


  	/* SMTP */
  	Route::get('/smtp-settings','SmtpController@index')->name('smtp');
  	Route::post('/smtp-settings','SmtpController@store')->name('smtp.store');

  	/* Invoice setting */
  	Route::get('/invoice-settings','SmtpController@invoiceshow');
  	Route::post('/invoice-settings-update','SmtpController@invoiceUpdate')->name('invoice.setting..update');
    Route::post('/delete-payonline_image', 'SmtpController@deletePayOnlineImage');
    Route::post('/delete-header_img', 'SmtpController@deleteheaderImg');

	/* Client Statement */
	Route::get('/client-statement','ReportsController@clientStatment')->name('client.statement');
    Route::get('/client-statement/report','ReportsController@clientStatmentReport')->name('client.statement.report');
  	Route::get('/fetch-sub-companies','ReportsController@fetchSubCompanies');
      Route::get('/account-statement','ReportsController@accountStatment')->name('account.statement');
    //   Route::get('/account-statement','ReportsController@accountStatment')->name('account.statement');
      Route::get('/AccountStatmentReport/report','ReportsController@AccountStatmentReport')->name('Account.statement.report');

  	/* Purchase Statement */
	Route::get('/purchase-statement','ReportsController@purchaseStatment')->name('purchase.statement');
    Route::get('/purchase-statement/report','ReportsController@purchaseStatmentReport')->name('purchase.statement.report');

  	/* Supplier Report */
  	Route::get('/supplier-companies-report','SupplierReportsController@supplierReport')->name('supplier.report');
  	Route::get('/supplier-companies-report-result','SupplierReportsController@supplierReportGeneration');
  	Route::get('/supplier-report-details/{id}','SupplierReportsController@supplierReportIndividual');

  	/* Income Report */
  	Route::get('/income-report','ReportsController@incomeReport')->name('income.report');
    Route::get('/purchase-report','ReportsController@purchaseReport')->name('purchase.report');
    Route::get('/income/report','ReportsController@incomeReportGet');
    Route::get('/purchase-report-pdf','ReportsController@purchaseReportGet');

    /* Account Summary Report */
    Route::get('/accounting-summary-report','ReportsController@accountSummary')->name('accounting.summary.report');
    Route::get('/accounting-summary/report','ReportsController@accountSummaryReportGet');

  	/* Deliveries */
  	Route::get('/deliveries-summary','DeliverySummariesController@deliveriesSummary')->name('deliveries.summary');
  	Route::get('/deliveries-summary-result','DeliverySummariesController@deliveriesSummaryResult')->name('deliveries.summary.result');
  	Route::get('/deliveries-summary-result-details/{date}','DeliverySummariesController@deliveriesSummaryResultDetails')->name('deliveries.summary.details');

    /* Client Detail Report */
    Route::get('/client-detail-report','ReportsController@clientDetailReport')->name('client.detail.report');
    Route::get('/client-detail-report-details','ReportsController@clientDetailReportGet');

    Route::get('/company-detail-report','ReportsController@companyDetailReport')->name('company.detail.report');
    Route::get('/company-detail-report-details','ReportsController@companyDetailReportGet');

  	/* Delivery Detail Report */
    Route::get('/delivery-detail-view','ReportsController@DeliveryDetailView')->name('delivery.detail.view');
    Route::get('/delivey-detail/report','ReportsController@DeliveryDetailReportGet');

  	/* Income Statistics Report */
  	Route::get('income-statistics-report','IncomeReportController@index');
  	Route::get('income-statistics-report-details','IncomeReportController@incomeStatisticsReport');
  	Route::get('income-statistics-report-details/{date}','IncomeReportController@incomeStatisticsReportDetails')->name('income.statistics.details');

    /* Driver */
  	Route::get('/driver','HomeController@driver');
    Route::get('/driver/change-password','HomeController@changePassword');
    Route::get('/driver/profile',function(){
       return view('driver.profile.index');
    });

  	Route::get('/driver/edit-profile',function(){
       return view('driver.profile.edit');
    });

    Route::post('/driver-profile-update/{id}','HomeController@DriverProfileUpdate');
  	Route::post('driver-agreement','HomeController@agreement');
  	Route::post('/driver','HomeController@submit')->name('driver.submit');

  	/* Purchase vs Sales */
  	Route::get('/purchases-vs-sales','PurchasesVsSalesController@index')->name('purchases.vs.sales');
  	Route::get('/purchases-vs-sales/{id}','PurchasesVsSalesController@show')->name('purchases.vs.sales.details');
  	Route::get('/purchases-vs-sales-company','PurchasesVsSalesController@purchasesVsSalesCompany')->name('purchases.vs.sales.company');

  	/* Expenses */
  	Route::get('/expenses','ExpensesController@index')->name('expenses');
  	Route::get('/expenses-create','ExpensesController@create')->name('expenses.create');
  	Route::post('/expenses-store','ExpensesController@store');
  	Route::get('/expenses-edit/{id}','ExpensesController@edit')->name('expenses.edit');
  	Route::post('/expenses-update/{id}','ExpensesController@update');
  	Route::get('/expenses-show/{id}','ExpensesController@show')->name('expenses.show');
    Route::post('/delete-expense_image','ExpensesController@deleteExpenseAttachment');

});
//excel
Route::get('export', 'RecordsController@export')->name('export');
Route::get('purchase_export', 'PurchaseController@export')->name('purchase.export');

/* Clear Cache Route */
Route::get('clear-cache', function () {
    Artisan::call('config:clear');
    Artisan::call('cache:clear');
    Artisan::call('view:clear');
    Session::flush();
	return redirect('/login');
});
/* MyOB Route */
Route::get('/myob', 'MyobController@myobredirect')->name('myobredirect'); // Authenticate MyOB
Route::get('/myob/callback', 'MyobController@myob_callback')->name('callback'); // Get Response from MyOB to generate Access Token
Route::get('/myob/tokenRefresh', 'MyobController@refresh_token')->name('tokenrefresh'); // Renew Expired Access Token
Route::get('/updateitem','MyobController@updateitem')->name('updateitem');
Route::get('/myob-setting','MyobController@setting')->name('myob-setting');
Route::post('/myob-setting_update','MyobController@updatesetting')->name('update.myob.settings');
Route::post ( '/updateMyobStatus', 'MyobController@updateMyobStatus' )->name('updateMyobStatus');

// all sync
Route::get( '/sync-to-myob', 'MyobController@syncmyob' )->name('sync.myob');
// Route::post( '/scanning', 'MyobController@scanner' )->name('post.portScanner');

// delete routes
Route::get('/delete-data','DeleteController@delete')->name('delete.data');
Route::get('/delete-contacts','DeleteController@deleteContacts')->name('delete.contacts');
Route::get('/delete-coa','DeleteController@deleteCOA')->name('delete.coa');
Route::get('/delete-employee','DeleteController@deleteEmployees')->name('delete.employee');
Route::get('/delete-items','DeleteController@deleteItems')->name('delete.items');
Route::get('/delete-supplier','DeleteController@deleteSupplier')->name('delete.supplier');
Route::get('/delete-personal','DeleteController@deletePersonal')->name('delete.personal');
