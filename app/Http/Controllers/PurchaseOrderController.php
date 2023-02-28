<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use DB;

class PurchaseOrderController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth');
	}

	public function ShowPurchaseRequestList(Request $request)
	{
		if (\Helper::checkMenuElegible()) {
			$title = "Purchase Order List";
			return view("po/orderpurchase_list", compact('title'));
		}
		abort(403, "Don't have permission to access.");
	}

	public function AddOrderPurchase(Request $request)
	{
		$title = "Add Order Purchase List";
		$brand = \App\Models\MasterBrandModel::where('bstatus', '1')->whereNull('deleted_at')->pluck('bname', 'id');
		$vendor = \App\Models\MasterVendorModel::where('status', '1')->whereNull('deleted_at')->pluck('vname', 'id');
		$model = \App\Models\MasterModel::where('mstatus', '1')->whereNull('deleted_at')->pluck('mname', 'id');
		$parts = \App\Models\MasterPartModel::where('status', '1')->whereNull('deleted_at')->pluck('name', 'id');
		$product_type = \App\Models\ProductTypeModel::where('status', '1')->whereNull('deleted_at')->pluck('type', 'id');
		$data = \App\Models\OrderRequestPart::join('els_order_request', function ($join) {
			$join->on('els_order_request.id', 'els_order_request_parts.request_order_id');
			$join->whereNull('els_order_request.deleted_at');
		})->whereNull('els_order_request_parts.deleted_at')->where('els_order_request_parts.status', '1')->selectRaw('els_order_request_parts.*, SUM(els_order_request_parts.quantity) as order_qunatity')->groupBy('spare_part_price_id')->get();
		return view('po/purchase_order_form', compact('title', 'data', 'brand', 'vendor', 'model', 'product_type', 'parts'));
	}

	// Get Order parts list as per GRN No
	// public function getOrderRequestList(Request $request){
	// $data = \App\Models\OrderRequestPart::join('els_order_request',function($join){
	// $join->on('els_order_request.id','els_order_request_parts.request_order_id');
	// $join->whereNull('els_order_request.deleted_at');
	// })->whereNull('els_order_request_parts.deleted_at')->where('els_order_request_parts.status','1')->where('els_order_request.grn_no',$request->grn_no)->select('els_order_request_parts.*')->get();
	// if(!$data->isEmpty()){
	// $brand = \App\Models\MasterBrandModel::where('bstatus','1')->whereNull('deleted_at')->pluck('bname','id');
	// $vendor = \App\Models\MasterVendorModel::where('status','1')->whereNull('deleted_at')->pluck('vname','id');
	// $model = \App\Models\MasterModel::where('mstatus','1')->whereNull('deleted_at')->pluck('mname','id');
	// $parts = \App\Models\MasterPartModel::where('status','1')->whereNull('deleted_at')->pluck('name','id');
	// $product_type = \App\Models\ProductTypeModel::where('status','1')->whereNull('deleted_at')->pluck('type','id');
	// $html = view('po/request_order_list',compact('data','brand','vendor','model','product_type','parts'))->render();
	// return response()->json(['status'=>true, 'data' => [], 'html' => \Helper::compressHtml($html), 'code'=>200, 'message'=> 'Order request list']);
	// }
	// return response()->json(['status'=>false, 'data' => [], 'code'=>401, 'message'=> 'No Data Found']);
	// }

	// Save Purchase Order Quantity
	public function savePurchaseOrder(Request $request)
	{
		$dataArr = [];
		if ($request->request_order_id) {
			foreach ($request->request_order_id as $key => $request_order_id) {
				if ($request->vendor_id[$key]) {
					$dataArr[$request->vendor_id[$key]][] = [
						'brand_id' => $request->brand_id[$key],
						'model_id' => $request->model_id[$key],
						'product_type_id' => $request->product_type_id[$key],
						'part_id' => $request->part_id[$key],
						'colour_id' => $request->colour_id[$key],
						'hsn_code' => $request->hsn_code[$key],
						'price' => $request->price[$key],
						'gst' => $request->gst[$key],
						'quantity' => $request->quantity[$key],
						'display_quantity' => $request->quantity[$key],
						'request_quantity' => $request->request_quantity[$key],
					];
				}
			}
			if ($dataArr) {
				foreach ($dataArr as $vendor_id => $d) {
					$save = \App\Models\PurchaseOrder::updateOrCreate(['id' =>  null], [
						'vendor_id' => $vendor_id,
						'created_by' => \Auth::user()->id,
					]);
					if ($save) {
						$purchase_order_id = $save->id;
						foreach ($d as $p) {
							\App\Models\PurchaseOrderParts::updateOrCreate(['id' =>  null], [
								'purchase_order_id' => $purchase_order_id,
								'brand_id' => $p['brand_id'],
								'model_id' => $p['model_id'],
								'product_type_id' => $p['product_type_id'],
								'part_id' => $p['part_id'],
								'colour_id' => $p['colour_id'],
								'hsn_code' => $p['hsn_code'],
								'price' => $p['price'],
								'gst' => $p['gst'],
								'quantity' => $p['quantity'],
								'remaining_quantity' => $p['quantity'],
							]);
							$part = \App\Models\OrderRequestPart::where('status', '1')->where('brand_id', $p['brand_id'])->where('model_id', $p['model_id'])->where('part_type_id', $p['product_type_id'])->where('part_id', $p['part_id'])->where('colour_id', $p['colour_id'])->orderBy('id', 'ASC')->get();
							if (!$part->isEmpty()) {
								$order_quantity = $p['quantity'];
								foreach ($part as $prt) {
									if ($order_quantity >= $prt->quantity) {
										\App\Models\OrderRequestPart::where('id', $prt->id)->update(['status' => '0']);
										$order_quantity = $order_quantity - $prt->quantity;
									} elseif ($order_quantity) {
										$quantity = $prt->quantity - $order_quantity;
										\App\Models\OrderRequestPart::where('id', $prt->id)->update(['quantity' => $quantity]);
										$order_quantity = 0;
									}
								}
							}
						}
						// return response()->json(['status'=>true, 'data' => [], 'code'=>200, 'message'=> 'Purchase Order successfully genrated.']);
						return redirect('order-purchase-list');
					}
				}
			}
		}
		return response()->json(['status' => false, 'data' => [], 'code' => 401, 'message' => 'Error Occure Please try again.']);
	}

	public function FetchOrderPurchase(Request $request)
	{

		$data = \App\Models\PurchaseOrder::join('vendor', function ($join) {
			$join->on('purchase_order_list.vendor_id', 'vendor.id');
		})->join('users', function ($join) {
			$join->on('purchase_order_list.created_by', 'users.id');
		})->whereNull('purchase_order_list.deleted_at')->where('purchase_order_list.status', '1')->orderBy('purchase_order_list.id', 'DESC');

		$where_like = false;
		$columnsArr = ['purchase_order_list.grn_no', 'users.name', 'purchase_order_list.id', 'vendor.vname'];
		foreach ($columnsArr as $columns) {
			if ($request->get('search')['value']) {
				if ($where_like) {
					$where_like .= ' OR ' . $columns . ' like "%' . $request->get('search')['value'] . '%"';
				} else {
					$where_like .= $columns . ' like "%' . $request->get('search')['value'] . '%"';
				}
			}
		}
		if ($where_like) {
			$data = $data->whereRaw('(' . $where_like . ')');
		}

		$totalRecord = $data->count();
		if ($request->get('length')) {
			$data = $data->skip($request->get('start'))->take($request->get('length'));
		}
		$data = $data->selectRaw('purchase_order_list.id,purchase_order_list.grn_no,CONCAT("PO-", purchase_order_list.id) as po_no,vendor.vname,users.name,DATE_FORMAT(purchase_order_list.created_at, "%d/%m/%Y") as date')->get();
		return response()->json(['status' => true, 'data' => $data, 'recordsTotal' => $totalRecord, 'recordsFiltered' => $totalRecord, 'code' => 200, 'message' => 'PO listed successfully']);
	}

	public function editPurchaseOrder(Request $request, $id)
	{
		$title = "Edit Purchase Order";
		$data = \App\Models\PurchaseOrderParts::whereNull('deleted_at')->where('purchase_order_id', $id)->get();
		if (!$data->isEmpty()) {
			$brand = \App\Models\MasterBrandModel::where('bstatus', '1')->whereNull('deleted_at')->pluck('bname', 'id');
			$vendor = \App\Models\MasterVendorModel::where('status', '1')->whereNull('deleted_at')->pluck('vname', 'id');
			$model = \App\Models\MasterModel::where('mstatus', '1')->whereNull('deleted_at')->pluck('mname', 'id');
			$parts = \App\Models\MasterPartModel::where('status', '1')->whereNull('deleted_at')->pluck('name', 'id');
			$product_type = \App\Models\ProductTypeModel::where('status', '1')->whereNull('deleted_at')->pluck('type', 'id');
			return view('po/edit_purchase_order_form', compact('data', 'brand', 'vendor', 'model', 'product_type', 'parts', 'id', 'title'));
		}
		return response()->json(['status' => false, 'data' => [], 'code' => 401, 'message' => 'No Data Found']);
	}

	public function deletePurchaseOrder(Request $request)
	{

		$id = $request->id;

		\App\Models\PurchaseOrder::where('id', $id)->delete();
		$arr = array(
			"code" => 200,
			"message" => "Delete Successfully",
			"data" => ''
		);

		\App\Models\PurchaseOrderParts::where('purchase_order_id', $id)->delete();
		$arr = array(
			"code" => 200,
			"message" => "Delete Successfully",
			"data" => ''
		);




		return \Response::json($arr);
	}


	public function deletePurchaseOrderPart(Request $request)
	{

		$id = $request->id;

		\App\Models\PurchaseOrderParts::where('id', $id)->delete();
		$arr = array(
			"code" => 200,
			"message" => "Delete Successfully",
			"data" => ''
		);
		return \Response::json($arr);
	}




	public function updatePurchaseOrder(Request $request)
	{
		if ($request->purchase_order_parts_id) {
			foreach ($request->purchase_order_parts_id as $key => $id) {
				$update = \App\Models\PurchaseOrderParts::updateOrCreate(['id' => $id], [
					'model_id' => $request->model_id[$key],
					'brand_id' => $request->brand_id[$key],
					'part_id' => $request->part_id[$key],
					'colour_id' => $request->colour_id[$key],
					'hsn_code' => $request->hsn_code[$key],
					'price' => $request->price[$key],
					'gst' => $request->gst[$key],
					'quantity' => $request->quantity[$key],
					'remaining_quantity' => $request->quantity[$key],
				]);

				\App\Models\PurchaseOrderParts::where('id', $update->id)->update(['remaining_quantity' => $request->quantity[$key]]);
			}
		}
		// return response()->json(['status'=>true, 'data' => [], 'code'=>200, 'message'=> 'PO Successfully update']);
		return redirect('order-purchase-list');
	}

	public function downloadPurchaseOrder(Request $request)
	{
		$data = \App\Models\PurchaseOrderParts::join('brand', function ($join) {
			$join->on('purchase_order_parts_list.brand_id', 'brand.id');
		})->join('model', function ($join) {
			$join->on('purchase_order_parts_list.model_id', 'model.id');
		})->join('part', function ($join) {
			$join->on('purchase_order_parts_list.part_id', 'part.id');
		})->join('colour', function ($join) {
			$join->on('purchase_order_parts_list.colour_id', 'colour.id');
		})->whereNull('purchase_order_parts_list.deleted_at')->where('purchase_order_parts_list.purchase_order_id', $request->po_id)->selectRaw('CONCAT(brand.bname," ",model.mname," ",part.name) as part_name,colour.name as colour_name,purchase_order_parts_list.quantity,purchase_order_parts_list.hsn_code,purchase_order_parts_list.price,purchase_order_parts_list.gst,purchase_order_parts_list.created_at,purchase_order_parts_list.purchase_order_id')->get();
		$po_details = \App\Models\PurchaseOrder::join('vendor', function ($join) {
			$join->on('purchase_order_list.vendor_id', 'vendor.id');
		})->where('purchase_order_list.id', $request->po_id)->first();
		if (!$data->isEmpty()) {
			$pdf = \PDF::loadView('po/invoice', compact('data', 'po_details'));
			return $pdf->download('PO-' . $request->po_id . '.pdf');
		}
		return redirect('order-purchase-list');
	}

	public function getSeriesColourList(Request $request)
	{
		$data = \App\Models\MasterColourModel::join('spare_part_price_list', function ($join) {
			$join->on('colour.id', 'spare_part_price_list.colour_id');
			$join->whereNull('spare_part_price_list.deleted_at');
		})->where('spare_part_price_list.series_id', $request->series_id)->select('colour.*')->get();
		return response()->json(['status' => true, 'data' => $data, 'code' => 200, 'message' => 'Series colour list']);
	}

	public function getSeriesUnitPrice(Request $request)
	{
		$data = \App\Models\MasterColourModel::join('spare_part_price_list', function ($join) {
			$join->on('spare_part_price_list.colour_id', 'colour.id');
			$join->whereNull('spare_part_price_list.deleted_at');
		})->join('spare_part_list', function ($join) {
			$join->on('spare_part_list.id', 'spare_part_price_list.series_id');
		})->where('spare_part_list.model_id', $request->model_id)->where('spare_part_list.type_id', $request->type_id)->where('spare_part_list.part_id', $request->part_id)->where('spare_part_price_list.colour_id', $request->colour_id)->groupBy('colour.id')->first();
		return response()->json(['status' => true, 'data' => $data, 'code' => 200, 'message' => 'Series colour price']);
	}

	public function genratePurchaseOrder(Request $request)
	{
		set_time_limit(0);
		$file = $request->file('file_csv');
		if (in_array(strtoupper($file->getClientOriginalExtension()), ['CSV'])) {
			$fileD = fopen($file, "r");
			$column = fgetcsv($fileD);
			if ($column) {
				foreach ($column as $k => $d) {
					if ($d == 'Brand') {
						$barnd_key = $k;
					}
					if ($d == 'Model') {
						$model_key = $k;
					}
					if ($d == 'Product Type') {
						$product_type_key = $k;
					}
					if ($d == 'Part Name') {
						$part_name_key = $k;
					}
					if ($d == 'Part Colour') {
						$colour_key = $k;
					}
					if ($d == 'HSN Code') {
						$hsn_code_key = $k;
					}
					if ($d == 'Unit Price') {
						$unit_price_key = $k;
					}
					if ($d == 'GST') {
						$gst_key = $k;
					}
					if ($d == 'Quantity') {
						$quantity_key = $k;
					}
					if ($d == 'Vendor') {
						$vendor_key = $k;
					}
				}
			}
			$i = 2;
			$error = false;
			$massage = '';
			$dataArr = [];
			while (!feof($fileD)) {
				$msg = 'Row No ' . $i . ' ';
				$rowData = fgetcsv($fileD);
				if ($rowData) {
					if (isset($rowData[$barnd_key])) {
						$brand = \App\Models\MasterBrandModel::where('bstatus', '1')->whereNull('deleted_at')->where('bname', $rowData[$barnd_key])->first();
						if ($brand) {
							$row['brand_id'] = $brand->id;
						} else {
							$msg .= 'Brand,';
							$error = true;
						}
					}
					if (isset($rowData[$model_key])) {
						$model = \App\Models\MasterModel::where('mstatus', '1')->whereNull('deleted_at')->where('mname', $rowData[$model_key])->first();
						if ($model) {
							$row['model_id'] = $model->id;
						} else {
							$msg .= 'Model,';
							$error = true;
						}
					}
					if (isset($rowData[$product_type_key])) {
						$type = \App\Models\ProductTypeModel::where('status', '1')->whereNull('deleted_at')->where('type', $rowData[$product_type_key])->first();
						if ($type) {
							$row['product_type_id'] = $type->id;
						} else {
							$msg .= 'Product Type';
							$error = true;
						}
					}
					if (isset($rowData[$part_name_key])) {
						$part = \App\Models\MasterPartModel::where('status', '1')->whereNull('deleted_at')->where('name', $rowData[$part_name_key])->first();
						if ($part) {
							$row['part_id'] = $part->id;
						} else {
							$msg .= 'Part Name';
							$error = true;
						}
					}
					if (isset($rowData[$colour_key])) {
						$colour = \App\Models\MasterColourModel::where('status', '1')->whereNull('deleted_at')->where('name', $rowData[$colour_key])->first();
						if ($colour) {
							$row['colour_id'] = $colour->id;
						} else {
							$msg .= 'Colour';
							$error = true;
						}
					}
					if (isset($rowData[$hsn_code_key])) {
						$row['hsn_code'] = $rowData[$hsn_code_key];
					}
					if (isset($rowData[$unit_price_key])) {
						$row['unit_price'] = $rowData[$unit_price_key];
					}
					if (isset($rowData[$gst_key])) {
						$row['gst'] = $rowData[$gst_key];
					}
					if (isset($rowData[$quantity_key])) {
						$row['quantity'] = $rowData[$quantity_key];
					}
					if (isset($rowData[$vendor_key])) {
						$vendor = \App\Models\MasterVendorModel::where('status', '1')->whereNull('deleted_at')->where('vname', $rowData[$vendor_key])->first();
						if ($vendor) {
							$row['vendor_id'] = $vendor->id;
						} else {
							$msg .= 'Vendor';
							$error = true;
						}
					}
					$dataArr[] = $row;
					if ($error) $massage .= $msg . ' Not Found. Please Correct The Details.';
					$i++;
				}
			}

			if ($error) {
				return response()->json(['status' => false, 'data' => [], 'code' => 401, 'message' => $massage]);
			}
			$dataArray = [];
			if ($dataArr) {
				foreach ($dataArr as $d) {
					if ($d['vendor_id']) {
						$dataArray[$d['vendor_id']][] = [
							'brand_id' => $d['brand_id'],
							'model_id' => $d['model_id'],
							'product_type_id' => $d['product_type_id'],
							'part_id' => $d['part_id'],
							'hsn_code' => $d['hsn_code'],
							'colour_id' => $d['colour_id'],
							'price' => $d['unit_price'],
							'gst' => $d['gst'],
							'quantity' => $d['quantity'],
							'remaining_quantity' => $d['quantity'],
						];
					}
				}
				if ($dataArray) {
					foreach ($dataArray as $vendor_id => $d) {
						$save = \App\Models\PurchaseOrder::updateOrCreate(['id' =>  null], [
							'vendor_id' => $vendor_id,
							'created_by' => \Auth::user()->id,
						]);
						if ($save) {
							$purchase_order_id = $save->id;
							foreach ($d as $p) {
								$savepart = \App\Models\PurchaseOrderParts::updateOrCreate(['id' =>  null], [
									'purchase_order_id' => $purchase_order_id,
									'brand_id' => $p['brand_id'],
									'model_id' => $p['model_id'],
									'product_type_id' => $p['product_type_id'],
									'part_id' => $p['part_id'],
									'hsn_code' => $p['hsn_code'],
									'colour_id' => $p['colour_id'],
									'price' => $p['price'],
									'gst' => $p['gst'],
									'quantity' => $p['quantity'],
									'remaining_quantity' => $p['quantity'],
								]);

								\App\Models\PurchaseOrderParts::where('id', $savepart->id)->update(['remaining_quantity' => $p['quantity']]);

								$part = \App\Models\OrderRequestPart::where('status', '1')->where('brand_id', $p['brand_id'])->where('model_id', $p['model_id'])->where('part_type_id', $p['product_type_id'])->where('part_id', $p['part_id'])->where('colour_id', $p['colour_id'])->orderBy('id', 'ASC')->get();
								if (!$part->isEmpty()) {
									$order_quantity = $p['quantity'];
									foreach ($part as $prt) {
										if ($order_quantity >= $prt->quantity) {
											\App\Models\OrderRequestPart::where('id', $prt->id)->update(['status' => '0']);
											$order_quantity = $order_quantity - $prt->quantity;
										} elseif ($order_quantity) {
											$quantity = $prt->quantity - $order_quantity;
											\App\Models\OrderRequestPart::where('id', $prt->id)->update(['quantity' => $quantity]);
											$order_quantity = 0;
										}
									}
								}
							}
						}
					}
				}
			}
			// return response()->json(['status'=>true, 'data' => [], 'code'=>200, 'message'=> 'Product successfully imported.']);
		}
		// return response()->json(['status'=>false, 'data' => [], 'code'=>401, 'message'=> 'Please choose a correct formate.']);
		return redirect()->back();
	}

	public function exportPurchaseOrder(Request $request)
	{
		header("Content-Type: application/xls");
		header("Content-Disposition: attachment; filename=PurchaseOrdeParts.xls");
		header("Pragma: no-cache");
		header("Expires: 0");
		$columnHeader = "Sr NO" . "\t" . "Date" . "\t" . "PO Number" . "\t" . "Brand" . "\t" . "Model" . "\t" . "Product Type" . "\t" . "Part Name" . "\t" . "Part Color" . "\t" . "HSN Code" . "\t" . "Unit Price" . "\t" . "GST" . "\t" . "Quantity" . "\t" . "Vendor" . "\t";

		$data = \App\Models\PurchaseOrderParts::join('brand', function ($join) {
			$join->on('purchase_order_parts_list.brand_id', 'brand.id');
		})->join('model', function ($join) {
			$join->on('purchase_order_parts_list.model_id', 'model.id');
		})->join('part', function ($join) {
			$join->on('purchase_order_parts_list.part_id', 'part.id');
		})->join('colour', function ($join) {
			$join->on('purchase_order_parts_list.colour_id', 'colour.id');
		})->join('product_type', function ($join) {
			$join->on('purchase_order_parts_list.product_type_id', 'product_type.id');
		})->join('purchase_order_list', function ($join) {
			$join->on('purchase_order_parts_list.purchase_order_id', 'purchase_order_list.id');
		})->join('vendor', function ($join) {
			$join->on('purchase_order_list.vendor_id', 'vendor.id');
		})->whereNull('purchase_order_parts_list.deleted_at')->selectRaw('brand.bname,model.mname,part.name as part_name,colour.name colour_name,product_type.type as part_type,purchase_order_parts_list.quantity,purchase_order_parts_list.hsn_code,purchase_order_parts_list.price,purchase_order_parts_list.gst,purchase_order_parts_list.created_at,purchase_order_parts_list.purchase_order_id,vendor.vname')->get();

		$i = 1;
		$setData = '';
		if (!$data->isEmpty()) {
			foreach ($data as $value) {
				$rowData = '';
				$rowData .= '"' . $i . '"' . "\t";
				$rowData .= '"' . date('d/m/Y', strtotime($value->created_at)) . '"' . "\t";
				$rowData .= '"' . $value->purchase_order_id . '"' . "\t";
				$rowData .= '"' . $value->bname . '"' . "\t";
				$rowData .= '"' . $value->mname . '"' . "\t";
				$rowData .= '"' . $value->part_type . '"' . "\t";
				$rowData .= '"' . $value->part_name . '"' . "\t";
				$rowData .= '"' . $value->colour_name . '"' . "\t";
				$rowData .= '"' . $value->hsn_code . '"' . "\t";
				$rowData .= '"' . $value->price . '"' . "\t";
				$rowData .= '"' . $value->gst . '"' . "\t";
				$rowData .= '"' . $value->quantity . '"' . "\t";
				$rowData .= '"' . $value->vname . '"' . "\t";
				$setData .= trim($rowData) . "\n";
				$i++;
			}
		}
		echo ucwords($columnHeader) . "\n" . $setData . "\n";
	}
}
