<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use carbon\Carbon;
// use DB;

class RecivedOrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function ShowOrderRecivedProductList(Request $request)
    {
        if (\Helper::checkMenuElegible()) {
            $title = "Purchase Order List";
            return view("orp/order_recived_product_list", compact('title'));
        }
        abort(403, "Don't have permission to access.");
    }
    public function AddOrderRecivedProduct(Request $request)
    {
        $title = "Add Order Purchase List";
        $model = \App\Models\MasterModel::whereNull('deleted_at')->where('mstatus', '1')->pluck('mname', 'id');
        return view('orp/purchase_order_recived_form', compact('title', 'model'));
    }

    // Get Purchase Order parts list as per PO No
    public function getPurchaseOrderPartsList(Request $request)
    {
        // dd($request->all());
        $purchase_order_id = str_replace('po-', '', strtolower($request->purchase_order_id));
        // dd($purchase_order_id);
        $data = \App\Models\PurchaseOrderParts::whereNull('deleted_at')->where('purchase_order_id', $purchase_order_id);
        if ($request->model_id) {
            $data = $data->where('model_id', $request->model_id);
        }


        $data = $data->get();
        if (!$data->isEmpty()) {
            $brand = \App\Models\MasterBrandModel::where('bstatus', '1')->whereNull('deleted_at')->pluck('bname', 'id');
            $vendor = \App\Models\MasterVendorModel::where('status', '1')->whereNull('deleted_at')->pluck('vname', 'id');
            $model = \App\Models\MasterModel::where('mstatus', '1')->whereNull('deleted_at')->pluck('mname', 'id');
            $parts = \App\Models\MasterPartModel::where('status', '1')->whereNull('deleted_at')->pluck('name', 'id');
            $product_type = \App\Models\ProductTypeModel::where('status', '1')->whereNull('deleted_at')->pluck('type', 'id');




            $html = view('orp/purchase_order_parts_list', compact('data', 'brand', 'vendor', 'model', 'product_type', 'parts'))->render();
            return response()->json(['status' => true, 'data' => [], 'html' => \Helper::compressHtml($html), 'code' => 200, 'message' => 'Order request list']);
        }
        return response()->json(['status' => false, 'data' => [], 'code' => 401, 'message' => 'No Data Found']);
    }

    // Save Purchase Order Quantity
    public function saveRecivedPurchaseOrder(Request $request)
    {

        // dd($request);

        $dataArr = [];

        if ($request->purchase_order_parts_id) {
            foreach ($request->purchase_order_parts_id as $key => $purchase_order_parts_id) {
                if ($request->quantity[$key]) {
                    $save = \App\Models\ReceivedPurchaseOrderParts::updateOrCreate([
                        'purchase_order_id' => $request->purchase_order_id[$key],
                        'brand_id' => $request->brand_id[$key],
                        'model_id' => $request->model_id[$key],
                        'product_type_id' => $request->product_type_id[$key],
                        'part_id' => $request->part_id[$key],
                        'colour_id' => $request->colour_id[$key],
                        'sku_id' => \Helper::getSKUID($request->brand_id[$key], $request->model_id[$key], $request->product_type_id[$key], $request->part_id[$key], $request->colour_id[$key]),
                        'price' => $request->price[$key],
                        'gst' => $request->gst[$key],
                        'quantity' => $request->quantity[$key],
                        'created_at' => date("Y-m-d h:i:s"),
                        'created_by' => \Auth::user()->id,
                    ]);


                    // dd($request->quantity[$key], $request->request_quantity[$key]);



                    if ($request->quantity[$key] > $request->request_quantity[$key]) {
                        // 		\App\Models\PurchaseOrderParts::where('id',$request->purchase_order_parts_id[$key])->update(['status'=>'0']);
                        // die("your request quantity not higher than quantity");
                        \App\Models\PurchaseOrderParts::where('id', $request->purchase_order_parts_id[$key])->update(['display_quantity' => 0]);
                    } elseif ($request->quantity[$key] == $request->request_quantity[$key]) {
                        \App\Models\PurchaseOrderParts::where('id', $request->purchase_order_parts_id[$key])->update(['status' => '0']);
                        $quantity = $request->request_quantity[$key] - $request->quantity[$key];
                        \App\Models\PurchaseOrderParts::where('id', $request->purchase_order_parts_id[$key])->update(['display_quantity' => 0]);
                    } else {
                        $quantity = $request->request_quantity[$key] - $request->quantity[$key];
                        \App\Models\PurchaseOrderParts::where('id', $request->purchase_order_parts_id[$key])->update(['display_quantity' => 0]);
                    }
                    if ($save) {
                        $price = ($request->price[$key] + ($request->price[$key] * ($request->gst[$key] / 100)));
                        $received_part_id = $save->id;
                        for ($i = 1; $i <= $request->quantity[$key]; $i++) {
                            // $barcode = $received_part_id.rand(10,100000000000);
                            if (strlen($received_part_id) < 9) {
                                $length = 9 - strlen($received_part_id);
                                // $barcode = $received_part_id.$this->random_strings($length);
                                $barcode = $this->random_strings(9);
                            } else {
                                $barcode = $this->random_strings(9);
                            }

                            \App\Models\ReceivedPurchaseOrderPartsBarcode::updateOrCreate(['id' =>  null], [
                                'received_part_id' => $received_part_id,
                                'barcode' => $barcode,
                                'price' => $price,
                            ]);
                        }
                    }
                }
            }
            return response()->json(['status' => true, 'data' => [], 'code' => 200, 'message' => 'Recived Purchase Order successfully Done.']);
        }
        return response()->json(['status' => false, 'data' => [], 'code' => 401, 'message' => 'Error Occure Please try again.']);
    }

    public function random_strings($length_of_string)
    {

        // String of all alphanumeric character
        $str_result = 'ABCDFGHIJKLMNOPQRSTUVWXYZ';

        // Shufle the $str_result and returns substring
        // of specified length
        return substr(str_shuffle($str_result), 0, $length_of_string);
    }

    public function FetchRevivedPurchaseParts(Request $request)
    {


        $data = \App\Models\ReceivedPurchaseOrderParts::join('brand', function ($join) {
            $join->on('received_purchase_order_parts_list.brand_id', 'brand.id');
        })->join('model', function ($join) {
            $join->on('received_purchase_order_parts_list.model_id', 'model.id');
        })->join('product_type', function ($join) {
            $join->on('received_purchase_order_parts_list.product_type_id', 'product_type.id');
        })->join('part', function ($join) {
            $join->on('received_purchase_order_parts_list.part_id', 'part.id');
        })->join('colour', function ($join) {
            $join->on('received_purchase_order_parts_list.colour_id', 'colour.id');
        })->join('users', function ($join) {
            $join->on('received_purchase_order_parts_list.created_by', 'users.id');
        })->whereNull('received_purchase_order_parts_list.deleted_at')->where('received_purchase_order_parts_list.status', '1')->groupBy('received_purchase_order_parts_list.id')->orderBy('received_purchase_order_parts_list.id', 'DESC');

        $where_like = false;
        $columnsArr = ['received_purchase_order_parts_list.purchase_order_id', 'users.name', 'brand.bname', 'model.mname', 'part.name', 'colour.name', 'product_type.type', 'received_purchase_order_parts_list.price', 'received_purchase_order_parts_list.quantity'];
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

        $totalRecord = count($data->get());
        if ($request->get('length')) {
            $data = $data->skip($request->get('start'))->take($request->get('length'));
        }
        $data = $data->selectRaw('received_purchase_order_parts_list.id,received_purchase_order_parts_list.price,received_purchase_order_parts_list.quantity,CONCAT("PO-", received_purchase_order_parts_list.purchase_order_id) as po_no,users.name,brand.bname,model.mname,part.name as part_name,colour.name colour_name,product_type.type,DATE_FORMAT(received_purchase_order_parts_list.created_at, "%d/%m/%Y") as date')->get();


        // Your Eloquent query executed by using get()




        return response()->json(['status' => true, 'data' => $data, 'recordsTotal' => $totalRecord, 'recordsFiltered' => $totalRecord, 'code' => 200, 'message' => 'PO listed successfully']);
    }

    public function downloadRecivedPurchaseOrder(Request $request)
    {
        header("Content-Type: application/xls");
        header("Content-Disposition: attachment; filename=RecivedPurchaseOrderReport.xls");
        header("Pragma: no-cache");
        header("Expires: 0");

        $columnHeader = '';
        $columnHeader = "Sr NO" . "\t" . "Date" . "\t" . "PO No" . "\t" . "Brand" . "\t" . "Model" . "\t" . "Product Type" . "\t" . "Part Name" . "\t" . "Colour" . "\t" . "Quantity" . "\t" . "Recived By" . "\t";

        $data = \App\Models\ReceivedPurchaseOrderParts::join('brand', function ($join) {
            $join->on('received_purchase_order_parts_list.brand_id', 'brand.id');
        })->join('model', function ($join) {
            $join->on('received_purchase_order_parts_list.model_id', 'model.id');
        })->join('product_type', function ($join) {
            $join->on('received_purchase_order_parts_list.product_type_id', 'product_type.id');
        })->join('part', function ($join) {
            $join->on('received_purchase_order_parts_list.part_id', 'part.id');
        })->join('colour', function ($join) {
            $join->on('received_purchase_order_parts_list.colour_id', 'colour.id');
        })->join('users', function ($join) {
            $join->on('received_purchase_order_parts_list.created_by', 'users.id');
        })->whereNull('received_purchase_order_parts_list.deleted_at')->where('received_purchase_order_parts_list.status', '1')->groupBy('received_purchase_order_parts_list.id')->orderBy('received_purchase_order_parts_list.id', 'DESC');

        $data = $data->selectRaw('received_purchase_order_parts_list.id,received_purchase_order_parts_list.price,received_purchase_order_parts_list.quantity,CONCAT("PO-", received_purchase_order_parts_list.purchase_order_id) as po_no,users.name,brand.bname,model.mname,part.name as part_name,colour.name colour_name,product_type.type,DATE_FORMAT(received_purchase_order_parts_list.created_at, "%d/%m/%Y") as recived_date')->get();

        $i = 1;
        $setData = '';
        if (!$data->isEmpty()) {
            foreach ($data as $value) {
                $rowData = '';
                $rowData .= '"' . $i . '"' . "\t";
                $rowData .= '"' . $value->recived_date . '"' . "\t";
                $rowData .= '"' . $value->po_no . '"' . "\t";
                $rowData .= '"' . $value->bname . '"' . "\t";
                $rowData .= '"' . $value->mname . '"' . "\t";
                $rowData .= '"' . $value->type . '"' . "\t";
                $rowData .= '"' . $value->part_name . '"' . "\t";
                $rowData .= '"' . $value->colour_name . '"' . "\t";
                $rowData .= '"' . $value->quantity . '"' . "\t";
                $rowData .= '"' . $value->name . '"' . "\t";
                $setData .= trim($rowData) . "\n";
                $i++;
            }
        }
        echo ucwords($columnHeader) . "\n" . $setData . "\n";
    }


    public function downloadBarCodeList(Request $request)
    {
        // $data = \App\Models\ReceivedPurchaseOrderPartsBarcode::whereNull('deleted_at')->where('received_part_id',$request->id)->get();
        // if(!$data->isEmpty()){
        // $pdf = \PDF::loadView('orp/barcode', compact('data'));
        // return $pdf->download('PO-BAR-'.$request->id.'.pdf');
        // }

        header("Content-Type: application/xls");
        header("Content-Disposition: attachment; filename=SparePartsBarcodeList.xls");
        header("Pragma: no-cache");
        header("Expires: 0");

        $columnHeader = '';
        $columnHeader = "Sr NO" . "\t" . "SKU No" . "\t" . "Parts Name" . "\t" . "PIN" . "\t";

        $data = \App\Models\ReceivedPurchaseOrderPartsBarcode::join('received_purchase_order_parts_list', function ($join) {
            $join->on('received_parts_barcode_list.received_part_id', 'received_purchase_order_parts_list.id');
            $join->whereNull('received_purchase_order_parts_list.deleted_at');
        })->join('spare_part_price_list', function ($join) {
            $join->on('received_purchase_order_parts_list.sku_id', 'spare_part_price_list.id');
        })->join('part', function ($join) {
            $join->on('part.id', 'received_purchase_order_parts_list.part_id');
        })->whereNull('received_parts_barcode_list.deleted_at')->where('received_parts_barcode_list.received_part_id', $request->id)->get();

        $i = 1;
        $setData = '';
        if (!$data->isEmpty()) {
            foreach ($data as $value) {
                $rowData = '';
                $rowData .= '"' . $i . '"' . "\t";
                $rowData .= '"' . $value->sku_no . '"' . "\t";
                $rowData .= '"' . $value->name . '"' . "\t";
                $rowData .= '"' . $value->barcode . '"' . "\t";
                $setData .= trim($rowData) . "\n";
                $i++;
            }
        }
        echo ucwords($columnHeader) . "\n" . $setData . "\n";

        // return redirect()->route('received-purchase-order');
    }

    public function stockInBarcodeList(Request $request)
    {
        if (\Helper::checkMenuElegible()) {
            $title = "Stock In Parts Barcode List";
            return view('orp/stock_in_parts_barcode_list', compact('title'));
        }
        abort(403, "Don't have permission to access.");
    }


    public function sparepart_aging(Request $request)
    {
        if (\Helper::checkMenuElegible()) {
            $title = "Stock In Parts Barcode List";
            return view('orp/sparepart_aging', compact('title'));
        }
        abort(403, "Don't have permission to access.");
    }



    public function po_wise_iqc_report(Request $request)
    {
        if (\Helper::checkMenuElegible()) {
            $title = "Stock In Parts Barcode List";
            return view('orp/po-wise-iqc-report', compact('title'));
        }
        abort(403, "Don't have permission to access.");
    }


    public function extra_part(Request $request)
    {
        if (\Helper::checkMenuElegible()) {
            $title = "Stock In Parts Barcode List";



            $data = \App\Models\OrderRequestPart::leftjoin('brand', function ($join) {
                $join->on('els_order_request_parts.brand_id', 'brand.id');
            })->leftjoin('model', function ($join) {
                $join->on('els_order_request_parts.model_id', 'model.id');
            })->leftjoin('els_order_request', function ($join) {
                $join->on('els_order_request_parts.request_order_id', 'els_order_request.id');
            })->leftjoin('els_system_info_details', function ($join) {
                $join->on('els_system_info_details.id', 'els_order_request.els_system_id');
            })->leftjoin('ram', function ($join) {
                $join->on('els_system_info_details.ram', 'ram.id');
            })->leftjoin('rom', function ($join) {
                $join->on('els_system_info_details.rom', 'rom.id');
            })->leftjoin('colour', function ($join) {
                $join->on('els_order_request_parts.colour_id', 'colour.id');
            })->leftjoin('product_type', function ($join) {
                $join->on('els_order_request_parts.part_type_id', 'product_type.id');
            })->leftjoin('part', function ($join) {
                $join->on('els_order_request_parts.part_id', 'part.id');
            })->where('els_order_request_parts.extra', 1)->orderBy('els_order_request_parts.id', 'DESC');

            $where_like = false;
            $columnsArr = ['part.name', 'els_order_request_parts.barcodes'];
            // 		foreach($columnsArr as $columns){
            // 			if($request->get('search')['value']){
            // 				if($where_like){
            // 					$where_like.= ' OR '.$columns.' like "%'.$request->get('search')['value'].'%"';
            // 				}else{
            // 					$where_like.= $columns.' like "%'.$request->get('search')['value'].'%"';
            // 				}
            // 			}
            // 		}
            if ($where_like) {
                $data = $data->whereRaw('(' . $where_like . ')');
            }


            if (session()->get('start_date') && session()->get('end_date')) {
                $start_date = session()->get('start_date');
                $end_date = date('Y-m-d', strtotime('+1 day', strtotime(session()->get('end_date'))));
            } else {
                $start_date = date('Y-m-d', strtotime('-29 day'));
                $end_date = date('Y-m-d', strtotime('+1 day'));
            }
            $data = $data->whereBetween('els_order_request_parts.created_at', [$start_date, $end_date]);


            $totalRecord = $data->count();
            if ($request->get('length')) {
                $data = $data->skip($request->get('start'))->take($request->get('length'));
            }
            $data = $data->selectRaw('els_order_request_parts.id,els_order_request_parts.request_order_id,part.name as part_name,els_order_request_parts.barcodes,colour.name as colour_name,brand.bname,model.mname,DATE_FORMAT(els_order_request_parts.created_at, "%d-%m-%Y") as formatted_dob,els_system_info_details.barcode,ram.name as ram,rom.name as rom,els_system_info_details.id as els_system_id')->get();
            //	return view('els_product/extra_part',compact('data'));





            return view('orp/extra_part', compact('title', 'data'));
        }
        abort(403, "Don't have permission to access.");
    }


    public function fresh_faulty(Request $request)
    {
        if (\Helper::checkMenuElegible()) {


            $title = "Fresh Faulty List";

            $data = \App\Models\ReceivedPurchaseOrderPartsBarcode::leftjoin('received_purchase_order_parts_list', function ($join) {
                $join->on('received_purchase_order_parts_list.id', 'received_parts_barcode_list.received_part_id');
                $join->whereNull('received_purchase_order_parts_list.deleted_at');
            })->leftjoin('spare_part_price_list', function ($join) {
                $join->on('received_purchase_order_parts_list.sku_id', 'spare_part_price_list.id');
            })->leftjoin('els_order_request_parts', function ($join) {
                $join->on('spare_part_price_list.id', 'els_order_request_parts.spare_part_price_id');
            })->leftjoin('els_order_request', function ($join) {
                $join->on('els_order_request.id', 'els_order_request_parts.request_order_id');
            })->leftjoin('brand', function ($join) {
                $join->on('received_purchase_order_parts_list.brand_id', 'brand.id');
            })->leftjoin('model', function ($join) {
                $join->on('received_purchase_order_parts_list.model_id', 'model.id');
            })->leftjoin('colour', function ($join) {
                $join->on('received_purchase_order_parts_list.colour_id', 'colour.id');
            })->leftjoin('product_type', function ($join) {
                $join->on('received_purchase_order_parts_list.product_type_id', 'product_type.id');
            })->leftjoin('part', function ($join) {
                $join->on('received_purchase_order_parts_list.part_id', 'part.id');
            })->leftjoin('users', function ($join) {
                $join->on('users.id', 'received_parts_barcode_list.tester_id');
            })->leftjoin('purchase_order_list', function ($join) {
                $join->on('received_purchase_order_parts_list.purchase_order_id', 'purchase_order_list.id');
            })->whereNotNull('received_parts_barcode_list.iqc_status')
                ->where('received_parts_barcode_list.iqc_status_one', '0')
                ->where('received_parts_barcode_list.iqc_status', '1')
                ->groupBy('received_parts_barcode_list.barcode')
                ->orderBy('received_parts_barcode_list.updated_at', 'DESC');

            $where_like = false;
            $columnsArr = ['part.name', 'received_parts_barcode_list.barcode', 'spare_part_price_list.sku_no'];
            // 		foreach($columnsArr as $columns){
            // 			if($request->get('search')['value']){
            // 				if($where_like){
            // 					$where_like.= ' OR '.$columns.' like "%'.$request->get('search')['value'].'%"';
            // 				}else{
            // 					$where_like.= $columns.' like "%'.$request->get('search')['value'].'%"';
            // 				}
            // 			}
            // 		}
            if ($where_like) {
                $data = $data->whereRaw('(' . $where_like . ')');
            }


            if (session()->get('start_date') && session()->get('end_date')) {
                $start_date = session()->get('start_date');
                $end_date = date('Y-m-d', strtotime('+1 day', strtotime(session()->get('end_date'))));
            } else {
                $start_date = date('Y-m-d', strtotime('-29 day'));
                $end_date = date('Y-m-d', strtotime('+1 day'));
            }
            $data = $data->whereBetween('received_parts_barcode_list.updated_at', [$start_date, $end_date]);


            $totalRecord = $data->count();
            if ($request->get('length')) {
                $data = $data->skip($request->get('start'))->take($request->get('length'));
            }
            $data = $data->selectRaw('received_parts_barcode_list.id,CONCAT("PO-", received_purchase_order_parts_list.purchase_order_id) as po_no,part.name as part_name,received_parts_barcode_list.barcode,received_parts_barcode_list.iqc_status,(CASE WHEN received_parts_barcode_list.status="1" THEN "Assigned" WHEN received_parts_barcode_list.status="2" THEN "Not In Used" WHEN received_parts_barcode_list.status="3" THEN "Allocated"  WHEN received_parts_barcode_list.status="4" THEN "Dispatched" ELSE "Consumed" END) as current_status,received_parts_barcode_list.status,users.name as engineer_name,received_parts_barcode_list.price as price,received_parts_barcode_list.remark,DATE_FORMAT(received_parts_barcode_list.received_date,"%d/%m/%Y") as received_date,spare_part_price_list.sku_no,colour.name as colour_name,brand.bname,model.mname,received_parts_barcode_list.iqc_status_one,TIMESTAMPDIFF(DAY, received_parts_barcode_list.created_at, now()) AS age,DATE_FORMAT(received_parts_barcode_list.created_at, "%d-%m-%Y") as formatted_dob,received_purchase_order_parts_list.price as prices,DATE_FORMAT(purchase_order_list.created_at, "%d-%m-%Y") as formatted_dobp,DATE_FORMAT(received_parts_barcode_list.updated_at, "%d-%m-%Y") as formatted_dobu,els_order_request.els_system_id')->get();

            // 		PRINT_R($data);
            // 		DIE();

            return view('orp/fresh_faulty', compact('title', 'data'));
        }
        abort(403, "Don't have permission to access.");
    }





    public function addPartBarcodeInStock(Request $request)
    {
        $data = \App\Models\ReceivedPurchaseOrderPartsBarcode::where('barcode', $request->barcode)->where('status', '2')->update(['received_date' => date('Y-m-d h:i:s'), 'iqc_status' => '0', 'status' => '2', 'tester_id' => \Auth::user()->id]);
        return response()->json(['status' => true, 'data' => [], 'code' => 200, 'message' => 'Barcode successfully added']);
    }

    public function setIQCStatus(Request $request)
    {
        $data = \App\Models\ReceivedPurchaseOrderPartsBarcode::where('id', $request->id)->update(['iqc_status_one' => $request->status]);
        return response()->json(['status' => true, 'data' => [], 'code' => 200, 'message' => 'IQC Status update successfully']);
    }

    public function getInStockPartList(Request $request)
    {
        //received_parts_barcode_list
        $data = \App\Models\ReceivedPurchaseOrderPartsBarcode::join('received_purchase_order_parts_list', function ($join) {
            $join->on('received_purchase_order_parts_list.id', 'received_parts_barcode_list.received_part_id');
            $join->whereNull('received_purchase_order_parts_list.deleted_at');
        })->join('brand', function ($join) {
            $join->on('received_purchase_order_parts_list.brand_id', 'brand.id');
        })->join('model', function ($join) {
            $join->on('received_purchase_order_parts_list.model_id', 'model.id');
        })->join('colour', function ($join) {
            $join->on('received_purchase_order_parts_list.colour_id', 'colour.id');
        })->join('product_type', function ($join) {
            $join->on('received_purchase_order_parts_list.product_type_id', 'product_type.id');
        })->join('spare_part_price_list', function ($join) {
            $join->on('received_purchase_order_parts_list.sku_id', 'spare_part_price_list.id');
        })->join('part', function ($join) {
            $join->on('received_purchase_order_parts_list.part_id', 'part.id');
        })->leftjoin('users', function ($join) {
            $join->on('users.id', 'received_parts_barcode_list.tester_id');
        })->orderBy('received_parts_barcode_list.id', 'DESC');

        // dd($data);

        $where_like = false;
        $columnsArr = ['part.name', 'received_parts_barcode_list.barcode', 'spare_part_price_list.sku_no'];
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
        // dd($data);
        $data = $data->selectRaw('received_parts_barcode_list.id,(CASE WHEN received_parts_barcode_list.old_status="1" THEN "Old Pin" ELSE CONCAT("PO-", received_purchase_order_parts_list.purchase_order_id) END) as po_no,part.name as part_name,received_parts_barcode_list.barcode,received_parts_barcode_list.iqc_status,(CASE WHEN received_parts_barcode_list.status="1" THEN "Assigned" WHEN received_parts_barcode_list.status="2" THEN "Not In Used" WHEN received_parts_barcode_list.status="3" THEN "Allocated"  WHEN received_parts_barcode_list.status="4" THEN "Dispatched" ELSE "Consumed" END) as current_status,received_parts_barcode_list.status,users.name as engineer_name,received_parts_barcode_list.price,received_parts_barcode_list.remark,DATE_FORMAT(received_parts_barcode_list.received_date,"%d/%m/%Y") as received_date,spare_part_price_list.sku_no,colour.name as colour_name,brand.bname,model.mname,received_parts_barcode_list.iqc_status_one,TIMESTAMPDIFF(DAY, received_parts_barcode_list.created_at, now()) AS age,DATE_FORMAT(received_parts_barcode_list.created_at, "%d-%m-%Y") as formatted_dob')->get();

        // dd($data[0]);

        return response()->json(['status' => true, 'data' => $data, 'recordsTotal' => $totalRecord, 'recordsFiltered' => $totalRecord, 'code' => 200, 'message' => 'Barcode listed successfully']);
    }

    public function getInStockPartList_new(Request $request)
    {
        if (session()->get('start_date') && session()->get('end_date')) {
            $start_date = Carbon::parse(date('Y-m-d', strtotime(session()->get('start_date'))));
            $end_date = Carbon::parse(date('Y-m-d', strtotime(session()->get('end_date'))));
            if ($start_date == $end_date) {
                $end_date->addDay();
            }
        } else {
            $start_date = today();
            $end_date = today()->addDay();
        }

        $data6 = DB::table('els_order_request_parts')
            ->where('els_order_request_parts.new_pin', '1')
            ->whereBetween('els_order_request_parts.updated_at', [$start_date, $end_date])

            ->join('brand', 'els_order_request_parts.brand_id', '=', 'brand.id')
            ->join('model', 'els_order_request_parts.model_id', '=', 'model.id')
            // ->join('colour', 'els_order_request_parts.colour_id', '=', 'colour.id')
            ->join('part', 'els_order_request_parts.part_id', '=', 'part.id')
            ->join('spare_part_price_list', 'els_order_request_parts.spare_part_price_id', '=', 'spare_part_price_list.id')
            ->select('brand.bname', 'model.mname', 'spare_part_price_list.sku_no', 'part.name', 'els_order_request_parts.barcodes', 'els_order_request_parts.status', 'els_order_request_parts.old_pin', 'els_order_request_parts.new_pin', 'model.mstatus', 'brand.bstatus')
            ->get();
        // dd($data6);

        $data66 = DB::table('els_order_request_parts')
            ->where('els_order_request_parts.new_pin', '1')
            ->whereBetween('els_order_request_parts.updated_at', [$start_date, $end_date])

            ->join('brand', 'els_order_request_parts.brand_id', '=', 'brand.id')
            ->join('model', 'els_order_request_parts.model_id', '=', 'model.id')
            // ->join('colour', 'els_order_request_parts.colour_id', '=', 'colour.id')
            ->join('part', 'els_order_request_parts.part_id', '=', 'part.id')
            ->join('spare_part_price_list', 'els_order_request_parts.spare_part_price_id', '=', 'spare_part_price_list.id')
            ->select('brand.bname', 'model.mname', 'spare_part_price_list.sku_no', 'part.name', 'els_order_request_parts.barcodes', 'els_order_request_parts.status', 'els_order_request_parts.old_pin',  'els_order_request_parts.new_pin', 'model.mstatus', 'brand.bstatus', 'els_order_request_parts.created_at')
            ->get();
        return view("renew_pin", ['data6' => $data6, 'data66' => $data66]);
    }

    public function getInStockPartLista(Request $request)
    {
        $data = \App\Models\ReceivedPurchaseOrderPartsBarcode::join('received_purchase_order_parts_list', function ($join) {
            $join->on('received_purchase_order_parts_list.id', 'received_parts_barcode_list.received_part_id');
            $join->whereNull('received_purchase_order_parts_list.deleted_at');
        })->join('brand', function ($join) {
            $join->on('received_purchase_order_parts_list.brand_id', 'brand.id');
        })->join('model', function ($join) {
            $join->on('received_purchase_order_parts_list.model_id', 'model.id');
        })->join('colour', function ($join) {
            $join->on('received_purchase_order_parts_list.colour_id', 'colour.id');
        })->join('product_type', function ($join) {
            $join->on('received_purchase_order_parts_list.product_type_id', 'product_type.id');
        })->join('spare_part_price_list', function ($join) {
            $join->on('received_purchase_order_parts_list.sku_id', 'spare_part_price_list.id');
        })->join('part', function ($join) {
            $join->on('received_purchase_order_parts_list.part_id', 'part.id');
        })->leftjoin('users', function ($join) {
            $join->on('users.id', 'received_parts_barcode_list.tester_id');
        })->whereNotNull('received_parts_barcode_list.iqc_status')->whereIn('received_parts_barcode_list.status', ['2'])->orderBy('received_parts_barcode_list.id', 'DESC');

        $where_like = false;
        $columnsArr = ['part.name', 'received_parts_barcode_list.barcode', 'spare_part_price_list.sku_no'];
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


        if (session()->get('start_date') && session()->get('end_date')) {
            $start_date = session()->get('start_date');
            $end_date = date('Y-m-d', strtotime('+1 day', strtotime(session()->get('end_date'))));
        } else {
            $start_date = date('Y-m-d', strtotime('-29 day'));
            $end_date = date('Y-m-d', strtotime('+1 day'));
        }
        $data = $data->whereBetween('received_parts_barcode_list.created_at', [$start_date, $end_date]);


        $totalRecord = $data->count();
        if ($request->get('length')) {
            $data = $data->skip($request->get('start'))->take($request->get('length'));
        }
        $data = $data->selectRaw('received_parts_barcode_list.id, (CASE WHEN received_purchase_order_parts_list.purchase_order_id="0" THEN "Renew" ELSE CONCAT("PO-", received_purchase_order_parts_list.purchase_order_id) END) as po_no,part.name as part_name,received_parts_barcode_list.barcode,received_parts_barcode_list.iqc_status,(CASE WHEN received_parts_barcode_list.status="1" THEN "Assigned" WHEN received_parts_barcode_list.status="2" THEN "Not In Used" WHEN received_parts_barcode_list.status="3" THEN "Allocated"  WHEN received_parts_barcode_list.status="4" THEN "Dispatched" ELSE "Consumed" END) as current_status,received_parts_barcode_list.status,users.name as engineer_name,received_parts_barcode_list.price,received_parts_barcode_list.remark,DATE_FORMAT(received_parts_barcode_list.received_date,"%d/%m/%Y") as received_date,spare_part_price_list.sku_no,colour.name as colour_name,brand.bname,model.mname,received_parts_barcode_list.iqc_status_one,TIMESTAMPDIFF(DAY, received_parts_barcode_list.created_at, now()) AS age,DATE_FORMAT(received_parts_barcode_list.created_at, "%d-%m-%Y") as formatted_dob')->get();
        return response()->json(['status' => true, 'data' => $data, 'recordsTotal' => $totalRecord, 'recordsFiltered' => $totalRecord, 'code' => 200, 'message' => 'Barcode listed successfully']);
    }


    public function getInStockPartListpp(Request $request)
    {
        $data = \App\Models\ReceivedPurchaseOrderPartsBarcode::join('received_purchase_order_parts_list', function ($join) {
            $join->on('received_purchase_order_parts_list.id', 'received_parts_barcode_list.received_part_id');
            $join->whereNull('received_purchase_order_parts_list.deleted_at');
        })->join('brand', function ($join) {
            $join->on('received_purchase_order_parts_list.brand_id', 'brand.id');
        })->join('model', function ($join) {
            $join->on('received_purchase_order_parts_list.model_id', 'model.id');
        })->join('colour', function ($join) {
            $join->on('received_purchase_order_parts_list.colour_id', 'colour.id');
        })->join('product_type', function ($join) {
            $join->on('received_purchase_order_parts_list.product_type_id', 'product_type.id');
        })->join('spare_part_price_list', function ($join) {
            $join->on('received_purchase_order_parts_list.sku_id', 'spare_part_price_list.id');
        })->join('part', function ($join) {
            $join->on('received_purchase_order_parts_list.part_id', 'part.id');
        })->leftjoin('users', function ($join) {
            $join->on('users.id', 'received_parts_barcode_list.tester_id');
        })->leftjoin('purchase_order_list', function ($join) {
            $join->on('received_purchase_order_parts_list.purchase_order_id', 'purchase_order_list.id');
        })->leftjoin('vendor', function ($join) {
            $join->on('purchase_order_list.vendor_id', 'vendor.id');
        })->whereNotNull('received_parts_barcode_list.iqc_status')->orderBy('received_parts_barcode_list.id', 'DESC');

        $where_like = false;
        $columnsArr = ['part.name', 'received_parts_barcode_list.barcode', 'spare_part_price_list.sku_no'];
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


        if (session()->get('start_date') && session()->get('end_date')) {
            $start_date = session()->get('start_date');
            $end_date = date('Y-m-d', strtotime('+1 day', strtotime(session()->get('end_date'))));
        } else {
            $start_date = date('Y-m-d', strtotime('-29 day'));
            $end_date = date('Y-m-d', strtotime('+1 day'));
        }
        $data = $data->whereBetween('received_parts_barcode_list.created_at', [$start_date, $end_date]);


        $totalRecord = $data->count();
        if ($request->get('length')) {
            $data = $data->skip($request->get('start'))->take($request->get('length'));
        }
        $data = $data->selectRaw('received_parts_barcode_list.id,CONCAT("PO-", received_purchase_order_parts_list.purchase_order_id) as po_no,part.name as part_name,received_parts_barcode_list.barcode,received_parts_barcode_list.iqc_status,(CASE WHEN received_parts_barcode_list.status="1" THEN "Assigned" WHEN received_parts_barcode_list.status="2" THEN "Not In Used" WHEN received_parts_barcode_list.status="3" THEN "Allocated"  WHEN received_parts_barcode_list.status="4" THEN "Dispatched" ELSE "Consumed" END) as current_status,received_parts_barcode_list.status,users.name as engineer_name,received_parts_barcode_list.price as price,received_parts_barcode_list.remark,DATE_FORMAT(received_parts_barcode_list.received_date,"%d/%m/%Y") as received_date,spare_part_price_list.sku_no,colour.name as colour_name,brand.bname,model.mname,received_parts_barcode_list.iqc_status_one,TIMESTAMPDIFF(DAY, received_parts_barcode_list.created_at, now()) AS age,DATE_FORMAT(received_parts_barcode_list.created_at, "%d-%m-%Y") as formatted_dob,received_purchase_order_parts_list.price as prices,DATE_FORMAT(purchase_order_list.created_at, "%d-%m-%Y") as formatted_dobp,vendor.vname,received_parts_barcode_list.price - received_purchase_order_parts_list.price as price_amount')->get();
        return response()->json(['status' => true, 'data' => $data, 'recordsTotal' => $totalRecord, 'recordsFiltered' => $totalRecord, 'code' => 200, 'message' => 'Barcode listed successfully']);
    }


    public function getInStockPartListppe(Request $request)
    {
        $data = \App\Models\OrderRequestPart::join('brand', function ($join) {
            $join->on('els_order_request_parts.brand_id', 'brand.id');
        })->join('model', function ($join) {
            $join->on('els_order_request_parts.model_id', 'model.id');
        })->join('els_order_request', function ($join) {
            $join->on('els_order_request_parts.request_order_id', 'els_order_request.id');
        })->join('els_system_info_details', function ($join) {
            $join->on('els_system_info_details.id', 'els_order_request.els_system_id');
        })->join('ram', function ($join) {
            $join->on('els_system_info_details.ram', 'ram.id');
        })->join('rom', function ($join) {
            $join->on('els_system_info_details.rom', 'rom.id');
        })->join('colour', function ($join) {
            $join->on('els_order_request_parts.colour_id', 'colour.id');
        })->join('product_type', function ($join) {
            $join->on('els_order_request_parts.part_type_id', 'product_type.id');
        })->join('part', function ($join) {
            $join->on('els_order_request_parts.part_id', 'part.id');
        })->where('els_order_request_parts.extra', 1)->orderBy('els_order_request_parts.id', 'DESC');

        $where_like = false;
        $columnsArr = ['part.name', 'els_order_request_parts.barcodes'];
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


        if (session()->get('start_date') && session()->get('end_date')) {
            $start_date = session()->get('start_date');
            $end_date = date('Y-m-d', strtotime('+1 day', strtotime(session()->get('end_date'))));
        } else {
            $start_date = date('Y-m-d', strtotime('-29 day'));
            $end_date = date('Y-m-d', strtotime('+1 day'));
        }
        $data = $data->whereBetween('els_order_request_parts.created_at', [$start_date, $end_date]);


        $totalRecord = $data->count();
        if ($request->get('length')) {
            $data = $data->skip($request->get('start'))->take($request->get('length'));
        }
        $data = $data->selectRaw('els_order_request_parts.id,els_order_request_parts.request_order_id,part.name as part_name,els_order_request_parts.barcodes,colour.name as colour_name,brand.bname,model.mname,DATE_FORMAT(els_order_request_parts.created_at, "%d-%m-%Y") as formatted_dob,els_system_info_details.barcode,ram.name as ram,rom.name as rom')->get();
        return 1;
        //	return view('els_product/extra_part',compact('data'));
        // 		return response()->json(['status'=>true, 'data' => $data,'recordsTotal'=>$totalRecord,'recordsFiltered'=>$totalRecord, 'code'=>200, 'message'=> 'Barcode listed successfully']);
    }



    public function getInStockPartListppf(Request $request)
    {
        $data = \App\Models\ReceivedPurchaseOrderPartsBarcode::join('received_purchase_order_parts_list', function ($join) {
            $join->on('received_purchase_order_parts_list.id', 'received_parts_barcode_list.received_part_id');
            $join->whereNull('received_purchase_order_parts_list.deleted_at');
        })->join('brand', function ($join) {
            $join->on('received_purchase_order_parts_list.brand_id', 'brand.id');
        })->join('model', function ($join) {
            $join->on('received_purchase_order_parts_list.model_id', 'model.id');
        })->join('colour', function ($join) {
            $join->on('received_purchase_order_parts_list.colour_id', 'colour.id');
        })->join('product_type', function ($join) {
            $join->on('received_purchase_order_parts_list.product_type_id', 'product_type.id');
        })->join('spare_part_price_list', function ($join) {
            $join->on('received_purchase_order_parts_list.sku_id', 'spare_part_price_list.id');
        })->join('part', function ($join) {
            $join->on('received_purchase_order_parts_list.part_id', 'part.id');
        })->leftjoin('users', function ($join) {
            $join->on('users.id', 'received_parts_barcode_list.tester_id');
        })->leftjoin('purchase_order_list', function ($join) {
            $join->on('received_purchase_order_parts_list.purchase_order_id', 'purchase_order_list.id');
        })->whereNotNull('received_parts_barcode_list.iqc_status')
            ->where('received_parts_barcode_list.iqc_status_one', '0')
            ->where('received_parts_barcode_list.iqc_status', '1')
            ->orderBy('received_parts_barcode_list.updated_at', 'DESC');

        $where_like = false;
        $columnsArr = ['part.name', 'received_parts_barcode_list.barcode', 'spare_part_price_list.sku_no'];
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


        if (session()->get('start_date') && session()->get('end_date')) {
            $start_date = session()->get('start_date');
            $end_date = date('Y-m-d', strtotime('+1 day', strtotime(session()->get('end_date'))));
        } else {
            $start_date = date('Y-m-d', strtotime('-29 day'));
            $end_date = date('Y-m-d', strtotime('+1 day'));
        }
        $data = $data->whereBetween('received_parts_barcode_list.updated_at', [$start_date, $end_date]);


        $totalRecord = $data->count();
        if ($request->get('length')) {
            $data = $data->skip($request->get('start'))->take($request->get('length'));
        }
        $data = $data->selectRaw('received_parts_barcode_list.id,CONCAT("PO-", received_purchase_order_parts_list.purchase_order_id) as po_no,part.name as part_name,received_parts_barcode_list.barcode,received_parts_barcode_list.iqc_status,(CASE WHEN received_parts_barcode_list.status="1" THEN "Assigned" WHEN received_parts_barcode_list.status="2" THEN "Not In Used" WHEN received_parts_barcode_list.status="3" THEN "Allocated"  WHEN received_parts_barcode_list.status="4" THEN "Dispatched" ELSE "Consumed" END) as current_status,received_parts_barcode_list.status,users.name as engineer_name,received_parts_barcode_list.price as price,received_parts_barcode_list.remark,DATE_FORMAT(received_parts_barcode_list.received_date,"%d/%m/%Y") as received_date,spare_part_price_list.sku_no,colour.name as colour_name,brand.bname,model.mname,received_parts_barcode_list.iqc_status_one,TIMESTAMPDIFF(DAY, received_parts_barcode_list.created_at, now()) AS age,DATE_FORMAT(received_parts_barcode_list.created_at, "%d-%m-%Y") as formatted_dob,received_purchase_order_parts_list.price as prices,DATE_FORMAT(purchase_order_list.created_at, "%d-%m-%Y") as formatted_dobp,DATE_FORMAT(received_parts_barcode_list.updated_at, "%d-%m-%Y") as formatted_dobu')->get();
        return response()->json(['status' => true, 'data' => $data, 'recordsTotal' => $totalRecord, 'recordsFiltered' => $totalRecord, 'code' => 200, 'message' => 'Barcode listed successfully']);
    }



    public function downloadNewPin(Request $request)
    {
        // dd('helo');
        // dd($request->all());
        // $dataa = $request->all();
        // dd($dataa);
        header("Content-Type: application/xls");
        header("Content-Disposition: attachment; filename=NewPinReport.xls");
        header("Pragma: no-cache");
        header("Expires: 0");

        $columnHeader = '';
        $columnHeader = "Sr NO" . "\t" . "PIN" . "\t" . "Brand Name" . "\t" . "Status" . "\t" . "Model Name" . "\t" . "Parts Name" . "\t" . "SKU_No" . "\t";
        $data = DB::table('els_order_request_parts')->where('new_pin', '1')
            ->join('brand', 'els_order_request_parts.brand_id', '=', 'brand.id')
            ->join('model', 'els_order_request_parts.model_id', '=', 'model.id')
            ->join('colour', 'els_order_request_parts.colour_id', '=', 'colour.id')
            ->join('part', 'els_order_request_parts.part_id', '=', 'part.id')

            ->join('spare_part_price_list', 'els_order_request_parts.spare_part_price_id', '=', 'spare_part_price_list.id')
            ->get();

        // dd($data);
        // $data = $data->select('brand_id,model_id,colour_id,part_id');
        // $data = json_encode($data);
        $data = json_decode(json_encode($data), true);
        // $data = $data->selectRaw('brand_id,model_id,colour_id,part_id');
        // return $data;
        // dd($data);
        $i = 0;
        foreach ($data as $datas) {
            // echo $datas['barcodes'];
            // echo $datas['bname'];
            // echo $datas['bstatus'];
            // echo $datas['mname'];
            // echo $datas['mstatus'];
            // echo $datas['name'];
            // echo $datas['sku_no'];
            $tdata[$i]['new_pin'] = $datas['new_pin'];
            $tdata[$i]['barcodes'] = $datas['barcodes'];
            $tdata[$i]['bname'] = $datas['bname'];
            $tdata[$i]['bstatus'] = $datas['bstatus'];
            $tdata[$i]['mname'] = $datas['mname'];
            $tdata[$i]['mstatus'] = $datas['mstatus'];
            $tdata[$i]['name'] = $datas['name'];
            $tdata[$i]['sku_no'] = $datas['sku_no'];
            $tdata[$i]['updated_at'] = $datas['updated_at'];
            // 	'bname'
            // 	'bstatus',
            // 	'mname',
            // 	'mstatus',
            // 	'name',
            // 	'sku_no',
            $i++;
            // ] = ([
            // 	$datas['barcodes'],
            //  $datas['bname'],
            //  $datas['bstatus'],
            //  $datas['mname'],
            //  $datas['mstatus'],
            //  $datas['name'],
            //  $datas['sku_no'],
            // ]);
            // echo "<br>";
            // echo $datas['mstatus'];
            // $dtata['','']
        }
        // dd($tdata);
        // $data = $data->selectRaw('els_order_request_parts.brand_id,users.name as engineer_name,received_parts_barcode_list.price,received_parts_barcode_list.remark,DATE_FORMAT(received_parts_barcode_list.received_date,"%d/%m/%Y") as received_date,spare_part_price_list.sku_no,received_parts_barcode_list.iqc_status_one,brand.bname,model.mname')->get();
        // dd($data);
        $i = 1;
        $setData = '';
        // if (!$tdata->isEmpty()) {
        foreach ($tdata as $value) {
            // if ($value->new_pin == '1') {
            // 	$status = 'Pass';
            // } elseif ($value->new_pin == '0') {
            // 	$status = 'Failed';
            // }
            // dd($value['barcodes']);
            $rowData = '';
            $rowData .= '"' . $value['new_pin'] . '"' . "\t";
            $rowData .= '"' . $value['barcodes'] . '"' . "\t";
            $rowData .= '"' . $value['bname'] . '"' . "\t";
            $rowData .= '"' . $value['bstatus'] . '"' . "\t";
            $rowData .= '"' . $value['mname'] . '"' . "\t";
            $rowData .= '"' . $value['name'] . '"' . "\t";
            $rowData .= '"' . $value['sku_no'] . '"' . "\t" . "\n";
            // $rowData .= '"' . $value['updated_at'] . '"' . "\t" . "\n";
            // $rowData .= '"' . $value->barcodes . '"' . "\t";
            // $rowData .= '"' . $value->bname . '"' . "\t";
            // $rowData .= '"' . $value->bstatus . '"' . "\t";
            // $rowData .= '"' . $value->mname . '"' . "\t";
            // $rowData .= '"' . $value->name . '"' . "\t";
            // $rowData .= '"' . $value->sku_no . '"' . "\t";
            // $r
            // $rowData = '';
            // $rowData .= '"' . $i . '"' . "\t";
            // $rowData .= '"' . $value->po_no . '"' . "\t";
            // $rowData .= '"' . $value->sku_no . '"' . "\t";
            // $rowData .= '"' . $value->bname . '"' . "\t";
            // $rowData .= '"' . $value->mname . '"' . "\t";
            // $rowData .= '"' . $value->part_name . '"' . "\t";
            // $rowData .= '"' . $value->barcode . '"' . "\t";
            // $rowData .= '"' . $value->price . '"' . "\t";
            // $rowData .= '"' . $value->engineer_name . '"' . "\t";
            // $rowData .= '"' . $status . '"' . "\t";
            // $rowData .= '"' . $value->status . '"' . "\t";
            // $rowData .= '"' . $value->received_date . '"' . "\t";
            // $rowData .= '"' . $value->remark . '"' . "\t";
            // $rowData .= '"' . $statusa . '"' . "\t";
            $setData .= trim($rowData) . "\n";
            $i++;
        }
        // }
        echo ucwords($columnHeader) . "\n" . $setData . "\n";
    }



    public function downloadSparePartsBarcode(Request $request)
    {
        header("Content-Type: application/xls");
        header("Content-Disposition: attachment; filename=SparePartsBarcodeReport.xls");
        header("Pragma: no-cache");
        header("Expires: 0");

        $columnHeader = '';
        $columnHeader = "Sr NO" . "\t" . "PO No" . "\t" . "SKU No" . "\t" . "Brand Name" . "\t" . "Model Name" . "\t" . "Parts Name" . "\t" . "PIN" . "\t" . "Price" . "\t" . "IQC Engineer Name" . "\t" . "IQC Pass/Failed" . "\t" . "Status" . "\t" . "Received Date" . "\t" . "Remark" . "\t" . "After IQC Pass/Failed" . "\t";
        $data = \App\Models\ReceivedPurchaseOrderPartsBarcode::join('received_purchase_order_parts_list', function ($join) {
            $join->on('received_purchase_order_parts_list.id', 'received_parts_barcode_list.received_part_id');
            $join->whereNull('received_purchase_order_parts_list.deleted_at');
        })->join('brand', function ($join) {
            $join->on('received_purchase_order_parts_list.brand_id', 'brand.id');
        })->join('model', function ($join) {
            $join->on('received_purchase_order_parts_list.model_id', 'model.id');
        })->join('spare_part_price_list', function ($join) {
            $join->on('received_purchase_order_parts_list.sku_id', 'spare_part_price_list.id');
        })->join('part', function ($join) {
            $join->on('received_purchase_order_parts_list.part_id', 'part.id');
        })->leftjoin('users', function ($join) {
            $join->on('users.id', 'received_parts_barcode_list.tester_id');
        })->orderBy('received_parts_barcode_list.id', 'DESC');

        $data = $data->selectRaw('received_parts_barcode_list.id,CONCAT("PO-", received_purchase_order_parts_list.purchase_order_id) as po_no,part.name as part_name,received_parts_barcode_list.barcode,received_parts_barcode_list.iqc_status,(CASE WHEN received_parts_barcode_list.status="1" THEN "Assigned" WHEN received_parts_barcode_list.status="2" THEN "Not In Used" WHEN received_parts_barcode_list.status="3" THEN "Allocated"  WHEN received_parts_barcode_list.status="4" THEN "Dispatched" ELSE "Consumed" END) as status,users.name as engineer_name,received_parts_barcode_list.price,received_parts_barcode_list.remark,DATE_FORMAT(received_parts_barcode_list.received_date,"%d/%m/%Y") as received_date,spare_part_price_list.sku_no,received_parts_barcode_list.iqc_status_one,brand.bname,model.mname')->get();

        $i = 1;
        $setData = '';
        if (!$data->isEmpty()) {
            foreach ($data as $value) {
                if ($value->iqc_status == '1') {
                    $status = 'Pass';
                } elseif ($value->iqc_status == '0') {
                    $status = 'Failed';
                } elseif ($value->iqc_status == NULL) {
                    $status = 'NA';
                }
                if ($value->iqc_status_one == '1') {
                    $statusa = 'Pass';
                } elseif ($value->iqc_status_one == '0') {
                    $statusa = 'Failed';
                } elseif ($value->iqc_status_one == NULL) {
                    $statusa = 'NA';
                }
                $rowData = '';
                $rowData .= '"' . $i . '"' . "\t";
                $rowData .= '"' . $value->po_no . '"' . "\t";
                $rowData .= '"' . $value->sku_no . '"' . "\t";
                $rowData .= '"' . $value->bname . '"' . "\t";
                $rowData .= '"' . $value->mname . '"' . "\t";
                $rowData .= '"' . $value->part_name . '"' . "\t";
                $rowData .= '"' . $value->barcode . '"' . "\t";
                $rowData .= '"' . $value->price . '"' . "\t";
                $rowData .= '"' . $value->engineer_name . '"' . "\t";
                $rowData .= '"' . $status . '"' . "\t";
                $rowData .= '"' . $value->status . '"' . "\t";
                $rowData .= '"' . $value->received_date . '"' . "\t";
                $rowData .= '"' . $value->remark . '"' . "\t";
                $rowData .= '"' . $statusa . '"' . "\t";
                $setData .= trim($rowData) . "\n";
                $i++;
            }
        }
        echo ucwords($columnHeader) . "\n" . $setData . "\n";
    }

    public function addBarcodeRemark(Request $request)
    {
        $data = \App\Models\ReceivedPurchaseOrderPartsBarcode::where('id', $request->id)->update(['remark' => $request->remark]);
        return response()->json(['status' => true, 'data' => [], 'code' => 200, 'message' => 'Barcode remark successfully added']);
    }

    public function finalQualityCheckReport(Request $request)
    {
        if (\Helper::checkMenuElegible()) {
            $title = "Stock In Parts Barcode List";
            return view('orp/final_quality_check_report', compact('title'));
        }
        abort(403, "Don't have permission to access.");
    }

    public function getFinalQualityCheckReportList(Request $request)
    {
        $data = \App\Models\ReceivedPurchaseOrderPartsBarcode::join('received_purchase_order_parts_list', function ($join) {
            $join->on('received_purchase_order_parts_list.id', 'received_parts_barcode_list.received_part_id');
            $join->whereNull('received_purchase_order_parts_list.deleted_at');
        })->join('spare_part_price_list', function ($join) {
            $join->on('received_purchase_order_parts_list.sku_id', 'spare_part_price_list.id');
        })->join('part', function ($join) {
            $join->on('received_purchase_order_parts_list.part_id', 'part.id');
        })->join('users', function ($join) {
            $join->on('users.id', 'received_parts_barcode_list.tester_id');
        })->where('received_parts_barcode_list.iqc_status', '0')->orderBy('received_parts_barcode_list.id', 'DESC');

        $where_like = false;
        $columnsArr = ['part.name', 'received_parts_barcode_list.barcode', 'spare_part_price_list.sku_no'];
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
        $data = $data->selectRaw('received_parts_barcode_list.id,CONCAT("PO-", received_purchase_order_parts_list.purchase_order_id) as po_no,part.name as part_name,received_parts_barcode_list.barcode,received_parts_barcode_list.iqc_status,(CASE WHEN received_parts_barcode_list.status="1" THEN "Assigned" WHEN received_parts_barcode_list.status="2" THEN "Not In Used" WHEN received_parts_barcode_list.status="3" THEN "Allocated" ELSE "Consumed" END) as status,users.name as engineer_name,spare_part_price_list.sku_no')->get();

        return response()->json(['status' => true, 'data' => $data, 'recordsTotal' => $totalRecord, 'recordsFiltered' => $totalRecord, 'code' => 200, 'message' => 'Barcode listed successfully']);
    }

    public function downloadFinalQualityCheckReport()
    {
        header("Content-Type: application/xls");
        header("Content-Disposition: attachment; filename=SparePartsReport.xls");
        header("Pragma: no-cache");
        header("Expires: 0");

        $columnHeader = "Sr NO" . "\t" . "PO No" . "\t" . "SKU No" . "\t" . "Parts Name" . "\t" . "PIN" . "\t" . "IQC Engineer Name" . "\t";


        $data = \App\Models\ReceivedPurchaseOrderPartsBarcode::join('received_purchase_order_parts_list', function ($join) {
            $join->on('received_purchase_order_parts_list.id', 'received_parts_barcode_list.received_part_id');
            $join->whereNull('received_purchase_order_parts_list.deleted_at');
        })->join('spare_part_price_list', function ($join) {
            $join->on('received_purchase_order_parts_list.sku_id', 'spare_part_price_list.id');
        })->join('part', function ($join) {
            $join->on('received_purchase_order_parts_list.part_id', 'part.id');
        })->join('users', function ($join) {
            $join->on('users.id', 'received_parts_barcode_list.tester_id');
        })->where('received_parts_barcode_list.iqc_status', '0')->orderBy('received_parts_barcode_list.id', 'DESC');

        $data = $data->selectRaw('received_parts_barcode_list.id,CONCAT("PO-", received_purchase_order_parts_list.purchase_order_id) as po_no,part.name as part_name,received_parts_barcode_list.barcode,received_parts_barcode_list.iqc_status,(CASE WHEN received_parts_barcode_list.status="1" THEN "Assigned" WHEN received_parts_barcode_list.status="2" THEN "Not In Used" WHEN received_parts_barcode_list.status="3" THEN "Allocated" ELSE "Consumed" END) as status,users.name as engineer_name,spare_part_price_list.sku_no')->get();

        $i = 1;
        $setData = '';
        if (!$data->isEmpty()) {
            foreach ($data as $value) {
                $rowData = '';
                $rowData .= '"' . $i . '"' . "\t";
                $rowData .= '"' . $value->po_no . '"' . "\t";
                $rowData .= '"' . $value->sku_no . '"' . "\t";
                $rowData .= '"' . $value->part_name . '"' . "\t";
                $rowData .= '"' . $value->barcode . '"' . "\t";
                $rowData .= '"' . $value->engineer_name . '"' . "\t";
                $setData .= trim($rowData) . "\n";
                $i++;
            }
        }
        echo ucwords($columnHeader) . "\n" . $setData . "\n";
    }

    public function stockPartList(Request $request)
    {
        if (\Helper::checkMenuElegible()) {
            $title = "Stock Parts List";
            return view('orp/stock_parts_list', compact('title'));
        }
        abort(403, "Don't have permission to access.");
    }

    public function getStockPartList(Request $request)
    {
        $data = \App\Models\OrderRequestPart::join('spare_part_price_list', function ($join) {
            $join->on('els_order_request_parts.spare_part_price_id', 'spare_part_price_list.id');
        })->join('brand', function ($join) {
            $join->on('els_order_request_parts.brand_id', 'brand.id');
        })->join('model', function ($join) {
            $join->on('els_order_request_parts.model_id', 'model.id');
        })->join('product_type', function ($join) {
            $join->on('els_order_request_parts.part_type_id', 'product_type.id');
        })->join('part', function ($join) {
            $join->on('els_order_request_parts.part_id', 'part.id');
        })->join('colour', function ($join) {
            $join->on('spare_part_price_list.colour_id', 'colour.id');
        })->leftjoin('received_purchase_order_parts_list', function ($join) {
            $join->on('received_purchase_order_parts_list.sku_id', 'spare_part_price_list.id');
        })->leftjoin('received_parts_barcode_list', function ($join) {
            $join->on('received_purchase_order_parts_list.id', 'received_parts_barcode_list.received_part_id');
            $join->where('received_parts_barcode_list.status', '2');
            $join->where('received_parts_barcode_list.iqc_status', '1');
            $join->whereNull('received_parts_barcode_list.deleted_at');
            $join->whereNull('received_purchase_order_parts_list.deleted_at');
        })->orderBy('brand.bname', 'ASC')->orderBy('model.mname', 'ASC')->orderBy('part.name', 'ASC');

        $where_like = false;
        $columnsArr = ['brand.bname', 'model.mname', 'part.name', 'spare_part_price_list.sku_no', 'product_type.type', 'colour.name'];
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

        $totalRecord = count($data->groupBy('spare_part_price_list.id')->get());

        if ($request->get('length')) {
            $data = $data->skip($request->get('start'))->take($request->get('length'));
        }
        $data = $data->selectRaw('els_order_request_parts.*,brand.bname,model.mname,part.name as part_name,product_type.type,(SELECT count(id) FROM received_parts_barcode_list WHERE received_part_id=received_purchase_order_parts_list.id AND iqc_status = "0") as iqc_failed,(SELECT count(id) FROM received_parts_barcode_list WHERE received_part_id=received_purchase_order_parts_list.id AND iqc_status = "1") as iqc_pass,(SELECT count(id) FROM received_parts_barcode_list WHERE received_part_id=received_purchase_order_parts_list.id AND iqc_status IS NOT NULL) as total,(SELECT count(id) FROM received_parts_barcode_list WHERE received_part_id=received_purchase_order_parts_list.id AND iqc_status IS NULL) as panding_qty,(SELECT count(id) FROM received_parts_barcode_list WHERE received_part_id=received_purchase_order_parts_list.id AND iqc_status = "1" AND status = "2") as available,(SELECT count(id) FROM received_parts_barcode_list WHERE received_part_id=received_purchase_order_parts_list.id AND iqc_status = "1" AND status = "3") as allocated,(SELECT count(id) FROM received_parts_barcode_list WHERE received_part_id=received_purchase_order_parts_list.id AND iqc_status = "1" AND status = "0") as consumed,colour.name as colour_name,spare_part_price_list.sku_no,SUM(els_order_request_parts.quantity) as required_qty')->get();
        return response()->json(['status' => true, 'data' => $data, 'recordsTotal' => $totalRecord, 'recordsFiltered' => $totalRecord, 'code' => 200, 'message' => 'Stock listed successfully']);
    }

    public function downloadStockPartReport()
    {
        header("Content-Type: application/xls");
        header("Content-Disposition: attachment; filename=SparePartsReport.xls");
        header("Pragma: no-cache");
        header("Expires: 0");

        $columnHeader = '';
        $columnHeader = "Sr NO" . "\t" . "Brand" . "\t" . "Model" . "\t" . "Part Type" . "\t" . "SKU No" . "\t" . "Parts Name" . "\t" . "Colour Name" . "\t" . "Required Quantity" . "\t" . "Pendding Quantity" . "\t" . "Recived Quantity" . "\t" . "IQC Failed" . "\t" . "IQC Pass" . "\t" . "Available Stock" . "\t" . "Allocated" . "\t" . "Consumed" . "\t";
        $data = \App\Models\OrderRequestPart::join('spare_part_price_list', function ($join) {
            $join->on('els_order_request_parts.spare_part_price_id', 'spare_part_price_list.id');
        })->join('brand', function ($join) {
            $join->on('els_order_request_parts.brand_id', 'brand.id');
        })->join('model', function ($join) {
            $join->on('els_order_request_parts.model_id', 'model.id');
        })->join('product_type', function ($join) {
            $join->on('els_order_request_parts.part_type_id', 'product_type.id');
        })->join('part', function ($join) {
            $join->on('els_order_request_parts.part_id', 'part.id');
        })->join('colour', function ($join) {
            $join->on('spare_part_price_list.colour_id', 'colour.id');
        })->leftjoin('received_purchase_order_parts_list', function ($join) {
            $join->on('received_purchase_order_parts_list.sku_id', 'spare_part_price_list.id');
        })->leftjoin('received_parts_barcode_list', function ($join) {
            $join->on('received_purchase_order_parts_list.id', 'received_parts_barcode_list.received_part_id');
            $join->where('received_parts_barcode_list.status', '2');
            $join->where('received_parts_barcode_list.iqc_status', '1');
            $join->whereNull('received_parts_barcode_list.deleted_at');
            $join->whereNull('received_purchase_order_parts_list.deleted_at');
        })->orderBy('brand.bname', 'ASC')->orderBy('model.mname', 'ASC')->orderBy('part.name', 'ASC');
        $data = $data->selectRaw('els_order_request_parts.*,brand.bname,model.mname,part.name as part_name,product_type.type,(SELECT count(id) FROM received_parts_barcode_list WHERE received_part_id=received_purchase_order_parts_list.id AND iqc_status = "0") as iqc_failed,(SELECT count(id) FROM received_parts_barcode_list WHERE received_part_id=received_purchase_order_parts_list.id AND iqc_status = "1") as iqc_pass,(SELECT count(id) FROM received_parts_barcode_list WHERE received_part_id=received_purchase_order_parts_list.id AND iqc_status IS NOT NULL) as total,(SELECT count(id) FROM received_parts_barcode_list WHERE received_part_id=received_purchase_order_parts_list.id AND iqc_status IS NULL) as panding_qty,(SELECT count(id) FROM received_parts_barcode_list WHERE received_part_id=received_purchase_order_parts_list.id AND iqc_status = "1" AND status = "2") as available,(SELECT count(id) FROM received_parts_barcode_list WHERE received_part_id=received_purchase_order_parts_list.id AND iqc_status = "1" AND status = "3") as allocated,(SELECT count(id) FROM received_parts_barcode_list WHERE received_part_id=received_purchase_order_parts_list.id AND iqc_status = "1" AND status = "0") as consumed,colour.name as colour_name,spare_part_price_list.sku_no,SUM(els_order_request_parts.quantity) as required_qty')->groupBy('spare_part_price_list.id')->get();

        $i = 1;
        $setData = '';
        if (!$data->isEmpty()) {
            foreach ($data as $value) {
                $required_qty = \Helper::getRequiredQuantity($value->brand_id, $value->model_id, $value->part_type_id, $value->part_id, $value->colour_id);
                $rowData = '';
                $rowData .= '"' . $i . '"' . "\t";
                $rowData .= '"' . $value->bname . '"' . "\t";
                $rowData .= '"' . $value->mname . '"' . "\t";
                $rowData .= '"' . $value->type . '"' . "\t";
                $rowData .= '"' . $value->sku_no . '"' . "\t";
                $rowData .= '"' . $value->part_name . '"' . "\t";
                $rowData .= '"' . $value->colour_name . '"' . "\t";
                $rowData .= '"' . $required_qty . '"' . "\t";
                $rowData .= '"' . $value->panding_qty . '"' . "\t";
                $rowData .= '"' . ($value->iqc_failed + $value->iqc_pass) . '"' . "\t";
                $rowData .= '"' . $value->iqc_failed . '"' . "\t";
                $rowData .= '"' . $value->iqc_pass . '"' . "\t";
                $rowData .= '"' . $value->available . '"' . "\t";
                $rowData .= '"' . $value->allocated . '"' . "\t";
                $rowData .= '"' . $value->consumed . '"' . "\t";
                $setData .= trim($rowData) . "\n";
                $i++;
            }
        }
        echo ucwords($columnHeader) . "\n" . $setData . "\n";
    }

    public function getUploadStockIn(Request $request)
    {
        set_time_limit(0);
        $file = $request->file('file_csv');
        if (in_array(strtoupper($file->getClientOriginalExtension()), ['CSV'])) {
            $fileD = fopen($file, "r");
            $column = fgetcsv($fileD);
            if ($column) {
                foreach ($column as $k => $d) {
                    if ($d == 'PIN') {
                        $barcode_key = $k;
                    }
                    if ($d == 'QC Status') {
                        $status_key = $k;
                    }
                    if ($d == 'Tester Name') {
                        $tester_key = $k;
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
                if (isset($rowData[$barcode_key])) {
                    $row['barcode'] = $rowData[$barcode_key];
                }
                if (isset($rowData[$status_key])) {
                    $row['iqc_status'] = '0';
                    $row['iqc_status_one'] = '0';
                    if (strtolower($rowData[$status_key]) == 'pass') {
                        $row['iqc_status'] = '1';
                        $row['iqc_status_one'] = '1';
                    }
                }
                if (isset($rowData[$tester_key])) {
                    $user = \App\Models\User::where('name', $rowData[$tester_key])->first();
                    if ($user) {
                        $row['tester_id'] = $user->id;
                    } else {
                        $msg .= 'Tester';
                        $error = true;
                    }
                }

                $dataArr[] = $row;
                if ($error) $massage .= $msg . ' Not Found. Please Correct The Details.';
                $i++;
            }
            if ($error) {
                return response()->json(['status' => false, 'data' => [], 'code' => 401, 'message' => $massage]);
            }

            if ($dataArr) {
                foreach ($dataArr as $val) {
                    $d = (object) $val;
                    $data = \App\Models\ReceivedPurchaseOrderPartsBarcode::where('barcode', $d->barcode)->where('status', '2')->first();
                    if ($data) {
                        $data->iqc_status = $d->iqc_status;
                        $data->uploaded_by = \Auth::user()->id;
                        $data->iqc_status_one = $d->iqc_status;
                        $data->tester_id = $d->tester_id;
                        $data->received_date = date('Y-m-d h:i:s');
                        $data->save();
                    }
                }
            }
            return response()->json(['status' => true, 'data' => [], 'code' => 200, 'message' => 'Part Stock In successfully imported.']);
        }
        return response()->json(['status' => false, 'data' => [], 'code' => 401, 'message' => 'Please choose a correct formate.']);
    }

    public function downloadRecivedPurchaseOrderBarcode(Request $request)
    {
        header("Content-Type: application/xls");
        header("Content-Disposition: attachment; filename=SparePartsPurchaseUINumberList.xls");
        header("Pragma: no-cache");
        header("Expires: 0");

        $columnHeader = '';
        $columnHeader = "Sr NO" . "\t" . "Date" . "\t" . "PO Number" . "\t" . "PIN" . "\t" . "Brand" . "\t" . "Model" . "\t" . "Part Type" . "\t" . "Parts Name" . "\t" . "Colour Name" . "\t" . "Price" . "\t";

        $data = \App\Models\ReceivedPurchaseOrderPartsBarcode::join('received_purchase_order_parts_list', function ($join) {
            $join->on('received_parts_barcode_list.received_part_id', 'received_purchase_order_parts_list.id');
        })->join('brand', function ($join) {
            $join->on('brand.id', 'received_purchase_order_parts_list.brand_id');
        })->join('model', function ($join) {
            $join->on('model.id', 'received_purchase_order_parts_list.model_id');
        })->join('product_type', function ($join) {
            $join->on('product_type.id', 'received_purchase_order_parts_list.product_type_id');
        })->join('part', function ($join) {
            $join->on('part.id', 'received_purchase_order_parts_list.part_id');
        })->join('colour', function ($join) {
            $join->on('colour.id', 'received_purchase_order_parts_list.colour_id');
        })->selectRaw('received_purchase_order_parts_list.purchase_order_id,received_parts_barcode_list.barcode,colour.name as colour_name,product_type.type as part_type,part.name as part_name,model.mname as model_name,brand.bname as brand_name,received_parts_barcode_list.price,received_parts_barcode_list.created_at')->get();

        $i = 1;
        $setData = '';
        if (!$data->isEmpty()) {
            foreach ($data as $value) {
                $rowData = '';
                $rowData .= '"' . $i . '"' . "\t";
                $rowData .= '"' . date('d/m/Y', strtotime($value->created_at)) . '"' . "\t";
                $rowData .= '"' . $value->purchase_order_id . '"' . "\t";
                $rowData .= '"' . $value->barcode . '"' . "\t";
                $rowData .= '"' . $value->brand_name . '"' . "\t";
                $rowData .= '"' . $value->model_name . '"' . "\t";
                $rowData .= '"' . $value->part_type . '"' . "\t";
                $rowData .= '"' . $value->part_name . '"' . "\t";
                $rowData .= '"' . $value->colour_name . '"' . "\t";
                $rowData .= '"' . $value->price . '"' . "\t";
                $setData .= trim($rowData) . "\n";
                $i++;
            }
        }
        echo ucwords($columnHeader) . "\n" . $setData . "\n";
    }
}
