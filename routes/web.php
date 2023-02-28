<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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


Auth::routes();
Route::redirect("/", url('login'));
Route::get('dashboard', [HomeController::class, 'index'])->name('home');
Route::get('test', [HomeController::class, 'test'])->name('test');
Route::get('download-inventory-report', 'HomeController@downloadInventoryReport');
Route::get('get-device-inventory-list', 'HomeController@getDeviceInventoryList');
Route::post('export-device-inventory-report', 'HomeController@downloadDeviceInventoryReport');
Route::post('get-device-backward-tracking', 'HomeController@getDeviceBackwardTracking')->name('get-device-backward-tracking');
Route::post('download-device-backward-tracking', 'HomeController@downloadDeviceBackwardTracking');
Route::post('get-spare-part-backward-tracking', 'HomeController@getSparePartBackwardTracking')->name('get-spare-part-backward-tracking');
Route::post('download-spare-part-backward-tracking', 'HomeController@downloadSparePartBackwardTracking');
/*..........................User Routes......................*/
Route::any('check_email', [App\Http\Controllers\DashboardController::class, 'CheckEmailExist']);
Route::any('edit_user/{id}', [App\Http\Controllers\DashboardController::class, 'AddUser']);
Route::get('user_list', [App\Http\Controllers\DashboardController::class, 'ShowUserList'])->name('user_list');
Route::any('add_user', [App\Http\Controllers\DashboardController::class, 'AddUser']);

// Route::get('user_list', [App\Http\Controllers\DashboardController::class,'ShowUserList']);
// Route::any('add_user', [App\Http\Controllers\DashboardController::class,'AddUser']);
Route::any('save_user', [App\Http\Controllers\DashboardController::class, 'SaveUser']);
Route::any('fetch_user_list', [App\Http\Controllers\DashboardController::class, 'FetchUserList']);
Route::any('update_user_status', [App\Http\Controllers\DashboardController::class, 'UpdateUserStatus']);
// Route::any('edit_user', [App\Http\Controllers\DashboardController::class,'EditUser']);
Route::any('update_user', [App\Http\Controllers\DashboardController::class, 'UpdateUser']);
Route::any('delete_user', [App\Http\Controllers\DashboardController::class, 'DeleteUser']);

Route::any('delete-purchase-order', 'PurchaseOrderController@deletePurchaseOrder')->name('delete-purchase-order');
Route::any('delete-purchase-order-part', 'PurchaseOrderController@deletePurchaseOrderPart')->name('delete-purchase-order-part');









/*..........................Spare Part Routes......................*/
Route::any('sparepart_list', 'SparePartController@SparepartList')->name('sparepart_list');
Route::any('sparepart_list_sku', 'SparePartController@SparepartListsku')->name('sparepart_list_sku');
Route::any('sparepart_list_auto', 'SparePartController@SparepartListAuto')->name('sparepart_list_auto');
Route::any('add_sparepart', 'SparePartController@SparepartForm');
Route::any('add_sparepart_auto', 'SparePartController@SparepartFormAuto');
Route::get('add_sparepart_auto/{id}', 'SparePartController@SparepartFormAuto');
Route::any('save_sparepart', 'SparePartController@SaveSparepart');
Route::any('save_sparepart_auto', 'SparePartController@SaveSparepartAuto');
Route::any('delete_sparepartproduct', 'SparePartController@DeleteSparepart');
Route::get('edit_sparepart/{id}', 'SparePartController@SparepartForm');
Route::post('get_html_price_color', 'SparePartController@GetHtmlPriceAndColor');
Route::post('get_html_price_color_auto', 'SparePartController@GetHtmlPriceAndColorAuto');
Route::post('get_html_sku_auto', 'SparePartController@GetHtmlSkuAuto');
Route::post('get_color_price_detail_by_seriesid', 'SparePartController@GetColorPriceDetailBySeriesid');
Route::post('export-spare-parts', 'SparePartController@dashboardSpareParts');
Route::get('export-spare-parts-sku', 'SparePartController@dashboardSparePartssku');
Route::post('upload-bulk-spare-parts', 'SparePartController@importSpartParts');
Route::post('fetch-model', 'ProductController@FetchModel');
Route::post('check-sku-number', 'SparePartController@checkSkuNumber');

//
Route::any('get_engg_activity_log', 'ElsProductController@get_engg_activity_log');
//

// Route::get('sparepart_list','SparePartController@SparepartList')->name('sparepart_list');
// Route::get('add_sparepart', 'SparePartController@SparepartForm');
// Route::post('save_sparepart','SparePartController@SaveSparepart');
// Route::post('delete_sparepartproduct','SparePartController@DeleteSparepart');
// Route::get('edit_sparepart/{id}','SparePartController@SparepartForm');
// Route::post('fetch-model', 'ProductController@FetchModel');
/*..........................product Routes......................*/
// Route::get('product_list', [App\Http\Controllers\ProductController::class,'ShowProductList'])->name('product_list');
// Route::any('add_product', [App\Http\Controllers\ProductController::class,'AddProduct']);
// Route::any('save_product', [App\Http\Controllers\ProductController::class,'SaveProduct']);
// Route::any('fetch_product_list', [App\Http\Controllers\ProductController::class,'FetchProductList']);
// Route::any('update_product_status', [App\Http\Controllers\ProductController::class,'UpdateProductStatus']);
// Route::any('edit_product', [App\Http\Controllers\ProductController::class,'EditProduct']);
// Route::any('update_product', [App\Http\Controllers\ProductController::class,'UpdateProduct']);
// Route::any('delete_product', [App\Http\Controllers\ProductController::class,'DeleteProduct']);


/*..........................ELS product Routes......................*/

Route::any('els_product_list', 'ElsProductController@ElsProductList')->name('els_product_list');
Route::get('fatch_els_product_list', 'ElsProductController@getElsProductList')->name('fatch_els_product_list');

Route::any('els_product_list_in', 'ElsProductController@ElsProductList_in')->name('els_product_list_in');

Route::get('fatch_els_product_list_in', 'ElsProductController@getElsProductList_in')->name('fatch_els_product_list_in');
Route::any('elsproduct_form', 'ElsProductController@ElsProductForm');
Route::any('save_elsproduct', 'ElsProductController@SaveElsproduct');
Route::any('deleteelsproduct', 'ElsProductController@DeleteElsproduct');
Route::get('edit_elsproduct/{id}', 'ElsProductController@ElsProductForm');
Route::any('delete_elsproduct', 'ElsProductController@DeleteelsProduct');
Route::any('export-els-product-report', 'ElsProductController@exportELSProduct');
Route::any('export-els-product-report-in', 'ElsProductController@exportELSProductin');


Route::any('phonecheck', 'ElsProductController@phonecheck');





Route::any('export-els-product-inv', 'ElsProductController@exportELSProductinv');
Route::any('upload-bulk-report', 'ElsProductController@uploadBulkProductReport')->name('upload.report');
Route::get('update-sku', 'ElsProductController@updateSKU');
Route::any('els_imei', 'ElsProductController@ElsImei');
Route::any('save_elsproductajax', 'ElsProductController@SaveElsproductajax');

/*..........................Order Request Routes......................*/
Route::any('request_order_list', 'RequestOrderController@RequestOrderList')->name('request_order_list');
Route::any('request_order_form', 'RequestOrderController@RequestOrderForm');
Route::any('get_html_multiple_request_order', 'RequestOrderController@GetHtmlMultipleRequestOrder');
Route::any('fetch_series_list', 'RequestOrderController@FetchSeriesList');
Route::any('fetch_colour', 'RequestOrderController@FetchColourList');
Route::any('save_request_order', 'RequestOrderController@SaveRequestOrder');
Route::any('delete_request_order', 'RequestOrderController@DeleteRequestOrder');
Route::any('get_part_list_by_id', 'RequestOrderController@GetPartListById');
Route::any('edit_request_order/{id}', 'RequestOrderController@RequestOrderForm');
Route::any('export-request-order-report', 'RequestOrderController@exportRequestOrder');

// Route::get('system-list', 'ElsProductController@ShowelsProductList')->name('system.list');
// Route::any('add_elsproduct', 'ElsProductController@AddelsProduct');
// Route::any('save_elsproduct', 'ElsProductController@SaveelsProduct');
// Route::any('fetch_elsproduct_list', 'ElsProductController@FetchelsProductList')->name('fetch.system.list');
// Route::any('update_elsproduct_status', 'ElsProductController@UpdateelsProductStatus');
// Route::any('edit_elsproduct', 'ElsProductController@EditelsProduct');
// Route::any('update_elsproduct', 'ElsProductController@UpdateelsProduct');

/*..........................Request Order Routes......................*/
Route::get('order-request-list', 'OrderRequestController@ShowOrderRequestList')->name('order-request-list');
Route::any('add_orderrequest', 'OrderRequestController@AddOrderRequest');
Route::any('save_orderrequest', 'OrderRequestController@SaveOrderRequest');
/*Route::any('fetch_orderrequest_list', 'OrderRequestController@FetchOrderRequest');*/
Route::any('update_orderrequest_status', 'OrderRequestController@UpdateOrderRequestStatus');
Route::any('edit_orderrequest/{id}', 'OrderRequestController@EditOrderRequest');
Route::any('update_orderrequest', 'OrderRequestController@UpdateOrderRequest');
Route::any('delete_orderrequest', 'OrderRequestController@DeleteOrderRequest');

Route::any('edit_request_order_data/{id}', 'OrderRequestController@EditRequestOrderView');


Route::any('get_request_order_data_by_id', 'OrderRequestController@GetRequestOrderDataById');

Route::any('get_model_colour_by_barcode', 'OrderRequestController@GetModelAndColourByBarcode');

Route::any('get_sparepart_product_list', 'OrderRequestController@GetSparepartProductList');

/*..........................Request Order Routes......................*/

Route::any('request_order_list', 'RequestOrderController@RequestOrderList')->name('request_order_list');
Route::any('request_order_form', 'RequestOrderController@RequestOrderForm');

Route::post('get_html_multiple_request_order', 'RequestOrderController@GetHtmlMultipleRequestOrder');

Route::any('fetch_series_list', 'RequestOrderController@FetchSeriesList');

Route::any('fetch_colour', 'RequestOrderController@FetchColourList');

Route::any('save_request_order', 'RequestOrderController@SaveRequestOrder');

Route::any('delete_request_order', 'RequestOrderController@DeleteRequestOrder');

Route::any('get_part_list_by_id', 'RequestOrderController@GetPartListById');

Route::any('edit_request_order/{id}', 'RequestOrderController@RequestOrderForm');

Route::any('fetch_barcode_deatils', 'RequestOrderController@FetchBarcodeDeatils');
Route::post('upload_request_order', 'RequestOrderController@uploadRequestOrder');
/*..........................Purchase Order Routes......................*/

Route::get('order-purchase-list', 'PurchaseOrderController@ShowPurchaseRequestList')->name('order-recived-product-list');
Route::get('fetch_orderpurchase_list', 'PurchaseOrderController@FetchOrderPurchase');
Route::get('add-purchase-order', 'PurchaseOrderController@AddOrderPurchase')->name('add-purchase-order');
Route::post('get-order-request-list', 'PurchaseOrderController@getOrderRequestList')->name('order-request-list-form');
Route::post('save-purchase-order', 'PurchaseOrderController@savePurchaseOrder')->name('save-purchase-order');
Route::get('edit-purchase-order/{id}', 'PurchaseOrderController@editPurchaseOrder')->name('edit-purchase-order');
Route::post('update-purchase-order', 'PurchaseOrderController@updatePurchaseOrder')->name('update-purchase-order');
Route::post('download-purchase-order', 'PurchaseOrderController@downloadPurchaseOrder')->name('download-purchase-order');
Route::post('get-series-colour', 'PurchaseOrderController@getSeriesColourList')->name('get-series-colour');
Route::post('get-series-unit-price', 'PurchaseOrderController@getSeriesUnitPrice')->name('get-series-unit-price');
Route::post('bulk-request-purchase-order', 'PurchaseOrderController@genratePurchaseOrder')->name('bulk-request-purchase-order');
Route::post('export-purchase-order', 'PurchaseOrderController@exportPurchaseOrder')->name('export-purchase-order');

/*..........................Order Recived Product Routes......................*/

Route::get('received-purchase-order', 'RecivedOrderController@ShowOrderRecivedProductList')->name('received-purchase-order');
Route::get('fetch_reveived_purchase_list', 'RecivedOrderController@FetchRevivedPurchaseParts');
Route::get('add-purchase-order-received-product-quantity', 'RecivedOrderController@AddOrderRecivedProduct')->name('add-received-quantity');
Route::post('purchase-order-parts-list-form', 'RecivedOrderController@getPurchaseOrderPartsList')->name('purchase-order-parts-list-form');
Route::post('save-recived-purchase-order', 'RecivedOrderController@saveRecivedPurchaseOrder')->name('save-recived-purchase-order');
Route::post('download-barcode-list', 'RecivedOrderController@downloadBarCodeList')->name('download-barcode-list');
Route::post('export-purchase-order-recived-report', 'RecivedOrderController@downloadRecivedPurchaseOrder')->name('export-purchase-order-recived-report');
Route::post('export-purchase-order-recived-barcode', 'RecivedOrderController@downloadRecivedPurchaseOrderBarcode')->name('export-purchase-order-recived-barcode');

/*..........................Stock In Barcode Routes......................*/
Route::get('stock-in-bardode', 'RecivedOrderController@stockInBarcodeList')->name('stock-in-bardode');
Route::post('add-barcode-in-stock', 'RecivedOrderController@addPartBarcodeInStock')->name('add-barcode-in-stock');
Route::post('set-iqc-status', 'RecivedOrderController@setIQCStatus')->name('set-iqc-status');
Route::get('fetch_stock_in_parts_list', 'RecivedOrderController@getInStockPartList');
Route::get('renew-pins', 'RecivedOrderController@getInStockPartList_new');
Route::post('export-spart-part-barcode-report', 'RecivedOrderController@downloadSparePartsBarcode');
Route::post('upload-stock-in-report', 'RecivedOrderController@getUploadStockIn');
Route::post('remark-stock-in-barcode', 'RecivedOrderController@addBarcodeRemark');

/*..........................Final Quality check failure Report Routes......................*/
Route::get('final-quality-failure-report', 'RecivedOrderController@finalQualityCheckReport');
Route::get('fetch_final_quality_report_list', 'RecivedOrderController@getFinalQualityCheckReportList');
Route::post('download-final-quality-check-report', 'RecivedOrderController@downloadFinalQualityCheckReport');
// Route::post('set-iqc-status', 'RecivedOrderController@setIQCStatus')->name('set-iqc-status');
// Route::post('export-spart-part-barcode-report', 'RecivedOrderController@downloadSparePartsBarcode');
// Route::post('upload-stock-in-report', 'RecivedOrderController@getUploadStockIn');

/*..........................Stock Parts List Routes......................*/
Route::get('stock-part-list', 'RecivedOrderController@stockPartList')->name('stock-part-list');
Route::get('fetch_stock_parts_list', 'RecivedOrderController@getStockPartList')->name('fetch_stock_parts_list');
Route::post('export-spart-part-report', 'RecivedOrderController@downloadStockPartReport');

/*..........................Allocate Engineers Routes......................*/
Route::get('allocated_els_product', 'ElsProductController@allocatedElsProductList')->name('allocated_els_product');
Route::get('fetch_assign_product_list', 'ElsProductController@getAllocatedElsProductList')->name('fetch_assign_product_list');
Route::get('add_engineer_in_product', 'ElsProductController@AllocatedProductPartsForm')->name('add_engineer_in_product');
Route::post('get-barcode-product-details', 'ElsProductController@getProductPartsDetails')->name('get-barcode-product-details');
Route::post('assign_engineer', 'ElsProductController@saveAssignEngineer')->name('assign_engineer');
Route::get('edit_assigned_engineer/{id}', 'ElsProductController@editAssignEngineer')->name('edit_assigned_engineer');

/*..........................Product Part Consumption Routes......................*/
Route::get('update-pin/{id}', 'ElsProductController@updatepin')->name('update-pin');



Route::get('consumed-parts-product-list', 'ElsProductController@consumedPartsProductList')->name('consumed-parts-product-list');
Route::get('part-consumed-product-list', 'ElsProductController@getConsumedPartProductList')->name('part-consumed-product-list');
Route::get('allocate_product_part', 'ElsProductController@allocateProductPart')->name('allocate_product_part');
Route::post('get-product-parts-details', 'ElsProductController@getELSProductPartsDetails')->name('get-product-parts-details');
Route::post('allocated_product_parts', 'ElsProductController@allocateProductPartPerBarcode')->name('allocated_product_parts');
Route::get('errorss', 'ElsProductController@errors')->name('allocated_product_parts');
Route::post('check-barcode-details', 'ElsProductController@checkBarcodeDetails')->name('check-barcode-details');
Route::post('save_elsproduct_status', 'ElsProductController@saveProductStatus')->name('save_elsproduct_status');
Route::post('remove-parts-barcode', 'ElsProductController@removePartBarcode')->name('remove-parts-barcode');
Route::post('add-more-parts', 'ElsProductController@addMoreParts')->name('add-more-parts');
Route::post('get-part-colours', 'ElsProductController@getPartColours')->name('get-part-colours');
Route::post('upload-allocation-enginner', 'ElsProductController@uploadAllocationEnginner')->name('upload-allocation-enginner');

/*..........................Product Quality Check Routes......................*/
Route::get('quality-check-product-list', 'ElsProductController@qualityCheckProductList')->name('quality-check-product-list');
Route::get('get-quality-check-product-list', 'ElsProductController@getQualityCheckProductList')->name('get-quality-check-product-list');
Route::get('allocate_check_product', 'ElsProductController@allocateCheckProduct')->name('allocate_check_product');
Route::post('get-product-details', 'ElsProductController@getELSProductDetails')->name('get-product-details');
Route::post('allocated_product_qulaity_checking', 'ElsProductController@assignProductForChecking')->name('allocated_product_qulaity_checking');
Route::post('save_qc_status', 'ElsProductController@saveQcStatus')->name('save_qc_status');

/*.......................... Product Quality Check Report Routes......................*/
Route::get('product-quality-check-report', 'ElsProductController@qualityCheckReport')->name('product-quality-check-report');
Route::get('download-quality-check-report', 'ElsProductController@downloadQualityCheckReport')->name('download-quality-check-report');

/*..........................Product Price Report Routes......................*/
Route::get('devices-price', 'ElsProductController@viewProductPrice');
Route::get('get-product-final-price-list', 'ElsProductController@getProductFinalPriceList');
Route::post('export-product-price-report', 'ElsProductController@downloadProductFinalPrice');
Route::post('add-more-expence', 'ElsProductController@addMoreExpence')->name('add-more-expence');
Route::post('add-more-expence-option', 'ElsProductController@addMoreExpenceOption')->name('add-more-expence-option');
Route::post('save_extra_expence', 'ElsProductController@saveExtraExpence');
Route::post('upload-device-price', 'ElsProductController@uploadBulkDevicePrice');

/*..........................Product Part Consumption Routes......................*/
Route::get('video-upload-product-list', 'ElsProductController@videoUploadProductList')->name('video-upload-product-list');
Route::get('get-video-upload-product-list', 'ElsProductController@getVideoUploadProductList')->name('get-video-upload-product-list');
Route::post('upload_elsproduct_video', 'ElsProductController@uploadProductVideo')->name('upload_elsproduct_video');
Route::post('download-upload-video', 'ElsProductController@downloadVideo')->name('download-upload-video');
Route::post('delete-upload-video', 'ElsProductController@deleteUploadProductVideo')->name('delete-upload-video');
Route::get('add-more-video', 'ElsProductController@addMoreVideo')->name('add-more-video');
Route::post('view-video-list', 'ElsProductController@uploadProductVideoList')->name('view-video-list');

/*.......................... Engineer Statistics Report Routes......................*/
Route::get('engineer-statistics-report', 'ElsProductController@enginnerStatisticsReport')->name('engineer-statistics-report');
Route::get('get-enginner-work-report-list', 'ElsProductController@getEngineerWorkReportList')->name('get-enginner-work-report-list');
Route::post('set_date_range_filter', 'ElsProductController@setDateRangeFilter');
Route::get('download-work-report', 'ElsProductController@downloadWorkReport')->name('download-work-report');




Route::get('production-statistics-report', 'ElsProductController@productionStatisticsReport')->name('production-statistics-report');
Route::get('get-production-work-report-list', 'ElsProductController@getproductionWorkReportList')->name('get-production-work-report-list');
Route::post('set_date_range_filterp', 'ElsProductController@setDateRangeFilterp');
Route::get('download-production-report', 'ElsProductController@downloadproductionReport')->name('download-production-report');






Route::get('fqc-statistics-report', 'ElsProductController@fqcStatisticsReport')->name('fqc-statistics-report');
Route::get('get-fqc-work-report-list', 'ElsProductController@getfqcWorkReportList')->name('get-fqc-work-report-list');
Route::post('set_date_range_filterf', 'ElsProductController@setDateRangeFilterf');
Route::get('download-fqc-report', 'ElsProductController@downloadfqcReport')->name('download-fqc-report');




Route::get('sparepart_aging', 'RecivedOrderController@sparepart_aging')->name('sparepart_aging');
Route::get('get-sparepart_aging', 'ElsProductController@getsparepart_aging')->name('get-sparepart_aging');
Route::get('fetch_stock_in_parts_lista', 'RecivedOrderController@getInStockPartLista');
Route::post('set_date_range_filters', 'ElsProductController@setDateRangeFilters');
Route::get('download-sparepart_aging', 'ElsProductController@downloadsparepart_aging')->name('download-sparepart_aging');




Route::get('device_aging', 'ElsProductController@device_aging')->name('device_aging');
Route::get('get-device_aging', 'ElsProductController@getdevice_aging')->name('get-device_aging');
Route::get('fatch_els_product_list_ind', 'ElsProductController@getElsProductList_ind')->name('fatch_els_product_list_ind');
Route::post('set_date_range_filterd', 'ElsProductController@setDateRangeFilterd');
Route::get('download-device_aging', 'ElsProductController@downloaddevice_aging')->name('download-device_aging');



Route::get('po-wise-iqc-report', 'RecivedOrderController@po_wise_iqc_report')->name('po-wise-iqc-report');
Route::get('get-po_aging', 'ElsProductController@getpo_aging')->name('get-po_aging');
Route::get('fetch_stock_in_parts_listpp', 'RecivedOrderController@getInStockPartListpp');
Route::post('set_date_range_filterpp', 'ElsProductController@setDateRangeFilterpp');
Route::get('download-po_aging', 'ElsProductController@downloadpo_aging')->name('download-po_aging');


Route::get('fresh_faulty', 'RecivedOrderController@fresh_faulty')->name('fresh_faulty');
Route::get('get-fresh_faulty', 'ElsProductController@getfresh_faulty')->name('get-fresh_faulty');
Route::get('fetch_stock_in_parts_listppf', 'RecivedOrderController@getInStockPartListppf');
Route::post('set_date_range_filterppp', 'ElsProductController@setDateRangeFilterppp');
Route::get('download-fresh_faulty', 'ElsProductController@downloadfresh_faulty')->name('download-fresh_faulty');




Route::get('extra_part', 'RecivedOrderController@extra_part')->name('extra_part');
Route::get('get-extra_part', 'ElsProductController@getextra_part')->name('get-extra_part');
Route::get('fetch_stock_in_parts_listppe', 'RecivedOrderController@getInStockPartListppe');
Route::post('set_date_range_filterppe', 'ElsProductController@setDateRangeFilterppe');
Route::get('download-extra_part', 'ElsProductController@downloadextra_part')->name('download-extra_part');






/*Menu Master(need to add) and comment/remove already exist menu routes .--------------------*/

Route::any('menu_list', 'MenuController@ShowMenuList')->name('menu_list');

Route::any('add_menu', 'MenuController@MenuForm');

Route::any('save_menu', 'MenuController@SaveMenu');
Route::any('update_menu_status', 'MenuController@UpdateMenuStatus');

Route::any('delete_menu', 'MenuController@DeleteMenu');

Route::any('edit_menu/{id}', 'MenuController@MenuForm');


//---------------Menu Permission Routes (need to add)----------------

Route::any('menu_permission_list', 'MenuPermissionController@ShowPermissionList')->name('menu_permission_list');
Route::any('menu_permission_form', 'MenuPermissionController@MenuPermissionForm');
Route::any('save_menu_permission', 'MenuPermissionController@SaveMenuPermission');
Route::any('edit_menu_permission/{id}', 'MenuPermissionController@MenuPermissionForm');

// ELS Product Status
Route::get('status', 'ElsProductController@viewStatus');
Route::get('get-status-list', 'ElsProductController@getStatusList');
Route::get('add-status', 'ElsProductController@addStatus');
Route::get('edit-status/{id}', 'ElsProductController@editStatus');
Route::post('save_product_status', 'ElsProductController@saveStatusDetails');
Route::post('update_product_status', 'ElsProductController@updateStatusDetails');

// ELS Product Sub Status
Route::get('sub-status', 'ElsProductController@viewSubStatus');
Route::get('get-els-product-status-list', 'ElsProductController@getSubStatusList');
Route::get('add-product-status', 'ElsProductController@addSubStatus');
Route::get('edit-product-status/{id}', 'ElsProductController@editSubStatus');
Route::post('save_els_product_status', 'ElsProductController@saveSubStatusDetails');
Route::post('update_els_product_status', 'ElsProductController@updateSubStatusDetails');

// ELS Product Status
Route::get('qc-status', 'ElsProductController@viewQcStatus');
Route::get('get-qc-status-list', 'ElsProductController@getQcStatusList');
Route::get('add-qc-status', 'ElsProductController@addQcStatus');
Route::get('edit-qc-status/{id}', 'ElsProductController@editQcStatus');
Route::post('save_qc_deatils', 'ElsProductController@saveQcStatusDetails');
Route::post('update_qc_status', 'ElsProductController@updateQcStatusDetails');


//Assign Routes (already exist , need to replace only) at line number 92-----------

Route::get('assign_role_list', [App\Http\Controllers\AssignRoleController::class, 'AssignRoleList'])->name('assign_role_list');
Route::any('add_role', [App\Http\Controllers\AssignRoleController::class, 'AddAssignRole']);
Route::any('save_role', [App\Http\Controllers\AssignRoleController::class, 'SaveAssignRole']);
Route::any('fetch_role_list', [App\Http\Controllers\AssignRoleController::class, 'FetchAssignRoleList']);
Route::any('update_role_status', [App\Http\Controllers\AssignRoleController::class, 'UpdateAssignRoleStatus']);
Route::any('edit_role/{id}', [App\Http\Controllers\AssignRoleController::class, 'AddAssignRole']);
Route::any('update_role', [App\Http\Controllers\AssignRoleController::class, 'UpdateAssignRole']);
Route::any('delete_role', [App\Http\Controllers\AssignRoleController::class, 'DeleteRoleProduct']);

// Route::any('add_orderpurchase/{id}', 'PurchaseOrderController@AddOrderPurchase');
// Route::any('save_orderpurchase', 'PurchaseOrderController@SaveOrderPurchase');

// Route::any('update_orderpurchase_status', 'PurchaseOrderController@UpdateOrderPurchaseStatus');
// Route::any('edit_orderpurchase', 'PurchaseOrderController@EditOrderPurchase');
// Route::any('update_orderpurchase', 'PurchaseOrderController@UpdateOrderPurchase');
// Route::any('delete_orderpurchase', 'PurchaseOrderController@DeleteOrderPurchase');

/*..........................Role Routes......................*/
Route::get('assign_role_list', [App\Http\Controllers\AssignRoleController::class, 'AssignRoleList']);
Route::any('add_role', [App\Http\Controllers\AssignRoleController::class, 'AddAssignRole']);
Route::any('save_role', [App\Http\Controllers\AssignRoleController::class, 'SaveAssignRole']);
Route::any('fetch_role_list', [App\Http\Controllers\AssignRoleController::class, 'FetchAssignRoleList']);
Route::any('update_role_status', [App\Http\Controllers\AssignRoleController::class, 'UpdateAssignRoleStatus']);
Route::any('edit_role', [App\Http\Controllers\AssignRoleController::class, 'EditAssignRole']);
Route::any('update_role', [App\Http\Controllers\AssignRoleController::class, 'UpdateAssignRole']);
Route::any('delete_role', [App\Http\Controllers\AssignRoleController::class, 'DeleteRoleProduct']);


/*..........................Menu Routes......................*/
// Route::get('menu_master_list', [App\Http\Controllers\AssignMenuController::class,'ShowMenuList']);
// Route::any('add_menu', [App\Http\Controllers\AssignMenuController::class,'AddMenu']);
// Route::any('save_menu', [App\Http\Controllers\AssignMenuController::class,'SaveMenu']);
// Route::any('fetch_menu_list', [App\Http\Controllers\AssignMenuController::class,'FetchMenuList']);
// Route::any('update_menu_status', [App\Http\Controllers\AssignMenuController::class,'UpdateMenuStatus']);
// Route::any('edit_menu', [App\Http\Controllers\AssignMenuController::class,'EditMenu']);
// Route::any('update_menu', [App\Http\Controllers\AssignMenuController::class,'UpdateMenu']);
// Route::any('delete_menu', [App\Http\Controllers\AssignMenuController::class,'DeleteMenu']);

/*..........................MenuAssignRole Routes......................*/
Route::get('menurole_list', [App\Http\Controllers\AssignMenuController::class, 'ShowMenuRoleList']);
Route::any('add_menurole', [App\Http\Controllers\AssignMenuController::class, 'AddMenuRole']);
Route::any('save_menurole', [App\Http\Controllers\AssignMenuController::class, 'SaveMenuRole']);
Route::any('fetch_menurole_list', [App\Http\Controllers\AssignMenuController::class, 'FetchMenuRoleList']);
Route::any('update_menurole_status', [App\Http\Controllers\AssignMenuController::class, 'UpdateMenuRoleStatus']);
Route::any('edit_menurole', [App\Http\Controllers\AssignMenuController::class, 'EditMenuRole']);
Route::any('update_menurole', [App\Http\Controllers\AssignMenuController::class, 'UpdateMenuRole']);
Route::any('delete_menurole', [App\Http\Controllers\AssignMenuController::class, 'DeleteMenuRole']);

/*Request Order Routes--------------------------------------*/
Route::any('request_order', [App\Http\Controllers\RequestOrderController::class, 'RequestOrder']);
Route::any('add_request_order', [App\Http\Controllers\RequestOrderController::class, 'AddRequestOrder']);
Route::any('edit_request_order', [App\Http\Controllers\RequestOrderController::class, 'EditRequestOrder']);
/*Purchase Order Routes--------------------------------------*/
Route::any('purchase_order', [App\Http\Controllers\PurchaseOrderController::class, 'PurchaseOrder']);
Route::any('add_purchase_order', [App\Http\Controllers\PurchaseOrderController::class, 'AddPurchaseOrder']);
Route::any('edit_purchase_order', [App\Http\Controllers\PurchaseOrderController::class, 'EditPurchaseOrder']);


/*---------------isha 06_04_2021---------------*/
/*--------------------------Maters Routes------------------------*/
Route::get('category_list', [App\Http\Controllers\MasterCategoryController::class, 'ShowCategoryList']);
Route::any('add_category', [App\Http\Controllers\MasterCategoryController::class, 'AddCategory']);
Route::any('save_category', [App\Http\Controllers\MasterCategoryController::class, 'SaveCategory']);

Route::any('fetch_category_list', [App\Http\Controllers\MasterCategoryController::class, 'FetchCategoryList']);

Route::any('update_category_status', [App\Http\Controllers\MasterCategoryController::class, 'UpdateCategoryStatus']);

Route::any('edit_category', [App\Http\Controllers\MasterCategoryController::class, 'EditCategory']);

Route::any('update_category', [App\Http\Controllers\MasterCategoryController::class, 'UpdateCategory']);


Route::any('delete_category', [App\Http\Controllers\MasterCategoryController::class, 'DeleteCategory']);

/*-----------------------Brand Master Routes-------------------------------*/
/*--------------------------Maters Routes------------------------*/
Route::get('brand_list', [App\Http\Controllers\MasterBrandController::class, 'ShowBrandList']);
Route::any('add_brand', [App\Http\Controllers\MasterBrandController::class, 'AddBrand']);
Route::any('save_brand', [App\Http\Controllers\MasterBrandController::class, 'SaveBrand']);


Route::any('fetch_brand_list', [App\Http\Controllers\MasterBrandController::class, 'FetchBrandList']);

Route::any('update_brand_status', [App\Http\Controllers\MasterBrandController::class, 'UpdateBrandStatus']);

Route::any('edit_brand', [App\Http\Controllers\MasterBrandController::class, 'EditBrand']);

Route::any('update_brand', [App\Http\Controllers\MasterBrandController::class, 'UpdateBrand']);


Route::any('delete_brand', [App\Http\Controllers\MasterBrandController::class, 'DeleteBrand']);



/*----------------------Vendor Master Routes-------------------------------*/
/*--------------------------Maters Routes------------------------*/
Route::get('vendor_list', [App\Http\Controllers\MasterVendorController::class, 'ShowVendorList']);
Route::any('add_vendor', [App\Http\Controllers\MasterVendorController::class, 'AddVendor']);
Route::any('save_vendor', [App\Http\Controllers\MasterVendorController::class, 'SaveVendor']);
Route::any('fetch_vendor_list', [App\Http\Controllers\MasterVendorController::class, 'FetchVendorList']);
Route::any('update_vendor_status', [App\Http\Controllers\MasterVendorController::class, 'UpdateVendorStatus']);
Route::any('edit_vendor/{id}', [App\Http\Controllers\MasterVendorController::class, 'editVendor']);
Route::any('update_vendor', [App\Http\Controllers\MasterVendorController::class, 'UpdateVendor']);
Route::any('delete_vendor', [App\Http\Controllers\MasterVendorController::class, 'DeleteVendor']);

/*----------------------Modelr Master Routes-------------------------------*/
/*--------------------------Maters Routes------------------------*/
Route::get('modelr_list', [App\Http\Controllers\MasterModelrController::class, 'ShowModelrList']);
Route::any('add_modelr', [App\Http\Controllers\MasterModelrController::class, 'AddModelr']);
Route::any('save_modelr', [App\Http\Controllers\MasterModelrController::class, 'SaveModelr']);
Route::any('fetch_modelr_list', [App\Http\Controllers\MasterModelrController::class, 'FetchModelrList']);
Route::any('update_modelr_status', [App\Http\Controllers\MasterModelrController::class, 'UpdateModelrStatus']);
Route::any('edit_modelr', [App\Http\Controllers\MasterModelrController::class, 'EditModelr']);
Route::any('update_modelr', [App\Http\Controllers\MasterModelrController::class, 'UpdateModelr']);
Route::any('delete_modelr', [App\Http\Controllers\MasterModelrController::class, 'DeleteModelr']);

/*----------------------Model Master Routes-------------------------------*/
/*--------------------------Maters Routes------------------------*/
Route::get('model_list', [App\Http\Controllers\MasterModelController::class, 'ShowModelList']);
Route::any('add_model', [App\Http\Controllers\MasterModelController::class, 'AddModel']);
Route::any('save_model', [App\Http\Controllers\MasterModelController::class, 'SaveModel']);
Route::any('fetch_model_list', [App\Http\Controllers\MasterModelController::class, 'FetchModelList']);
Route::any('update_model_status', [App\Http\Controllers\MasterModelController::class, 'UpdateModelStatus']);
Route::any('edit_model', [App\Http\Controllers\MasterModelController::class, 'EditModel']);
Route::any('update_model', [App\Http\Controllers\MasterModelController::class, 'UpdateModel']);
Route::any('delete_model', [App\Http\Controllers\MasterModelController::class, 'DeleteModel']);

Route::any('get_active_brand_list', [App\Http\Controllers\MasterModelController::class, 'GetActiveBrandList']);


//color master routes..........................................
Route::get('color_list', [App\Http\Controllers\MasterColorController::class, 'ShowColorList']);
Route::any('add_color', [App\Http\Controllers\MasterColorController::class, 'AddColor']);
Route::any('save_color', [App\Http\Controllers\MasterColorController::class, 'SaveColor']);

Route::any('fetch_color_list', [App\Http\Controllers\MasterColorController::class, 'FetchColorList']);

Route::any('update_color_status', [App\Http\Controllers\MasterColorController::class, 'UpdateColorStatus']);

Route::any('edit_color', [App\Http\Controllers\MasterColorController::class, 'EditColor']);

Route::any('update_color', [App\Http\Controllers\MasterColorController::class, 'UpdateColor']);


Route::any('delete_color', [App\Http\Controllers\MasterColorController::class, 'DeleteColor']);

//Rom Master ..........................................................................
Route::get('ram_list', [App\Http\Controllers\MasterRAMController::class, 'ShowRAMList']);
Route::any('add_ram', [App\Http\Controllers\MasterRAMController::class, 'AddRAM']);
Route::any('save_ram', [App\Http\Controllers\MasterRAMController::class, 'SaveRAM']);

Route::any('fetch_ram_list', [App\Http\Controllers\MasterRAMController::class, 'FetchRAMList']);

Route::any('update_ram_status', [App\Http\Controllers\MasterRAMController::class, 'UpdateRAMStatus']);

Route::any('edit_ram', [App\Http\Controllers\MasterRAMController::class, 'EditRAM']);

Route::any('update_ram', [App\Http\Controllers\MasterRAMController::class, 'UpdateRAM']);


Route::any('delete_ram', [App\Http\Controllers\MasterRAMController::class, 'DeleteRAM']);

//ROM Master...........
Route::get('rom_list', [App\Http\Controllers\MasterROMController::class, 'ShowROMList']);
Route::any('add_rom', [App\Http\Controllers\MasterROMController::class, 'AddROM']);
Route::any('save_rom', [App\Http\Controllers\MasterROMController::class, 'SaveROM']);

Route::any('fetch_rom_list', [App\Http\Controllers\MasterROMController::class, 'FetchROMList']);

Route::any('update_rom_status', [App\Http\Controllers\MasterROMController::class, 'UpdateROMStatus']);

Route::any('edit_rom', [App\Http\Controllers\MasterROMController::class, 'EditROM']);

Route::any('update_rom', [App\Http\Controllers\MasterROMController::class, 'UpdateROM']);


Route::any('delete_rom', [App\Http\Controllers\MasterROMController::class, 'DeleteROM']);

//Grade Master.............................
Route::get('grade_list', [App\Http\Controllers\MasterGradeController::class, 'ShowGradeList']);
Route::any('add_grade', [App\Http\Controllers\MasterGradeController::class, 'AddGrade']);
Route::any('save_grade', [App\Http\Controllers\MasterGradeController::class, 'SaveGrade']);

Route::any('fetch_grade_list', [App\Http\Controllers\MasterGradeController::class, 'FetchGradeList']);

Route::any('update_grade_status', [App\Http\Controllers\MasterGradeController::class, 'UpdateGradeStatus']);

Route::any('edit_grade', [App\Http\Controllers\MasterGradeController::class, 'EditGrade']);

Route::any('update_grade', [App\Http\Controllers\MasterGradeController::class, 'UpdateGrade']);


Route::any('delete_grade', [App\Http\Controllers\MasterGradeController::class, 'DeleteGrade']);

//Part Master.....................................
Route::get('part_list', [App\Http\Controllers\MasterPartController::class, 'ShowPartList']);
Route::any('add_part', [App\Http\Controllers\MasterPartController::class, 'AddPart']);
Route::any('save_part', [App\Http\Controllers\MasterPartController::class, 'SavePart']);

Route::any('fetch_part_list', [App\Http\Controllers\MasterPartController::class, 'FetchPartList']);

Route::any('update_part_status', [App\Http\Controllers\MasterPartController::class, 'UpdatePartStatus']);

Route::any('edit_part', [App\Http\Controllers\MasterPartController::class, 'EditPart']);

Route::any('update_part', [App\Http\Controllers\MasterPartController::class, 'UpdatePart']);


Route::any('delete_part', [App\Http\Controllers\MasterPartController::class, 'DeletePart']);



/*------AUTO search ajax....... MRO Page*/
//Route::get('ajax-autocomplete-search', [App\Http\Controllers\OrderRequestController::class,'selectSearch']);

Route::get('autocomplete', [App\Http\Controllers\OrderRequestController::class, 'autocomplete'])->name('autocomplete');
Route::any('get_product_list_by_grn_no', [App\Http\Controllers\OrderRequestController::class, 'GetProductListByGrnNum']);
Route::any('get_product_list_by_order_id', 'PurchaseOrderController@get_product_list_by_order_id');



/*..........................Manage Warranty Routes......................*/
Route::get('manage-warranty', 'ElsProductController@viewProductWarranty')->name('manage-warranty');
Route::get('add_product_warranty', 'ElsProductController@addProductWarranty');
Route::get('edit_product_warranty/{id}', 'ElsProductController@editProductWarranty');
Route::post('save_elsproduct_warranty', 'ElsProductController@saveWarrantyDetails');
Route::post('delete_elsproduct_warranty', 'ElsProductController@deleteWarrantyDetails');
Route::post('export-warranty-report', 'ElsProductController@downloadWarrantyReport');
Route::post('upload-bulk-warranty', 'ElsProductController@uploadBulkWarranty');

Route::any('stock-out-product-list', 'ElsProductController@stockOutProductList')->name('stock-out-product-list');
Route::any('export-stock-out-product-report', 'ElsProductController@stockOutProductReport');
Route::post('upload-device-status', 'ElsProductController@uploadBulkDeviceStatus');

Route::post('upload-change-status', 'ElsProductController@uploadBulkChangeStatus');

Route::get('upload-change-statuss', 'ElsProductController@uploadBulkChangeStatusss');


/*..........................Engineer date wise allocated devices Routes......................*/
Route::get('engineer-date-wise-allocated-devices', 'EngineerController@dateWiseAllocatedDevice')->name('engineer-date-wise-allocated-devices');
Route::get('enginner-date-wise-allocated-device-list', 'EngineerController@getAllocatedDeviceList')->name('enginner-date-wise-allocated-device-list');
Route::post('export-enginner-date-wise-allocated-device', 'EngineerController@exportAllocatedDeviceList')->name('export-enginner-date-wise-allocated-device');

/*..........................Manage device dispatch Routes......................*/
Route::get('device-dispatching', 'ElsProductController@viewDispatchDevice')->name('device-dispatch-list');
Route::get('fetch_dipatch_device_list', 'ElsProductController@getDispatchDeviceList')->name('fetch_dipatch_device_list');
Route::any('export-device-dispatch-report', 'ElsProductController@DeviceDispatchReport');
Route::any('export-device-dispatch-reportt', 'ElsProductController@DeviceDispatchReportd');


Route::post('upload-device-status', 'ElsProductController@uploadBulkDeviceStatus');

Route::post('download-challan', 'ElsProductController@DownloadChallan')->name('download-challan');



/*..........................Product distributor Consumption Routes......................*/
Route::get('distributor-parts-product-list', 'ElsProductController@distributorPartsProductList')->name('distributor-parts-product-list');
Route::get('part-distributor-product-list', 'ElsProductController@getdistributorPartProductList')->name('part-distributor-product-list');
Route::get('distributor_product_part', 'ElsProductController@distributorProductPart')->name('distributor_product_part');
Route::post('distributor-product-parts-details', 'ElsProductController@distributorELSProductPartsDetails')->name('distributor-product-parts-details');
Route::post('distributor_product_parts', 'ElsProductController@distributorProductPartPerBarcode')->name('distributor_product_parts');
Route::post('distributor-barcode-details', 'ElsProductController@distributorBarcodeDetails')->name('distributor-barcode-details');
Route::post('distributor_elsproduct_status', 'ElsProductController@distributorProductStatus')->name('distributor_elsproduct_status');
Route::post('remove-distributor-barcode', 'ElsProductController@removedistributorBarcode')->name('remove-distributor-barcode');
Route::post('add-more-distributor', 'ElsProductController@addMoredistributor')->name('add-more-distributor');
Route::post('get-distributor-colours', 'ElsProductController@getdistributorColours')->name('get-distributor-colours');
Route::post('upload-distributor-allocation-enginner', 'ElsProductController@uploaddistributorAllocationEnginner')->name('upload-distributor-allocation-enginner');



/*..........................Product distributor Consumption Routes......................*/
Route::get('collect-parts-product-list', 'ElsProductController@collectPartsProductList')->name('collect-parts-product-list');
Route::get('part-collect-product-list', 'ElsProductController@getcollectPartProductList')->name('part-collect-product-list');
Route::get('collect_product_part', 'ElsProductController@collectProductPart')->name('collect_product_part');
Route::post('collect-product-parts-details', 'ElsProductController@collectELSProductPartsDetails')->name('collect-product-parts-details');
Route::post('collect_product_parts', 'ElsProductController@collectProductPartPerBarcode')->name('collect_product_parts');
Route::post('collect-barcode-details', 'ElsProductController@collectBarcodeDetails')->name('collect-barcode-details');

Route::any('exportdistributor', 'ElsProductController@exportdistributor')->name('exportdistributor');
Route::any('exportcollectback', 'ElsProductController@exportcollectback')->name('exportcollectback');


Route::post('collect_elsproduct_status', 'ElsProductController@collectProductStatus')->name('collect_elsproduct_status');
Route::post('remove-collect-barcode', 'ElsProductController@removecollectBarcode')->name('remove-collect-barcode');
Route::post('add-more-collect', 'ElsProductController@addMorecollect')->name('add-more-collect');
Route::post('get-collect-colours', 'ElsProductController@getcollectColours')->name('get-collect-colours');
Route::post('upload-collect-allocation-enginner', 'ElsProductController@uploadcollectAllocationEnginner')->name('upload-collect-allocation-enginner');





/*..........................Manage device dispatch Routes......................*/
Route::get('parts-dispatch', 'EngineerController@viewDispatchParts')->name('parts-dispatch');
Route::post('upload-dispatch-part', 'EngineerController@uploadDispatchParts')->name('upload-dispatch-part');
Route::get('fetch_dipatch_part_list', 'EngineerController@getDispatchParts')->name('fetch_dipatch_part_list');
Route::post('export-device-dispatch-report', 'EngineerController@exportDispatchParts')->name('export-device-dispatch-report');






// Route::view('good-receive-notes', 'good-receive-notes');
Route::get('goods-receive-notes', 'GRNController@ShowGoodReceiveNotes');
Route::post('store-grn', 'GRNController@StoreGoodReceiveNotes');




Route::get('export-new-pin', 'RecivedOrderController@downloadNewPin');
Route::post('export-new-pin', 'RecivedOrderController@downloadNewPin');
Route::get('new-pin-report', 'ElsProductController@NewPinReport');
Route::post('set_date_range_filterr', 'ElsProductController@setDateRangeFilterr');


Route::post('GRNexport', 'ExportController@export');
Route::post('renew-hub-status-update', 'ExportController@renewstatusupdate');
Route::post('renew-hub-status-update-price', 'ExportController@renewstatusupdateprice');
Route::post('export-renewhub-status', 'ExportController@export_renewhub_status');

Route::get('status_list', 'StatusController@show');
Route::get('add_status', 'StatusController@add_status');
Route::any('save_status', [App\Http\Controllers\StatusController::class, 'save_status']);
Route::any('fetch_status_list', [App\Http\Controllers\StatusController::class, 'FetchStatusList']);
Route::any('update_status_status', [App\Http\Controllers\StatusController::class, 'UpdateStatus']);
Route::any('edit_status', [App\Http\Controllers\StatusController::class, 'EditStatus']);
Route::any('update_status', [App\Http\Controllers\StatusController::class, 'Update_Status']);
Route::any('delete_status', [App\Http\Controllers\StatusController::class, 'DeleteStatus']);



Route::get('get_data_po_no', 'GRNController@get_data');
Route::get('get_sku_print/{barcode}', 'GRNController@printpin');
Route::get('generate_bar_code', 'GRNController@generate_bar_code');
