<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\MasterCategoryModel;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function test()
    {
        $data = \App\Models\InwardDate::join('els_system_info_details', function ($join) {
            $join->on('inward_date.els_system_id', 'els_system_info_details.id');
            // $join->where('inward_date.status','1');
            $join->whereNull('inward_date.deleted_at');
        })->join('brand', function ($join) {
            $join->on('els_system_info_details.brand_id', 'brand.id');
        })->join('model', function ($join) {
            $join->on('els_system_info_details.model_id', 'model.id');
        })->join('colour', function ($join) {
            $join->on('els_system_info_details.colour_id', 'colour.id');
        })->leftjoin('ram', function ($join) {
            $join->on('els_system_info_details.ram', 'ram.id');
        })->leftjoin('rom', function ($join) {
            $join->on('els_system_info_details.rom', 'rom.id');
        })->leftjoin('grade', function ($join) {
            $join->on('els_system_info_details.grade', 'grade.id');
        })->join('users', function ($join) {
            $join->on('els_system_info_details.created_by', 'users.id');
        })->leftjoin('els_system_allocated_engineer', function ($join) {
            $join->on('els_system_allocated_engineer.els_system_id', 'els_system_info_details.id');
            $join->whereNull('els_system_allocated_engineer.deleted_at');
            $join->where('els_system_allocated_engineer.active', '1');
        })->leftjoin('els_product_status', function ($join) {
            $join->on('els_system_info_details.status', 'els_product_status.id');
            // $join->whereNull('els_product_status.deleted_at');
        })->leftjoin('els_system_status_log', function ($join) {
            $join->on('els_system_allocated_engineer.id', 'els_system_status_log.els_system_allocation_id');
            $join->on('els_system_info_details.status', 'els_system_status_log.status');
            $join->on('els_system_allocated_engineer.els_system_id', 'els_system_status_log.els_system_id');
            // $join->whereNull('els_system_status_log.deleted_at');
        })->leftjoin('els_product_sub_status', function ($join) {
            $join->on('els_product_status.sub_status_id', 'els_product_sub_status.id');
            // $join->whereNull('els_product_sub_status.deleted_at');
        })->leftjoin('vendor', 'vendor.id', '=', 'els_system_info_details.vendor_id')
            ->leftjoin('vendor as out', 'out.id', '=', 'els_system_status_log.vendor_id')
            ->whereNull('els_system_info_details.deleted_at')
            ->groupBy('inward_date.els_system_id')
            ->orderBy('els_system_info_details.resived_date', 'DESC')->get();


        echo '<pre>';
        print_r($data);

        foreach ($data as $key => $val) {
            echo $key;
            $data->$key['ril'] = $key;
        }
        echo '<pre>';
        print_r($data);
    }
    public function index()
    {
        $title = "Dashboard";
        $status = \App\Models\ELSProductStatus::whereNull('deleted_at')->where('status', '1')->get();


        $data1 = \App\Models\SparePartPriceList::join('spare_part_list', function ($join) {
            $join->on('spare_part_price_list.series_id', 'spare_part_list.id');
            $join->whereNull('spare_part_price_list.deleted_at');
        })->join('brand', 'spare_part_list.brand_id', '=', 'brand.id')
            ->join('model', 'spare_part_list.model_id', '=', 'model.id')
            ->join('product_type', 'spare_part_list.type_id', '=', 'product_type.id')
            ->join('part', 'spare_part_list.part_id', '=', 'part.id')
            ->join('colour', 'spare_part_price_list.colour_id', '=', 'colour.id')
            ->orderBy('spare_part_list.id', 'desc')
            ->groupBy('spare_part_price_list.sku_no')
            ->select('spare_part_list.*', 'brand.bname', 'model.mname', 'product_type.type', 'part.name', 'spare_part_price_list.sku_no', 'colour.name as colour_name', 'spare_part_price_list.id as sku_id')->get();





        $data2 = \App\Models\InwardDate::join('els_system_info_details', function ($join) {
            $join->on('inward_date.els_system_id', 'els_system_info_details.id');
            // $join->where('inward_date.status','1');
            $join->whereNull('inward_date.deleted_at');
        })->join('brand', function ($join) {
            $join->on('els_system_info_details.brand_id', 'brand.id');
        })->join('model', function ($join) {
            $join->on('els_system_info_details.model_id', 'model.id');
        })->join('colour', function ($join) {
            $join->on('els_system_info_details.colour_id', 'colour.id');
        })->leftjoin('ram', function ($join) {
            $join->on('els_system_info_details.ram', 'ram.id');
        })->leftjoin('rom', function ($join) {
            $join->on('els_system_info_details.rom', 'rom.id');
        })->leftjoin('grade', function ($join) {
            $join->on('els_system_info_details.grade', 'grade.id');
        })->join('users', function ($join) {
            $join->on('els_system_info_details.created_by', 'users.id');
        })->leftjoin('els_system_allocated_engineer', function ($join) {
            $join->on('els_system_allocated_engineer.els_system_id', 'els_system_info_details.id');
            $join->whereNull('els_system_allocated_engineer.deleted_at');
            $join->where('els_system_allocated_engineer.active', '1');
        })->leftjoin('els_product_status', function ($join) {
            $join->on('els_system_allocated_engineer.status', 'els_product_status.id');
            // $join->whereNull('els_product_status.deleted_at');
        })->leftjoin('els_system_status_log', function ($join) {
            $join->on('els_system_allocated_engineer.id', 'els_system_status_log.els_system_allocation_id');
            $join->on('els_system_info_details.status', 'els_system_status_log.status');
            $join->on('els_system_allocated_engineer.els_system_id', 'els_system_status_log.els_system_id');
            // $join->whereNull('els_system_status_log.deleted_at');
        })->leftjoin('els_product_sub_status', function ($join) {
            $join->on('els_system_info_details.status', 'els_product_sub_status.id');
            // $join->whereNull('els_product_sub_status.deleted_at');
        })->leftjoin('vendor', 'vendor.id', '=', 'els_system_info_details.vendor_id')
            ->leftjoin('vendor as out', 'out.id', '=', 'els_system_status_log.vendor_id')
            ->whereNull('els_system_info_details.deleted_at')
            ->where('els_system_info_details.stock_in', 1)
            // 			->groupBy('inward_date.els_system_id')
            ->groupBy('els_system_info_details.model_id')
            ->orderBy('els_system_info_details.resived_date', 'DESC');




        $data2 = $data2->selectRaw('els_system_info_details.id,DATE_FORMAT(els_system_info_details.resived_date,"%d/%m/%Y") as resived_date,els_system_info_details.grn_no,ram.name as ram,rom.name as rom,grade.name as grade,els_system_info_details.remark,els_system_info_details.barcode,els_system_info_details.model_id,els_system_info_details.mrp,els_system_info_details.imei_1,els_system_info_details.imei_2,brand.bname,model.mname,vendor.vname,colour.name as colour_name,IF(inward_date.id, "Not assign",IF(els_product_status.id, els_product_status.name, "Not Assign")) as status,IF(els_system_status_log.created_at, CONCAT(els_product_sub_status.name,"(",DATE_FORMAT(els_system_status_log.created_at,"%d/%m/%Y"),")"), els_product_sub_status.name) as sub_status,out.vname as out_vendor,round((els_system_info_details.mrp +  IF((select round(SUM(received_purchase_order_parts_list.price),2) from els_system_allocated_parts_barcode join received_parts_barcode_list on received_parts_barcode_list.id=els_system_allocated_parts_barcode.barcode_id join received_purchase_order_parts_list on received_purchase_order_parts_list.id=received_parts_barcode_list.received_part_id where els_system_id = els_system_info_details.id), (select round(SUM(received_purchase_order_parts_list.price),2) from els_system_allocated_parts_barcode join received_parts_barcode_list on received_parts_barcode_list.id=els_system_allocated_parts_barcode.barcode_id join received_purchase_order_parts_list on received_purchase_order_parts_list.id=received_parts_barcode_list.received_part_id where els_system_id = els_system_info_details.id), 0) + IF((select sum(amount) from els_system_extra_expence where els_system_id = `els_system_info_details`.`id`), (select sum(amount) from els_system_extra_expence where els_system_id = `els_system_info_details`.`id`), 0)),2) as new_price')->get();

        return view('dashboard/home', compact('title', 'status', 'data1', 'data2'));
    }

    public function downloadInventoryReport(Request $request)
    {
        header("Content-Type: application/xls");
        header("Content-Disposition: attachment; filename=DeviceInventoryReport.xls");
        header("Pragma: no-cache");
        header("Expires: 0");

        $columnHeader = "Sr NO" . "\t" . "Model" . "\t" . "In Stock(Qty)" . "\t" . "In Stock(Prexo)" . "\t";

        $status = \App\Models\ELSProductStatus::whereNull('deleted_at')->where('status', '1')->get();
        foreach ($status as $s) {
            $columnHeader .= $s->name . "\t";
        }
        $data = \App\Models\ElsSystemInfoDtailsModel::join('model', function ($join) {
            $join->on('els_system_info_details.model_id', 'model.id');
        })->whereNull('els_system_info_details.deleted_at')->groupBy('els_system_info_details.model_id')->selectRaw('model.id,model.mname,count(distinct els_system_info_details.id)as in_stock')->get();

        $i = 1;
        $setData = '';
        if (!$data->isEmpty()) {
            foreach ($data as $value) {
                $rowData = '';
                $rowData .= '"' . $i . '"' . "\t";
                $rowData .= '"' . $value->mname . '"' . "\t";
                $rowData .= '"' . $value->in_stock . '"' . "\t";
                $rowData .= '"0"' . "\t";
                foreach ($status as $s) {
                    $count = \Helper::getStatusModelCount($value->id, $s->id);
                    $rowData .= '"' . $count . '"' . "\t";
                }
                $setData .= trim($rowData) . "\n";
                $i++;
            }
        }
        echo ucwords($columnHeader) . "\n" . $setData . "\n";
    }

    public function getDeviceInventoryList(Request $request)
    {
        $data = \App\Models\ElsSystemInfoDtailsModel::leftjoin('els_system_allocated_engineer', function ($join) {
            $join->on('els_system_info_details.id', 'els_system_allocated_engineer.els_system_id');
            $join->whereNull('els_system_allocated_engineer.deleted_at');
            $join->where('els_system_allocated_engineer.active', '1');
        })->leftjoin('els_product_status', function ($join) {
            $join->on('els_product_status.id', 'els_system_allocated_engineer.status');
        })->join('brand', function ($join) {
            $join->on('els_system_info_details.brand_id', 'brand.id');
        })->join('model', function ($join) {
            $join->on('els_system_info_details.model_id', 'model.id');
        })->join('colour', function ($join) {
            $join->on('els_system_info_details.colour_id', 'colour.id');
        })->join('ram', function ($join) {
            $join->on('els_system_info_details.ram', 'ram.id');
        })->join('rom', function ($join) {
            $join->on('els_system_info_details.rom', 'rom.id');
        })->join('grade', function ($join) {
            $join->on('els_system_info_details.grade', 'grade.id');
        })->whereNull('els_system_info_details.deleted_at')->orderBy('els_system_info_details.resived_date', 'DESC')->groupBy('els_system_info_details.id');

        $where_like = false;
        $columnsArr = ['els_system_info_details.resived_date', 'els_system_info_details.sku_no', 'els_system_info_details.barcode', 'model.mname', 'ram.name', 'rom.name', 'grade.name', 'colour.name', 'els_product_status.name'];
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

        $data = $data->selectRaw('els_system_info_details.*,ram.name as ram,rom.name as rom,grade.name as grade,brand.bname,model.mname,colour.name as colour_name,els_product_status.name as current_status')->get();
        return response()->json(['status' => true, 'data' => $data, 'recordsTotal' => $totalRecord, 'recordsFiltered' => $totalRecord, 'code' => 200, 'message' => 'Engneer work report listed successfully']);
    }

    public function downloadDeviceInventoryReport(Request $request)
    {
        header("Content-Type: application/xls");
        header("Content-Disposition: attachment; filename=DeviceStatusWiseReport.xls");
        header("Pragma: no-cache");
        header("Expires: 0");

        $columnHeader = "Sr NO" . "\t" . "Received Date" . "\t" . "Barcode" . "\t" . "SKU Number" . "\t" . "Brand" . "\t" . "Model" . "\t" . "Colour" . "\t" . "Current Status" . "\t" . "RAM" . "\t" . "ROM" . "\t" . "Grade" . "\t";

        $data = \App\Models\ElsSystemInfoDtailsModel::leftjoin('els_system_allocated_engineer', function ($join) {
            $join->on('els_system_info_details.id', 'els_system_allocated_engineer.els_system_id');
            $join->whereNull('els_system_allocated_engineer.deleted_at');
            $join->where('els_system_allocated_engineer.active', '1');
        })->leftjoin('els_product_status', function ($join) {
            $join->on('els_product_status.id', 'els_system_allocated_engineer.status');
        })->join('brand', function ($join) {
            $join->on('els_system_info_details.brand_id', 'brand.id');
        })->join('model', function ($join) {
            $join->on('els_system_info_details.model_id', 'model.id');
        })->join('colour', function ($join) {
            $join->on('els_system_info_details.colour_id', 'colour.id');
        })->join('ram', function ($join) {
            $join->on('els_system_info_details.ram', 'ram.id');
        })->join('rom', function ($join) {
            $join->on('els_system_info_details.rom', 'rom.id');
        })->join('grade', function ($join) {
            $join->on('els_system_info_details.grade', 'grade.id');
        })->whereNull('els_system_info_details.deleted_at')->orderBy('els_system_info_details.resived_date', 'DESC')->groupBy('els_system_info_details.id');

        $data = $data->selectRaw('els_system_info_details.*,ram.name as ram,rom.name as rom,grade.name as grade,brand.bname,model.mname,colour.name as colour_name,els_product_status.name as current_status')->get();

        $i = 1;
        $setData = '';
        if (!$data->isEmpty()) {
            foreach ($data as $value) {
                $rowData = '';
                $rowData .= '"' . $i . '"' . "\t";
                $rowData .= '"' . $value->resived_date . '"' . "\t";
                $rowData .= '"' . $value->barcode . '"' . "\t";
                $rowData .= '"' . $value->sku_no . '"' . "\t";
                $rowData .= '"' . $value->bname . '"' . "\t";
                $rowData .= '"' . $value->mname . '"' . "\t";
                $rowData .= '"' . $value->colour_name . '"' . "\t";
                $rowData .= '"' . $value->current_status . '"' . "\t";
                $rowData .= '"' . $value->ram . '"' . "\t";
                $rowData .= '"' . $value->rom . '"' . "\t";
                $rowData .= '"' . $value->grade . '"' . "\t";
                $setData .= trim($rowData) . "\n";
                $i++;
            }
        }
        echo ucwords($columnHeader) . "\n" . $setData . "\n";
    }

    public function getDeviceBackwardTracking(Request $request)
    {
        $data = \App\Models\ElsSystemInfoDtailsModel::leftjoin('vendor', function ($join) {
            $join->on('els_system_info_details.vendor_id', 'vendor.id');
        })->join('els_product_status', function ($join) {
            $join->on('els_system_info_details.status', 'els_product_status.id');
            $join->whereNull('els_product_status.deleted_at');
        })->where('barcode', $request->barcode)->select('els_system_info_details.*', 'vendor.vname', 'els_product_status.name as sub_status')->first();

        if ($data) {
            $html = view('dashboard/device_backward_tracking', compact('data'))->render();
            return response()->json(['status' => true, 'data' => [], 'html' => \Helper::compressHtml($html), 'code' => 200, 'message' => 'Device backward tracking']);
        }
        return response()->json(['status' => false, 'data' => [], 'code' => 401, 'message' => 'No Data Found']);
    }

    public function downloadDeviceBackwardTracking(Request $request)
    {
        $data = \App\Models\ElsSystemInfoDtailsModel::leftjoin('vendor', function ($join) {
            $join->on('els_system_info_details.vendor_id', 'vendor.id');
        })->join('els_product_status', function ($join) {
            $join->on('els_system_info_details.status', 'els_product_status.id');
            $join->whereNull('els_product_status.deleted_at');
        })->where('barcode', $request->barcode)->select('els_system_info_details.*', 'vendor.vname', 'els_product_status.name as sub_status')->first();

        $i = 1;
        $setData = '';
        if ($data) {
            header("Content-Type: application/xls");
            header("Content-Disposition: attachment; filename=DeviceBackwardTracking.xls");
            header("Pragma: no-cache");
            header("Expires: 0");

            $current_in_stock = 0;
            $inwrd = \Helper::getInwardDate($data->id);
            $or = \Helper::getOrderRequest($data->id);
            $bar = \Helper::getBarcodeAllocation($data->id);
            $warrenty = \Helper::getDeviceWarrenty($data->id);
            $allocation = \Helper::getEngineerAllocationDetails($data->id);
            $status = \Helper::getStatusLog($data->id);
            $rowData = '';
            $rowData .= '"Barcode"' . "\t";
            $rowData .= '"' . $data->barcode . '"' . "\t";
            $setData .= trim($rowData) . "\n";
            $rowData = '';
            foreach ($inwrd as $key => $d) {
                if ($d->status) {
                    $current_in_stock = 1;
                }
                $rowData = '';
                $rowData .= '"Inward date ' . ($key + 1) . '"' . "\t";
                $rowData .= '"' . date('d/m/Y', strtotime($d->received_date)) . '"' . "\t";
                $setData .= trim($rowData) . "\n";
            }
            foreach ($or as $v) {
                $rowData = '';
                $rowData .= '"Order Request Date"' . "\t";
                $rowData .= '"' . date('d/m/Y', strtotime($v->created_at)) . '"' . "\t";
                $setData .= trim($rowData) . "\n";
                $rowData = '';
                $rowData .= '"Order Request Parts"' . "\t";
                $rowData .= '"' . $v->part_name . '"' . "\t";
                $setData .= trim($rowData) . "\n";
            }
            if ($bar) {
                $rowData = '';
                $rowData .= '"Allocated Barcode"' . "\t";
                $rowData .= '"' . $bar->barcode . '"' . "\t";
                $setData .= trim($rowData) . "\n";
            }
            foreach ($allocation as $v) {
                $rowData = '';
                $rowData .= '"Enginner Allocation Date"' . "\t";
                $rowData .= '"' . date('d/m/Y', strtotime($v->created_at)) . '"' . "\t";
                $setData .= trim($rowData) . "\n";
                $rowData = '';
                $rowData .= '"Enginner Name"' . "\t";
                $rowData .= '"' . $v->name . '"' . "\t";
                $setData .= trim($rowData) . "\n";
                $rowData = '';
                $rowData .= '"Status"' . "\t";
                $rowData .= '"' . $v->status . '"' . "\t";
                $setData .= trim($rowData) . "\n";
            }
            if ($current_in_stock) {
                $rowData = '';
                $rowData .= '"Status"' . "\t";
                $rowData .= '"Not assign"' . "\t";
                $setData .= trim($rowData) . "\n";
            }
            if ($data->sub_status) {
                foreach ($status as $sd) {
                    $rowData = '';
                    $rowData .= '"Status"' . "\t";
                    $rowData .= '"' . $sd->name . '(' . date("d/m/Y", strtotime($sd->created_at)) . ')' . '"' . "\t";
                    $setData .= trim($rowData) . "\n";

                    if ($sd->vname) {
                        $rowData = '';
                        $rowData .= '"Out Vendor"' . "\t";
                        $rowData .= '"' . $sd->vname . '"' . "\t";
                        $setData .= trim($rowData) . "\n";
                    }
                }
            }
            if ($warrenty) {
                $rowData = '';
                $rowData .= '"Warrenty Start Date"' . "\t";
                $rowData .= '"' . date('d/m/Y', strtotime($warrenty->start_date)) . '"' . "\t";
                $setData .= trim($rowData) . "\n";
                $rowData = '';
                $rowData .= '"Warrenty End Date"' . "\t";
                $rowData .= '"' . date('d/m/Y', strtotime($warrenty->end_date)) . '"' . "\t";
                $setData .= trim($rowData) . "\n";
            }
            if ($data->vname) {
                $rowData = '';
                $rowData .= '"Vendor"' . "\t";
                $rowData .= '"' . $data->vname . '"' . "\t";
                $setData .= trim($rowData) . "\n";
            }
            echo $setData . "\n";
        } else {
            return redirect('dashboard');
        }
    }

    public function getSparePartBackwardTracking(Request $request)
    {
        $data = \App\Models\ReceivedPurchaseOrderPartsBarcode::join('received_purchase_order_parts_list', function ($join) {
            $join->on('received_purchase_order_parts_list.id', 'received_parts_barcode_list.received_part_id');
        })->join('purchase_order_list', function ($join) {
            $join->on('purchase_order_list.id', 'received_purchase_order_parts_list.purchase_order_id');
        })->join('vendor', function ($join) {
            $join->on('vendor.id', 'purchase_order_list.vendor_id');
        })->leftjoin('users', function ($join) {
            $join->on('users.id', 'received_parts_barcode_list.tester_id');
        })->where('received_parts_barcode_list.barcode', $request->barcode)->select('received_parts_barcode_list.*', 'users.name', 'vendor.vname')->first();
        if ($data) {
            $html = view('dashboard/spare_part_backward_tracking', compact('data'))->render();
            return response()->json(['status' => true, 'data' => [], 'html' => \Helper::compressHtml($html), 'code' => 200, 'message' => 'Spare Part backward tracking']);
        }
        return response()->json(['status' => false, 'data' => [], 'code' => 401, 'message' => 'No Data Found']);
    }

    public function downloadSparePartBackwardTracking(Request $request)
    {
        $data = \App\Models\ReceivedPurchaseOrderPartsBarcode::join('received_purchase_order_parts_list', function ($join) {
            $join->on('received_purchase_order_parts_list.id', 'received_parts_barcode_list.received_part_id');
        })->join('purchase_order_list', function ($join) {
            $join->on('purchase_order_list.id', 'received_purchase_order_parts_list.purchase_order_id');
        })->join('vendor', function ($join) {
            $join->on('vendor.id', 'purchase_order_list.vendor_id');
        })->leftjoin('users', function ($join) {
            $join->on('users.id', 'received_parts_barcode_list.tester_id');
        })->where('received_parts_barcode_list.barcode', $request->spare_barcode)->select('received_parts_barcode_list.*', 'users.name', 'vendor.vname')->first();

        $i = 1;
        $setData = '';
        if ($data) {
            header("Content-Type: application/xls");
            header("Content-Disposition: attachment; filename=SparePartBackwardTracking.xls");
            header("Pragma: no-cache");
            header("Expires: 0");

            $status = '';
            $ab = \Helper::getAllocatedBarcode($data->id);
            if ($data->status == '2') {
                $status = 'Available';
            }
            if ($data->status == '3') {
                $status = 'Allocated';
            }
            if ($data->status == '0') {
                $status = 'Used';
            }

            $rowData = '';
            $rowData .= '"Barcode"' . "\t";
            $rowData .= '"' . $data->barcode . '"' . "\t";
            $setData .= trim($rowData) . "\n";
            $rowData = '';
            $rowData .= '"Inward date"' . "\t";
            $rowData .= '"' . date('d/m/Y', strtotime($data->created_at)) . '"' . "\t";
            $setData .= trim($rowData) . "\n";
            $rowData = '';
            $rowData .= '"Price"' . "\t";
            $rowData .= '"' . number_format($data->price, 2) . '"' . "\t";
            $setData .= trim($rowData) . "\n";
            $iqc_status = '';
            if ($data->iqc_status) {
                $iqc_status = 'Pass';
            } elseif ($data->iqc_status == '0') {
                $iqc_status = 'Failed';
            }
            $rowData = '';
            $rowData .= '"IQC Status"' . "\t";
            $rowData .= '"' . $iqc_status . '"' . "\t";
            $setData .= trim($rowData) . "\n";
            $rowData = '';
            $rowData .= '"Tester Name"' . "\t";
            $rowData .= '"' . $data->name . '"' . "\t";
            $setData .= trim($rowData) . "\n";

            foreach ($ab as $v) {
                $rowData = '';
                $rowData .= '"Assigned Date"' . "\t";
                $rowData .= '"' . date('d/m/Y', strtotime($v->created_at)) . '"' . "\t";
                $setData .= trim($rowData) . "\n";
                $rowData = '';
                $rowData .= '"Assigned Device Barcode"' . "\t";
                $rowData .= '"' . $v->barcode . '"' . "\t";
                $setData .= trim($rowData) . "\n";
            }
            $rowData = '';
            $rowData .= '"Current Status"' . "\t";
            $rowData .= '"' . $status . '"' . "\t";
            $setData .= trim($rowData) . "\n";

            if ($data->vname) {
                $rowData = '';
                $rowData .= '"Vendor"' . "\t";
                $rowData .= '"' . $data->vname . '"' . "\t";
                $setData .= trim($rowData) . "\n";
            }
            echo $setData . "\n";
        } else {
            return redirect('dashboard');
        }
    }
}