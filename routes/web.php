<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Controller;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\HsnController;
use App\Http\Controllers\GstPercentController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\DistributionController;
use App\Http\Controllers\UserDistributionController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ChartController;
use App\Http\Controllers\StateController;
use App\Http\Controllers\DistrictController;
use App\Http\Controllers\AreaController;
use App\Http\Controllers\ProductChargeController;
use App\Http\Controllers\PushNotificationsController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\ExpenseMasterController;
use App\Http\Controllers\TasksController;
use App\Http\Controllers\FacebookController;
use App\Http\Controllers\RazorpayController;
use App\Http\Controllers\HolidayController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\ContractTypeController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CustomerContactController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\UtilitiesController;
use App\Http\Controllers\LeadSourcesController;
use App\Http\Controllers\LeadStatusController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ProposalController;
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

Route::get('/', [HomeController::class, 'index']);

Auth::routes();
Route::group(['middleware' => ['auth','has_permission']], function () {
    
    Route::resource('/users', UserController::class);
    Route::post('quickupdate/user/status/{id}',[UserController::class,'status_update'])->name('user.status.update');
    Route::get('user/list/api',[UserController::class,'user_list_api'])->name('user.list.api');

    Route::resource('roles', RoleController::class);
    Route::resource('category', CategoryController::class);
    Route::resource('hsn', HsnController::class);
    Route::resource('gst', GstPercentController::class);

    Route::resource('item', ItemController::class);
    Route::get('item/list/api',[ItemController::class,'item_list_api'])->name('item.list.api');

    Route::resource('stocks', StockController::class);
    Route::get('stock/list/api',[StockController::class,'stock_list_api'])->name('stock.list.api');
    
    Route::resource('vendors', VendorController::class);  
    Route::resource('stock-distributions', DistributionController::class); 
    Route::resource('proposal-orders', ProposalController::class); 
    Route::resource('purchase-order', PurchaseOrderController::class); 
    Route::resource('expense-master', ExpenseMasterController::class); 
    
    Route::resource('expenses', ExpenseController::class); 
    Route::resource('states', StateController::class); 
    Route::resource('districts', DistrictController::class); 
    Route::resource('areas', AreaController::class); 
    Route::resource('product_charge', ProductChargeController::class); 
    Route::resource('tasks', TasksController::class); 
     
    Route::get('switch-to-kanban/tasks',[TasksController::class,'switchToKanBan'])->name('kanban.tasks');
    Route::get('switch-to-kanban/mytasks',[TasksController::class,'mytaskSwitchToKanBan'])->name('kanban.mytasks');
    Route::get('mytasks', [TasksController::class,'myTasksIndex'])->name('mytasks.index'); 
    Route::get('mytasks/{id}', [TasksController::class,'myTasksView'])->name('mytasks.view'); 
    Route::put('mytasks/{id}', [TasksController::class,'myTasksUpdate'])->name('mytasks.update'); 

    Route::get('profit-chart', [ChartController::class,'index'])->name('profit-chart.index'); 
     
    Route::get('users-stock/list', [UserDistributionController::class,'users_stock_list'])->name('users-stock.list');
    Route::resource('local-stock-distribution', UserDistributionController::class);
    
    Route::get('invoice/setting',[SettingController::class, 'invoice_master'])->name('invoice.master');
    Route::get('billing/setting',[SettingController::class, 'billing_master'])->name('billing.master');
    Route::get('email/setting',[SettingController::class, 'email_master'])->name('email-master.index');
    Route::get('sms/setting',[SettingController::class, 'sms_master'])->name('sms.master');
    Route::get('general/setting',[SettingController::class, 'general_master'])->name('general.master');
    Route::get('payment-gateway/setting',[SettingController::class, 'paymentGatewayMaster'])->name('payment-gateway.master');
    Route::get('facebook-page-subscription/setting',[FacebookController::class, 'facebookPageSubscription'])->name('facebook-page-subscription.master');

    Route::resource('holiday',HolidayController::class);
    Route::resource('leave',LeaveController::class);
    Route::resource('calendar',CalendarController::class);
    Route::get('attendance',[AttendanceController::class,'attendance_index'])->name('attendance.index');
    Route::get('attendance/api',[AttendanceController::class,'attendance_api'])->name('attendance.api');

    Route::resource('contract',ContractController::class);
    Route::resource('contract-type',ContractTypeController::class);
    Route::resource('customers',CustomerController::class);
    Route::get('customer-contact/{customer_id}',[CustomerContactController::class,'index']);
    Route::post('customer-contact/{customer_id}',[CustomerContactController::class,'store']);
    Route::get('customer-contact/{customer_id}/{id}/edit',[CustomerContactController::class,'edit']);
    Route::put('customer-contact/{customer_id}/{id}',[CustomerContactController::class,'update']);
    Route::delete('customer-contact/{customer_id}/{id}',[CustomerContactController::class,'delete']);

    Route::resource('announcements', AnnouncementController::class); 
    Route::resource('lead-sources', LeadSourcesController::class); 
    Route::resource('lead-status', LeadStatusController::class); 
    Route::resource('leads', LeadController::class); 

    Route::group(['prefix'=> 'reports','as'=>'reports.'],function(){
        Route::get('sales', [ReportController::class,'salesReportIndex'])->name('sales.index');
        Route::get('sales-invoices', [ReportController::class,'salesInvoiceReportsApi'])->name('sales.invoices');
        Route::get('sales-item', [ReportController::class,'salesItemReportsApi'])->name('sales.items');
        Route::get('sales-payment', [ReportController::class,'salesPaymentReportsApi'])->name('sales.payments');
        Route::get('sales-purchase-order', [ReportController::class,'salesPurchaseOrderReportsApi'])->name('sales.credit-note');
    });

});

// apis or not included url in permission
Route::group(['middleware' => ['auth']],function(){
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    Route::get('/get/items/by/category',[StockController::class, 'get_items_by_category'])->name('category.items');
    Route::get('/get/items/details',[StockController::class, 'get_items_details'])->name('item.details');
    Route::get('/delete/item/photo/{id}',[ItemController::class, 'delete_item_photo'])->name('delete.item.photo');

    // invoice
    Route::post('invoice/setting',[SettingController::class, 'save_invoice_master'])->name('save.invoice.master');
    Route::post('billing/setting',[SettingController::class, 'save_billing_master'])->name('save.billing.master');
    Route::post('email/setting',[SettingController::class, 'email_master_db'])->name('email.setting.save');
    Route::post('sms/setting',[SettingController::class, 'sms_master_db'])->name('sms.setting.save');
    Route::post('general/setting',[SettingController::class, 'general_master_db'])->name('general.setting.save');
    Route::post('payment-gateway/setting',[SettingController::class, 'paymentGatewayMasterDb'])->name('rpay.setting.update');

    Route::get('get/users/by/role',[DistributionController::class,'get_user'])->name('role.user');
    Route::get('get/stock/item/details',[DistributionController::class,'get_stock_item_detail'])->name('stock.item.detail');

    Route::get('print/invoice/{id}',[DistributionController::class,'print_invoice'])->name('print.invoice');
    Route::get('print/singleinvoice/{id}',[DistributionController::class,'print_single_invoice'])->name('print.singleinvoice');
    
    Route::get('print/proposal-order/{id}',[ProposalController::class,'print_invoice'])->name('print.proposal.order');
    
    Route::get('print/purchase/invoice/{id}',[PurchaseOrderController::class,'print_purchase_invoice'])->name('print.purchase.invoice');
    
    Route::post('distribution/payment/form',[DistributionController::class, 'distribution_payment'])->name('distribution.payment');
    Route::post('local/distribution/payment/form',[UserDistributionController::class, 'distribution_payment'])->name('local.distribution.payment');

    Route::get('download/profit-chart/pdf', [ChartController::class,'download_pdf'])->name('profit-chart.pdf.download'); 
    Route::get('download/users/{type}', [UserController::class,'export_table'])->name('users.export'); 
    Route::get('download/vendors/{type}', [VendorController::class,'export_table'])->name('vendors.export'); 
    
    Route::get('user-profile/update', [UserController::class,'user_profile_update'])->name('userprofile.update');
    Route::post('update/user-profile/password', [UserController::class,'update_user_password'])->name('user.password.update');
    Route::put('user-profile/update/{id}', [UserController::class,'update'])->name('user.profile.db');

    Route::get('state/district/api',[AreaController::class,'getDistrictByState'])->name('state.district.list');
    Route::get('district/area/api',[AreaController::class,'getAreaByDistrict'])->name('district.area.list');
    Route::get('get/item/charge/api',[ProductChargeController::class,'getItemCharge'])->name('district.area.list');
    Route::get('get/user/invoice/setting/api',[SettingController::class,'getUserInvoiceSetting'])->name('user.invoice.setting');
    
    Route::post('request/product',[UserDistributionController::class,'request_product_from_admin'])->name('request.product');
    Route::get('usermarkreadsingle',[PushNotificationsController::class,'usermarkreadsingle'])->name('unreadsinglenotification');
    
    Route::get('task/status/update',[TasksController::class,'task_status_update'])->name('task.status.update');
    Route::get('task/attachment/delete/{id}',[TasksController::class,'task_attachment_delete'])->name('task.attachment.delete');
    Route::get('task/checklist/delete/{id}',[TasksController::class,'task_checklist_delete'])->name('task.checklist.delete');
    Route::post('task/upload/attachment',[TasksController::class,'task_upload_attachment'])->name('task.upload.attachment');
    Route::get('mark/task/checklist/{id}',[TasksController::class,'task_checklist_update'])->name('task.checklist.update');
    
    Route::get('leads/fb_page_subscription_api',[FacebookController::class,'fb_page_subscription_api'])->name('fb_page_subscription_api');

    Route::get('fb-page-subscription/destroy/{id}',[FacebookController::class, 'fb_page_subscription_delete'])->name('fb.sub.destroy');
    Route::post('leave/approve/{id}',[LeaveController::class,'leave_approved']);

    Route::get('checkin-checkout-data',[AttendanceController::class,'check_in_out_data'])->name('checkinout.data');
    Route::post('user-checkin',[AttendanceController::class,'checkin'])->name('checkin');
    Route::post('user-checkout',[AttendanceController::class,'checkout'])->name('checkout');

    Route::get('renew-contracts/{contract_id}',[ContractController::class,'renew_contract'])->name('renew.contract');
    Route::post('renew-contracts/{contract_id}',[ContractController::class,'renew_contract_store'])->name('renew.contract.store');

    Route::get('renew-contracts/delete/{renew_id}',[ContractController::class,'delete_renewal_contract']);

    Route::post('convert-to-customer/{lead_id}',[CustomerController::class,'convertToCustomer']);
    Route::get('lead-details/{lead_id}',[CustomerController::class,'getLeadDetails']);

    Route::get('update-status/kanban',[TasksController::class,'updateKanbanStatus']);
    Route::get('dismiss/announcement/{id}',[AnnouncementController::class,'dismissAnnouncement']);

    Route::get('backup',[UtilitiesController::class,'databaseBackupIndex']);
    Route::get('admin/download/{filename}',[UtilitiesController::class,'databaseBackupDownload'])->name('backup.download');
    Route::get('backup/create',[UtilitiesController::class,'databaseBackupCreate']);
    Route::get('backup/delete/{path}',[UtilitiesController::class,'databaseBackupDelete']);

    Route::get('import-leads',[LeadController::class,'lead_import']);
    Route::post('import-leads',[LeadController::class,'lead_import_db']);
});
Route::get('print/local/invoice/{id}',[UserDistributionController::class,'print_invoice'])->name('print.local.invoice');
Route::get('print/local/singleinvoice/{id}',[UserDistributionController::class,'print_single_invoice'])->name('print.local.singleinvoice');

Route::get('fb/save_token_globally',[FacebookController::class,'save_token_globally'])->name('fb.save_token_globally');
Route::post('leads/save_fb_page_subscription',[FacebookController::class,'save_fb_page_subscription'])->name('save.fb_page_subscription');

Route::get('stock-distribution/gateways/payment/{user_id}/{id}',[DistributionController::class,'payment_gateway']);

Route::post('stock-distribution/razorpay/payment/{user_id}/{order_id}', [RazorpayController::class, 'stockDistributionStore'])->name('stock-distribution.razorpay.payment.store');

Route::get('local-distribution/gateways/payment/{user_id}/{id}',[UserDistributionController::class,'payment_gateway']);

Route::post('local-distribution/razorpay/payment/{user_id}/{order_id}', [RazorpayController::class, 'localDistributionStore'])->name('local-distribution.razorpay.payment.store');
