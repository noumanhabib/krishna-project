<?php

namespace App\Http\Controllers;

use Auth;
use ZipArchive;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\ElsSystemInfoDtailsModel;
use App\Models\MasterVendorModel;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ElsProductController extends Controller
{

    public function ElsProductList(Request $request)
    {
        if (\Helper::checkMenuElegible()) {
            $title = "ELS Product List";
            return view('els_product.els_product_list', compact('title'));
        }
        abort(403, "Don't have permission to access.");
    }



    public function exportdistributor(Request $request)
    {
        header("Content-Type: application/xls");
        header("Content-Disposition: attachment; filename=DistributorReport.xls");
        header("Pragma: no-cache");
        header("Expires: 0");

        $columnHeader = "Sr NO" . "\t" . "Id" . "\t" . "BarCode" . "\t" . "Imei One" . "\t" . "Imei Two" . "\t" . "Brand Name" . "\t" . "Modal Name" . "\t" . "Color Name" . "\t" . "Consumed Part" . "\t" . "Status" . "\t" . "Remark" . "\t" . "Date" . "\t";


        $data = \App\Models\ElsSystemInfoDtailsModel::join('els_system_allocated_engineer', function ($join) {
            $join->on('els_system_info_details.id', 'els_system_allocated_engineer.els_system_id');
            $join->where('els_system_allocated_engineer.active', '1');
            $join->whereNull('els_system_allocated_engineer.deleted_at');
        })->join('brand', function ($join) {
            $join->on('els_system_info_details.brand_id', 'brand.id');
        })->join('model', function ($join) {
            $join->on('els_system_info_details.model_id', 'model.id');
        })->join('colour', function ($join) {
            $join->on('els_system_info_details.colour_id', 'colour.id');
        })->join('users', function ($join) {
            $join->on('els_system_allocated_engineer.engineer_id', 'users.id');
        })->leftjoin('els_product_status', function ($join) {
            $join->on('els_product_status.id', 'els_system_allocated_engineer.status');
        })->leftjoin('els_system_allocated_parts_barcode', function ($join) {
            $join->on('els_system_info_details.id', 'els_system_allocated_parts_barcode.els_system_id');
            $join->where('els_system_allocated_parts_barcode.status', '1');
            $join->whereNull('els_system_allocated_parts_barcode.deleted_at');
        })->leftjoin('received_parts_barcode_list', function ($join) {
            $join->on('els_system_allocated_parts_barcode.barcode_id', 'received_parts_barcode_list.id');
            $join->whereNull('received_parts_barcode_list.deleted_at');
        })->whereNull('els_system_info_details.deleted_at')->where('els_system_info_details.current_stage', 1)->groupBy('els_system_info_details.id');


        $data = $data->selectRaw('els_system_info_details.id,els_system_info_details.barcode,els_system_info_details.imei_1,els_system_info_details.imei_2,brand.bname,model.mname,users.name,colour.name as colour_name,count(distinct els_system_allocated_parts_barcode.id) as consumed_part,els_system_allocated_engineer.id as allocated_id,els_system_allocated_engineer.status as status_id,els_product_status.name status,els_system_info_details.remark,els_system_allocated_engineer.created_at')->get();

        $i = 1;
        $setData = '';
        if (!$data->isEmpty()) {
            foreach ($data as $value) {
                $rowData = '';
                $rowData .= '"' . $i . '"' . "\t";
                $rowData .= '"' . $value->id . '"' . "\t";
                $rowData .= '"' . $value->barcode . '"' . "\t";
                $rowData .= '"' . $value->imei_1 . '"' . "\t";
                $rowData .= '"' . $value->imei_2 . '"' . "\t";
                $rowData .= '"' . $value->bname . '"' . "\t";
                $rowData .= '"' . $value->mname . '"' . "\t";
                $rowData .= '"' . $value->colour_name . '"' . "\t";
                $rowData .= '"' . $value->consumed_part . '"' . "\t";
                $rowData .= '"' . $value->status . '"' . "\t";
                $rowData .= '"' . $value->remark . '"' . "\t";
                $rowData .= '"' . $value->created_at . '"' . "\t";
                $setData .= trim($rowData) . "\n";
                $i++;
            }
        }
        echo ucwords($columnHeader) . "\n" . $setData . "\n";
    }

    public function exportcollectback(Request $request)
    {
        header("Content-Type: application/xls");
        header("Content-Disposition: attachment; filename=CollectbackReport.xls");
        header("Pragma: no-cache");
        header("Expires: 0");


        $columnHeader = "Sr NO" . "\t" . "Id" . "\t" . "BarCode" . "\t" . "Imei One" . "\t" . "Imei Two" . "\t" . "Brand Name" . "\t" . "Modal Name" . "\t" . "Color Name" . "\t" . "Consumed Part" . "\t" . "Status" . "\t" . "Remark" . "\t" . "Date" . "\t";


        $data = \App\Models\ElsSystemInfoDtailsModel::join('els_system_allocated_engineer', function ($join) {
            $join->on('els_system_info_details.id', 'els_system_allocated_engineer.els_system_id');
            $join->where('els_system_allocated_engineer.active', '1');
            $join->whereNull('els_system_allocated_engineer.deleted_at');
        })->join('brand', function ($join) {
            $join->on('els_system_info_details.brand_id', 'brand.id');
        })->join('model', function ($join) {
            $join->on('els_system_info_details.model_id', 'model.id');
        })->join('colour', function ($join) {
            $join->on('els_system_info_details.colour_id', 'colour.id');
        })->join('users', function ($join) {
            $join->on('els_system_allocated_engineer.engineer_id', 'users.id');
        })->leftjoin('els_product_status', function ($join) {
            $join->on('els_product_status.id', 'els_system_allocated_engineer.status');
        })->leftjoin('els_system_allocated_parts_barcode', function ($join) {
            $join->on('els_system_info_details.id', 'els_system_allocated_parts_barcode.els_system_id');
            $join->where('els_system_allocated_parts_barcode.status', '1');
            $join->whereNull('els_system_allocated_parts_barcode.deleted_at');
        })->leftjoin('received_parts_barcode_list', function ($join) {
            $join->on('els_system_allocated_parts_barcode.barcode_id', 'received_parts_barcode_list.id');
            $join->whereNull('received_parts_barcode_list.deleted_at');
        })->whereNull('els_system_info_details.deleted_at')->where('els_system_info_details.current_stage', 2)->groupBy('els_system_info_details.id');


        $data = $data->selectRaw('els_system_info_details.id,els_system_info_details.barcode,els_system_info_details.imei_1,els_system_info_details.imei_2,brand.bname,model.mname,users.name,colour.name as colour_name,count(distinct els_system_allocated_parts_barcode.id) as consumed_part,els_system_allocated_engineer.id as allocated_id,els_system_allocated_engineer.status as status_id,els_product_status.name status,els_system_info_details.remark,els_system_allocated_engineer.created_at')->get();

        $i = 1;
        $setData = '';
        if (!$data->isEmpty()) {
            foreach ($data as $value) {
                $rowData = '';
                $rowData .= '"' . $i . '"' . "\t";
                $rowData .= '"' . $value->id . '"' . "\t";
                $rowData .= '"' . $value->barcode . '"' . "\t";
                $rowData .= '"' . $value->imei_1 . '"' . "\t";
                $rowData .= '"' . $value->imei_2 . '"' . "\t";
                $rowData .= '"' . $value->bname . '"' . "\t";
                $rowData .= '"' . $value->mname . '"' . "\t";
                $rowData .= '"' . $value->colour_name . '"' . "\t";
                $rowData .= '"' . $value->consumed_part . '"' . "\t";
                $rowData .= '"' . $value->status . '"' . "\t";
                $rowData .= '"' . $value->remark . '"' . "\t";
                $rowData .= '"' . $value->created_at . '"' . "\t";
                $setData .= trim($rowData) . "\n";
                $i++;
            }
        }
        echo ucwords($columnHeader) . "\n" . $setData . "\n";
    }







    public function getElsProductList(Request $request)
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
            // ->where('els_system_info_details.id',25742)
            ->groupBy('inward_date.els_system_id')
            ->orderBy('els_system_info_details.resived_date', 'DESC');

        $where_like = false;
        $columnsArr = ['els_system_info_details.resived_date', 'els_system_info_details.grn_no', 'els_system_info_details.barcode', 'els_system_info_details.imei_1', 'els_system_info_details.imei_2', 'els_system_info_details.remark', 'ram.name', 'rom.name', 'grade.name', 'vendor.vname', 'brand.bname', 'model.mname', 'colour.name', 'out.vname', 'els_product_status.name', 'els_product_sub_status.name'];
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

        $data = $data->selectRaw('els_system_info_details.id,DATE_FORMAT(els_system_info_details.resived_date,"%d/%m/%Y") as resived_date,els_system_info_details.grn_no,ram.name as ram,rom.name as rom,grade.name as grade,els_system_info_details.remark,els_system_info_details.barcode,els_system_info_details.mrp,els_system_info_details.imei_1,els_system_info_details.imei_2,brand.bname,model.mname,vendor.vname,colour.name as colour_name,els_product_sub_status.name as sub_status,els_product_status.name as status,out.vname as out_vendor,round((els_system_info_details.mrp +  IF((select round(SUM(received_purchase_order_parts_list.price),2) from els_system_allocated_parts_barcode join received_parts_barcode_list on received_parts_barcode_list.id=els_system_allocated_parts_barcode.barcode_id join received_purchase_order_parts_list on received_purchase_order_parts_list.id=received_parts_barcode_list.received_part_id where els_system_id = els_system_info_details.id), (select round(SUM(received_purchase_order_parts_list.price),2) from els_system_allocated_parts_barcode join received_parts_barcode_list on received_parts_barcode_list.id=els_system_allocated_parts_barcode.barcode_id join received_purchase_order_parts_list on received_purchase_order_parts_list.id=received_parts_barcode_list.received_part_id where els_system_id = els_system_info_details.id), 0) + IF((select sum(amount) from els_system_extra_expence where els_system_id = `els_system_info_details`.`id`), (select sum(amount) from els_system_extra_expence where els_system_id = `els_system_info_details`.`id`), 0)),2) as new_price')->get();
        return response()->json(['status' => true, 'data' => $data, 'recordsTotal' => $totalRecord, 'recordsFiltered' => $totalRecord, 'code' => 200, 'message' => 'Device listed successfully']);
    }

    public function ElsProductList_in(Request $request)
    {
        if (\Helper::checkMenuElegible()) {
            $title = "ELS Product List";
            return view('els_product.els_product_list_inv', compact('title'));
        }
        abort(403, "Don't have permission to access.");
    }


    public function device_aging(Request $request)
    {
        if (\Helper::checkMenuElegible()) {
            $title = "ELS Product List";
            return view('els_product.device_aging', compact('title'));
        }
        abort(403, "Don't have permission to access.");
    }


    public function getElsProductList_in(Request $request)
    {
        // dd("heloo");
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
            ->where('els_system_info_details.stock_in', 1)
            ->groupBy('inward_date.els_system_id')
            ->orderBy('els_system_info_details.resived_date', 'DESC');

        $where_like = false;
        $columnsArr = ['els_system_info_details.resived_date', 'els_system_info_details.grn_no', 'els_system_info_details.barcode', 'els_system_info_details.imei_1', 'els_system_info_details.imei_2', 'els_system_info_details.remark', 'ram.name', 'rom.name', 'grade.name', 'vendor.vname', 'brand.bname', 'model.mname', 'colour.name', 'out.vname', 'els_product_status.name', 'els_product_sub_status.name'];
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





        /////////////////////////////


        // $bar = DB::table('els_order_request_parts')
        // 	->select('barcodes')
        // 	->get();



        // dd($bar);
        // $bar = \App\Models\OrderRequest::leftjoin('els_system_info_details', function ($join) {
        // 	$join->on('els_order_request.els_system_id', 'els_system_info_details.id');
        // })->orderBy('els_order_request.id', 'DESC')->select('els_order_request.*', 'els_system_info_details.barcode')->limit(200)->get();

        // $bar = DB::table('');
        // dd($bar);






        $data = $data->selectRaw('els_system_info_details.id,DATE_FORMAT(els_system_info_details.resived_date,"%d/%m/%Y") as resived_date,els_system_info_details.grn_no,ram.name as ram,rom.name as rom,grade.name as grade,els_system_info_details.remark,els_system_info_details.barcode,els_system_info_details.mrp,els_system_info_details.imei_1,els_system_info_details.imei_2,brand.bname,model.mname,vendor.vname,colour.name as colour_name,els_product_sub_status.name as status,els_product_status.name as sub_status,out.vname as out_vendor,round((els_system_info_details.mrp +  IF((select round(SUM(received_purchase_order_parts_list.price),2) from els_system_allocated_parts_barcode join received_parts_barcode_list on received_parts_barcode_list.id=els_system_allocated_parts_barcode.barcode_id join received_purchase_order_parts_list on received_purchase_order_parts_list.id=received_parts_barcode_list.received_part_id where els_system_id = els_system_info_details.id), (select round(SUM(received_purchase_order_parts_list.price),2) from els_system_allocated_parts_barcode join received_parts_barcode_list on received_parts_barcode_list.id=els_system_allocated_parts_barcode.barcode_id join received_purchase_order_parts_list on received_purchase_order_parts_list.id=received_parts_barcode_list.received_part_id where els_system_id = els_system_info_details.id), 0) + IF((select sum(amount) from els_system_extra_expence where els_system_id = `els_system_info_details`.`id`), (select sum(amount) from els_system_extra_expence where els_system_id = `els_system_info_details`.`id`), 0)),2) as new_price,TIMESTAMPDIFF(DAY, els_system_info_details.created_at, now()) AS age,DATE_FORMAT(els_system_info_details.created_at, "%d-%m-%Y") as formatted_dob')->get();


        return response()->json(['status' => true,  'data' => $data, 'recordsTotal' => $totalRecord, 'recordsFiltered' => $totalRecord, 'code' => 200, 'message' => 'Device listed successfully']);
    }


    public function getElsProductList_ind(Request $request)
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
            ->where('els_system_info_details.stock_in', 1)
            ->where('els_system_info_details.is_active', 1)
            ->groupBy('inward_date.els_system_id')
            ->orderBy('els_system_info_details.resived_date', 'DESC');




        $where_like = false;
        $columnsArr = ['els_system_info_details.resived_date', 'els_system_info_details.grn_no', 'els_system_info_details.barcode', 'els_system_info_details.imei_1', 'els_system_info_details.imei_2', 'els_system_info_details.remark', 'ram.name', 'rom.name', 'grade.name', 'vendor.vname', 'brand.bname', 'model.mname', 'colour.name', 'out.vname', 'els_product_status.name', 'els_product_sub_status.name'];
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


        // 			if(session()->get('start_date') && session()->get('end_date')){
        // 										$start_date = session()->get('start_date');
        // 										$end_date = date('Y-m-d', strtotime('+1 day', strtotime(session()->get('end_date'))));
        // 									}else{
        // 										$start_date = date('Y-m-d', strtotime('-29 day'));
        // 										$end_date = date('Y-m-d', strtotime('+1 day'));
        // 									}
        // $data = $data->whereBetween('els_system_info_details.created_at', [$start_date, $end_date]);


        $totalRecord = count($data->get());
        if ($request->get('length')) {
            $data = $data->skip($request->get('start'))->take($request->get('length'));
        }

















        $data = $data->selectRaw('els_system_info_details.id,DATE_FORMAT(els_system_info_details.resived_date,"%d/%m/%Y") as resived_date,els_system_info_details.grn_no,ram.name as ram,rom.name as rom,grade.name as grade,els_system_info_details.remark,els_system_info_details.barcode,els_system_info_details.mrp,els_system_info_details.imei_1,els_system_info_details.imei_2,brand.bname,model.mname,vendor.vname,colour.name as colour_name,els_product_sub_status.name as status,els_product_status.name as sub_status,out.vname as out_vendor,round((els_system_info_details.mrp +  IF((select round(SUM(received_purchase_order_parts_list.price),2) from els_system_allocated_parts_barcode join received_parts_barcode_list on received_parts_barcode_list.id=els_system_allocated_parts_barcode.barcode_id join received_purchase_order_parts_list on received_purchase_order_parts_list.id=received_parts_barcode_list.received_part_id where els_system_id = els_system_info_details.id), (select round(SUM(received_purchase_order_parts_list.price),2) from els_system_allocated_parts_barcode join received_parts_barcode_list on received_parts_barcode_list.id=els_system_allocated_parts_barcode.barcode_id join received_purchase_order_parts_list on received_purchase_order_parts_list.id=received_parts_barcode_list.received_part_id where els_system_id = els_system_info_details.id), 0) + IF((select sum(amount) from els_system_extra_expence where els_system_id = `els_system_info_details`.`id`), (select sum(amount) from els_system_extra_expence where els_system_id = `els_system_info_details`.`id`), 0)),2) as new_price,TIMESTAMPDIFF(DAY, els_system_info_details.resived_date, now()) AS age,DATE_FORMAT(els_system_info_details.created_at, "%d-%m-%Y") as formatted_dob')->get();
        return response()->json(['status' => true, 'data' => $data, 'recordsTotal' => $totalRecord, 'recordsFiltered' => $totalRecord, 'code' => 200, 'message' => 'Device listed successfully']);
    }


    public function ElsProductForm(Request $request, $id = null)
    {
        $imeione = '';
        $data = [];
        if ($id) {
            $title = "Edit Product";
            $data = \App\Models\ElsSystemInfoDtailsModel::where('id', $id)->first();
            if ($data) {
                $model = \App\Models\MasterModel::where('brand_id', $data->brand_id)->whereNull('deleted_at')->pluck('mname', 'id');
            }
        } else {
            $title = "ELS Product Form";
            $model = [];
        }

        $brand_list = \App\Models\MasterBrandModel::where('bstatus', '1')->whereNull('deleted_at')->pluck('bname', 'id');
        $color_list = \App\Models\MasterColourModel::where('status', '1')->whereNull('deleted_at')->pluck('name', 'id');
        $grade = \App\Models\MasterGradeModel::where('status', '1')->whereNull('deleted_at')->pluck('name', 'id');
        $ram = \App\Models\MasterRamModel::where('status', '1')->whereNull('deleted_at')->pluck('name', 'id');
        $rom = \App\Models\MasterRomModel::where('status', '1')->whereNull('deleted_at')->pluck('name', 'id');
        $vendor = \App\Models\MasterVendorModel::where('status', '1')->whereNull('deleted_at')->pluck('vname', 'id');
        return view('els_product.els_product_form', compact('title', 'grade', 'ram', 'rom', 'brand_list', 'color_list', 'model', 'data', 'vendor', 'imeione'));
    }

    public function ElsImei(Request $request)
    {
        $imeione = $request->imei_1;
        $data = \App\Models\ElsSystemInfoDtailsModel::where('imei_1', $imeione)->whereNotNull('imei_1')->first();
        $id = $data->id;
        $data = [];
        if (@$id) {
            $title = "Edit Product";
            $data = \App\Models\ElsSystemInfoDtailsModel::where('id', $id)->first();
            if ($data) {
                $model = \App\Models\MasterModel::where('brand_id', $data->brand_id)->whereNull('deleted_at')->pluck('mname', 'id');
            }
        } else {
            $title = "ELS Product Form";
            $model = [];
        }
        $brand_list = \App\Models\MasterBrandModel::where('bstatus', '1')->whereNull('deleted_at')->pluck('bname', 'id');
        $color_list = \App\Models\MasterColourModel::where('status', '1')->whereNull('deleted_at')->pluck('name', 'id');
        $grade = \App\Models\MasterGradeModel::where('status', '1')->whereNull('deleted_at')->pluck('name', 'id');
        $ram = \App\Models\MasterRamModel::where('status', '1')->whereNull('deleted_at')->pluck('name', 'id');
        $rom = \App\Models\MasterRomModel::where('status', '1')->whereNull('deleted_at')->pluck('name', 'id');
        $vendor = \App\Models\MasterVendorModel::where('status', '1')->whereNull('deleted_at')->pluck('vname', 'id');

        return view('els_product.els_product_form_ajax', compact('title', 'grade', 'ram', 'rom', 'brand_list', 'color_list', 'model', 'data', 'vendor', 'imeione'));
    }




    public function phonecheck()
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "https://clientapiv2.phonecheck.com/cloud/cloudDB/GetAllDevices/");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt(
            $ch,
            CURLOPT_POSTFIELDS,
            "Apikey=59c6ee21-8f90-489d-9a7e-3e5ba3846fcb&Username=sgcm1"
        );

        // In real life you should use something like:
        // curl_setopt($ch, CURLOPT_POSTFIELDS,
        //          http_build_query(array('postvar1' => 'value1')));

        // Receive server response ...
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $server_output = curl_exec($ch);

        curl_close($ch);
        // $arr=array(
        // 'result'=> $server_output
        // );
        // DB::table('phonecheck_response')->insert($arr);

        echo '<pre>';
        $server_output1 = json_decode($server_output, true);
        print_r($server_output1);
        if (count($server_output1) > 0) {
            $msg = '';
            for ($vv = 0; $vv < count($server_output1); $vv++) {
                $vendors = $output = $server_output1[$vv]['MasterName'];
                $modelss = $output = $server_output1[$vv]['Model'];
                $models = DB::table('model_syn')->where('mname1', $modelss)->count();
                if ($models > 0) {
                    $models = DB::table('model_syn')->where('mname1', $modelss)->first();
                    $models = $models->mname2;
                } else {
                    $models = $modelss;
                }


                $brands = $output = $server_output1[$vv]['Make'];
                $roms = $output = $server_output1[$vv]['Memory'];
                $ramsss = $output = $server_output1[$vv]['Ram'];
                $ramss = explode(" ", $ramsss);
                array_pop($ramss);
                $rams = implode(" ", $ramss);
                $colors = $output = $server_output1[$vv]['Color'];

                $grades = $output = $server_output1[$vv]['Grade'];
                $imei = $output = $server_output1[$vv]['IMEI'];
                $imei2 = $server_output1[$vv]['IMEI2'];
                $grn = $output = $server_output1[$vv]['Serial'];
                $mrp = $output = $server_output1[$vv]['final_price'];
                $revised_date = $output = $server_output1[$vv]['DeviceUpdatedDate'];

                if (isset($brands)) {
                    $brand = \App\Models\MasterBrandModel::where('bstatus', '1')->whereNull('deleted_at')->where('bname', $brands)->first();
                    if ($brand) {
                        $row['brand_id'] = $brand->id;
                    } else {
                        $msg .= 'Brand,';
                        $error = true;
                    }
                }


                if (isset($models)) {
                    $model = \App\Models\MasterModel::where('mstatus', '1')->whereNull('deleted_at')->where('mname', $models)->first();
                    if ($model) {
                        $row['model_id'] = $model->id;
                    } else {
                        $msg .= 'Model,';
                        $error = true;
                    }
                }


                if (isset($colors)) {
                    $colour = \App\Models\MasterColourModel::where('status', '1')->whereNull('deleted_at')->where('name', $colors)->first();
                    if ($colour) {
                        $row['colour_id'] = $colour->id;
                    } else {
                        $msg .= 'Colour';
                        $error = true;
                    }
                }

                $row['grn_no'] = null;
                if (isset($imei)) {
                    $row['imei_1'] = trim($imei);
                }
                if (isset($imei2)) {
                    $row['imei_2'] = trim($imei2);
                }



                if (!empty($rams)) {



                    $ram = DB::select("select * from `ram` where `status` = '1' and `deleted_at` is null and `name` = '" . $rams . "' limit 1");


                    if (!empty($ram)) {
                        $row['ram'] = $ram[0]->id;
                    } else {
                        $row['ram'] = NULL;
                    }
                } else {
                    $row['ram'] = NULL;
                }
                if (!empty($roms)) {


                    $rom = DB::select("select * from `rom` where `status` = '1' and `deleted_at` is null and `name` = '" . trim($roms) . "' limit 1");



                    if (!empty($rom)) {
                        $row['rom'] = $rom[0]->id;
                    } else {
                        $row['rom'] = NULL;
                    }
                } else {
                    $row['rom'] = NULL;
                }


                if (isset($grades)) {
                    $grade = \App\Models\MasterGradeModel::where('status', '1')->whereNull('deleted_at')->where('name', $grades)->first();
                    if ($grade) {
                        $row['grade'] = $grade->id;
                    } else {
                        $row['grade'] = NULL;
                    }
                } else {
                    $row['grade'] = NULL;
                }



                if (isset($mrp)) {
                    $row['mrp'] = $mrp;
                }

                $row['remark'] = 'Automated';

                if (isset($revised_date)) {
                    $row['resived_date'] = date('Y-m-d', strtotime(str_replace('-', '/', $revised_date)));
                }

                if ($colors == null) {
                    $row['colour_id'] = 37;
                }

                $row['created_at'] = date('Y-m-d', strtotime(str_replace('-', '/', $revised_date)));
                $row['created_by'] = 66;
                $row['updated_by'] = 66;

                $row['vendor_id'] = NULL;

                if (strlen($row['imei_1']) == 15) {

                    $data = \App\Models\ElsSystemInfoDtailsModel::where('imei_1', trim($row['imei_1']))->first();



                    if (isset($data->id)) {
                        $id = $data->id;
                        \App\Models\ElsSystemInfoDtailsModel::where('id', $id)->update(['stock_in' => 1]);


                        //                 DB::table('els_system_info_details')->where('id',$data->id)->update($row);
                        //                 $id=$data->id;
                        //                 $barcode=$data->barcode;
                        //                 	\App\Models\ElsSystemInfoDtailsModel::where('id',$id)->update(['barcode'=>$barcode,'stock_in'=>1]);

                        //                 		$inwd = \App\Models\InwardDate::where('els_system_id',$id)->orderBy('id','DESC')->first();
                        // 			\App\Models\InwardDate::where('id',$inwd->id)->update(['received_date'=>$revised_date]);

                    } else {
                        $row['status'] = 0;
                        $row['current_status'] = 0;
                        $id = DB::table('els_system_info_details')->insertGetId($row);
                        if (strlen($id) < 6) {
                            $length = 6 - strlen($id);
                            // $barcode = $id.$this->random_stringss($length);
                            $barcode = $this->random_strings(8);
                        } else {
                            $barcode = $this->random_strings(8);
                        }

                        \App\Models\ElsSystemInfoDtailsModel::where('id', $id)->update(['barcode' => $barcode, 'stock_in' => 1]);

                        \App\Models\InwardDate::Create([
                            'els_system_id'   => $id,
                            'received_date'   => $revised_date,
                        ]);
                    }
                }
            }
        }

        // Further processing ...
        if ($server_output == "OK") {
        } else {
        }
    }

    public function SaveElsproduct(Request $request)
    {
        if (!$request->id) {
            $dataArr['created_by'] = Auth::user()->id;
        }
        if ($request->resived_date) {
            $resived_date = date('Y-m-d', strtotime($request->resived_date));
        } else {
            $resived_date = date('Y-m-d');
        }
        $dataArr['brand_id'] = $request->brand_id;
        $dataArr['model_id'] = $request->model_id;
        $dataArr['colour_id'] = $request->color_id;
        $dataArr['grn_no'] = $request->grn_no;
        $dataArr['ram'] = $request->ram;
        $dataArr['rom'] = $request->rom;
        $dataArr['grade'] = $request->grade;
        $dataArr['mrp'] = $request->mrp;
        $dataArr['remark'] = $request->remark;
        $dataArr['vendor_id'] = $request->vendor_id;
        $dataArr['quantity'] = 1;
        $dataArr['resived_date'] = $resived_date;
        $dataArr['updated_by'] = Auth::user()->id;
        $dataArr['imei_2'] = $request->imei_2;
        $dataArr['sku_no'] = \Helper::getDeviceSKUNumber($request->brand_id, $request->model_id, $request->color_id);

        $data = \App\Models\ElsSystemInfoDtailsModel::where('imei_1', $request->imei_1)->first();
        if ($request->id) {
            DB::table('els_system_info_details')->where('id', $request->id)->update($dataArr);
            $id = $request->id;
        } else {

            $id = DB::table('els_system_info_details')->insertGetId($dataArr);
        }


        //         $save = \App\Models\ElsSystemInfoDtailsModel::updateOrCreate([
        // // 			'imei_1'   =>$request->imei_1,
        // 		],$dataArr);



        // 		$id = $save->id;
        if (!$data) {

            if ($request->id) {
                $datas = \App\Models\ElsSystemInfoDtailsModel::where('id', $request->id)->first();
                $barcode = $datas->barcode;
            } else {
                if (strlen($id) < 6) {
                    $length = 6 - strlen($id);
                    // $barcode = $id.$this->random_stringss($length);
                    $barcode = $this->random_strings(8);
                } else {
                    $barcode = $this->random_strings(8);
                }
            }
            if ($request->id) {
                $imei_1 = $request->imei_1;
                \App\Models\ElsSystemInfoDtailsModel::where('id', $id)->update(['imei_1' => $imei_1]);
            } else {
                $imei_1 = $request->imei_1;
                \App\Models\ElsSystemInfoDtailsModel::where('id', $id)->update(['barcode' => $barcode, 'imei_1' => $imei_1]);
            }
        } else {
            if ($request->id) {
                \App\Models\ElsSystemInfoDtailsModel::where('id', $id)->update(['status' => 1]);
            } else {
                $barcode = $this->random_strings(8);
                // 			\App\Models\ElsSystemInfoDtailsModel::where('id',$id)->update(['status'=>1,'barcode'=>$barcode]);
                \App\Models\ElsSystemInfoDtailsModel::where('id', $id)->update(['status' => 1, 'barcode' => $barcode, 'stock_in' => 1]);
            }
        }
        if ($request->id) {
            $inwd = \App\Models\InwardDate::where('els_system_id', $id)->orderBy('id', 'DESC')->first();
            \App\Models\InwardDate::where('id', $inwd->id)->update(['received_date' => $resived_date]);
        } else {
            \App\Models\InwardDate::Create([
                'els_system_id'   => $id,
                'received_date'   => $resived_date,
            ]);
        }
        return redirect()->route('els_product_list');
    }


    public function SaveElsproductajax(Request $request)
    {
        if (!$request->id) {
            $dataArr['created_by'] = Auth::user()->id;
        }
        if ($request->resived_date) {
            $resived_date = date('Y-m-d', strtotime($request->resived_date));
        } else {
            $resived_date = date('Y-m-d');
        }
        $dataArr['brand_id'] = $request->brand_id;
        $dataArr['model_id'] = $request->model_id;
        $dataArr['colour_id'] = $request->color_id;
        $dataArr['grn_no'] = $request->grn_no;
        $dataArr['ram'] = $request->ram;
        $dataArr['rom'] = $request->rom;
        $dataArr['grade'] = $request->grade;
        $dataArr['mrp'] = $request->mrp;
        $dataArr['remark'] = $request->remark;
        $dataArr['vendor_id'] = $request->vendor_id;
        $dataArr['quantity'] = 1;
        $dataArr['resived_date'] = $resived_date;
        $dataArr['updated_by'] = Auth::user()->id;
        $dataArr['imei_2'] = $request->imei_2;
        $dataArr['sku_no'] = \Helper::getDeviceSKUNumber($request->brand_id, $request->model_id, $request->color_id);

        $data = \App\Models\ElsSystemInfoDtailsModel::where('imei_1', $request->imei_1)->first();

        $save = \App\Models\ElsSystemInfoDtailsModel::updateOrCreate([
            'imei_1'   => $request->imei_1,
        ], $dataArr);

        $id = $save->id;
        if (!$data) {
            if (strlen($id) < 6) {
                $length = 6 - strlen($id);
                // $barcode = $id.$this->random_stringss($length);
                $barcode = $this->random_strings(8);
            } else {
                $barcode = $this->random_strings(8);
            }
            // 			\App\Models\ElsSystemInfoDtailsModel::where('id',$id)->update(['barcode'=>$barcode]);
        } else {
            \App\Models\ElsSystemInfoDtailsModel::where('id', $id)->update(['status' => 1]);
        }
        // 		if($request->id){
        // 			$inwd = \App\Models\InwardDate::where('els_system_id',$id)->orderBy('id','DESC')->first();
        // 			\App\Models\InwardDate::where('id',$inwd->id)->update(['received_date'=>$resived_date]);
        //         }else{
        \App\Models\InwardDate::Create([
            'els_system_id'   => $id,
            'received_date'   => $resived_date,
        ]);
        // 		}
        return redirect()->route('els_product_list');
    }


    // 	 public function SaveElsproductajax(Request $request)
    //     {
    // 		if(!$request->id){
    // 			$dataArr['created_by'] = Auth::user()->id;
    // 		}
    // 		if($request->resived_date){
    // 			$resived_date = date('Y-m-d',strtotime($request->resived_date));
    // 		}else{
    // 			$resived_date = date('Y-m-d');
    // 		}
    // 		$dataArr['brand_id'] = $request->brand_id;
    // 		$dataArr['model_id'] = $request->model_id;
    // 		$dataArr['colour_id'] = $request->color_id;
    // 		$dataArr['grn_no'] = $request->grn_no;
    // 		$dataArr['ram'] = $request->ram;
    // 		$dataArr['rom'] = $request->rom;
    // 		$dataArr['grade'] = $request->grade;
    // 		$dataArr['mrp'] = $request->mrp;
    // 		$dataArr['remark'] = $request->remark;
    // 		$dataArr['vendor_id'] = $request->vendor_id;
    // 		$dataArr['quantity'] = 1;
    // 		$dataArr['resived_date'] = $resived_date;
    // 		$dataArr['updated_by'] = Auth::user()->id;
    // 		$dataArr['imei_2'] = $request->imei_2;
    // 		$dataArr['sku_no'] = \Helper::getDeviceSKUNumber($request->brand_id,$request->model_id,$request->color_id);
    // 		$dataArr['imei_1'] = $request->imei_1;
    // 		$time=time();
    // 	    $created_on = date('Y-m-d',strtotime($time));
    // 	    $dataArr['created_at'] = $created_on;

    //         // $data= \App\Models\ElsSystemInfoDtailsModel::where('imei_1',$request->imei_1)->first();

    // //         $save = \App\Models\ElsSystemInfoDtailsModel::updateOrCreate([
    // // 		'imei_1'   =>$request->imei_1,
    // // 		],$dataArr);

    // // 		$id = $save->id;


    //         if($request->id)
    //         {
    //           $elsinfos = DB::select('select * from els_system_info_details where id = :id', ['id' => $request->id]);
    //         //   print_r($elsinfos);die();
    //           $barcode=$elsinfos[0]->barcode;

    //           $dataArr['barcode']=$barcode;
    //         }




    // 		$id = DB::table('els_system_info_details')-> insertGetId($dataArr);

    // 			\App\Models\ElsSystemInfoDtailsModel::where('id',$id)->update(['status'=>1]);

    // 		if($request->id){
    // 			$inwd = \App\Models\InwardDate::where('els_system_id',$id)->orderBy('id','DESC')->first();
    // 			\App\Models\InwardDate::where('id',$inwd->id)->update(['received_date'=>$resived_date]);
    //         }else{
    // 			\App\Models\InwardDate::Create([
    // 				'els_system_id'   =>$id,
    // 				'received_date'   =>$resived_date,
    // 			]);
    // 		}
    //         return redirect()->route('els_product_list');
    //     }

    public function random_strings($length_of_string)
    {

        // String of all alphanumeric character
        $str_result = '0123456789ABCDFGHIJKLMNOPQRSTUVWXYZ';

        // Shufle the $str_result and returns substring
        // of specified length
        return substr(str_shuffle($str_result), 0, $length_of_string);
    }

    public function random_stringss($length_of_string)
    {

        // String of all alphanumeric character
        $str_result = 'ABCDFGHIJKLMNOPQRSTUVWXYZ';

        // Shufle the $str_result and returns substring
        // of specified length
        return substr(str_shuffle($str_result), 0, $length_of_string);
    }

    public function DeleteElsproduct(Request $request)
    {

        $id = $request->id;

        \App\Models\ElsSystemInfoDtailsModel::where('id', $id)->delete();
        $arr = array(
            "code" => 200,
            "message" => "Delete Successfully",
            "data" => ''
        );
        return \Response::json($arr);
    }

    public function allocatedElsProductList(Request $request)
    {
        if (\Helper::checkMenuElegible()) {
            $title = "Allocated Product List";
            return view('els_product/allocated_product_list', compact('title'));
        }
        abort(403, "Don't have permission to access.");
    }

    public function getAllocatedElsProductList(Request $request)
    {
        $data = \App\Models\AssignEngineer::join('els_system_info_details', function ($join) {
            $join->on('els_system_info_details.id', 'els_system_allocated_engineer.els_system_id');
            $join->whereNull('els_system_info_details.deleted_at');
        })->join('brand', function ($join) {
            $join->on('els_system_info_details.brand_id', 'brand.id');
        })->join('model', function ($join) {
            $join->on('els_system_info_details.model_id', 'model.id');
        })->join('colour', function ($join) {
            $join->on('els_system_info_details.colour_id', 'colour.id');
        })->join('users', function ($join) {
            $join->on('els_system_allocated_engineer.engineer_id', 'users.id');
        })->whereNull('els_system_allocated_engineer.deleted_at')->where('els_system_allocated_engineer.status', '1')->orderBy('els_system_allocated_engineer.id', 'DESC');

        $where_like = false;
        $columnsArr = ['els_system_info_details.barcode', 'els_system_info_details.imei_1', 'els_system_info_details.imei_2', 'users.name', 'brand.bname', 'model.mname', 'colour.name'];
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
        $data = $data->selectRaw('els_system_info_details.id,els_system_info_details.barcode,els_system_info_details.imei_1,els_system_info_details.imei_2,users.name,brand.bname,model.mname,colour.name as colour_name')->get();
        return response()->json(['status' => true, 'data' => $data, 'recordsTotal' => $totalRecord, 'recordsFiltered' => $totalRecord, 'code' => 200, 'message' => 'Assign product with engineer listed successfully']);
    }

    public function AllocatedProductPartsForm(Request $request)
    {
        if (\Helper::checkMenuElegible()) {
            $data = [];
            $title = "Request Order List";
            return view('els_product/allocated_engineer_in_product', compact('title'));
        }
        abort(403, "Don't have permission to access.");
    }

    public function editAssignEngineer(Request $request, $id)
    {
        $title = "Request Order List";
        $data = \App\Models\ElsSystemInfoDtailsModel::find($id);
        return view('els_product/edit_allocated_engineer_in_product', compact('title', 'data'));
    }

    public function getProductPartsDetails(Request $request)
    {
        $data = \App\Models\ElsSystemInfoDtailsModel::leftjoin('els_system_allocated_engineer', function ($join) {
            $join->on('els_system_info_details.id', 'els_system_allocated_engineer.els_system_id');
            $join->where('els_system_allocated_engineer.status', '1');
            $join->whereNull('els_system_allocated_engineer.deleted_at');
        })->join('brand', function ($join) {
            $join->on('els_system_info_details.brand_id', 'brand.id');
        })->join('model', function ($join) {
            $join->on('els_system_info_details.model_id', 'model.id');
        })->join('colour', function ($join) {
            $join->on('els_system_info_details.colour_id', 'colour.id');
        })->where(['barcode' => $request->barcode])->select('els_system_info_details.*', 'brand.bname', 'model.mname', 'colour.name as colour_name', 'els_system_allocated_engineer.id as engineer_id')->first();
        if ($data) {
            $parts = \App\Models\OrderRequest::join('els_order_request_parts', function ($join) {
                $join->on('els_order_request.id', 'els_order_request_parts.request_order_id');
                $join->whereNull('els_order_request_parts.deleted_at');
            })->where('els_order_request.els_system_id', $data->id)->select('els_order_request_parts.*')->get();
            $part_list = \App\Models\MasterPartModel::where('status', '1')->whereNull('deleted_at')->pluck('name', 'id');
            $details_html = view('els_product/barcode_product_details', compact('data'))->render();
            $engineer = \App\Models\User::join('role', function ($join) {
                $join->on('users.role', 'role.id');
            })->where('role.role_name', 'engineer')->pluck('users.name', 'users.id');
            $product_parts_html = view('els_product/els_product_parts_list_form', compact('data', 'parts', 'part_list', 'engineer'))->render();
            return response()->json(['status' => true, 'data' => [], 'details_html' => \Helper::compressHtml($details_html), 'product_parts_html' => \Helper::compressHtml($product_parts_html), 'code' => 200, 'message' => 'Order request list']);
        }
    }

    public function saveAssignEngineer(Request $request)
    {
        \App\Models\AssignEngineer::where('els_system_id', $request->els_system_id)->update(['active' => '0']);
        \App\Models\InwardDate::where('els_system_id', $request->els_system_id)->update(['status' => '0']);
        $save = \App\Models\AssignEngineer::updateOrCreate([
            'els_system_id' => $request->els_system_id,
            'engineer_id' => $request->engineer_id,
            'active' => '1',
        ]);
        return redirect()->route('allocated_els_product');
    }

    public function consumedPartsProductList(Request $request)
    {
        if (\Helper::checkMenuElegible()) {
            $data = [];
            // dd($data);
            $title = "Consumed Parts Product List";
            $status = \App\Models\ELSProductStatus::whereNull('deleted_at')->where('status', '1')->pluck('name', 'id');
            $vendor = \App\Models\MasterVendorModel::whereNull('deleted_at')->where('status', '1')->pluck('vname', 'id');
            // dd($status);
            return view('els_product/consumed_parts_product_list', compact('title', 'status', 'vendor'));
        }
        abort(403, "Don't have permission to access.");
    }

    public function updatepin(Request $request, $dataa)
    {
        $update = ElsSystemInfoDtailsModel::find($dataa);
        dd($data);
        // Available alpha caracters
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';

        // generate a pin based on 2 * 7 digits + a random character
        $pin = ((mt_rand(10, 99)) * 9)
            . mt_rand(10, 99)
            . $characters[rand(0, strlen($characters) - 1)];

        // shuffle the result
        $string = str_shuffle($pin);
        // dd($string);
        $update->barcode = $string;
        $update->save();

        return redirect('consumed-parts-product-list');

        // return $dataa;

        // return $data;
        // return "Hello";
        // if (\Helper::checkMenuElegible()) {
        // $data = [];
        // return $data;
        // dd($data);
        // dd($data);
        // $title = "Consumed Parts Product List";
        // $status = \App\Models\ELSProductStatus::whereNull('deleted_at')->where('status', '1')->pluck('name', 'id');
        // $vendor = \App\Models\MasterVendorModel::whereNull('deleted_at')->where('status', '1')->pluck('vname', 'id');
        // // dd($vendor);
        // return view('els_product/consumed_parts_product_list', compact('title', 'status', 'vendor'));
        // return view('els_product/consumed_parts_product_list');
        // }
        // abort(403, "Don't have permission to access.");
    }

    public function getConsumedPartProductList(Request $request)
    {
        $data = \App\Models\ElsSystemInfoDtailsModel::join('els_system_allocated_engineer', function ($join) {
            $join->on('els_system_info_details.id', 'els_system_allocated_engineer.els_system_id');
            $join->where('els_system_allocated_engineer.active', '1');
            $join->whereNull('els_system_allocated_engineer.deleted_at');
        })->join('brand', function ($join) {
            $join->on('els_system_info_details.brand_id', 'brand.id');
        })->join('model', function ($join) {
            $join->on('els_system_info_details.model_id', 'model.id');
        })->join('colour', function ($join) {
            $join->on('els_system_info_details.colour_id', 'colour.id');
        })->join('users', function ($join) {
            $join->on('els_system_allocated_engineer.engineer_id', 'users.id');
        })->leftjoin('els_product_status', function ($join) {
            $join->on('els_product_status.id', 'els_system_allocated_engineer.status');
        })->leftjoin('els_system_allocated_parts_barcode', function ($join) {
            $join->on('els_system_info_details.id', 'els_system_allocated_parts_barcode.els_system_id');
            $join->where('els_system_allocated_parts_barcode.status', '1');
            $join->whereNull('els_system_allocated_parts_barcode.deleted_at');
        })->leftjoin('received_parts_barcode_list', function ($join) {
            $join->on('els_system_allocated_parts_barcode.barcode_id', 'received_parts_barcode_list.id');
            $join->whereNull('received_parts_barcode_list.deleted_at');
        })->whereNull('els_system_info_details.deleted_at');

        $where_like = false;
        $columnsArr = ['els_system_info_details.barcode', 'els_system_info_details.imei_1', 'els_system_info_details.imei_2', 'users.name', 'brand.bname', 'model.mname', 'colour.name'];
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
        $data = $data->groupBy('els_system_info_details.id');
        $data = $data->selectRaw('els_system_info_details.id,els_system_info_details.barcode,els_system_info_details.imei_1,els_system_info_details.imei_2,brand.bname,model.mname,users.name,colour.name as colour_name,count(distinct els_system_allocated_parts_barcode.id) as consumed_part,els_system_allocated_engineer.id as allocated_id,els_system_allocated_engineer.status as status_id,els_product_status.name status,els_system_info_details.remark');
        if ($request->get('length')) {
            $data = $data->skip($request->get('start'))->take($request->get('length'))->get();
        } else {
            $data = $data->get();
        }
        return response()->json(['status' => true, 'data' => $data, 'recordsTotal' => $totalRecord, 'recordsFiltered' => $totalRecord, 'code' => 200, 'message' => 'Part consumed product listed successfully']);
    }

    public function uploadAllocationEnginner(Request $request)
    {
        set_time_limit(0);
        $file = $request->file('file_csv');
        if (in_array(strtoupper($file->getClientOriginalExtension()), ['CSV'])) {
            $fileD = fopen($file, "r");
            $column = fgetcsv($fileD);
            if ($column) {
                foreach ($column as $k => $d) {
                    if ($d == 'UIN') {
                        $uin_key = $k;
                    }
                    if ($d == 'Enginner') {
                        $enginner_key = $k;
                    }
                    if ($d == 'Part Name') {
                        $part_key = $k;
                    }
                    if ($d == 'PIN') {
                        $pin_key = $k;
                    }
                    if ($d == 'Remark') {
                        $remark_key = $k;
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
                    if (isset($rowData[$enginner_key])) {
                        $engineer = \App\Models\User::join('role', function ($join) {
                            $join->on('users.role', 'role.id');
                            $join->where('role.role_name', 'engineer');
                        })->where('users.is_active', '1')->where('users.name', $rowData[$enginner_key])->select('users.*')->first();
                        if ($engineer) {
                            $row['engineer_id'] = $engineer->id;
                        } else {
                            $msg .= 'Engineer,';
                            $error = true;
                        }
                    }

                    if (isset($rowData[$uin_key])) {
                        $els_system = \App\Models\ElsSystemInfoDtailsModel::where('deleted_at')->where('barcode', $rowData[$uin_key])->first();
                        if ($els_system) {
                            $row['els_system_id'] = $els_system->id;
                        } else {
                            $msg .= 'UIN,';
                            $error = true;
                        }
                    }

                    if (($rowData[$part_key])) {
                        $part = \App\Models\MasterPartModel::whereNull('deleted_at')->where('status', '1')->where('name', $rowData[$part_key])->first();
                        if ($part) {
                            $row['part_id'] = $part->id;
                        }
                    }

                    if (($rowData[$pin_key]) && ($rowData[$part_key])) {
                        $pin = \App\Models\ReceivedPurchaseOrderPartsBarcode::join('received_purchase_order_parts_list', function ($join) {
                            $join->on('received_parts_barcode_list.received_part_id', 'received_purchase_order_parts_list.id');
                        })->where('received_parts_barcode_list.deleted_at')->where('received_parts_barcode_list.iqc_status', '1')->where('received_parts_barcode_list.status', '2')->where('received_parts_barcode_list.barcode', $rowData[$pin_key])->where('received_purchase_order_parts_list.part_id', $row['part_id'])->select('received_parts_barcode_list.id', 'received_purchase_order_parts_list.brand_id', 'received_purchase_order_parts_list.model_id', 'received_purchase_order_parts_list.part_id', 'received_purchase_order_parts_list.colour_id')->first();
                        if ($pin) {
                            $row['brand_id'] = $pin->brand_id;
                            $row['model_id'] = $pin->model_id;
                            $row['colour_id'] = $pin->colour_id;
                            $row['barcode_id'] = $pin->id;
                        } else {
                            $msg .= 'PIN,';
                            $error = true;
                        }
                    }

                    if (($rowData[$remark_key])) {
                        $row['remark'] = $rowData[$remark_key];
                    }

                    $dataArr[] = $row;
                    if ($error) $massage .= $msg . ' Not Found. Please Correct The Details.';
                    $i++;
                }
            }

            if ($error) {
                return response()->json(['status' => false, 'data' => [], 'code' => 401, 'message' => $massage]);
            }
            if ($dataArr) {
                foreach ($dataArr as $val) {
                    $d = (object) $val;
                    $day = \App\Models\AssignEngineer::updateOrCreate([
                        'els_system_id' => $d->els_system_id,
                        'engineer_id' => $d->engineer_id,
                    ], [
                        'active' => '1',
                    ]);
                    if (isset($d->barcode_id)) {
                        $product_type_id = 3;
                        $spare_part_price_id = 0;
                        $spare_part_price = \App\Models\SparePartPriceList::join('spare_part_list', function ($join) {
                            $join->on('spare_part_price_list.series_id', 'spare_part_list.id');
                        })->where('spare_part_list.brand_id', $d->brand_id)->where('spare_part_list.model_id', $d->model_id)->where('spare_part_list.type_id', $product_type_id)->where('spare_part_list.part_id', $d->part_id)->where('spare_part_price_list.colour_id', $d->colour_id)->select('spare_part_price_list.id')->first();
                        if ($spare_part_price) {
                            $spare_part_price_id = $spare_part_price->id;
                        }

                        $order_request = \App\Models\OrderRequest::updateOrCreate(['els_system_id' => $d->els_system_id]);

                        \App\Models\OrderRequestPart::updateOrCreate(['part_id' => $d->part_id], [
                            'request_order_id' => $order_request->id,
                            'brand_id' => $d->brand_id,
                            'model_id' => $d->model_id,
                            'part_type_id' => $product_type_id,
                            'colour_id' => $d->colour_id,
                            'quantity'    => 1,
                            'spare_part_price_id' => $spare_part_price_id,
                            'status' => '0',
                        ]);

                        \App\Models\AllocatedBarcode::updateOrCreate([
                            'els_system_id' => $d->els_system_id,
                            'barcode_id' => $d->barcode_id,
                        ], [
                            'remark' => $d->remark,
                        ]);
                        \App\Models\ReceivedPurchaseOrderPartsBarcode::where('id', $d->barcode_id)->update(['status' => '3']);
                        \App\Models\InwardDate::where('els_system_id', $d->els_system_id)->update(['status' => '0']);
                    }
                }
            }
            // return response()->json(['status'=>true, 'data' => [], 'code'=>200, 'message'=> 'Product successfully imported.']);
        }
        // return response()->json(['status'=>false, 'data' => [], 'code'=>401, 'message'=> 'Please choose a correct formate.']);
        return redirect()->back();
    }

    public function allocateProductPart(Request $request)
    {
        $title = "Allocated Product Part";
        return view('els_product/allocated_product_part', compact('title'));
    }

    public function getELSProductPartsDetails(Request $request)
    {
        $data = \App\Models\ElsSystemInfoDtailsModel::join('brand', function ($join) {
            $join->on('els_system_info_details.brand_id', 'brand.id');
        })->join('model', function ($join) {
            $join->on('els_system_info_details.model_id', 'model.id');
        })->join('colour', function ($join) {
            $join->on('els_system_info_details.colour_id', 'colour.id');
        })->leftjoin('els_system_allocated_engineer', function ($join) {
            $join->on('els_system_info_details.id', 'els_system_allocated_engineer.els_system_id');
            $join->where('els_system_allocated_engineer.active', '1');
            $join->whereNull('els_system_allocated_engineer.deleted_at');
        })->where(['barcode' => $request->barcode])->select('els_system_info_details.*', 'brand.bname', 'model.mname', 'colour.name as colour_name', 'els_system_allocated_engineer.engineer_id')->first();

        if ($data) {
            $parts = \App\Models\OrderRequest::join('els_order_request_parts', function ($join) {
                $join->on('els_order_request.id', 'els_order_request_parts.request_order_id');
                $join->whereNull('els_order_request_parts.deleted_at');
            })->leftjoin('received_purchase_order_parts_list', function ($join) {
                $join->on('els_order_request_parts.brand_id', 'received_purchase_order_parts_list.brand_id');
                $join->on('els_order_request_parts.model_id', 'received_purchase_order_parts_list.model_id');
                $join->on('els_order_request_parts.part_type_id', 'received_purchase_order_parts_list.product_type_id');
                $join->on('els_order_request_parts.part_id', 'received_purchase_order_parts_list.part_id');
                $join->whereNull('received_purchase_order_parts_list.deleted_at');
            })->leftjoin('received_parts_barcode_list', function ($join) {
                $join->on('received_purchase_order_parts_list.id', 'received_parts_barcode_list.received_part_id');
            })->join('spare_part_price_list', 'received_purchase_order_parts_list.sku_id', '=', 'spare_part_price_list.id')->where('els_order_request.els_system_id', $data->id)
                ->groupBy('els_order_request_parts.id')->select('els_order_request_parts.*', 'els_order_request.els_system_id', 'spare_part_price_list.sku_no as sku_no')->get();

            $part_list = \App\Models\MasterPartModel::where('status', '1')->whereNull('deleted_at')->pluck('name', 'id');
            $details_html = view('els_product/barcode_product_details', compact('data'))->render();
            $engineer = \App\Models\User::join('role', function ($join) {
                $join->on('users.role', 'role.id');
            })->where('role.role_name', 'engineer')->pluck('users.name', 'users.id');
            // dd($data);
            $product_parts_html = view('els_product/els_product_parts_form', compact('data', 'parts', 'part_list', 'engineer'))->render();
            // dd($product_parts_html->part_list);
            return response()->json(['status' => true, 'data' => [], 'details_html' => \Helper::compressHtml($details_html), 'product_parts_html' => \Helper::compressHtml($product_parts_html), 'code' => 200, 'message' => 'Order request list']);
        }
        return response()->json(['status' => false, 'data' => [], 'code' => 401, 'message' => 'No Data Found.']);
    }

    public function checkBarcodeDetails(Request $request)
    {
        $data = \App\Models\ReceivedPurchaseOrderPartsBarcode::join('received_purchase_order_parts_list', function ($join) {
            $join->on('received_parts_barcode_list.received_part_id', 'received_purchase_order_parts_list.id');
            $join->whereNull('received_purchase_order_parts_list.deleted_at');
        })
            // 		->where('received_purchase_order_parts_list.model_id',$request->model_id)
            // 		->where('received_purchase_order_parts_list.part_id',$request->part_id)
            ->where('received_parts_barcode_list.barcode', $request->barcode)->where('received_parts_barcode_list.iqc_status_one', '1')->where('received_parts_barcode_list.status', '2')->count();

        // dd($data);
        if ($data) {
            return response()->json(['status' => true, 'data' => [], 'code' => 200, 'message' => 'Barcode Exist']);
        }
        return response()->json(['status' => false, 'data' => [], 'code' => 401, 'message' => 'Barcode is not valid.']);
    }
    /////////////////start of
    public function allocateProductPartPerBarcode(Request $request)
    {
        $product_type_id = 3;
        $order_request = \App\Models\OrderRequest::whereNull('deleted_at')->where('els_order_request.els_system_id', $request->els_system_id)
            ->orderBy('id', 'DESC')->first();

        if (is_null($order_request)) {
            $order_request = \App\Models\OrderRequest::updateOrCreate(['els_system_id' => $request->els_system_id]);
        }
        if ($order_request && $request->part_id) {
            foreach ($request->part_id as $key => $part_id) {

                $brand_id = \App\Models\MasterModel::whereNull('deleted_at')->whereIn('id', $request->model_id)->first()->brand_id;

                $request_parts = \App\Models\OrderRequest::join('els_order_request_parts', function ($join) {
                    $join->on('els_order_request.id', 'els_order_request_parts.request_order_id');
                })->where('els_order_request.els_system_id', $request->els_system_id)
                    ->whereIn('els_order_request_parts.model_id', $request->model_id)
                    ->where('els_order_request_parts.part_id', $part_id)
                    ->where('els_order_request_parts.id', $request->id[$key])
                    ->where('els_order_request_parts.colour_id', $request->part_color[$key])->count();
                if (!$request_parts) {
                    $spare_part_price_id = 0;
                    $spare_part_price = \App\Models\SparePartPriceList::join('spare_part_list', function ($join) {
                        $join->on('spare_part_price_list.series_id', 'spare_part_list.id');
                    })->where('spare_part_list.brand_id', $brand_id)->where('spare_part_list.model_id', $request->model_id)->where('spare_part_list.type_id', $product_type_id)->where('spare_part_list.part_id', $part_id)->where('spare_part_price_list.colour_id', $request['part_color'][$key])->select('spare_part_price_list.id')->first();
                    if ($spare_part_price) {
                        $spare_part_price_id = $spare_part_price->id;
                    }
                    $time = time();
                    if (@$request['extra'][$key] == 1) {
                        $extra = 1;
                    }
                    $sve = \App\Models\OrderRequestPart::updateOrCreate(['id' => null], [
                        'request_order_id' => $order_request->id,
                        'brand_id' => $brand_id,
                        'model_id' => $request['model_id'][$key],
                        'part_type_id' => $product_type_id,
                        'part_id' => $part_id,
                        'colour_id' => $request['part_color'][$key],
                        'quantity' => 1,
                        'spare_part_price_id' => $spare_part_price_id,
                        'status' => '0',
                        'time' => $time,
                        'extra' => $extra,
                        'barcodes' => $request['part_barcode'][$key],
                    ]);
                    if ($extra == 1) {
                        \App\Models\OrderRequestPart::where('id', $sve->id)->update(['extra' => $extra]);
                    }
                } else {

                    $id = $request['id'][$key];
                    if (isset($request['part_barcode_new'][$id])) {
                        $fetchDefaultRecord = \App\Models\OrderRequestPart::where('id', $id)->first();
                        $old_pin = $request['part_barcode_old'][$id] ? $request['part_barcode_old'][$id] : "";
                        if ($fetchDefaultRecord) {
                            $data = [
                                'request_order_id' => $fetchDefaultRecord->request_order_id,
                                'brand_id' => $fetchDefaultRecord->brand_id,
                                'model_id' => $fetchDefaultRecord->model_id,
                                'part_type_id' => $fetchDefaultRecord->part_type_id,
                                'part_id' => $fetchDefaultRecord->part_id,
                                'colour_id' => $fetchDefaultRecord->colour_id,
                                'quantity'    => 1,
                                'spare_part_price_id' => $fetchDefaultRecord->spare_part_price_id,
                                'status' => "Diagnose Pending",
                                'time' => $fetchDefaultRecord->time,
                                'extra' => $fetchDefaultRecord->extra,
                                'new_pin' => '1',
                                'barcodes' => $old_pin,
                                'old_pin' => $request['part_barcode_new'][$id],
                                "updated_at" => now()
                            ];
                            \App\Models\OrderRequestPart::where("id", $id)->update($data);
                            $data = [
                                'purchase_order_id' => $fetchDefaultRecord->request_order_id,
                                'brand_id' => $fetchDefaultRecord->brand_id,
                                'model_id' => $fetchDefaultRecord->model_id,
                                'product_type_id' => 3,
                                'part_id' => $fetchDefaultRecord->part_id,
                                'sku_id' => $fetchDefaultRecord->spare_part_price_id,
                                'colour_id' => $fetchDefaultRecord->colour_id,
                                'price' => 0,
                                'gst' => 0,
                                'quantity' => 1,
                                'status' => 1,
                                'created_by' => Auth::user()->id,
                                "created_at" => now(),
                            ];

                            $purchase_id = DB::table('received_purchase_order_parts_list')->insertGetId($data);

                            if (isset($request['part_barcode_old'][$id]) && $request['part_barcode_old'][$id]) {
                                $old_received_parts_barcode_list = DB::table('received_parts_barcode_list')->where('barcode', $request['part_barcode_old'][$id])->first();
                                if ($old_received_parts_barcode_list && $old_received_parts_barcode_list->received_part_id) {
                                    $old_purchase_id = $old_received_parts_barcode_list->received_part_id;
                                } else {
                                    $old_purchase_id = $purchase_id;
                                }
                            } else {
                                $old_purchase_id = $purchase_id;
                            }
                            $received_parts_barcode_list_new_record = array([
                                'received_part_id' => $old_purchase_id,
                                'price' => 0,
                                'iqc_status' => 2,
                                'barcode' => $request['part_barcode_new'][$id],
                                'status' => 3,
                                'tester_id' => 0,
                                'received_date' => today(),
                                'dispatch_date' =>  NULL,
                                'remark' => NULL,
                                'iqc_status_one' => 2,
                                'vendor_name' => 0,
                                'uploaded_by' => Auth::user()->id,
                                "created_at" => now(),
                                'old_status' => 1,
                            ]);
                            DB::table('received_parts_barcode_list')->insert($received_parts_barcode_list_new_record);
                        }
                    }
                }
            }
        } else {

            if ($request->part_id) {
                $order_request = \App\Models\OrderRequest::updateOrCreate(['els_system_id' => $request->els_system_id]);

                foreach ($request->part_id as $key => $part_id) {

                    if (@$request['extra'][$key] == 1) {
                        $extra = 1;
                    }

                    $brand_id = \App\Models\MasterModel::whereNull('deleted_at')->whereIn('id', $request->model_id)
                        ->first()->brand_id;
                    $spare_part_price_id = 0;
                    $spare_part_price = \App\Models\SparePartPriceList::join('spare_part_list', function ($join) {
                        $join->on('spare_part_price_list.series_id', 'spare_part_list.id');
                    })->where('spare_part_list.brand_id', $brand_id)->where(
                        'spare_part_list.model_id',
                        $request->model_id
                    )->where('spare_part_list.type_id', $product_type_id)->where('spare_part_list.part_id', $part_id)->where('spare_part_price_list.colour_id', $request['part_color'][$key])->select('spare_part_price_list.id')->first();
                    if ($spare_part_price) {
                        $spare_part_price_id = $spare_part_price->id;
                    }

                    if (@$request['extra'][$key] == 1) {
                        $extra = 1;
                    }
                    // 		echo $request['extra'][$key];echo "ty";
                    $time = time();
                    $save = \App\Models\OrderRequestPart::updateOrCreate(['id' => null], [
                        'request_order_id' => $order_request->id,
                        'brand_id' => $brand_id,
                        'model_id' => $request['model_id'][$key],
                        'part_type_id' => $product_type_id,
                        'part_id' => $part_id,
                        'colour_id' => $request['part_color'][$key],
                        'quantity'    => 1,
                        'spare_part_price_id' => $spare_part_price_id,
                        'status' => '0',
                        'time' => $time,
                        'extra' => $extra,
                        'barcodes' => $request['part_barcode'][$key],

                    ]);

                    if ($extra == 1) {
                        \App\Models\OrderRequestPart::where('id', $save->id)->update(['extra' => $extra]);
                    }
                    DB::table('els_order_request_parts')->where('id', $request['id'][$key])->update(array('barcodes' => $request['part_barcode'][$key]));
                }
            }
        }

        if ($request->part_barcode) {
            $barcodeArr = array_filter($request->part_barcode);
            $res = \App\Models\AllocatedBarcode::join('received_parts_barcode_list', function ($join) {
                $join->on('els_system_allocated_parts_barcode.barcode_id', 'received_parts_barcode_list.id');
                $join->whereNull('received_parts_barcode_list.deleted_at');
            })->whereNull('els_system_allocated_parts_barcode.deleted_at')
                ->where('els_system_allocated_parts_barcode.els_system_id', $request->els_system_id)
                ->whereNotIn('received_parts_barcode_list.barcode', $barcodeArr)
                ->select('els_system_allocated_parts_barcode.id as allocated_id', 'received_parts_barcode_list.id as barcode_id')
                ->get()->toArray();
            if (!empty($res)) {
                $barcode_id = array_column($res, 'barcode_id');
                $allocated_id = array_column($res, 'allocated_id');
                \App\Models\AllocatedBarcode::whereIn('id', $allocated_id)->delete();

                $mie = \App\Models\ElsSystemInfoDtailsModel::where('id', $request->els_system_id)->first();
                $returned = 'Returned parts ' . $mie->barcode;
                \App\Models\ReceivedPurchaseOrderPartsBarcode::whereIn('id', $barcode_id)
                    ->update(['status' => '2', 'remark' => $returned]);
            }
            if ($barcodeArr) {
                foreach ($barcodeArr as $key => $barcode) {
                    if ($barcode) {
                        $data = \App\Models\ReceivedPurchaseOrderPartsBarcode::where('barcode', $barcode)
                            ->where('iqc_status_one', '1')->first();
                        if ($data) {
                            $data->status = '3';
                            $data->save();
                            $save = \App\Models\AllocatedBarcode::updateOrCreate([
                                'els_system_id' => $request->els_system_id,
                                'barcode_id' => $data->id,
                            ]);
                        }
                    }
                }
            }
        }
        $inward = \App\Models\InwardDate::where('els_system_id', $request->els_system_id)->where('status', '1')->count();
        \App\Models\AssignEngineer::where('els_system_id', $request->els_system_id)->update(['active' => '0']);
        if ($inward) {
            \App\Models\AssignEngineer::Create([
                'els_system_id' => $request->els_system_id,
                'engineer_id' => $request->engineer_id,
                'active' => '1',
            ]);
            \App\Models\InwardDate::where('els_system_id', $request->els_system_id)->update(['status' => '0']);
        } else {
            \App\Models\AssignEngineer::updateOrCreate(
                [
                    'els_system_id' => $request->els_system_id,
                    'engineer_id' => $request->engineer_id
                ],
                ['active' => '1']
            );
        }

        return redirect()->route('consumed-parts-product-list');
    }

    public function errors(Request $request)
    {
        $old_record = DB::table('received_parts_barcode_list')->where('barcode', $request->id)->first();


        if ($old_record != null) {

            if ($old_record->status == 2 && $old_record->iqc_status == 1) {
                $string = $request->id . " You can use this pin.";
                return response()->json(['message' => $string]);
            } else {
                $string = $request->id . " PIN is in used or it's status is not pass.";
                return response()->json(['message' => $string]);
            }
        } else {
            $string = $request->id . " Does not find this pin in system.";
            return response()->json(['message' => $string]);
        }
    }
    /////end of


    public function addMoreParts(Request $request)
    {
        // dd($request->all());
        $data = \App\Models\ElsSystemInfoDtailsModel::where('barcode', $request->barcode)->first();
        if ($data) {
            $part_list = \App\Models\MasterPartModel::join('spare_part_list', function ($join) {
                $join->on('part.id', 'spare_part_list.part_id');
                $join->whereNull('spare_part_list.deleted_at');
            })->where('spare_part_list.model_id', $data->model_id)->where('part.status', '1')->whereNull('part.deleted_at')->pluck('part.name', 'part.id');
            $key = $request->id;
            $html = view('els_product/add_more_parts', compact('data', 'part_list', 'key'))->render();
            return response()->json(['status' => true, 'data' => [], 'html' => \Helper::compressHtml($html), 'code' => 200, 'message' => 'Add more parts']);
        }
    }

    public function renewPart(Request $request)
    {
        $data = \App\Models\ElsSystemInfoDtailsModel::where('barcode', $request->barcode)->first();
        if ($data) {
            $part_list = \App\Models\MasterPartModel::join('spare_part_list', function ($join) {
                $join->on('part.id', 'spare_part_list.part_id');
                $join->whereNull('spare_part_list.deleted_at');
            })->where('spare_part_list.model_id', $data->model_id)->where('part.status', '1')->whereNull('part.deleted_at')->pluck('part.name', 'part.id');
            $key = $request->id;
            $partName = $request->part_name;
            $partColor = $request->part_color;
            $barCode = $request->barcode;
            $html = view('els_product/add_more_parts', compact('data', 'part_list', 'key'))->render();
            return response()->json(['status' => true, 'data' => [], 'html' => \Helper::compressHtml($html), 'code' => 200, 'message' => 'Add more parts']);
        }
    }


    public function getPartColours(Request $request)
    {
        $data = \App\Models\SparePartPriceList::join('spare_part_list', function ($join) {
            $join->on('spare_part_list.id', 'spare_part_price_list.series_id');
            $join->whereNull('spare_part_list.deleted_at');
        })->join('colour', function ($join) {
            $join->on('colour.id', 'spare_part_price_list.colour_id');
            $join->whereNull('colour.deleted_at');
        })->where('spare_part_list.model_id', $request->model_id)->where('spare_part_list.part_id', $request->part_id)->where('colour.status', '1')->select('colour.name', 'colour.id')->groupBy('colour.id')->get();
        return response()->json(['status' => true, 'data' => $data, 'code' => 200, 'message' => 'colour listed successfully']);
    }

    public function saveProductStatus(Request $request)
    {
        $els_system = \App\Models\AssignEngineer::find($request->id);
        if ($els_system) {
            $els_system_id = $els_system->els_system_id;
            $s = \App\Models\ELSProductStatus::find($request->status);
            $data = \App\Models\ElsSystemInfoDtailsModel::where('id', $els_system_id)->update(['status' => $s->sub_status_id]);

            $data = \App\Models\AssignEngineer::join('els_system_allocated_parts_barcode', function ($join) {
                $join->on('els_system_allocated_engineer.els_system_id', 'els_system_allocated_parts_barcode.els_system_id');
                $join->whereNull('els_system_allocated_parts_barcode.deleted_at');
            })->where('els_system_allocated_engineer.id', $request->id)->select('els_system_allocated_parts_barcode.els_system_id', 'els_system_allocated_parts_barcode.barcode_id')->get()->toArray();
            if ($data) {
                $id = array_unique(array_column($data, 'barcode_id'));
                $status = '0';
                if ($s->sub_status_id != 1) {
                    $stockUpdate = \App\Models\ReceivedPurchaseOrderPartsBarcode::whereIn('id', $id)->update(['status' => $status]);
                }
            }
            $save = \App\Models\AssignEngineer::updateOrCreate([
                'id' => $request->id,
            ], [
                'status' => $request->status,
            ]);
            if ($s->sub_status_id != 1) {
                $save = \App\Models\ELSProductStatusLog::updateOrCreate([
                    'els_system_allocation_id' => $els_system->id,
                    'vendor_id' => $request->vendor_id,
                    'els_system_id' => $els_system_id,
                    'status' => $s->sub_status_id,
                ]);
            }
        }
        return response()->json(['status' => true, 'data' => [], 'code' => 200, 'message' => 'Status update successfully']);
    }

    public function removePartBarcode(Request $request)
    {
        if ($request->request_part_id) {
            \App\Models\OrderRequestPart::where('id', $request->request_part_id)->delete();
        }
        if ($request->barcode) {
            $data = \App\Models\ReceivedPurchaseOrderPartsBarcode::where('barcode', $request->barcode)->first();
            if ($data) {
                \App\Models\AllocatedBarcode::where('barcode_id', $data->id)->delete();
                \App\Models\ReceivedPurchaseOrderPartsBarcode::where('id', $data->id)->update(['status' => '2', 'remark' => 'Returned parts']);
            }
        }
        return response()->json(['status' => true, 'data' => [], 'code' => 200, 'message' => 'Barcode remove successfully']);
    }

    public function videoUploadProductList(Request $request)
    {
        if (\Helper::checkMenuElegible()) {
            $data = [];
            $title = "Consumed Parts Product List";
            return view('els_product/video_upload_product_list', compact('title'));
        }
        abort(403, "Don't have permission to access.");
    }

    public function getVideoUploadProductList(Request $request)
    {
        $data = \App\Models\ElsSystemInfoDtailsModel::join('els_system_allocated_engineer', function ($join) {
            $join->on('els_system_info_details.id', 'els_system_allocated_engineer.els_system_id');

            $join->whereNull('els_system_allocated_engineer.deleted_at');
        })->join('els_product_status', function ($join) {
            $join->on('els_product_status.id', 'els_system_allocated_engineer.status');
        })->join('brand', function ($join) {
            $join->on('els_system_info_details.brand_id', 'brand.id');
        })->join('model', function ($join) {
            $join->on('els_system_info_details.model_id', 'model.id');
        })->join('colour', function ($join) {
            $join->on('els_system_info_details.colour_id', 'colour.id');
        })->join('users', function ($join) {
            $join->on('els_system_allocated_engineer.engineer_id', 'users.id');
        })->leftjoin('els_system_packaging_video', function ($join) {
            $join->on('els_system_info_details.id', 'els_system_packaging_video.els_system_id');
            $join->where('els_system_packaging_video.status', '1');
            $join->whereNull('els_system_packaging_video.deleted_at');
        })->whereNull('els_system_info_details.deleted_at')->groupBy('els_system_info_details.id')->orderBy('els_system_allocated_engineer.id', 'DESC');

        $where_like = false;
        $columnsArr = ['els_system_info_details.barcode', 'els_system_info_details.imei_1', 'els_system_info_details.imei_2', 'users.name', 'brand.bname', 'model.mname', 'colour.name'];
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
        $data = $data->selectRaw('els_system_info_details.id,els_system_info_details.barcode,els_system_info_details.imei_1,els_system_info_details.imei_2,brand.bname,model.mname,users.name,colour.name as colour_name,els_system_packaging_video.id as video_id,els_product_status.name as status')->get();
        return response()->json(['status' => true, 'data' => $data, 'recordsTotal' => $totalRecord, 'recordsFiltered' => $totalRecord, 'code' => 200, 'message' => 'Part consumed product listed successfully']);
    }

    public function addMoreVideo(Request $request)
    {
        $html = view('els_product/add_more_video')->render();
        return response()->json(['status' => true, 'data' => [], 'html' => \Helper::compressHtml($html), 'code' => 200, 'message' => 'Add more video']);
    }

    // 	public function uploadProductVideo(Request $request){

    // 		$files = $request->file('video_file');
    // 		if($files){
    // 			$path = 'uploads/videos/'.rand();
    // 			foreach($files as $file){
    // 				if(in_array(strtoupper($file->getClientOriginalExtension()),['WEBM','MPG','MP2','MPEG','MPE','MPV','OGG','MP4','M4P','M4V','AVI','WMV','MOV','QT','FLV','SWF'])){
    // 					$filename = $file->getClientOriginalName();
    // 					$full_path = $path.'/'.$filename;
    // 					$data= \App\Models\ElsSystemPackagingVideo::Create(
    // 						[
    // 							'els_system_id'=>$request->get('els_system_id'),
    // 							'user_id'=>Auth()->user()->id,
    // 							'video_path'=>$full_path,
    // 						]
    // 					);
    // 					$file->move($path,$filename);
    // 				}
    // 			}

    // 		}
    // 		return redirect()->back();
    // 	}

    public function uploadProductVideo(Request $request)
    {

        $files = $request->file('video_file');
        for ($i = 0; $i < count($files); $i++) {

            $imageName = time() . '.' . $files[$i]->extension();

            if (in_array(strtoupper($files[$i]->getClientOriginalExtension()), ['WEBM', 'MPG', 'MP2', 'MPEG', 'MPE', 'MPV', 'OGG', 'MP4', 'M4P', 'M4V', 'AVI', 'WMV', 'MOV', 'QT', 'FLV', 'SWF'])) {
                $path = Storage::disk('s3')->put('images', $files[$i]);
                echo   $path = Storage::disk('s3')->url($path);
                echo $imageName;

                $data = \App\Models\ElsSystemPackagingVideo::Create(
                    [
                        'els_system_id' => $request->get('els_system_id'),
                        'user_id' => Auth()->user()->id,
                        'video_path' => $path,
                    ]
                );
            }
        }
        return redirect()->back();
    }

    public function downloadVideo(Request $request)
    {
        $data = \App\Models\ElsSystemPackagingVideo::whereNull('deleted_at')->where('status', '1')->where('els_system_id', $request->get('els_system_id'))->get();
        if (!$data->isEmpty()) {
            $zip = new ZipArchive();
            $tempFileUri = tempnam(sys_get_temp_dir(), "FOO");
            if ($zip->open($tempFileUri, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) === TRUE) {
                // Add File in ZipArchive
                foreach ($data as $file) {

                    if ($file->id < 8443) {

                        if (!$zip->addFile(base_path($file->video_path), basename($file->video_path))) {
                            echo 'Could not add file to ZIP: ';
                        }
                    } else {


                        header('Content-Type: application/octet-stream');
                        header("Content-Transfer-Encoding: Binary");
                        header("Content-disposition: attachment; filename=\"" . basename($file->video_path) . "\"");
                        ob_clean();
                        flush();
                        readfile($file->video_path);
                    }
                }
                // Close ZipArchive
                if ($file->id < 8443) {
                    $zip->close();
                    header('Content-disposition: attachment; filename=files.zip');
                    header('Content-type: application/zip');
                    readfile($tempFileUri);
                }
            } else {
                echo 'Could not open ZIP file.';
            }
        }
        return redirect()->back();
    }

    public function uploadProductVideoList(Request $request)
    {
        $data = \App\Models\ElsSystemPackagingVideo::whereNull('deleted_at')->where('status', '1')->where('els_system_id', $request->get('id'))->get();
        $html = view('els_product/upload_video_list', compact('data'))->render();
        return response()->json(['status' => true, 'data' => [], 'html' => \Helper::compressHtml($html), 'code' => 200, 'message' => 'Uploaded Video List']);
    }

    public function deleteUploadProductVideo(Request $request)
    {
        \App\Models\ElsSystemPackagingVideo::where('id', $request->get('id'))->delete();
        return response()->json(['status' => true, 'data' => [], 'code' => 200, 'message' => 'Video successfully deleted.']);
    }

    public function setDateRangeFilter(Request $request)
    {
        session()->put('start_date', $request->start_date);
        session()->put('end_date', $request->end_date);
        return true;
    }

    public function enginnerStatisticsReport(Request $request)
    {
        if (\Helper::checkMenuElegible()) {
            $title = "Consumed Parts Product List";
            $data = \App\Models\User::join('role', function ($join) {
                $join->on('role.id', 'users.role');
                $join->whereNull('role.deleted_at');
            })->leftjoin('els_system_allocated_engineer', function ($join) {
                $join->on('els_system_allocated_engineer.engineer_id', 'users.id');
                $join->whereNull('els_system_allocated_engineer.deleted_at');
            })->where('role.role_name', 'engineer')->groupBy('users.id')->orderBy('users.name', 'ASC')->select('users.*')->get();

            $status = \App\Models\ELSProductStatus::whereNull('deleted_at')->where('status', '1')->get();
            return view('els_product/engineer_statistics_report', compact('title', 'status', 'data'));
        }
        abort(403, "Don't have permission to access.");
    }

    public function downloadWorkReport(Request $request)
    {
        header("Content-Type: application/xls");
        header("Content-Disposition: attachment; filename=EngineerStatisticsReport.xls");
        header("Pragma: no-cache");
        header("Expires: 0");
        $status = \App\Models\ELSProductStatus::whereNull('deleted_at')->where('status', '1')->get();

        $columnHeader = "Sr NO" . "\t";
        $columnHeader .= "Engneer Name" . "\t";
        foreach ($status as $s) {
            $columnHeader .= "Total " . $s->name . "\t";
        }
        $columnHeader .= "Total issued" . "\t";
        $columnHeader .= "Target" . "\t";
        $columnHeader .= "Variance" . "\t";


        $data = \App\Models\User::join('role', function ($join) {
            $join->on('role.id', 'users.role');
            $join->whereNull('role.deleted_at');
        })->leftjoin('els_system_allocated_engineer', function ($join) {
            $join->on('els_system_allocated_engineer.engineer_id', 'users.id');
            $join->whereNull('els_system_allocated_engineer.deleted_at');
        })->where('role.role_name', 'engineer')->groupBy('users.id')->orderBy('users.name', 'ASC')->select('users.*')->get();

        if (session()->get('start_date') && session()->get('end_date')) {
            $start_date = session()->get('start_date');
            $end_date = date('Y-m-d', strtotime('+1 day', strtotime(session()->get('end_date'))));
        } else {
            $start_date = date('Y-m-d', strtotime('-29 day'));
            $end_date = date('Y-m-d', strtotime('+1 day'));
        }
        $date1 = date_create($end_date);
        $date2 = date_create($start_date);
        $diff = date_diff($date1, $date2);
        $days = $diff->days;

        $i = 1;
        $setData = '';
        if (!$data->isEmpty()) {
            foreach ($data as $value) {
                $total = $variance = 0;
                $rowData = '';
                $rowData .= '"' . $i . '"' . "\t";
                $rowData .= '"' . $value->name . '"' . "\t";
                foreach ($status as $v) {
                    $count = \Helper::getAssignedSystemCount($value->id, $v->id);
                    $rowData .= '"' . $count . '"' . "\t";
                    if (!in_array($v->id, [1])) {
                        $total += $count;
                    }
                    $target = $days * $value->target;
                    $variance = $total - $target;
                }

                $rowData .= '"' . $total . '"' . "\t";
                $rowData .= '"' . $target . '"' . "\t";
                $rowData .= '"' . $variance . '"' . "\t";
                $setData .= trim($rowData) . "\n";
                $i++;
            }
        }
        echo ucwords($columnHeader) . "\n" . $setData . "\n";
    }

    public function getEngineerWorkReportList(Request $request)
    {
        $data = \App\Models\User::join('role', function ($join) {
            $join->on('role.id', 'users.role');
            $join->whereNull('role.deleted_at');
        })->leftjoin('els_system_allocated_engineer', function ($join) {
            $join->on('els_system_allocated_engineer.engineer_id', 'users.id');
            $join->whereNull('els_system_allocated_engineer.deleted_at');
        })->leftjoin('els_system_allocated_engineer as a', function ($join) {
            $join->on('a.engineer_id', 'users.id');
            $join->where('a.status', '0');
            $join->whereNull('a.deleted_at');
        })->leftjoin('els_system_allocated_engineer as b', function ($join) {
            $join->on('b.engineer_id', 'users.id');
            $join->where('b.status', '1');
            $join->whereNull('b.deleted_at');
        })->leftjoin('els_system_allocated_engineer as c', function ($join) {
            $join->on('c.engineer_id', 'users.id');
            $join->where('c.status', '2');
            $join->whereNull('c.deleted_at');
        })->leftjoin('els_system_allocated_engineer as d', function ($join) {
            $join->on('d.engineer_id', 'users.id');
            $join->where('d.status', '3');
            $join->whereNull('d.deleted_at');
        })->leftjoin('els_system_allocated_engineer as e', function ($join) {
            $join->on('e.engineer_id', 'users.id');
            $join->where('e.status', '4');
            $join->whereNull('e.deleted_at');
        })->leftjoin('els_system_allocated_engineer as f', function ($join) {
            $join->on('f.engineer_id', 'users.id');
            $join->where('f.status', '5');
            $join->whereNull('f.deleted_at');
        })->where('role.role_name', 'engineer')->groupBy('users.id')->orderBy('users.name', 'ASC');

        $where_like = false;
        $columnsArr = ['users.name'];
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
        $data = $data->selectRaw('users.id,users.name,count(distinct a.els_system_id) as repair,count(distinct b.els_system_id) as l3,count(distinct c.els_system_id) as l4,count(distinct d.els_system_id) as fqc,count(distinct e.els_system_id) as fqc_fails,count(distinct f.els_system_id) as shrink_pack,count(distinct els_system_allocated_engineer.els_system_id) as total_system')->get();
        return response()->json(['status' => true, 'data' => $data, 'recordsTotal' => $totalRecord, 'recordsFiltered' => $totalRecord, 'code' => 200, 'message' => 'Engneer work report listed successfully']);
    }

    //''//

    ///////

    public function setDateRangeFilters(Request $request)
    {
        session()->put('start_date', $request->start_date);
        session()->put('end_date', $request->end_date);
        return true;
    }

    public function sparepart_aging(Request $request)
    {
        if (\Helper::checkMenuElegible()) {
            $title = "Prodcution Static Report List";
            $data = \App\Models\ElsSystemInfoDtailsModel::join('els_system_allocated_engineer', function ($join) {
                $join->on('els_system_info_details.id', 'els_system_allocated_engineer.els_system_id');

                $join->whereNull('els_system_allocated_engineer.deleted_at');
            })->join('els_product_status', function ($join) {
                $join->on('els_product_status.id', 'els_system_info_details.status');
            })->join('brand', function ($join) {
                $join->on('els_system_info_details.brand_id', 'brand.id');
            })->join('ram', function ($join) {
                $join->on('els_system_info_details.ram', 'ram.id');
            })->join('rom', function ($join) {
                $join->on('els_system_info_details.rom', 'rom.id');
            })->join('els_system_status_log', function ($join) {
                $join->on('els_system_status_log.els_system_id', 'els_system_info_details.id');
            })->join('model', function ($join) {
                $join->on('els_system_info_details.model_id', 'model.id');
            })->join('colour', function ($join) {
                $join->on('els_system_info_details.colour_id', 'colour.id');
            })->join('users', function ($join) {
                $join->on('els_system_status_log.els_system_allocation_id', 'users.id');
            })->leftjoin('els_system_packaging_video', function ($join) {
                $join->on('els_system_info_details.id', 'els_system_packaging_video.els_system_id');
                $join->where('els_system_packaging_video.status', '1');
                $join->whereNull('els_system_packaging_video.deleted_at');
            })->whereNull('els_system_info_details.deleted_at')->groupBy('els_system_info_details.id')->where('els_system_status_log.status', '22')->orderBy('els_system_status_log.id', 'DESC');

            // 		if(session()->get('start_date') && session()->get('end_date')){
            // 										$start_date = session()->get('start_date');
            // 										$end_date = date('Y-m-d', strtotime('+1 day', strtotime(session()->get('end_date'))));
            // 									}else{
            // 										$start_date = date('Y-m-d', strtotime('-29 day'));
            // 										$end_date = date('Y-m-d', strtotime('+1 day'));
            // 									}
            // $data = $data->whereBetween('els_system_status_log.created_at', [$start_date, $end_date]);

            $where_like = false;
            $columnsArr = ['els_system_info_details.id,els_system_info_details.barcode,ram.name as ram,rom.name as rom,brand.bname,model.mname,users.name,colour.name as colour_name,els_system_packaging_video.id as video_id,els_product_status.name as status,els_system_status_log.created_at'];
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

            $totalRecord = $data->count();
            if ($request->get('length')) {
                $data = $data->skip($request->get('start'))->take($request->get('length'));
            }
            $data = $data->selectRaw('els_system_info_details.id,els_system_info_details.created_at,els_system_info_details.barcode,ram.name as ram,rom.name as rom,brand.bname,model.mname,users.name,colour.name as colour_name,els_system_packaging_video.id as video_id,els_product_status.name as status,els_system_status_log.created_at as fqcdate,els_system_status_log.els_system_id')->get();

            $status = \App\Models\ELSProductStatus::whereNull('deleted_at')->where('status', '1')->get();
            return view('els_product/sparepart_aging', compact('title', 'status', 'data'));
        }
        abort(403, "Don't have permission to access.");
    }

    public function downloadsparepart_aging(Request $request)
    {
        header("Content-Type: application/xls");
        header("Content-Disposition: attachment; filename=SpareAgeStatisticsReport.xls");
        header("Pragma: no-cache");
        header("Expires: 0");
        $status = \App\Models\ELSProductStatus::whereNull('deleted_at')->where('status', '1')->get();

        $columnHeader = "Sr NO" . "\t";
        $columnHeader .= "Inward Date" . "\t";
        $columnHeader .= "Brand" . "\t";
        $columnHeader .= "Model" . "\t";
        $columnHeader .= "Colour" . "\t";
        $columnHeader .= "SKU No" . "\t";
        $columnHeader .= "Parts Name" . "\t";
        $columnHeader .= "PIN" . "\t";
        $columnHeader .= "IQC Engg. Name" . "\t";
        $columnHeader .= "IQC Pass/Failed" . "\t";
        $columnHeader .= "Status" . "\t";
        $columnHeader .= "Received Date" . "\t";
        $columnHeader .= "After IQC Pass/Failed" . "\t";
        $columnHeader .= "Aging (Days)" . "\t";







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


        // 		if(session()->get('start_date') && session()->get('end_date')){
        // 										$start_date = session()->get('start_date');
        // 										$end_date = date('Y-m-d', strtotime('+1 day', strtotime(session()->get('end_date'))));
        // 									}else{
        // 										$start_date = date('Y-m-d', strtotime('-29 day'));
        // 										$end_date = date('Y-m-d', strtotime('+1 day'));
        // 									}
        // $data = $data->whereBetween('received_parts_barcode_list.created_at', [$start_date, $end_date]);


        $totalRecord = $data->count();
        if ($request->get('length')) {
            $data = $data->skip($request->get('start'))->take($request->get('length'));
        }
        $data = $data->selectRaw('received_parts_barcode_list.id,CONCAT("PO-", received_purchase_order_parts_list.purchase_order_id) as po_no,part.name as part_name,received_parts_barcode_list.barcode,received_parts_barcode_list.iqc_status,(CASE WHEN received_parts_barcode_list.status="1" THEN "Assigned" WHEN received_parts_barcode_list.status="2" THEN "Not In Used" WHEN received_parts_barcode_list.status="3" THEN "Allocated"  WHEN received_parts_barcode_list.status="4" THEN "Dispatched" ELSE "Consumed" END) as current_status,received_parts_barcode_list.status,users.name as engineer_name,received_parts_barcode_list.price,received_parts_barcode_list.remark,DATE_FORMAT(received_parts_barcode_list.received_date,"%d/%m/%Y") as received_date,spare_part_price_list.sku_no,colour.name as colour_name,brand.bname,model.mname,received_parts_barcode_list.iqc_status_one,TIMESTAMPDIFF(DAY, received_parts_barcode_list.created_at, now()) AS age,DATE_FORMAT(received_parts_barcode_list.created_at, "%d-%m-%Y") as formatted_dob')->get();





        $i = 1;
        $setData = '';
        if (!$data->isEmpty()) {
            foreach ($data as $value) {


                if ($value->iqc_status == 1) {
                    $result = 'Pass';
                } else {
                    $result = 'Failed';
                }

                if ($value->iqc_status_one == 1) {
                    $resultt = 'Pass';
                } else {
                    $resultt = 'Failed';
                }


                $total = $variance = 0;
                $rowData = '';
                $rowData .= '"' . $i . '"' . "\t";
                $rowData .= '"' . $value->formatted_dob . '"' . "\t";
                $rowData .= '"' . $value->bname . '"' . "\t";
                $rowData .= '"' . $value->mname . '"' . "\t";
                $rowData .= '"' . $value->colour_name . '"' . "\t";
                $rowData .= '"' . $value->sku_no . '"' . "\t";
                $rowData .= '"' . $value->part_name . '"' . "\t";
                $rowData .= '"' . $value->barcode . '"' . "\t";
                $rowData .= '"' . $value->engineer_name . '"' . "\t";
                $rowData .= '"' . $result . '"' . "\t";
                $rowData .= '"' . $value->current_status . '"' . "\t";
                $rowData .= '"' . $value->received_date . '"' . "\t";
                $rowData .= '"' . $resultt . '"' . "\t";
                $rowData .= '"' . $value->age . '"' . "\t";


                $setData .= trim($rowData) . "\n";
                $i++;
            }
        }
        echo ucwords($columnHeader) . "\n" . $setData . "\n";
    }



    public function downloaddevice_aging(Request $request)
    {
        header("Content-Type: application/xls");
        header("Content-Disposition: attachment; filename=DeviceAgeStatisticsReport.xls");
        header("Pragma: no-cache");
        header("Expires: 0");
        $status = \App\Models\ELSProductStatus::whereNull('deleted_at')->where('status', '1')->get();

        $columnHeader = "Sr NO" . "\t";
        $columnHeader .= "Date" . "\t";
        $columnHeader .= "ELS Brand" . "\t";
        $columnHeader .= "ELS Model" . "\t";
        $columnHeader .= "Colour" . "\t";
        $columnHeader .= "UIN" . "\t";
        $columnHeader .= "RAM" . "\t";
        $columnHeader .= "ROM" . "\t";
        $columnHeader .= "Sub Status" . "\t";
        $columnHeader .= "Status" . "\t";
        $columnHeader .= "Aging (Days)" . "\t";


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
            ->where('els_system_info_details.stock_in', 1)
            ->where('els_system_info_details.is_active', 1)
            ->groupBy('inward_date.els_system_id')
            ->orderBy('els_system_info_details.resived_date', 'DESC');




        $where_like = false;
        $columnsArr = ['els_system_info_details.resived_date', 'els_system_info_details.grn_no', 'els_system_info_details.barcode', 'els_system_info_details.imei_1', 'els_system_info_details.imei_2', 'els_system_info_details.remark', 'ram.name', 'rom.name', 'grade.name', 'vendor.vname', 'brand.bname', 'model.mname', 'colour.name', 'out.vname', 'els_product_status.name', 'els_product_sub_status.name'];
        // 			foreach($columnsArr as $columns){
        // 				if($request->get('search')['value']){
        // 					if($where_like){
        // 						$where_like.= ' OR '.$columns.' like "%'.$request->get('search')['value'].'%"';
        // 					}else{
        // 						$where_like.= $columns.' like "%'.$request->get('search')['value'].'%"';
        // 					}
        // 				}
        // 			}
        if ($where_like) {
            $data = $data->whereRaw('(' . $where_like . ')');
        }


        // 			if(session()->get('start_date') && session()->get('end_date')){
        // 										$start_date = session()->get('start_date');
        // 										$end_date = date('Y-m-d', strtotime('+1 day', strtotime(session()->get('end_date'))));
        // 									}else{
        // 										$start_date = date('Y-m-d', strtotime('-29 day'));
        // 										$end_date = date('Y-m-d', strtotime('+1 day'));
        // 									}
        // 				$data = $data->whereBetween('els_system_info_details.created_at', [$start_date, $end_date]);


        $totalRecord = count($data->get());
        if ($request->get('length')) {
            $data = $data->skip($request->get('start'))->take($request->get('length'));
        }

        $data = $data->selectRaw('els_system_info_details.id,DATE_FORMAT(els_system_info_details.resived_date,"%d/%m/%Y") as resived_date,els_system_info_details.grn_no,ram.name as ram,rom.name as rom,grade.name as grade,els_system_info_details.remark,els_system_info_details.barcode,els_system_info_details.mrp,els_system_info_details.imei_1,els_system_info_details.imei_2,brand.bname,model.mname,vendor.vname,colour.name as colour_name,els_product_sub_status.name as status,els_product_status.name as sub_status,out.vname as out_vendor,round((els_system_info_details.mrp +  IF((select round(SUM(received_purchase_order_parts_list.price),2) from els_system_allocated_parts_barcode join received_parts_barcode_list on received_parts_barcode_list.id=els_system_allocated_parts_barcode.barcode_id join received_purchase_order_parts_list on received_purchase_order_parts_list.id=received_parts_barcode_list.received_part_id where els_system_id = els_system_info_details.id), (select round(SUM(received_purchase_order_parts_list.price),2) from els_system_allocated_parts_barcode join received_parts_barcode_list on received_parts_barcode_list.id=els_system_allocated_parts_barcode.barcode_id join received_purchase_order_parts_list on received_purchase_order_parts_list.id=received_parts_barcode_list.received_part_id where els_system_id = els_system_info_details.id), 0) + IF((select sum(amount) from els_system_extra_expence where els_system_id = `els_system_info_details`.`id`), (select sum(amount) from els_system_extra_expence where els_system_id = `els_system_info_details`.`id`), 0)),2) as new_price,TIMESTAMPDIFF(DAY, els_system_info_details.resived_date, now()) AS age,DATE_FORMAT(els_system_info_details.created_at, "%d-%m-%Y") as formatted_dob')->get();



        $i = 1;
        $setData = '';
        if (!$data->isEmpty()) {
            foreach ($data as $value) {


                $total = $variance = 0;
                $rowData = '';
                $rowData .= '"' . $i . '"' . "\t";
                $rowData .= '"' . $value->resived_date . '"' . "\t";
                $rowData .= '"' . $value->bname . '"' . "\t";
                $rowData .= '"' . $value->mname . '"' . "\t";
                $rowData .= '"' . $value->colour_name . '"' . "\t";
                $rowData .= '"' . $value->barcode . '"' . "\t";
                $rowData .= '"' . $value->ram . '"' . "\t";
                $rowData .= '"' . $value->rom . '"' . "\t";
                $rowData .= '"' . $value->sub_status . '"' . "\t";
                $rowData .= '"' . $value->status . '"' . "\t";
                $rowData .= '"' . $value->age . '"' . "\t";
                $setData .= trim($rowData) . "\n";
                $i++;
            }
        }
        echo ucwords($columnHeader) . "\n" . $setData . "\n";
    }



    public function downloadfresh_faulty(Request $request)
    {
        header("Content-Type: application/xls");
        header("Content-Disposition: attachment; filename=FreshfaultyStatisticsReport.xls");
        header("Pragma: no-cache");
        header("Expires: 0");
        $status = \App\Models\ELSProductStatus::whereNull('deleted_at')->where('status', '1')->get();

        $columnHeader =  "Sr NO" . "\t";
        $columnHeader .= "PO No" . "\t";
        $columnHeader .= "PO Date" . "\t";
        $columnHeader .= "Brand" . "\t";
        $columnHeader .= "Model" . "\t";
        $columnHeader .= "Colour" . "\t";
        $columnHeader .= "SKU No" . "\t";
        $columnHeader .= "Parts Name" . "\t";
        $columnHeader .= "PIN" . "\t";
        $columnHeader .= "IQC Engineer Name" . "\t";
        $columnHeader .= "IQC Pass/Failed" . "\t";
        $columnHeader .= "Status" . "\t";
        $columnHeader .= "Allocated Date" . "\t";
        $columnHeader .= "After IQC Pass/Failed" . "\t";
        $columnHeader .= "Remark" . "\t";
        $columnHeader .= "Repaired Engg." . "\t";



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




        $i = 1;
        $setData = '';
        if (!$data->isEmpty()) {
            foreach ($data as $value) {



                $usersddfio = DB::table('els_system_status_log')
                    ->where('els_system_id', $value->els_system_id)
                    ->where('status', 22)
                    ->count();

                if ($usersddfio > 0) {


                    $usersddfiof = DB::table('els_system_status_log')
                        ->where('els_system_id', $value->els_system_id)
                        ->where('status', 22)
                        ->first();


                    $created = $usersddfiof->created_at;
                    $userss = $usersddfiof->els_system_allocation_id;



                    $usersuu = DB::table('users')
                        ->where('id', @$userss)
                        ->first();
                    if (!empty($usersuu)) {
                        $uname = $usersuu->name;
                    } else {
                        $uname = '';
                    }
                } else {
                    $created = '';
                    $uname = '';
                }





                if ($value->iqc_status == 1) {
                    $result = 'Pass';
                } else {
                    $result = 'Failed';
                }

                if ($value->iqc_status_one == 1) {
                    $resultt = 'Pass';
                } else {
                    $resultt = 'Failed';
                }


                $total = $variance = 0;
                $rowData = '';
                $rowData .= '"' . $i . '"' . "\t";
                $rowData .= '"' . $value->po_no . '"' . "\t";
                $rowData .= '"' . $value->formatted_dobp . '"' . "\t";
                $rowData .= '"' . $value->bname . '"' . "\t";
                $rowData .= '"' . $value->mname . '"' . "\t";
                $rowData .= '"' . $value->colour_name . '"' . "\t";
                $rowData .= '"' . $value->sku_no . '"' . "\t";
                $rowData .= '"' . $value->part_name . '"' . "\t";
                $rowData .= '"' . $value->barcode . '"' . "\t";
                $rowData .= '"' . $value->engineer_name . '"' . "\t";
                $rowData .= '"' . $result . '"' . "\t";
                $rowData .= '"' . $value->current_status . '"' . "\t";
                $rowData .= '"' . $value->formatted_dobu . '"' . "\t";
                $rowData .= '"' . $resultt . '"' . "\t";
                $rowData .= '"' . $value->remark . '"' . "\t";
                $rowData .= '"' . $uname . '"' . "\t";


                $setData .= trim($rowData) . "\n";
                $i++;
            }
        }
        echo ucwords($columnHeader) . "\n" . $setData . "\n";
    }




    public function downloadpo_aging(Request $request)
    {
        header("Content-Type: application/xls");
        header("Content-Disposition: attachment; filename=PO-WISEIQCReport.xls");
        header("Pragma: no-cache");
        header("Expires: 0");
        $status = \App\Models\ELSProductStatus::whereNull('deleted_at')->where('status', '1')->get();

        $columnHeader = "Sr NO" . "\t";
        $columnHeader .= "PO No" . "\t";
        $columnHeader .= "PO Date" . "\t";
        $columnHeader .= "Brand" . "\t";
        $columnHeader .= "Parts Name" . "\t";
        $columnHeader .= "Model" . "\t";
        $columnHeader .= "Colour" . "\t";
        $columnHeader .= "SKU No" . "\t";
        $columnHeader .= "PIN" . "\t";
        $columnHeader .= "Unit Price" . "\t";
        $columnHeader .= "GST Amount" . "\t";
        $columnHeader .= "Total Amount" . "\t";
        $columnHeader .= "IQC Engineer Name" . "\t";
        $columnHeader .= "IQC Pass/Failed" . "\t";
        $columnHeader .= "Status" . "\t";
        $columnHeader .= "Received Date" . "\t";
        $columnHeader .= "After IQC Pass/Failed" . "\t";
        $columnHeader .= "Uploaded Date" . "\t";
        $columnHeader .= "Vendor" . "\t";



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
        $data = $data->whereBetween('received_parts_barcode_list.created_at', [$start_date, $end_date]);


        $totalRecord = $data->count();
        if ($request->get('length')) {
            $data = $data->skip($request->get('start'))->take($request->get('length'));
        }
        $data = $data->selectRaw('received_parts_barcode_list.id,CONCAT("PO-", received_purchase_order_parts_list.purchase_order_id) as po_no,part.name as part_name,received_parts_barcode_list.barcode,received_parts_barcode_list.iqc_status,(CASE WHEN received_parts_barcode_list.status="1" THEN "Assigned" WHEN received_parts_barcode_list.status="2" THEN "Not In Used" WHEN received_parts_barcode_list.status="3" THEN "Allocated"  WHEN received_parts_barcode_list.status="4" THEN "Dispatched" ELSE "Consumed" END) as current_status,received_parts_barcode_list.status,users.name as engineer_name,received_parts_barcode_list.price as price,received_parts_barcode_list.remark,DATE_FORMAT(received_parts_barcode_list.received_date,"%d/%m/%Y") as received_date,spare_part_price_list.sku_no,colour.name as colour_name,brand.bname,model.mname,received_parts_barcode_list.iqc_status_one,TIMESTAMPDIFF(DAY, received_parts_barcode_list.created_at, now()) AS age,DATE_FORMAT(received_parts_barcode_list.created_at, "%d-%m-%Y") as formatted_dob,received_purchase_order_parts_list.price as prices,DATE_FORMAT(purchase_order_list.created_at, "%d-%m-%Y") as formatted_dobp,vendor.vname,received_parts_barcode_list.price - received_purchase_order_parts_list.price as price_amount')->get();




        $i = 1;
        $setData = '';
        if (!$data->isEmpty()) {
            foreach ($data as $value) {


                if ($value->iqc_status == 1) {
                    $result = 'Pass';
                } else {
                    $result = 'Failed';
                }

                if ($value->iqc_status_one == 1) {
                    $resultt = 'Pass';
                } else {
                    $resultt = 'Failed';
                }


                $total = $variance = 0;
                $rowData = '';
                $rowData .= '"' . $i . '"' . "\t";
                $rowData .= '"' . $value->po_no . '"' . "\t";
                $rowData .= '"' . $value->formatted_dobp . '"' . "\t";
                $rowData .= '"' . $value->bname . '"' . "\t";
                $rowData .= '"' . $value->part_name . '"' . "\t";
                $rowData .= '"' . $value->mname . '"' . "\t";
                $rowData .= '"' . $value->colour_name . '"' . "\t";
                $rowData .= '"' . $value->sku_no . '"' . "\t";

                $rowData .= '"' . $value->barcode . '"' . "\t";
                $rowData .= '"' . $value->prices . '"' . "\t";
                $rowData .= '"' . $value->price_amount . '"' . "\t";
                $rowData .= '"' . $value->price . '"' . "\t";
                $rowData .= '"' . $value->engineer_name . '"' . "\t";
                $rowData .= '"' . $result . '"' . "\t";
                $rowData .= '"' . $value->current_status . '"' . "\t";
                $rowData .= '"' . $value->received_date . '"' . "\t";
                $rowData .= '"' . $resultt . '"' . "\t";
                $rowData .= '"' . $value->formatted_dob . '"' . "\t";
                $rowData .= '"' . $value->vname . '"' . "\t";

                $setData .= trim($rowData) . "\n";
                $i++;
            }
        }
        echo ucwords($columnHeader) . "\n" . $setData . "\n";
    }



    public function downloadextra_part(Request $request)
    {
        header("Content-Type: application/xls");
        header("Content-Disposition: attachment; filename=ExtraPartReport.xls");
        header("Pragma: no-cache");
        header("Expires: 0");
        $status = \App\Models\ELSProductStatus::whereNull('deleted_at')->where('status', '1')->get();

        $columnHeader = "Sr NO" . "\t";
        $columnHeader .= "Allocated Date" . "\t";
        $columnHeader .= "UIN" . "\t";
        $columnHeader .= "Brand" . "\t";
        $columnHeader .= "Model" . "\t";
        $columnHeader .= "Colour" . "\t";
        $columnHeader .= "RAM" . "\t";
        $columnHeader .= "ROM" . "\t";
        $columnHeader .= "Repaired Engg" . "\t";
        $columnHeader .= "Repaired Date" . "\t";
        $columnHeader .= "Parts Name" . "\t";
        $columnHeader .= "RO" . "\t";
        $columnHeader .= "PIN" . "\t";
        $columnHeader .= "Dignose Engg Name" . "\t";





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


        $i = 1;
        $setData = '';
        if (!$data->isEmpty()) {
            foreach ($data as $value) {

                @$usersddfio = DB::table('els_system_status_log')
                    ->where('els_system_id', $value->els_system_id)
                    ->where('status', 22)
                    ->count();

                if ($usersddfio > 0) {

                    $date = date('Y-m-d h:i:s', strtotime($value->formatted_dob));
                    @$usersddfiof = DB::table('els_system_status_log')
                        ->where('els_system_id', $value->els_system_id)
                        ->where('created_at', '>=', $date)
                        ->where('status', 22)
                        ->first();


                    @$created = $usersddfiof->created_at;
                    @$userss = $usersddfiof->els_system_allocation_id;



                    @$usersuu = DB::table('users')
                        ->where('id', @$userss)
                        ->first();
                    if (!empty($usersuu)) {
                        @$uname = $usersuu->name;
                    } else {
                        $uname = '';
                    }
                } else {
                    $created = '';
                    $uname = '';
                }








                $usersddfiod = DB::table('els_system_status_log')
                    ->where('els_system_id', $value->els_system_id)
                    ->where('status', 19)
                    ->count();

                if ($usersddfiod > 0) {


                    $usersddfiofd = DB::table('els_system_status_log')
                        ->where('els_system_id', $value->els_system_id)
                        ->where('status', 19)
                        ->first();



                    $userssd = $usersddfiofd->els_system_allocation_id;



                    $usersuud = DB::table('users')
                        ->where('id', @$userssd)
                        ->first();
                    if (!empty($usersuud)) {
                        $unamed = $usersuud->name;
                    } else {
                        $unamed = '';
                    }
                } else {

                    $unamed = '';
                }




                $total = $variance = 0;
                $rowData = '';
                $rowData .= '"' . $i . '"' . "\t";
                $rowData .= '"' . $value->formatted_dob . '"' . "\t";
                $rowData .= '"' . $value->barcode . '"' . "\t";
                $rowData .= '"' . $value->bname . '"' . "\t";
                $rowData .= '"' . $value->mname . '"' . "\t";
                $rowData .= '"' . $value->colour_name . '"' . "\t";
                $rowData .= '"' . $value->ram . '"' . "\t";
                $rowData .= '"' . $value->rom . '"' . "\t";
                $rowData .= '"' . $created . '"' . "\t";
                $rowData .= '"' . $uname . '"' . "\t";
                $rowData .= '"' . $value->part_name . '"' . "\t";
                $rowData .= '"' . $value->request_order_id . '"' . "\t";
                $rowData .= '"' . $value->barcodes . '"' . "\t";
                $rowData .= '"' . $unamed . '"' . "\t";

                $setData .= trim($rowData) . "\n";
                $i++;
            }
        }
        echo ucwords($columnHeader) . "\n" . $setData . "\n";
    }









    public function getsparepart_aging(Request $request)
    {
        $data = \App\Models\User::join('role', function ($join) {
            $join->on('role.id', 'users.role');
            $join->whereNull('role.deleted_at');
        })->leftjoin('els_system_allocated_engineer', function ($join) {
            $join->on('els_system_allocated_engineer.engineer_id', 'users.id');
            $join->whereNull('els_system_allocated_engineer.deleted_at');
        })->leftjoin('els_system_allocated_engineer as a', function ($join) {
            $join->on('a.engineer_id', 'users.id');
            $join->where('a.status', '0');
            $join->whereNull('a.deleted_at');
        })->leftjoin('els_system_allocated_engineer as b', function ($join) {
            $join->on('b.engineer_id', 'users.id');
            $join->where('b.status', '1');
            $join->whereNull('b.deleted_at');
        })->leftjoin('els_system_allocated_engineer as c', function ($join) {
            $join->on('c.engineer_id', 'users.id');
            $join->where('c.status', '2');
            $join->whereNull('c.deleted_at');
        })->leftjoin('els_system_allocated_engineer as d', function ($join) {
            $join->on('d.engineer_id', 'users.id');
            $join->where('d.status', '3');
            $join->whereNull('d.deleted_at');
        })->leftjoin('els_system_allocated_engineer as e', function ($join) {
            $join->on('e.engineer_id', 'users.id');
            $join->where('e.status', '4');
            $join->whereNull('e.deleted_at');
        })->leftjoin('els_system_allocated_engineer as f', function ($join) {
            $join->on('f.engineer_id', 'users.id');
            $join->where('f.status', '5');
            $join->whereNull('f.deleted_at');
        })->where('role.role_name', 'engineer')->groupBy('users.id')->orderBy('users.name', 'ASC');

        $where_like = false;
        $columnsArr = ['users.name'];
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
        $data = $data->selectRaw('users.id,users.name,count(distinct a.els_system_id) as repair,count(distinct b.els_system_id) as l3,count(distinct c.els_system_id) as l4,count(distinct d.els_system_id) as fqc,count(distinct e.els_system_id) as fqc_fails,count(distinct f.els_system_id) as shrink_pack,count(distinct els_system_allocated_engineer.els_system_id) as total_system')->get();
        return response()->json(['status' => true, 'data' => $data, 'recordsTotal' => $totalRecord, 'recordsFiltered' => $totalRecord, 'code' => 200, 'message' => 'Engneer work report listed successfully']);
    }




    public function setDateRangeFilterp(Request $request)
    {
        session()->put('start_date', $request->start_date);
        session()->put('end_date', $request->end_date);
        return true;
    }

    public function setDateRangeFilterr(Request $request)
    {
        // dd('helo');
        session()->put('start_date', $request->start_date);
        session()->put('end_date', $request->end_date);
        return true;
    }

    public function setDateRangeFilterd(Request $request)
    {
        session()->put('start_date', $request->start_date);
        session()->put('end_date', $request->end_date);
        return true;
    }
    public function setDateRangeFilterpp(Request $request)
    {
        session()->put('start_date', $request->start_date);
        session()->put('end_date', $request->end_date);
        return true;
    }

    public function setDateRangeFilterppe(Request $request)
    {
        session()->put('start_date', $request->start_date);
        session()->put('end_date', $request->end_date);
        return true;
    }



    public function productionStatisticsReport(Request $request)
    {
        if (\Helper::checkMenuElegible()) {
            $title = "Prodcution Static Report List";
            $data = DB::table('els_system_allocated_engineer')->join('els_system_info_details', function ($join) {
                $join->on('els_system_info_details.id', 'els_system_allocated_engineer.els_system_id');
            })->join('els_product_status', function ($join) {
                $join->on('els_product_status.id', 'els_system_info_details.status');
            })->join('brand', function ($join) {
                $join->on('els_system_info_details.brand_id', 'brand.id');
            })->leftjoin('ram', function ($join) {
                $join->on('els_system_info_details.ram', 'ram.id');
            })->leftjoin('rom', function ($join) {
                $join->on('els_system_info_details.rom', 'rom.id');
            })->join('model', function ($join) {
                $join->on('els_system_info_details.model_id', 'model.id');
            })->join('colour', function ($join) {
                $join->on('els_system_info_details.colour_id', 'colour.id');
            })->join('users', function ($join) {
                $join->on('els_system_allocated_engineer.engineer_id', 'users.id');
            })->where('els_system_allocated_engineer.status', 22)->orderBy('els_system_allocated_engineer.id', 'DESC');

            if (session()->get('start_date') && session()->get('end_date')) {
                $start_date = session()->get('start_date');
                $end_date = date('Y-m-d', strtotime('+1 day', strtotime(session()->get('end_date'))));
            } else {
                $start_date = date('Y-m-d', strtotime('-29 day'));
                $end_date = date('Y-m-d', strtotime('+1 day'));
            }
            $data = $data->whereBetween('els_system_allocated_engineer.created_at', [$start_date, $end_date]);

            $where_like = false;
            $columnsArr = ['els_system_info_details.id,els_system_info_details.barcode,ram.name as ram,rom.name as rom,brand.bname,model.mname,users.name,colour.name as colour_name,els_product_status.name as status,els_system_allocated_engineer.created_at,els_system_allocated_engineer.remark'];
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

            $totalRecord = $data->count();
            if ($request->get('length')) {
                $data = $data->skip($request->get('start'))->take($request->get('length'));
            }
            $data = $data->selectRaw('els_system_info_details.barcode,ram.name as ram,rom.name as rom,brand.bname,model.mname,users.name,colour.name as colour_name,els_product_status.name as status,els_system_allocated_engineer.created_at as fqcdate,els_system_allocated_engineer.els_system_id,els_system_allocated_engineer.remark,els_system_allocated_engineer.id as id')->get();

            $status = \App\Models\ELSProductStatus::whereNull('deleted_at')->where('status', '1')->get();
            return view('els_product/production_statistics_report', compact('title', 'status', 'data'));
        }
        abort(403, "Don't have permission to access.");
    }

    public function downloadproductionReport(Request $request)
    {
        header("Content-Type: application/xls");
        header("Content-Disposition: attachment; filename=ProductionStatisticsReport.xls");
        header("Pragma: no-cache");
        header("Expires: 0");
        $status = \App\Models\ELSProductStatus::whereNull('deleted_at')->where('status', '1')->get();

        $columnHeader = "Sr NO" . "\t";
        $columnHeader .= "Repaired Date" . "\t";
        $columnHeader .= "UIN" . "\t";
        $columnHeader .= "Brand" . "\t";
        $columnHeader .= "Model" . "\t";
        $columnHeader .= "Color" . "\t";
        $columnHeader .= "RAM" . "\t";
        $columnHeader .= "ROM" . "\t";
        $columnHeader .= "Repaired Engg. Name" . "\t";
        $columnHeader .= "FQC Status" . "\t";
        $columnHeader .= "FQC Date" . "\t";
        $columnHeader .= "Remark" . "\t";

        $data = DB::table('els_system_allocated_engineer')->join('els_system_info_details', function ($join) {
            $join->on('els_system_info_details.id', 'els_system_allocated_engineer.els_system_id');
        })->join('els_product_status', function ($join) {
            $join->on('els_product_status.id', 'els_system_info_details.status');
        })->join('brand', function ($join) {
            $join->on('els_system_info_details.brand_id', 'brand.id');
        })->leftjoin('ram', function ($join) {
            $join->on('els_system_info_details.ram', 'ram.id');
        })->leftjoin('rom', function ($join) {
            $join->on('els_system_info_details.rom', 'rom.id');
        })->join('model', function ($join) {
            $join->on('els_system_info_details.model_id', 'model.id');
        })->join('colour', function ($join) {
            $join->on('els_system_info_details.colour_id', 'colour.id');
        })->join('users', function ($join) {
            $join->on('els_system_allocated_engineer.engineer_id', 'users.id');
        })->where('els_system_allocated_engineer.status', 22)->orderBy('els_system_allocated_engineer.id', 'DESC');

        if (session()->get('start_date') && session()->get('end_date')) {
            $start_date = session()->get('start_date');
            $end_date = date('Y-m-d', strtotime('+1 day', strtotime(session()->get('end_date'))));
        } else {
            $start_date = date('Y-m-d', strtotime('-29 day'));
            $end_date = date('Y-m-d', strtotime('+1 day'));
        }
        $data = $data->whereBetween('els_system_allocated_engineer.created_at', [$start_date, $end_date]);

        $data = $data->selectRaw('els_system_info_details.barcode,ram.name as ram,rom.name as rom,brand.bname,model.mname,users.name,colour.name as colour_name,els_product_status.name as status,els_system_allocated_engineer.created_at as fqcdate,els_system_allocated_engineer.els_system_id,els_system_allocated_engineer.remark,els_system_allocated_engineer.id as id')->get();

        $i = 1;
        $setData = '';
        if (!$data->isEmpty()) {
            foreach ($data as $value) {
                $usersddfio = DB::table('els_system_allocated_engineer')
                    ->where('els_system_id', $value->els_system_id)
                    ->where('status', 33)
                    ->count();

                if ($usersddfio > 0) {


                    $usersddfiof = DB::table('els_system_allocated_engineer')
                        ->where('els_system_id', $value->els_system_id)
                        ->where('status', 33)
                        ->first();

                    $fqcdate = $usersddfiof->created_at;


                    $usersddfi = DB::table('els_system_allocated_engineer')
                        ->where('els_system_id', $value->els_system_id)
                        ->where('id', '>=', $value->id)
                        ->where('status', 30)
                        ->count();

                    $usersddfii = DB::table('els_system_allocated_engineer')
                        ->where('els_system_id', $value->els_system_id)
                        ->where('id', '>=', $value->id)
                        ->where('status', 20)
                        ->count();



                    if ($usersddfii > 0) {


                        $usersddfioFQC = DB::table('els_system_allocated_engineer')
                            ->where('els_system_id', $value->els_system_id)
                            ->where('status', 33)
                            ->where('id', '>=', $value->id)
                            ->first();
                        @$fqcdate = $usersddfioFQC->created_at;

                        if (@$usersddfioFQC->id) {
                            $fqc = 'Failed';
                        } else {
                            $fqc = 'Under Observed';
                        }

                        $usersddfivf = DB::table('els_system_allocated_engineer')
                            ->where('els_system_id', $value->els_system_id)
                            ->where('id', '>=', $value->id)
                            ->where('status', 20)
                            ->first();

                        @$remarks = @$usersddfivf->remark;
                        @$created_att = $usersddfivf->created_att;
                    } else  if ($usersddfi > 0) {

                        $usersddfivp = DB::table('els_system_allocated_engineer')
                            ->where('els_system_id', $value->els_system_id)
                            ->where('status', 30)
                            ->where('id', '>=', $value->id)
                            ->first();


                        $usersddfioFQC = DB::table('els_system_allocated_engineer')
                            ->where('els_system_id', $value->els_system_id)
                            ->where('status', 33)
                            ->where('id', '>=', $value->id)
                            ->first();

                        @$fqcdate = $usersddfioFQC->created_at;
                        @$remarks = @$usersddfivp->remark;
                        @$created_att = $usersddfivp->created_att;

                        if (@$usersddfioFQC->id) {
                            $fqc = 'Pass';
                        } else {
                            $fqc = 'Under Observed';
                        }
                    } else {
                        $fqcdate = '';
                        $fqc = 'Under Observe';
                        $remarks = '';
                    }
                } else {
                    $fqcdate = '';
                    $fqc = 'Under Observe SR';
                    $fqcdate = '';
                    $remarks = '';
                }



                $usersddfivfc = DB::table('els_system_allocated_engineer')
                    ->where('els_system_id', $value->els_system_id)
                    ->where('status', '22')
                    ->where('id', '>=', $value->id)
                    ->orderBy('id', 'DESC')
                    ->first();
                $usersddfivff = DB::table('els_system_allocated_engineer')
                    ->where('els_system_id', $value->els_system_id)
                    ->where('status', '22')
                    ->where('id', '>=', $value->id)
                    ->orderBy('id', 'DESC')
                    ->count();



                if ($usersddfivff > 0) {
                    $usersddfivffcc = DB::table('users')
                        ->where('id', $usersddfivfc->engineer_id)
                        ->orderBy('id', 'DESC')


                        ->first();
                }





                $usersddfivfcvv = DB::table('els_system_allocated_engineer')
                    ->where('els_system_id', $value->els_system_id)
                    ->where('status', '18')
                    ->where('id', '<=', $value->id)
                    ->orderBy('id', 'DESC')
                    ->first();








                $total = $variance = 0;
                $rowData = '';
                $rowData .= '"' . $i . '"' . "\t";
                $rowData .= '"' . @$value->fqcdate . '"' . "\t";
                $rowData .= '"' . $value->barcode . '"' . "\t";
                $rowData .= '"' . $value->bname . '"' . "\t";
                $rowData .= '"' . $value->mname . '"' . "\t";
                $rowData .= '"' . $value->colour_name . '"' . "\t";
                $rowData .= '"' . $value->ram . '"' . "\t";
                $rowData .= '"' . $value->rom . '"' . "\t";
                $rowData .= '"' . @$value->name . '"' . "\t";
                $rowData .= '"' . @$fqc . '"' . "\t";
                $rowData .= '"' .  @$fqcdate  . '"' . "\t";

                $rowData .= '"' .  @$remarks  . '"' . "\t";

                $setData .= trim($rowData) . "\n";
                $i++;
            }
        }
        echo ucwords($columnHeader) . "\n" . $setData . "\n";
    }
    public function getproductionWorkReportList(Request $request)
    {
        $data = \App\Models\User::join('role', function ($join) {
            $join->on('role.id', 'users.role');
            $join->whereNull('role.deleted_at');
        })->leftjoin('els_system_allocated_engineer', function ($join) {
            $join->on('els_system_allocated_engineer.engineer_id', 'users.id');
            $join->whereNull('els_system_allocated_engineer.deleted_at');
        })->leftjoin('els_system_allocated_engineer as a', function ($join) {
            $join->on('a.engineer_id', 'users.id');
            $join->where('a.status', '0');
            $join->whereNull('a.deleted_at');
        })->leftjoin('els_system_allocated_engineer as b', function ($join) {
            $join->on('b.engineer_id', 'users.id');
            $join->where('b.status', '1');
            $join->whereNull('b.deleted_at');
        })->leftjoin('els_system_allocated_engineer as c', function ($join) {
            $join->on('c.engineer_id', 'users.id');
            $join->where('c.status', '2');
            $join->whereNull('c.deleted_at');
        })->leftjoin('els_system_allocated_engineer as d', function ($join) {
            $join->on('d.engineer_id', 'users.id');
            $join->where('d.status', '3');
            $join->whereNull('d.deleted_at');
        })->leftjoin('els_system_allocated_engineer as e', function ($join) {
            $join->on('e.engineer_id', 'users.id');
            $join->where('e.status', '4');
            $join->whereNull('e.deleted_at');
        })->leftjoin('els_system_allocated_engineer as f', function ($join) {
            $join->on('f.engineer_id', 'users.id');
            $join->where('f.status', '5');
            $join->whereNull('f.deleted_at');
        })->where('role.role_name', 'engineer')->groupBy('users.id')->orderBy('users.name', 'ASC');

        $where_like = false;
        $columnsArr = ['users.name'];
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
        $data = $data->selectRaw('users.id,users.name,count(distinct a.els_system_id) as repair,count(distinct b.els_system_id) as l3,count(distinct c.els_system_id) as l4,count(distinct d.els_system_id) as fqc,count(distinct e.els_system_id) as fqc_fails,count(distinct f.els_system_id) as shrink_pack,count(distinct els_system_allocated_engineer.els_system_id) as total_system')->get();
        return response()->json(['status' => true, 'data' => $data, 'recordsTotal' => $totalRecord, 'recordsFiltered' => $totalRecord, 'code' => 200, 'message' => 'Engneer work report listed successfully']);
    }



    //////


    //--//

    public function setDateRangeFilterf(Request $request)
    {
        session()->put('start_date', $request->start_date);
        session()->put('end_date', $request->end_date);
        return true;
    }

    public function NewPinReport(Request $request)
    {

        $title = "Renew Pin Report";
        $data = DB::table('els_order_request_parts')->where('new_pin', '1')
            ->join('brand', 'els_order_request_parts.brand_id', '=', 'brand.id')
            ->join('model', 'els_order_request_parts.model_id', '=', 'model.id')
            ->join('colour', 'els_order_request_parts.colour_id', '=', 'colour.id')
            ->join('part', 'els_order_request_parts.part_id', '=', 'part.id')
            ->join('spare_part_price_list', 'els_order_request_parts.spare_part_price_id', '=', 'spare_part_price_list.id')
            ->get();


        // $data = DB::table('els_system_allocated_engineer')->join('els_system_info_details', function ($join) {
        // 	$join->on('els_system_info_details.id', 'els_system_allocated_engineer.els_system_id');
        // 	$join->whereNull('els_system_allocated_engineer.deleted_at');
        // })->join('els_product_status', function ($join) {
        // 	$join->on('els_product_status.id', 'els_system_info_details.status');
        // })->join('brand', function ($join) {
        // 	$join->on('els_system_info_details.brand_id', 'brand.id');
        // })->leftjoin('ram', function ($join) {
        // 	$join->on('els_system_info_details.ram', 'ram.id');
        // })->leftjoin('rom', function ($join) {
        // 	$join->on('els_system_info_details.rom', 'rom.id');
        // })->join('model', function ($join) {
        // 	$join->on('els_system_info_details.model_id', 'model.id');
        // })->join('colour', function ($join) {
        // 	$join->on('els_system_info_details.colour_id', 'colour.id');
        // })->join('users', function ($join) {
        // 	$join->on('els_system_allocated_engineer.engineer_id', 'users.id');
        // })->where('els_system_allocated_engineer.status', 33)->orderBy('els_system_allocated_engineer.id', 'DESC');
        if (session()->get('start_date') && session()->get('end_date')) {
            $start_date = session()->get('start_date');
            $end_date = date('Y-m-d', strtotime('+1 day', strtotime(session()->get('end_date'))));
        } else {
            $start_date = date('Y-m-d', strtotime('-29 day'));
            $end_date = date('Y-m-d', strtotime('+1 day'));
        }
        $data = $data->whereBetween('els_order_request_parts.updated_at', [$start_date, $end_date]);
        // dd($data);

        $where_like = false;
        // $columnsArr = ['els_system_info_details.id,els_system_info_details.barcode,ram.name as ram,rom.name as rom,brand.bname,model.mname,users.name,colour.name as colour_name,els_product_status.name as status,els_system_allocated_engineer.created_at,els_system_allocated_engineer.remark'];
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

        $totalRecord = $data->count();
        if ($request->get('length')) {
            $data = $data->skip($request->get('start'))->take($request->get('length'));
        }

        $data = $data->selectRaw('els_order_request_parts.new_pin,els_order_request_parts.updated_at,els_order_request_parts.barcodes,els_order_request_parts.bname,els_order_request_parts.bstatus,els_order_request_parts.mname,els_order_request_parts.mstatus,els_order_request_parts.name,els_order_request_parts.sku_no')->get();



        // dd($tdata);
        $status = \App\Models\RenewPin::whereNull('deleted_at')->where('new_pin', '1')->get();
        // dd($status);
        return view('renew_pin', compact('title', 'status', 'tdata'));
        // }
        // abort(403, "Don't have permission to access.");
    }


    public function fqcStatisticsReport(Request $request)
    {
        if (\Helper::checkMenuElegible()) {
            $title = "Fqc Static Report List";
            $data = DB::table('els_system_allocated_engineer')->join('els_system_info_details', function ($join) {
                $join->on('els_system_info_details.id', 'els_system_allocated_engineer.els_system_id');
                $join->whereNull('els_system_allocated_engineer.deleted_at');
            })->join('els_product_status', function ($join) {
                $join->on('els_product_status.id', 'els_system_info_details.status');
            })->join('brand', function ($join) {
                $join->on('els_system_info_details.brand_id', 'brand.id');
            })->leftjoin('ram', function ($join) {
                $join->on('els_system_info_details.ram', 'ram.id');
            })->leftjoin('rom', function ($join) {
                $join->on('els_system_info_details.rom', 'rom.id');
            })->join('model', function ($join) {
                $join->on('els_system_info_details.model_id', 'model.id');
            })->join('colour', function ($join) {
                $join->on('els_system_info_details.colour_id', 'colour.id');
            })->join('users', function ($join) {
                $join->on('els_system_allocated_engineer.engineer_id', 'users.id');
            })->where('els_system_allocated_engineer.status', 33)->orderBy('els_system_allocated_engineer.id', 'DESC');
            if (session()->get('start_date') && session()->get('end_date')) {
                $start_date = session()->get('start_date');
                $end_date = date('Y-m-d', strtotime('+1 day', strtotime(session()->get('end_date'))));
            } else {
                $start_date = date('Y-m-d', strtotime('-29 day'));
                $end_date = date('Y-m-d', strtotime('+1 day'));
            }
            $data = $data->whereBetween('els_system_allocated_engineer.created_at', [$start_date, $end_date]);

            $where_like = false;
            $columnsArr = ['els_system_info_details.id,els_system_info_details.barcode,ram.name as ram,rom.name as rom,brand.bname,model.mname,users.name,colour.name as colour_name,els_product_status.name as status,els_system_allocated_engineer.created_at,els_system_allocated_engineer.remark'];
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

            $totalRecord = $data->count();
            if ($request->get('length')) {
                $data = $data->skip($request->get('start'))->take($request->get('length'));
            }
            $data = $data->selectRaw('els_system_info_details.id,els_system_info_details.created_at,els_system_info_details.barcode,ram.name as ram,rom.name as rom,brand.bname,model.mname,users.name,colour.name as colour_name,els_product_status.name as status,els_system_allocated_engineer.created_at as fqcdate,els_system_allocated_engineer.els_system_id,els_system_allocated_engineer.remark,els_system_allocated_engineer.id as id')->get();

            $status = \App\Models\ELSProductStatus::whereNull('deleted_at')->where('status', '1')->get();
            return view('els_product/fqc_statistics_report', compact('title', 'status', 'data'));
        }
        abort(403, "Don't have permission to access.");
    }


    public function fqcStatisticsReportold(Request $request)
    {
        if (\Helper::checkMenuElegible()) {
            $title = "Fqc Static Report List";
            $data = \App\Models\ElsSystemInfoDtailsModel::join('els_system_allocated_engineer', function ($join) {
                $join->on('els_system_info_details.id', 'els_system_allocated_engineer.els_system_id');

                $join->whereNull('els_system_allocated_engineer.deleted_at');
            })->join('els_product_status', function ($join) {
                $join->on('els_product_status.id', 'els_system_info_details.status');
            })->join('brand', function ($join) {
                $join->on('els_system_info_details.brand_id', 'brand.id');
            })->leftjoin('ram', function ($join) {
                $join->on('els_system_info_details.ram', 'ram.id');
            })->leftjoin('rom', function ($join) {
                $join->on('els_system_info_details.rom', 'rom.id');
            })->join('els_system_status_log', function ($join) {
                $join->on('els_system_status_log.els_system_id', 'els_system_info_details.id');
            })->join('model', function ($join) {
                $join->on('els_system_info_details.model_id', 'model.id');
            })->join('colour', function ($join) {
                $join->on('els_system_info_details.colour_id', 'colour.id');
            })->join('users', function ($join) {
                $join->on('els_system_status_log.els_system_allocation_id', 'users.id');
            })->whereNull('els_system_info_details.deleted_at')->groupBy('els_system_info_details.id')->where('els_system_status_log.status', 33)->orderBy('els_system_status_log.id', 'DESC');

            if (session()->get('start_date') && session()->get('end_date')) {
                $start_date = session()->get('start_date');
                $end_date = date('Y-m-d', strtotime('+1 day', strtotime(session()->get('end_date'))));
            } else {
                $start_date = date('Y-m-d', strtotime('-29 day'));
                $end_date = date('Y-m-d', strtotime('+1 day'));
            }
            $data = $data->whereBetween('els_system_status_log.created_at', [$start_date, $end_date]);

            $where_like = false;
            $columnsArr = ['els_system_info_details.id,els_system_info_details.barcode,ram.name as ram,rom.name as rom,brand.bname,model.mname,users.name,colour.name as colour_name,els_product_status.name as status,els_system_status_log.created_at'];
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

            $totalRecord = $data->count();
            if ($request->get('length')) {
                $data = $data->skip($request->get('start'))->take($request->get('length'));
            }
            $data = $data->selectRaw('els_system_info_details.id,els_system_info_details.created_at,els_system_info_details.barcode,ram.name as ram,rom.name as rom,brand.bname,model.mname,users.name,colour.name as colour_name,els_product_status.name as status,els_system_status_log.created_at as fqcdate,els_system_status_log.els_system_id')->get();

            $status = \App\Models\ELSProductStatus::whereNull('deleted_at')->where('status', '1')->get();
            return view('els_product/fqc_statistics_report', compact('title', 'status', 'data'));
        }
        abort(403, "Don't have permission to access.");
    }



    public function downloadfqcReportold(Request $request)
    {
        header("Content-Type: application/xls");
        header("Content-Disposition: attachment; filename=FQCStatisticsReport.xls");
        header("Pragma: no-cache");
        header("Expires: 0");
        $status = \App\Models\ELSProductStatus::whereNull('deleted_at')->where('status', '1')->get();

        $columnHeader = "Sr NO" . "\t";
        $columnHeader .= "FQC Date" . "\t";
        $columnHeader .= "UIN" . "\t";
        $columnHeader .= "Brand" . "\t";
        $columnHeader .= "Model" . "\t";
        $columnHeader .= "Color" . "\t";
        $columnHeader .= "RAM" . "\t";
        $columnHeader .= "ROM" . "\t";
        $columnHeader .= "Fqc Engg. Name" . "\t";
        $columnHeader .= "Repaired Engg. Name" . "\t";
        $columnHeader .= "FQC Status" . "\t";
        $columnHeader .= "Current Sub Status" . "\t";
        $columnHeader .= "Remarked" . "\t";
        $columnHeader .= "Repaired Date" . "\t";


        $data = \App\Models\ElsSystemInfoDtailsModel::join('els_system_allocated_engineer', function ($join) {
            $join->on('els_system_info_details.id', 'els_system_allocated_engineer.els_system_id');

            $join->whereNull('els_system_allocated_engineer.deleted_at');
        })->join('els_product_status', function ($join) {
            $join->on('els_product_status.id', 'els_system_info_details.status');
        })->join('brand', function ($join) {
            $join->on('els_system_info_details.brand_id', 'brand.id');
        })->leftjoin('ram', function ($join) {
            $join->on('els_system_info_details.ram', 'ram.id');
        })->leftjoin('rom', function ($join) {
            $join->on('els_system_info_details.rom', 'rom.id');
        })->join('els_system_status_log', function ($join) {
            $join->on('els_system_status_log.els_system_id', 'els_system_info_details.id');
        })->join('model', function ($join) {
            $join->on('els_system_info_details.model_id', 'model.id');
        })->join('colour', function ($join) {
            $join->on('els_system_info_details.colour_id', 'colour.id');
        })->join('users', function ($join) {
            $join->on('els_system_status_log.els_system_allocation_id', 'users.id');
        })->leftjoin('els_system_packaging_video', function ($join) {
            $join->on('els_system_info_details.id', 'els_system_packaging_video.els_system_id');
            $join->where('els_system_packaging_video.status', '1');
            $join->whereNull('els_system_packaging_video.deleted_at');
        })->whereNull('els_system_info_details.deleted_at')->groupBy('els_system_info_details.id')->where('els_system_status_log.status', '33')->orderBy('els_system_status_log.id', 'DESC');

        if (session()->get('start_date') && session()->get('end_date')) {
            $start_date = session()->get('start_date');
            $end_date = date('Y-m-d', strtotime('+1 day', strtotime(session()->get('end_date'))));
        } else {
            $start_date = date('Y-m-d', strtotime('-29 day'));
            $end_date = date('Y-m-d', strtotime('+1 day'));
        }
        $data = $data->whereBetween('els_system_status_log.created_at', [$start_date, $end_date]);

        $where_like = false;
        $columnsArr = ['els_system_info_details.id,els_system_info_details.barcode,ram.name as ram,rom.name as rom,brand.bname,model.mname,users.name,colour.name as colour_name,els_system_packaging_video.id as video_id,els_product_status.name as status,els_system_status_log.created_at'];
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

        $totalRecord = $data->count();
        if ($request->get('length')) {
            $data = $data->skip($request->get('start'))->take($request->get('length'));
        }
        $data = $data->selectRaw('els_system_info_details.id,els_system_info_details.created_at,els_system_info_details.barcode,ram.name as ram,rom.name as rom,brand.bname,model.mname,users.name,colour.name as colour_name,els_system_packaging_video.id as video_id,els_product_status.name as status,els_system_status_log.created_at as fqcdate,els_system_status_log.els_system_id')->get();



        $i = 1;
        $setData = '';
        if (!$data->isEmpty()) {
            foreach ($data as $value) {




                $usersddfi = DB::table('els_system_status_log')
                    ->where('els_system_id', $value->els_system_id)
                    ->where('status', 30)
                    ->count();

                $usersddfiv = DB::table('els_system_status_log')
                    ->where('els_system_id', $value->els_system_id)
                    ->where('status', 33)
                    ->count();
                if ($usersddfiv > 0) {
                    $usersddfii = DB::table('els_system_status_log')
                        ->where('els_system_id', $value->els_system_id)
                        ->where('status', 20)
                        ->count();
                } else {
                    $usersddfii = 0;
                }



                if ($usersddfii > 0) {


                    $usersdeng = DB::table('els_system_allocated_engineer')
                        ->select(DB::raw('group_concat(remark) as remark'))
                        ->where('els_system_id', $value->els_system_id)
                        ->where('status', '20')
                        ->where('remark', '!=', '')
                        ->first();


                    $usersdengb = DB::table('els_system_allocated_engineer')
                        ->where('els_system_id', $value->els_system_id)
                        ->where('status', '22')
                        ->orderby('id', 'Desc')
                        ->first();
                    $fqc = 'Failed';
                    if (!empty($usersdengb)) {

                        $remark = $usersdeng->remark;
                        $date = $usersdengb->created_at;
                    }
                } else if ($usersddfi > 0) {


                    $usersdeng = DB::table('els_system_allocated_engineer')
                        ->select(DB::raw('group_concat(remark) as remark'))
                        ->where('els_system_id', $value->els_system_id)
                        ->where('status', '30')
                        ->where('remark', '!=', '')
                        ->first();


                    $usersdengb = DB::table('els_system_allocated_engineer')
                        ->where('els_system_id', $value->els_system_id)
                        ->where('status', '22')
                        ->orderby('id', 'Desc')
                        ->first();

                    if (!empty($usersdengb)) {
                        $remark = $usersdeng->remark;
                        $date = $usersdengb->created_at;
                    }

                    $fqc = 'Pass';
                } else {
                    $usersdengb = DB::table('els_system_allocated_engineer')
                        ->where('els_system_id', $value->els_system_id)
                        ->where('status', '22')
                        ->orderby('id', 'Desc')
                        ->first();

                    if (!empty($usersdengb)) {

                        $fqc = 'Under Observe';
                        $remark = $usersdengb->remark;
                        $date = $usersdengb->created_at;
                    } else {

                        $fqc = 'Under Observe';
                        $remark = '';
                        $date = '';
                    }
                }

                $usersddfio = DB::table('els_system_status_log')
                    ->where('els_system_id', $value->els_system_id)
                    ->where('status', '22')
                    ->count();

                if ($usersddfio > 0) {

                    $usersddfiof = DB::table('els_system_status_log')
                        ->where('els_system_id', $value->els_system_id)
                        ->where('status', '22')
                        ->first();
                    $created = $usersddfiof->created_at;
                    $userss = $usersddfiof->els_system_allocation_id;



                    $usersuu = DB::table('users')
                        ->where('id', @$userss)
                        ->first();
                    if (!empty($usersuu)) {
                        $uname = $usersuu->name;
                    } else {
                        $uname = '';
                    }
                } else {
                    $fqc = 'Under Observe SR';
                    $created = '';
                }






                $total = $variance = 0;
                $rowData = '';
                $rowData .= '"' . $i . '"' . "\t";
                $rowData .= '"' . $value->fqcdate . '"' . "\t";
                $rowData .= '"' . $value->barcode . '"' . "\t";
                $rowData .= '"' . $value->bname . '"' . "\t";
                $rowData .= '"' . $value->mname . '"' . "\t";
                $rowData .= '"' . $value->colour_name . '"' . "\t";
                $rowData .= '"' . $value->ram . '"' . "\t";
                $rowData .= '"' . $value->rom . '"' . "\t";
                $rowData .= '"' . $value->name . '"' . "\t";
                $rowData .= '"' . @$uname . '"' . "\t";
                $rowData .= '"' . @$fqc . '"' . "\t";
                $rowData .= '"' . @$value->status . '"' . "\t";
                $rowData .= '"' . @$remark . '"' . "\t";
                $rowData .= '"' . @$date . '"' . "\t";

                $setData .= trim($rowData) . "\n";
                $i++;
            }
        }
        echo ucwords($columnHeader) . "\n" . $setData . "\n";
    }





    public function downloadfqcReport(Request $request)
    {
        header("Content-Type: application/xls");
        header("Content-Disposition: attachment; filename=FQCStatisticsReport.xls");
        header("Pragma: no-cache");
        header("Expires: 0");
        $status = \App\Models\ELSProductStatus::whereNull('deleted_at')->where('status', '1')->get();

        $columnHeader = "Sr NO" . "\t";
        $columnHeader .= "FQC Date" . "\t";
        $columnHeader .= "UIN" . "\t";
        $columnHeader .= "Brand" . "\t";
        $columnHeader .= "Model" . "\t";
        $columnHeader .= "Color" . "\t";
        $columnHeader .= "RAM" . "\t";
        $columnHeader .= "ROM" . "\t";
        $columnHeader .= "Fqc Engg. Name" . "\t";
        $columnHeader .= "Repaired Engg. Name" . "\t";
        $columnHeader .= "FQC Status" . "\t";
        $columnHeader .= "Current Sub Status" . "\t";
        $columnHeader .= "Remarked" . "\t";
        $columnHeader .= "Repaired Date" . "\t";

        $data = DB::table('els_system_allocated_engineer')->join('els_system_info_details', function ($join) {
            $join->on('els_system_info_details.id', 'els_system_allocated_engineer.els_system_id');
        })->join('els_product_status', function ($join) {
            $join->on('els_product_status.id', 'els_system_info_details.status');
        })->join('brand', function ($join) {
            $join->on('els_system_info_details.brand_id', 'brand.id');
        })->leftjoin('ram', function ($join) {
            $join->on('els_system_info_details.ram', 'ram.id');
        })->leftjoin('rom', function ($join) {
            $join->on('els_system_info_details.rom', 'rom.id');
        })->join('model', function ($join) {
            $join->on('els_system_info_details.model_id', 'model.id');
        })->join('colour', function ($join) {
            $join->on('els_system_info_details.colour_id', 'colour.id');
        })->join('users', function ($join) {
            $join->on('els_system_allocated_engineer.engineer_id', 'users.id');
        })->where('els_system_allocated_engineer.status', 33)->orderBy('els_system_allocated_engineer.id', 'DESC');

        if (session()->get('start_date') && session()->get('end_date')) {
            $start_date = session()->get('start_date');
            $end_date = date('Y-m-d', strtotime('+1 day', strtotime(session()->get('end_date'))));
        } else {
            $start_date = date('Y-m-d', strtotime('-29 day'));
            $end_date = date('Y-m-d', strtotime('+1 day'));
        }
        $data = $data->whereBetween('els_system_allocated_engineer.created_at', [$start_date, $end_date]);

        $data = $data->selectRaw('els_system_info_details.barcode,ram.name as ram,rom.name as rom,brand.bname,model.mname,users.name,colour.name as colour_name,els_product_status.name as status,els_system_allocated_engineer.created_at as fqcdate,els_system_allocated_engineer.els_system_id,els_system_allocated_engineer.remark,els_system_allocated_engineer.id as id')->get();




        $i = 1;
        $setData = '';
        if (!$data->isEmpty()) {
            foreach ($data as $value) {

                $usersddfio = DB::table('els_system_allocated_engineer')
                    ->where('els_system_id', $value->els_system_id)
                    ->where('status', 33)
                    ->count();

                if ($usersddfio > 0) {


                    $usersddfiof = DB::table('els_system_allocated_engineer')
                        ->where('els_system_id', $value->els_system_id)
                        ->where('status', 33)
                        ->first();

                    $fqcdate = $usersddfiof->created_at;


                    $usersddfi = DB::table('els_system_allocated_engineer')
                        ->where('els_system_id', $value->els_system_id)
                        ->where('id', '>=', $value->id)
                        ->where('status', 30)
                        ->count();

                    $usersddfii = DB::table('els_system_allocated_engineer')
                        ->where('els_system_id', $value->els_system_id)
                        ->where('id', '>=', $value->id)
                        ->where('status', 20)
                        ->count();


                    if ($usersddfii > 0) {


                        $usersddfioFQC = DB::table('els_system_allocated_engineer')
                            ->where('els_system_id', $value->els_system_id)
                            ->where('status', 33)
                            ->where('id', '>=', $value->id)
                            ->first();
                        @$fqcdate = $usersddfioFQC->created_at;

                        if (@$usersddfioFQC->id) {
                            $fqc = 'Failed';
                        } else {
                            $fqc = 'Under Observed';
                        }

                        $usersddfivf = DB::table('els_system_allocated_engineer')
                            ->where('els_system_id', $value->els_system_id)
                            ->where('id', '>=', $value->id)
                            ->where('status', 20)
                            ->first();

                        @$remarks = @$usersddfivf->remark;
                        @$created_att = $usersddfivf->created_att;
                    } else if ($usersddfi > 0) {

                        $usersddfivp = DB::table('els_system_allocated_engineer')
                            ->where('els_system_id', $value->els_system_id)
                            ->where('status', 30)
                            ->where('id', '>=', $value->id)
                            ->first();


                        $usersddfioFQC = DB::table('els_system_allocated_engineer')
                            ->where('els_system_id', $value->els_system_id)
                            ->where('status', 33)
                            ->where('id', '>=', $value->id)
                            ->first();

                        @$fqcdate = $usersddfioFQC->created_at;
                        @$remarks = @$usersddfivp->remark;
                        @$created_att = $usersddfivp->created_att;

                        if (@$usersddfioFQC->id) {
                            $fqc = 'Pass';
                        } else {
                            $fqc = 'Under Observed';
                        }
                        $fqc = 'Pass';
                    } else {
                        $fqcdate = '';
                        $fqc = 'Under Observe';
                        $remarks = '';
                    }
                } else {
                    $fqcdate = '';
                    $fqc = 'Under Observe SR';
                    $fqcdate = '';
                    $remarks = '';
                }



                $usersddfivfc = DB::table('els_system_allocated_engineer')
                    ->where('els_system_id', $value->els_system_id)
                    ->where('status', '22')
                    ->where('id', '<=', $value->id)
                    ->orderBy('id', 'DESC')
                    ->first();
                $usersddfivff = DB::table('els_system_allocated_engineer')
                    ->where('els_system_id', $value->els_system_id)
                    ->where('status', '22')
                    ->where('id', '<=', $value->id)
                    ->orderBy('id', 'DESC')
                    ->count();



                if ($usersddfivff > 0) {
                    $usersddfivffcc = DB::table('users')
                        ->where('id', $usersddfivfc->engineer_id)
                        ->orderBy('id', 'DESC')


                        ->first();


                    $dated = $usersddfivfc->created_at;

                    //  $dated=$usersddfivfc->created_at;


                }





                $usersddfivfcvv = DB::table('els_system_allocated_engineer')
                    ->where('els_system_id', $value->els_system_id)
                    ->where('status', '18')
                    ->where('id', '<=', $value->id)
                    ->orderBy('id', 'DESC')
                    ->first();








                $value->fqcdate;
                $value->name;
                $fqcdate;



                $total = $variance = 0;
                $rowData = '';
                $rowData .= '"' . $i . '"' . "\t";
                $rowData .= '"' . $value->fqcdate . '"' . "\t";
                $rowData .= '"' . $value->barcode . '"' . "\t";
                $rowData .= '"' . $value->bname . '"' . "\t";
                $rowData .= '"' . $value->mname . '"' . "\t";
                $rowData .= '"' . $value->colour_name . '"' . "\t";
                $rowData .= '"' . $value->ram . '"' . "\t";
                $rowData .= '"' . $value->rom . '"' . "\t";
                $rowData .= '"' . $value->name . '"' . "\t";
                $rowData .= '"' . @$usersddfivffcc->name . '"' . "\t";
                $rowData .= '"' . @$fqc . '"' . "\t";
                $rowData .= '"' . @$value->status . '"' . "\t";
                $rowData .= '"' . @$remarks . '"' . "\t";
                $rowData .= '"' . @$dated . '"' . "\t";

                $setData .= trim($rowData) . "\n";
                $i++;
            }
        }
        echo ucwords($columnHeader) . "\n" . $setData . "\n";
    }
    public function getfqcWorkReportList(Request $request)
    {
        $data = \App\Models\User::join('role', function ($join) {
            $join->on('role.id', 'users.role');
            $join->whereNull('role.deleted_at');
        })->leftjoin('els_system_allocated_engineer', function ($join) {
            $join->on('els_system_allocated_engineer.engineer_id', 'users.id');
            $join->whereNull('els_system_allocated_engineer.deleted_at');
        })->leftjoin('els_system_allocated_engineer as a', function ($join) {
            $join->on('a.engineer_id', 'users.id');
            $join->where('a.status', '0');
            $join->whereNull('a.deleted_at');
        })->leftjoin('els_system_allocated_engineer as b', function ($join) {
            $join->on('b.engineer_id', 'users.id');
            $join->where('b.status', '1');
            $join->whereNull('b.deleted_at');
        })->leftjoin('els_system_allocated_engineer as c', function ($join) {
            $join->on('c.engineer_id', 'users.id');
            $join->where('c.status', '2');
            $join->whereNull('c.deleted_at');
        })->leftjoin('els_system_allocated_engineer as d', function ($join) {
            $join->on('d.engineer_id', 'users.id');
            $join->where('d.status', '3');
            $join->whereNull('d.deleted_at');
        })->leftjoin('els_system_allocated_engineer as e', function ($join) {
            $join->on('e.engineer_id', 'users.id');
            $join->where('e.status', '4');
            $join->whereNull('e.deleted_at');
        })->leftjoin('els_system_allocated_engineer as f', function ($join) {
            $join->on('f.engineer_id', 'users.id');
            $join->where('f.status', '5');
            $join->whereNull('f.deleted_at');
        })->where('role.role_name', 'engineer')->groupBy('users.id')->orderBy('users.name', 'ASC');

        $where_like = false;
        $columnsArr = ['users.name'];
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
        $data = $data->selectRaw('users.id,users.name,count(distinct a.els_system_id) as repair,count(distinct b.els_system_id) as l3,count(distinct c.els_system_id) as l4,count(distinct d.els_system_id) as fqc,count(distinct e.els_system_id) as fqc_fails,count(distinct f.els_system_id) as shrink_pack,count(distinct els_system_allocated_engineer.els_system_id) as total_system')->get();
        return response()->json(['status' => true, 'data' => $data, 'recordsTotal' => $totalRecord, 'recordsFiltered' => $totalRecord, 'code' => 200, 'message' => 'Engneer work report listed successfully']);
    }



    //--//

    public function exportELSProduct(Request $request)
    {
        header("Content-Type: application/xls");
        header("Content-Disposition: attachment; filename=ElsProductReport.xls");
        header("Pragma: no-cache");
        header("Expires: 0");

        $columnHeader = "Sr NO" . "\t" . "Date" . "\t" . "Brand" . "\t" . "Model" . "\t" . "Colour" . "\t" . "UIN" . "\t" . "GRN No" . "\t" . "IMEI 1" . "\t" . "IMEI 2" . "\t" . "RAM" . "\t" . "ROM" . "\t" . "Incoming Grade" . "\t";
        if (\App\Models\RoleModel::find(\Auth::user()->role)->role_name == 'admin') {
            $columnHeader .= "Cost" . "\t";
        }
        $columnHeader .= "Remark" . "\t" . "In Vendor Name" . "\t" . "Status" . "\t" . "Sub Status" . "\t" . "Out Vendor Name" . "\t" . "Stock status" . "\t";

        $data = \App\Models\ElsSystemInfoDtailsModel::join('brand', function ($join) {
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
            // 			$join->whereNull('els_product_status.deleted_at');
        })->leftjoin('els_system_status_log', function ($join) {
            $join->on('els_system_allocated_engineer.id', 'els_system_status_log.els_system_allocation_id');
            $join->on('els_system_info_details.status', 'els_system_status_log.status');
            $join->on('els_system_allocated_engineer.els_system_id', 'els_system_status_log.els_system_id');
            // 			$join->whereNull('els_system_status_log.deleted_at');
        })->leftjoin('els_product_sub_status', function ($join) {
            $join->on('els_product_status.sub_status_id', 'els_product_sub_status.id');
            // 			$join->whereNull('els_product_sub_status.deleted_at');
        })->leftjoin('vendor', 'vendor.id', '=', 'els_system_info_details.vendor_id')
            ->leftjoin('vendor as out', 'out.id', '=', 'els_system_status_log.vendor_id')
            ->whereNull('els_system_info_details.deleted_at')->orderBy('els_system_info_details.resived_date', 'DESC')->groupBy('els_system_info_details.id');

        $data = $data->selectRaw('els_system_info_details.id,els_system_info_details.resived_date,els_system_info_details.grn_no,ram.name as ram,rom.name as rom,grade.name as grade,els_system_info_details.remark,els_system_info_details.barcode,els_system_info_details.mrp,els_system_info_details.imei_1,els_system_info_details.imei_2,brand.bname,model.mname,vendor.vname,colour.name as colour_name,els_product_status.name as status,els_product_sub_status.name as sub_status,els_system_status_log.created_at as status_date,out.vname as out_vendor,els_system_info_details.stock_in')->get();

        $i = 1;
        $setData = '';
        if (!$data->isEmpty()) {
            foreach ($data as $value) {
                $rowData = '';
                $rowData .= '"' . $i . '"' . "\t";
                $rowData .= '"' . date('d-M-Y', strtotime($value->resived_date)) . '"' . "\t";
                $rowData .= '"' . $value->bname . '"' . "\t";
                $rowData .= '"' . $value->mname . '"' . "\t";
                $rowData .= '"' . $value->colour_name . '"' . "\t";
                $rowData .= '"' . $value->barcode . '"' . "\t";
                $rowData .= '"' . $value->grn_no . '"' . "\t";
                $rowData .= '"' . $value->imei_1 . '"' . "\t";
                $rowData .= '"' . $value->imei_2 . '"' . "\t";
                $rowData .= '"' . $value->ram . '"' . "\t";
                $rowData .= '"' . $value->rom . '"' . "\t";
                $rowData .= '"' . $value->grade . '"' . "\t";
                if (in_array(\App\Models\RoleModel::find(\Auth::user()->role)->role_name, ['admin', 'finance'])) {
                    $rowData .= '"' . $value->mrp . '"' . "\t";
                }
                $rowData .= '"' . $value->remark . '"' . "\t";
                $rowData .= '"' . $value->vname . '"' . "\t";

                if ($value->status_date) {
                    $rowData .= '"' . $value->sub_status . '(' . date('d-M-Y', strtotime($value->status_date)) . ')' . '"' . "\t";
                } else {
                    $rowData .= '"' . $value->sub_status . '"' . "\t";
                }

                $in_stock = \Helper::getActiveInwardDate($value->id);
                if ($in_stock) {
                    $rowData .= '"Not assign"' . "\t";
                } elseif ($value->status) {
                    $rowData .= '"' . $value->status . '"' . "\t";
                } else {
                    $rowData .= '"Not assign"' . "\t";
                }
                $rowData .= '"' . $value->out_vendor . '"' . "\t";
                if ($value->stock_in == 1) {
                    $rowData .= '"Stock IN"' . "\t";
                } else {
                    $rowData .= '"Stock OUT"' . "\t";
                }
                $setData .= trim($rowData) . "\n";
                $i++;
            }
        }
        echo ucwords($columnHeader) . "\n" . $setData . "\n";
    }

    public function exportELSProductin(Request $request)
    {
        header("Content-Type: application/xls");
        header("Content-Disposition: attachment; filename=ElsProductReport.xls");
        header("Pragma: no-cache");
        header("Expires: 0");

        $columnHeader = "Sr NO" . "\t" . "Date" . "\t" . "Brand" . "\t" . "Model" . "\t" . "Colour" . "\t" . "UIN" . "\t" . "GRN No" . "\t" . "IMEI 1" . "\t" . "IMEI 2" . "\t" . "RAM" . "\t" . "ROM" . "\t" . "Incoming Grade" . "\t";
        if (\App\Models\RoleModel::find(\Auth::user()->role)->role_name == 'admin') {
            // 			$columnHeader .= "Cost"."\t";
        }
        $columnHeader .= "Remark" . "\t" . "Sub Status" . "\t" . "Status" . "\t" . "Stock status" . "\t";

        $data = \App\Models\ElsSystemInfoDtailsModel::join('brand', function ($join) {
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
            // 			$join->whereNull('els_product_status.deleted_at');
        })->leftjoin('els_system_status_log', function ($join) {
            $join->on('els_system_allocated_engineer.id', 'els_system_status_log.els_system_allocation_id');
            $join->on('els_system_info_details.status', 'els_system_status_log.status');
            $join->on('els_system_allocated_engineer.els_system_id', 'els_system_status_log.els_system_id');
            // 			$join->whereNull('els_system_status_log.deleted_at');
        })->leftjoin('els_product_sub_status', function ($join) {
            $join->on('els_product_status.sub_status_id', 'els_product_sub_status.id');
            // 			$join->whereNull('els_product_sub_status.deleted_at');
        })->leftjoin('vendor', 'vendor.id', '=', 'els_system_info_details.vendor_id')
            ->leftjoin('vendor as out', 'out.id', '=', 'els_system_status_log.vendor_id')
            ->whereNull('els_system_info_details.deleted_at')
            ->where('els_system_info_details.stock_in', 1)
            ->orderBy('els_system_info_details.resived_date', 'DESC')->groupBy('els_system_info_details.id');

        $data = $data->selectRaw('els_system_info_details.id,els_system_info_details.resived_date,els_system_info_details.grn_no,ram.name as ram,rom.name as rom,grade.name as grade,els_system_info_details.remark,els_system_info_details.barcode,els_system_info_details.mrp,els_system_info_details.imei_1,els_system_info_details.imei_2,brand.bname,model.mname,vendor.vname,colour.name as colour_name,els_product_sub_status.name as status,els_product_status.name as sub_status,els_system_status_log.created_at as status_date,out.vname as out_vendor,els_system_info_details.stock_in')->get();

        $i = 1;
        $setData = '';
        if (!$data->isEmpty()) {
            foreach ($data as $value) {
                $rowData = '';
                $rowData .= '"' . $i . '"' . "\t";
                $rowData .= '"' . date('d-M-Y', strtotime($value->resived_date)) . '"' . "\t";
                $rowData .= '"' . $value->bname . '"' . "\t";
                $rowData .= '"' . $value->mname . '"' . "\t";
                $rowData .= '"' . $value->colour_name . '"' . "\t";
                $rowData .= '"' . $value->barcode . '"' . "\t";
                $rowData .= '"' . $value->grn_no . '"' . "\t";
                $rowData .= '"' . $value->imei_1 . '"' . "\t";
                $rowData .= '"' . $value->imei_2 . '"' . "\t";
                $rowData .= '"' . $value->ram . '"' . "\t";
                $rowData .= '"' . $value->rom . '"' . "\t";
                $rowData .= '"' . $value->grade . '"' . "\t";
                if (in_array(\App\Models\RoleModel::find(\Auth::user()->role)->role_name, ['admin', 'finance'])) {
                    // 	$rowData .= '"' . $value->mrp . '"' . "\t";
                }
                $rowData .= '"' . $value->remark . '"' . "\t";
                // $rowData .= '"' . $value->vname . '"' . "\t";

                if ($value->status_date) {
                    $rowData .= '"' . $value->sub_status . '(' . date('d-M-Y', strtotime($value->status_date)) . ')' . '"' . "\t";
                } else {
                    $rowData .= '"' . $value->sub_status . '"' . "\t";
                }

                $in_stock = \Helper::getActiveInwardDate($value->id);
                if ($value->status) {
                    $rowData .= '"' . $value->status . '"' . "\t";
                } else {
                    $rowData .= '"Not assign"' . "\t";
                }
                // $rowData .= '"' . $value->out_vendor . '"' . "\t";
                if ($value->stock_in == 1) {
                    $rowData .= '"Stock IN"' . "\t";
                } else {
                    $rowData .= '"Stock OUT"' . "\t";
                }
                $setData .= trim($rowData) . "\n";
                $i++;
            }
        }
        echo ucwords($columnHeader) . "\n" . $setData . "\n";
    }

    public function exportELSProductinv(Request $request)
    {

        header("Content-Type: application/xls");
        header("Content-Disposition: attachment; filename=ElsProductInventoryReport.xls");
        header("Pragma: no-cache");
        header("Expires: 0");

        $columnHeader = "Sr NO" . "\t" . "Brand" . "\t" . "Model" . "\t" . "Stock In" . "\t";



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
            $join->on('els_product_status.sub_status_id', 'els_product_sub_status.id');
            // $join->whereNull('els_product_sub_status.deleted_at');
        })->leftjoin('vendor', 'vendor.id', '=', 'els_system_info_details.vendor_id')
            ->leftjoin('vendor as out', 'out.id', '=', 'els_system_status_log.vendor_id')
            ->whereNull('els_system_info_details.deleted_at')
            // 			->groupBy('inward_date.els_system_id')
            ->groupBy('els_system_info_details.model_id')
            ->orderBy('els_system_info_details.resived_date', 'DESC');




        $data2 = $data2->selectRaw('els_system_info_details.id,DATE_FORMAT(els_system_info_details.resived_date,"%d/%m/%Y") as resived_date,els_system_info_details.grn_no,ram.name as ram,rom.name as rom,grade.name as grade,els_system_info_details.remark,els_system_info_details.barcode,els_system_info_details.model_id,els_system_info_details.mrp,els_system_info_details.imei_1,els_system_info_details.imei_2,brand.bname,model.mname,vendor.vname,colour.name as colour_name,els_product_sub_status.name as status,els_product_status.name as sub_status,out.vname as out_vendor,round((els_system_info_details.mrp +  IF((select round(SUM(received_purchase_order_parts_list.price),2) from els_system_allocated_parts_barcode join received_parts_barcode_list on received_parts_barcode_list.id=els_system_allocated_parts_barcode.barcode_id join received_purchase_order_parts_list on received_purchase_order_parts_list.id=received_parts_barcode_list.received_part_id where els_system_id = els_system_info_details.id), (select round(SUM(received_purchase_order_parts_list.price),2) from els_system_allocated_parts_barcode join received_parts_barcode_list on received_parts_barcode_list.id=els_system_allocated_parts_barcode.barcode_id join received_purchase_order_parts_list on received_purchase_order_parts_list.id=received_parts_barcode_list.received_part_id where els_system_id = els_system_info_details.id), 0) + IF((select sum(amount) from els_system_extra_expence where els_system_id = `els_system_info_details`.`id`), (select sum(amount) from els_system_extra_expence where els_system_id = `els_system_info_details`.`id`), 0)),2) as new_price')->get();


        $i = 1;
        $setData = '';
        if (!$data2->isEmpty()) {
            foreach ($data2 as $value) {
                $usersddfi = DB::table('els_system_info_details')
                    ->selectRaw('count(id) as live')
                    ->where('model_id', $value['model_id'])
                    ->where('stock_in', 1)
                    ->whereNull('deleted_at')
                    ->get();


                $rowData = '';
                $rowData .= '"' . $i . '"' . "\t";
                $rowData .= '"' . $value->bname . '"' . "\t";
                $rowData .= '"' . $value->mname . '"' . "\t";
                $rowData .= '"' . $usersddfi[0]->live . '"' . "\t";

                $setData .= trim($rowData) . "\n";
                $i++;
            }
        }
        echo ucwords($columnHeader) . "\n" . $setData . "\n";
    }


    public function uploadBulkProductReport(Request $request)
    {
        set_time_limit(0);
        $file = $request->file('file_csv');
        if (in_array(strtoupper($file->getClientOriginalExtension()), ['CSV'])) {
            $fileD = fopen($file, "r");
            $column = fgetcsv($fileD);
            // print_r($column);

            if ($column) {
                foreach ($column as $k => $d) {
                    if ($d == 'Brand') {
                        $barnd_key = $k;
                    }
                    if ($d == 'Model') {
                        $model_key = $k;
                    }
                    if ($d == 'Colour') {
                        $colour_key = $k;
                    }
                    if ($d == 'GRN No') {
                        $grn_key = $k;
                    }
                    if ($d == 'IMEI 1') {
                        $imei_1_key = $k;
                    }
                    if ($d == 'IMEI 2') {
                        $imei_2_key = $k;
                    }
                    if ($d == 'RAM') {
                        $ram_key = $k;
                    }
                    if ($d == 'ROM') {
                        $rom_key = $k;
                    }
                    if ($d == 'GRADE') {
                        $grade_key = $k;
                    }
                    if ($d == 'Cost Price') {
                        $price_key = $k;
                    }
                    if ($d == 'Remark') {
                        $remark_key = $k;
                    }
                    if ($d == 'Vendor') {
                        $vendor_key = $k;
                    }
                    if ($d == 'Resived Date') {
                        $resived_date_key = $k;
                    }
                    if ($d == 'Sub Status') {
                        $status_key = $k;
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
                // print_r($rowData);


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
                if (isset($rowData[$colour_key])) {
                    $colour = \App\Models\MasterColourModel::where('status', '1')->whereNull('deleted_at')->where('name', $rowData[$colour_key])->first();
                    if ($colour) {
                        $row['colour_id'] = $colour->id;
                    } else {
                        $msg .= 'Colour';
                        $error = true;
                    }
                }
                if (isset($rowData[$grn_key])) {
                    $row['grn_no'] = $rowData[$grn_key];
                }
                if (isset($rowData[$imei_1_key])) {
                    $row['imei_1'] = trim($rowData[$imei_1_key]);
                }
                if (isset($rowData[$imei_2_key])) {
                    $row['imei_2'] = $rowData[$imei_2_key];
                }




                if (!empty($rowData[$ram_key])) {



                    $ram = DB::select("select * from `ram` where `status` = '1' and `deleted_at` is null and `name` = '" . trim($rowData[$ram_key]) . "' limit 1");


                    if (!empty($ram)) {
                        $row['ram'] = $ram[0]->id;
                    } else {
                        $row['ram'] = NULL;
                    }
                } else {
                    $row['ram'] = NULL;
                }
                if (!empty($rowData[$rom_key])) {


                    $rom = DB::select("select * from `rom` where `status` = '1' and `deleted_at` is null and `name` = '" . trim($rowData[$rom_key]) . "' limit 1");



                    if (!empty($rom)) {
                        $row['rom'] = $rom[0]->id;
                    } else {
                        $row['rom'] = NULL;
                    }
                } else {
                    $row['rom'] = NULL;
                }
                if (isset($rowData[$grade_key])) {
                    $grade = \App\Models\MasterGradeModel::where('status', '1')->whereNull('deleted_at')->where('name', $rowData[$grade_key])->first();
                    if ($grade) {
                        $row['grade'] = $grade->id;
                    } else {
                        $row['grade'] = NULL;
                    }
                } else {
                    $row['grade'] = NULL;
                }
                if (isset($rowData[$price_key])) {
                    $row['mrp'] = $rowData[$price_key];
                }
                if (isset($rowData[$remark_key])) {
                    $row['remark'] = $rowData[$remark_key];
                }
                if (isset($rowData[$resived_date_key])) {
                    $row['resived_date'] = date('Y-m-d', strtotime(str_replace('-', '/', $rowData[$resived_date_key])));
                }
                if (isset($rowData[$vendor_key])) {
                    $vendor = \App\Models\MasterVendorModel::where('status', '1')->whereNull('deleted_at')->where('vname', trim($rowData[$vendor_key]))->first();
                    if ($vendor) {
                        $row['vendor_id'] = $vendor->id;
                    } else {
                        $row['vendor_id'] = NULL;
                    }
                } else {
                    $row['vendor_id'] = NULL;
                }
                if (isset($rowData[$status_key])) {
                    $status = \App\Models\ELSProductStatus::where('status', '1')->whereNull('deleted_at')->where('name', $rowData[$status_key])->first();
                    if ($status) {
                        $row['status'] = $status->id;
                    } else {
                        $row['status'] = NULL;
                    }
                } else {
                    $row['status'] = NULL;
                }


                // print_r($row);

                $dataArr[] = $row;

                // die("jj");
                if ($error) $massage .= $msg . ' Not Found. Please Correct The Details.';
                $i++;
            }

            if ($error) {
                return response()->json(['status' => false, 'data' => [], 'code' => 401, 'message' => $massage]);
            }

            // echo "<pre>";
            //  print_r($dataArr);
            array_pop($dataArr);
            //  	echo "<pre>";
            //  print_r($dataArr);

            if ($dataArr) {
                foreach ($dataArr as $val) {
                    $d = (object) $val;

                    // 	 print_r($vendor);
                    // print_r($d);
                    // 	die();
                    $addData['brand_id'] = $d->brand_id;
                    $addData['model_id'] = $d->model_id;
                    $addData['vendor_id'] = $d->vendor_id;
                    $addData['colour_id'] = $d->colour_id;
                    $addData['grn_no'] = $d->grn_no;
                    $addData['imei_1'] = trim($d->imei_1);
                    $addData['imei_2'] = $d->imei_2;
                    // 	$addData['imei_2'] = $d->imei_2;
                    // 	if(isset($d->ram))
                    $addData['ram'] = $d->ram;
                    // 	if(isset($d->rom))
                    $addData['rom'] = $d->rom;
                    // 	if(isset($d->grade))
                    $addData['grade'] = $d->grade;
                    if (isset($d->mrp))
                        $addData['mrp'] = $d->mrp;
                    if (isset($d->remark))
                        $addData['remark'] = $d->remark;
                    if (isset($d->resived_date))
                        $addData['resived_date'] = $d->resived_date;
                    $addData['updated_by'] = Auth::user()->id;
                    $dataArr['sku_no'] = \Helper::getDeviceSKUNumber($d->brand_id, $d->model_id, $d->colour_id);

                    // 	echo '<pre>';
                    // 	print_r($addData);


                    $data = \App\Models\ElsSystemInfoDtailsModel::where('imei_1', trim($d->imei_1))->where('deleted_at', NULL)->first();
                    // 	echo '<pre>';
                    //	print_r($data);
                    if (!empty($data)) {

                        if ($data->status == 4) {
                            $addData['status'] = 0;
                        }
                        $addData['is_active'] = 1;
                        $addData['stock_in'] = 1;
                        $addData['deleted_at'] = null;
                        $save = \App\Models\ElsSystemInfoDtailsModel::where('id', $data->id)->Update($addData);
                        $id = $data->id;
                        if (isset($d->status)) {
                            // 			$s = \App\Models\AssignEngineer::whereNull('deleted_at')->where('els_system_id',$id)->orderBy('id','DESC')->first();
                            // 			if($s){
                            // $st = \App\models\ELSProductStatus::find($d->status);
                            // \App\Models\ElsSystemInfoDtailsModel::where('id',$id)->update(['status'=>$st->sub_status_id]);
                            if (!in_array($data->status, [2])) {
                                \App\Models\AssignEngineer::where('id', $s->id)->update(['status' => $d->status]);
                            }
                            // 			}
                        }
                    } else {
                        $addData['stock_in'] = 1;
                        $addData['created_by'] = \Auth::user()->id;
                        $save = \App\Models\ElsSystemInfoDtailsModel::Create($addData);
                        $id = $save->id;
                        if (strlen($id) < 6) {
                            $length = 6 - strlen($id);
                            // 			$barcode = $id.$this->random_stringss($length);
                            $barcode = $this->random_strings(8);
                        } else {
                            $barcode = $this->random_strings(8);
                        }
                        \App\Models\ElsSystemInfoDtailsModel::where('id', $id)->update(['barcode' => $barcode]);
                    }
                    $inwd = \App\Models\InwardDate::where('els_system_id', $id)->orderBy('id', 'DESC')->first();
                    if ($data && $data->status == 1 && $inwd) {
                        \App\Models\InwardDate::where('id', $inwd->id)->update(['received_date' => $d->resived_date]);
                    } else {
                        \App\Models\InwardDate::Create([
                            'els_system_id'   => $id,
                            'received_date'   => $d->resived_date,
                        ]);
                    }
                }
            }
            // return response()->json(['status'=>true, 'data' => [], 'code'=>200, 'message'=> 'Product successfully imported.']);
        }
        // return response()->json(['status'=>false, 'data' => [], 'code'=>401, 'message'=> 'Please choose a correct formate.']);
        return redirect()->back();
    }

    public function viewStatus(Request $request)
    {
        if (\Helper::checkMenuElegible()) {
            $data = [];
            $title = "Status List";
            return view('els_product/status_list', compact('title'));
        }
        abort(403, "Don't have permission to access.");
    }

    public function getStatusList(Request $request)
    {
        $data = \App\Models\ELSProductSubStatus::whereNull('deleted_at');

        $where_like = false;
        $columnsArr = ['els_product_sub_status.name'];
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
        $data = $data->selectRaw('els_product_sub_status.id,els_product_sub_status.name,els_product_sub_status.status')->get();
        return response()->json(['status' => true, 'data' => $data, 'recordsTotal' => $totalRecord, 'recordsFiltered' => $totalRecord, 'code' => 200, 'message' => 'Status listed successfully']);
    }

    public function addStatus(Request $request)
    {
        $data = [];
        $title = "Add Status";
        return view('els_product/add_status', compact('title'));
    }

    public function editStatus(Request $request, $id)
    {
        $data = [];
        $title = "Edit Status";
        $data = \App\Models\ELSProductSubStatus::find($id);
        return view('els_product/edit_status', compact('title', 'data'));
    }

    public function saveStatusDetails(Request $request)
    {
        $data = \App\Models\ELSProductSubStatus::updateOrCreate(['id' => $request->id], ['name' => $request->name]);
        return response()->json(['status' => true, 'data' => [], 'code' => 200, 'message' => 'Status successfully saved']);
    }

    public function updateStatusDetails(Request $request)
    {
        $data = \App\Models\ELSProductSubStatus::updateOrCreate(['id' => $request->id], ['status' => $request->status]);
        return response()->json(['status' => true, 'data' => [], 'code' => 200, 'message' => 'Status successfully updated']);
    }

    public function viewSubStatus(Request $request)
    {
        if (\Helper::checkMenuElegible()) {
            $data = [];
            $title = "Status List";
            return view('els_product/product_status_list', compact('title'));
        }
        abort(403, "Don't have permission to access.");
    }

    public function getSubStatusList(Request $request)
    {
        $data = \App\Models\ELSProductStatus::whereNull('deleted_at');

        $where_like = false;
        $columnsArr = ['els_product_status.name'];
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
        $data = $data->selectRaw('els_product_status.id,els_product_status.name,els_product_status.status')->get();
        return response()->json(['status' => true, 'data' => $data, 'recordsTotal' => $totalRecord, 'recordsFiltered' => $totalRecord, 'code' => 200, 'message' => 'Engneer work report listed successfully']);
    }

    public function addSubStatus(Request $request)
    {
        $data = [];
        $title = "Add Status";
        $status = \App\Models\ELSProductSubStatus::whereNull('deleted_at')->where('status', '1')->pluck('name', 'id');
        return view('els_product/add_sub_status', compact('title', 'status'));
    }

    public function editSubStatus(Request $request, $id)
    {
        $data = [];
        $title = "Edit Status";
        $status = \App\Models\ELSProductSubStatus::whereNull('deleted_at')->where('status', '1')->pluck('name', 'id');
        $data = \App\Models\ELSProductStatus::find($id);
        return view('els_product/edit_sub_status', compact('title', 'data', 'status'));
    }

    public function saveSubStatusDetails(Request $request)
    {
        $data = \App\Models\ELSProductStatus::updateOrCreate(['id' => $request->id], ['sub_status_id' => $request->status_id, 'name' => $request->name]);
        return response()->json(['status' => true, 'data' => [], 'code' => 200, 'message' => 'Status successfully saved']);
    }

    public function updateSubStatusDetails(Request $request)
    {
        $data = \App\Models\ELSProductStatus::updateOrCreate(['id' => $request->id], ['status' => $request->status]);
        return response()->json(['status' => true, 'data' => [], 'code' => 200, 'message' => 'Status successfully updated']);
    }

    public function viewProductPrice(Request $request)
    {
        if (\Helper::checkMenuElegible()) {
            $data = [];
            $title = "Status List";

            \DB::enableQueryLog(); // Enable query log

            // Your Eloquent query executed by using get()


            $data = \App\Models\ElsSystemInfoDtailsModel::leftjoin('els_system_allocated_parts_barcode', function ($join) {
                $join->on('els_system_info_details.id', 'els_system_allocated_parts_barcode.els_system_id');
                $join->whereNull('els_system_allocated_parts_barcode.deleted_at');
            })->join('received_parts_barcode_list', function ($join) {
                $join->on('received_parts_barcode_list.id', 'els_system_allocated_parts_barcode.barcode_id');
            })->join('els_system_allocated_engineer', function ($join) {
                $join->on('els_system_info_details.id', 'els_system_allocated_engineer.els_system_id');
                // 			$join->where('els_system_allocated_engineer.status','1');
                $join->whereNull('els_system_allocated_engineer.deleted_at');
            })->join('brand', function ($join) {
                $join->on('els_system_info_details.brand_id', 'brand.id');
            })->join('model', function ($join) {
                $join->on('els_system_info_details.model_id', 'model.id');
            })->join('colour', function ($join) {
                $join->on('els_system_info_details.colour_id', 'colour.id');
            })->join('users', function ($join) {
                $join->on('els_system_allocated_engineer.engineer_id', 'users.id');
            })->join('els_product_status', function ($join) {
                $join->on('els_product_status.id', 'els_system_allocated_engineer.status');
            })->whereNull('els_system_info_details.deleted_at')

                ->groupBy('els_system_info_details.id')
                ->orderBy('els_system_allocated_parts_barcode.id', 'DESC');

            $where_like = false;
            $columnsArr = ['els_system_info_details.barcode', 'els_system_info_details.imei_1', 'els_system_info_details.imei_2', 'users.name', 'brand.bname', 'model.mname', 'colour.name'];
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
            $data = $data->selectRaw('els_system_info_details.id,els_system_info_details.barcode,brand.bname,model.mname,users.name,colour.name as colour_name,received_parts_barcode_list.barcode as barcodes,(select count(id) from els_system_allocated_parts_barcode where els_system_id = els_system_info_details.id) as consumed_part,(select GROUP_CONCAT(part.name) from els_system_allocated_parts_barcode join received_parts_barcode_list on received_parts_barcode_list.id=els_system_allocated_parts_barcode.barcode_id join received_purchase_order_parts_list on received_purchase_order_parts_list.id=received_parts_barcode_list.received_part_id join part on part.id=received_purchase_order_parts_list.part_id where els_system_id = els_system_info_details.id  and received_parts_barcode_list.deleted_at IS NULL  and els_system_allocated_parts_barcode.deleted_at IS NULL)  as part_name,(select GROUP_CONCAT(received_parts_barcode_list.barcode) from els_system_allocated_parts_barcode join received_parts_barcode_list on received_parts_barcode_list.id=els_system_allocated_parts_barcode.barcode_id join received_purchase_order_parts_list on received_purchase_order_parts_list.id=received_parts_barcode_list.received_part_id join part on part.id=received_purchase_order_parts_list.part_id where els_system_id = els_system_info_details.id  and received_parts_barcode_list.deleted_at IS NULL  and els_system_allocated_parts_barcode.deleted_at IS NULL)  as pin_name,
		    (select GROUP_CONCAT(received_purchase_order_parts_list.price) from els_system_allocated_parts_barcode join received_parts_barcode_list on received_parts_barcode_list.id=els_system_allocated_parts_barcode.barcode_id join received_purchase_order_parts_list on received_purchase_order_parts_list.id=received_parts_barcode_list.received_part_id where els_system_id = els_system_info_details.id and received_parts_barcode_list.deleted_at IS NULL and els_system_allocated_parts_barcode.deleted_at IS NULL)  as part_price,

		      (select GROUP_CONCAT(els_system_extra_expence.title) from els_system_extra_expence where els_system_id = `els_system_info_details`.`id`) as extra_expence,round((select sum(amount) from els_system_extra_expence where els_system_id = `els_system_info_details`.`id`),2) as extra_amount,els_system_allocated_engineer.id as allocated_id,round(els_system_info_details.mrp,2) as old_price,round((els_system_info_details.mrp +  IF((select round(SUM(received_purchase_order_parts_list.price),2) from els_system_allocated_parts_barcode join received_parts_barcode_list on received_parts_barcode_list.id=els_system_allocated_parts_barcode.barcode_id join received_purchase_order_parts_list on received_purchase_order_parts_list.id=received_parts_barcode_list.received_part_id where els_system_id = els_system_info_details.id), (select round(SUM(received_purchase_order_parts_list.price),2) from els_system_allocated_parts_barcode join received_parts_barcode_list on received_parts_barcode_list.id=els_system_allocated_parts_barcode.barcode_id join received_purchase_order_parts_list on received_purchase_order_parts_list.id=received_parts_barcode_list.received_part_id where els_system_id = els_system_info_details.id  and received_parts_barcode_list.deleted_at IS NULL AND els_system_allocated_parts_barcode.deleted_at IS NULL), 0) + IF((select sum(amount) from els_system_extra_expence where els_system_id = `els_system_info_details`.`id`), (select sum(amount) from els_system_extra_expence where els_system_id = `els_system_info_details`.`id`), 0)),2) as new_price,els_product_status.name status')->get();


            // dd(\DB::getQueryLog()); // Show results of log


            return view('els_product/final_product_price_list', compact('title', 'data'));
        }
        abort(403, "Don't have permission to access.");
    }

    public function getProductFinalPriceList(Request $request)
    {
        $data = \App\Models\ElsSystemInfoDtailsModel::leftjoin('els_system_allocated_parts_barcode', function ($join) {
            $join->on('els_system_info_details.id', 'els_system_allocated_parts_barcode.els_system_id');


            $join->whereNull('els_system_allocated_parts_barcode.deleted_at');


            // 		})->join('received_parts_barcode_list',function($join){
            // 			$join->on('received_parts_barcode_list.id'             ,'els_system_allocated_parts_barcode.barcode_id');
            // 			$join->whereNull('received_parts_barcode_list.deleted_at');
            // 		})->join('received_purchase_order_parts_list',function($join){
            // 			$join->on('received_purchase_order_parts_list.id'             ,'received_parts_barcode_list.received_part_id');
            // 		})->join('part',function($join){
            // 			$join->on('part.id','received_purchase_order_parts_list.part_id'
            // 			,'els_system_allocated_engineer.els_system_id');
            // 			$join->where('els_system_id','els_system_info_details.id');

        })->join('els_system_allocated_engineer', function ($join) {
            $join->on('els_system_info_details.id', 'els_system_allocated_engineer.els_system_id');
            $join->where('els_system_allocated_engineer.status', '1');
            $join->whereNull('els_system_allocated_engineer.deleted_at');
        })->join('brand', function ($join) {
            $join->on('els_system_info_details.brand_id', 'brand.id');
        })->join('model', function ($join) {
            $join->on('els_system_info_details.model_id', 'model.id');
        })->join('colour', function ($join) {
            $join->on('els_system_info_details.colour_id', 'colour.id');
        })->join('users', function ($join) {
            $join->on('els_system_allocated_engineer.engineer_id', 'users.id');
        })->join('els_product_status', function ($join) {
            $join->on('els_product_status.id', 'els_system_allocated_engineer.status');
        })->whereNull('els_system_info_details.deleted_at')

            ->groupBy('els_system_info_details.id')
            ->orderBy('els_system_allocated_parts_barcode.id', 'DESC');

        $where_like = false;
        $columnsArr = ['els_system_info_details.barcode', 'els_system_info_details.imei_1', 'els_system_info_details.imei_2', 'users.name', 'brand.bname', 'model.mname', 'colour.name'];
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
        $data = $data->selectRaw('els_system_info_details.id,els_system_info_details.barcode,brand.bname,model.mname,users.name,colour.name as colour_name,els_system_allocated_parts_barcode.els_system_id,(select count(id) from els_system_allocated_parts_barcode where els_system_id = els_system_info_details.id) as consumed_part,(select GROUP_CONCAT(part.name) from els_system_allocated_parts_barcode join received_parts_barcode_list on received_parts_barcode_list.id=els_system_allocated_parts_barcode.barcode_id join received_purchase_order_parts_list on received_purchase_order_parts_list.id=received_parts_barcode_list.received_part_id join part on part.id=received_purchase_order_parts_list.part_id where els_system_id = els_system_info_details.id  and received_parts_barcode_list.deleted_at IS NULL)  as part_name,
		    (select GROUP_CONCAT(received_purchase_order_parts_list.price) from els_system_allocated_parts_barcode join received_parts_barcode_list on received_parts_barcode_list.id=els_system_allocated_parts_barcode.barcode_id join received_purchase_order_parts_list on received_purchase_order_parts_list.id=received_parts_barcode_list.received_part_id where els_system_id = els_system_info_details.id and received_parts_barcode_list.deleted_at IS NULL)  as part_price,

		      (select GROUP_CONCAT(els_system_extra_expence.title) from els_system_extra_expence where els_system_id = `els_system_info_details`.`id`) as extra_expence,round((select sum(amount) from els_system_extra_expence where els_system_id = `els_system_info_details`.`id`),2) as extra_amount,els_system_allocated_engineer.id as allocated_id,round(els_system_info_details.mrp,2) as old_price,round((els_system_info_details.mrp +  IF((select round(SUM(received_purchase_order_parts_list.price),2) from els_system_allocated_parts_barcode join received_parts_barcode_list on received_parts_barcode_list.id=els_system_allocated_parts_barcode.barcode_id join received_purchase_order_parts_list on received_purchase_order_parts_list.id=received_parts_barcode_list.received_part_id where els_system_id = els_system_info_details.id), (select round(SUM(received_purchase_order_parts_list.price),2) from els_system_allocated_parts_barcode join received_parts_barcode_list on received_parts_barcode_list.id=els_system_allocated_parts_barcode.barcode_id join received_purchase_order_parts_list on received_purchase_order_parts_list.id=received_parts_barcode_list.received_part_id where els_system_id = els_system_info_details.id  and received_parts_barcode_list.deleted_at IS NULL), 0) + IF((select sum(amount) from els_system_extra_expence where els_system_id = `els_system_info_details`.`id`), (select sum(amount) from els_system_extra_expence where els_system_id = `els_system_info_details`.`id`), 0)),2) as new_price,els_product_status.name status')->get();
        return response()->json(['status' => true, 'data' => $data, 'recordsTotal' => $totalRecord, 'recordsFiltered' => $totalRecord, 'code' => 200, 'message' => 'Part consumed product listed successfully']);
    }

    public function addMoreExpence(Request $request)
    {
        $els_system_id = $request->id;
        $data =  \App\Models\ELSProductExtraExpence::whereNull('deleted_at')->where('els_system_id', $els_system_id)->get();
        $html = view('els_product/add_more_extra_expence', compact('data', 'els_system_id'))->render();
        return response()->json(['status' => true, 'data' => [], 'html' => \Helper::compressHtml($html), 'code' => 200, 'message' => 'Add More Expence']);
    }

    public function addMoreExpenceOption(Request $request)
    {
        $html = view('els_product/add_more_expence_option')->render();
        return response()->json(['status' => true, 'data' => [], 'html' => \Helper::compressHtml($html), 'code' => 200, 'message' => 'Add More Expence Option']);
    }

    public function saveExtraExpence(Request $request)
    {
        \App\Models\ELSProductExtraExpence::whereNotIn('id', $request->id)->where('els_system_id', $request->els_system_id)->delete();
        foreach ($request->amount as $key => $amount) {
            if ($amount) {
                $id = null;
                $addData['amount'] = $amount;
                $addData['title'] = $request->title[$key];
                $addData['els_system_id'] = $request->els_system_id;
                if (isset($request->id[$key])) {
                    $id = $request->id[$key];
                }
                $save = \App\Models\ELSProductExtraExpence::updateOrCreate(['id' => $id], $addData);
            }
        }
        return response()->json(['status' => true, 'data' => [], 'code' => 200, 'message' => 'Extra amount successfully added']);
    }

    public function uploadBulkDevicePrice(Request $request)
    {
        set_time_limit(0);
        $file = $request->file('file_csv');
        if (in_array(strtoupper($file->getClientOriginalExtension()), ['CSV'])) {
            $fileD = fopen($file, "r");
            $column = fgetcsv($fileD);
            if ($column) {
                foreach ($column as $k => $d) {
                    if ($d == 'UIN') {
                        $barcode_key = $k;
                    }
                    if ($d == 'Cost Price') {
                        $price_key = $k;
                    }
                }
            }
            $i = 2;
            $error = false;
            $massage = '';
            $dataArr = [];
            while (!feof($fileD)) {
                $rowData = fgetcsv($fileD);
                if (isset($rowData[$barcode_key])) {
                    \App\Models\ElsSystemInfoDtailsModel::where('barcode', $rowData[$barcode_key])->update(['mrp' => $rowData[$price_key]]);
                }
            }
        }
        return redirect()->back();
    }

    public function downloadProductFinalPrice(Request $request)
    {
        header("Content-Type: application/xls");
        header("Content-Disposition: attachment; filename=ProductPriceExport.xls");
        header("Pragma: no-cache");
        header("Expires: 0");

        $columnHeader = '';
        $columnHeader = "Sr NO" . "\t" . "UIN" . "\t" . "PIN" . "\t" . "Brand" . "\t" . "Model" . "\t" . "Colour" . "\t" . "Assignd Engineer" . "\t" . "Part Consumed" . "\t" . "Part Cost" . "\t" . "Extra Cost" . "\t" . "Exrta Amount" . "\t" . "Device Cost" . "\t" . "Total Cost" . "\t" . "Status" . "\t";



        // 			$data = \App\Models\ElsSystemInfoDtailsModel::leftjoin('els_system_allocated_parts_barcode',function($join){
        // 			$join->on('els_system_info_details.id','els_system_allocated_parts_barcode.els_system_id');
        // 			$join->whereNull('els_system_allocated_parts_barcode.deleted_at');
        // 		})->join('els_system_allocated_engineer',function($join){
        // 			$join->on('els_system_info_details.id'             ,'els_system_allocated_engineer.els_system_id');
        // // 			$join->where('els_system_allocated_engineer.status','1');
        // // 			$join->whereNull('els_system_allocated_engineer.deleted_at');
        // 		})->join('brand',function($join){
        // 			$join->on('els_system_info_details.brand_id','brand.id');
        // 		})->join('model',function($join){
        // 			$join->on('els_system_info_details.model_id','model.id');
        // 		})->join('colour',function($join){
        // 			$join->on('els_system_info_details.colour_id','colour.id');
        // 		})->join('users',function($join){
        // 			$join->on('els_system_allocated_engineer.engineer_id','users.id');
        // 		})->join('els_product_status',function($join){
        // 			$join->on('els_product_status.id','els_system_allocated_engineer.status');
        // 		})->whereNull('els_system_info_details.deleted_at')
        // 		//->where('els_system_allocated_parts_barcode.status','1')
        // 		->groupBy('els_system_info_details.id')->orderBy('els_system_allocated_parts_barcode.id','DESC');




        // 		$data = \App\Models\ElsSystemInfoDtailsModel::leftjoin('els_system_allocated_parts_barcode',function($join){
        // 			$join->on('els_system_info_details.id','els_system_allocated_parts_barcode.els_system_id');
        // 			$join->whereNull('els_system_info_details.deleted_at');
        // 		})->join('els_system_allocated_engineer',function($join){
        // 			$join->on('els_system_info_details.id','els_system_allocated_engineer.els_system_id');
        // // 			$join->where('els_system_allocated_engineer.status','1');
        // 			$join->whereNull('els_system_allocated_engineer.deleted_at');
        // 		})->join('brand',function($join){
        // 			$join->on('els_system_info_details.brand_id','brand.id');
        // 		})->join('model',function($join){
        // 			$join->on('els_system_info_details.model_id','model.id');
        // 		})->join('colour',function($join){
        // 			$join->on('els_system_info_details.colour_id','colour.id');
        // 		})->join('users',function($join){
        // 			$join->on('els_system_allocated_engineer.engineer_id','users.id');
        // 		})->join('els_product_status',function($join){
        // 			$join->on('els_product_status.id','els_system_allocated_engineer.status');
        // 		})->whereNull('els_system_allocated_parts_barcode.deleted_at')
        // // 		->where('els_system_allocated_parts_barcode.status','1')
        // 		->groupBy('els_system_info_details.id')->orderBy('els_system_allocated_parts_barcode.id','DESC');

        // 		$data = $data->selectRaw('els_system_info_details.id,els_system_info_details.barcode,brand.bname,model.mname,users.name,colour.name as colour_name,(select count(id) from els_system_allocated_parts_barcode where els_system_id = els_system_info_details.id) as consumed_part,(select GROUP_CONCAT(part.name) from els_system_allocated_parts_barcode join received_parts_barcode_list on received_parts_barcode_list.id=els_system_allocated_parts_barcode.barcode_id join received_purchase_order_parts_list on received_purchase_order_parts_list.id=received_parts_barcode_list.received_part_id join part on part.id=received_purchase_order_parts_list.part_id where els_system_id = els_system_info_details.id  and received_parts_barcode_list.deleted_at IS NULL) as part_name,(select GROUP_CONCAT(received_purchase_order_parts_list.price) from els_system_allocated_parts_barcode join received_parts_barcode_list on received_parts_barcode_list.id=els_system_allocated_parts_barcode.barcode_id join received_purchase_order_parts_list on received_purchase_order_parts_list.id=received_parts_barcode_list.received_part_id where els_system_id = els_system_info_details.id  and received_parts_barcode_list.deleted_at IS NULL) as part_price,(select GROUP_CONCAT(els_system_extra_expence.title) from els_system_extra_expence where els_system_id = `els_system_info_details`.`id`) as extra_expence,round((select sum(amount) from els_system_extra_expence where els_system_id = `els_system_info_details`.`id`),2) as extra_amount,els_system_allocated_engineer.id as allocated_id,round(els_system_info_details.mrp,2) as old_price,round((els_system_info_details.mrp +  IF((select round(SUM(received_purchase_order_parts_list.price),2) from els_system_allocated_parts_barcode join received_parts_barcode_list on received_parts_barcode_list.id=els_system_allocated_parts_barcode.barcode_id join received_purchase_order_parts_list on received_purchase_order_parts_list.id=received_parts_barcode_list.received_part_id where els_system_id = els_system_info_details.id), (select round(SUM(received_purchase_order_parts_list.price),2) from els_system_allocated_parts_barcode join received_parts_barcode_list on received_parts_barcode_list.id=els_system_allocated_parts_barcode.barcode_id join received_purchase_order_parts_list on received_purchase_order_parts_list.id=received_parts_barcode_list.received_part_id where els_system_id = els_system_info_details.id), 0) + IF((select sum(amount) from els_system_extra_expence where els_system_id = `els_system_info_details`.`id`), (select sum(amount) from els_system_extra_expence where els_system_id = `els_system_info_details`.`id`), 0)),2) as new_price,els_product_status.name status')->get();





        $data = \App\Models\ElsSystemInfoDtailsModel::leftjoin('els_system_allocated_parts_barcode', function ($join) {
            $join->on('els_system_info_details.id', 'els_system_allocated_parts_barcode.els_system_id');
            $join->whereNull('els_system_allocated_parts_barcode.deleted_at');
        })->join('received_parts_barcode_list', function ($join) {
            $join->on('received_parts_barcode_list.id', 'els_system_allocated_parts_barcode.barcode_id');
        })->join('els_system_allocated_engineer', function ($join) {
            $join->on('els_system_info_details.id', 'els_system_allocated_engineer.els_system_id');
            // 			$join->where('els_system_allocated_engineer.status','1');
            $join->whereNull('els_system_allocated_engineer.deleted_at');
        })->join('brand', function ($join) {
            $join->on('els_system_info_details.brand_id', 'brand.id');
        })->join('model', function ($join) {
            $join->on('els_system_info_details.model_id', 'model.id');
        })->join('colour', function ($join) {
            $join->on('els_system_info_details.colour_id', 'colour.id');
        })->join('users', function ($join) {
            $join->on('els_system_allocated_engineer.engineer_id', 'users.id');
        })->join('els_product_status', function ($join) {
            $join->on('els_product_status.id', 'els_system_allocated_engineer.status');
        })->whereNull('els_system_info_details.deleted_at')

            ->groupBy('els_system_info_details.id')
            ->orderBy('els_system_allocated_parts_barcode.id', 'DESC');

        // 		$where_like = false;
        // 		$columnsArr = ['els_system_info_details.barcode','els_system_info_details.imei_1','els_system_info_details.imei_2','users.name','brand.bname','model.mname','colour.name'];
        // 		foreach($columnsArr as $columns){
        // 			if($request->get('search')['value']){
        // 				if($where_like){
        // 					$where_like.= ' OR '.$columns.' like "%'.$request->get('search')['value'].'%"';
        // 				}else{
        // 					$where_like.= $columns.' like "%'.$request->get('search')['value'].'%"';
        // 				}
        // 			}
        // 		}
        // 		if($where_like){
        // 			$data = $data->whereRaw('('.$where_like.')');
        // 		}

        // 		$totalRecord = count($data->get());
        // 		if($request->get('length')){
        // 			$data = $data->skip($request->get('start'))->take($request->get('length'));
        // 		}
        $data = $data->selectRaw('els_system_info_details.id,els_system_info_details.barcode,brand.bname,model.mname,users.name,colour.name as colour_name,received_parts_barcode_list.barcode as barcodes,(select count(id) from els_system_allocated_parts_barcode where els_system_id = els_system_info_details.id) as consumed_part,(select GROUP_CONCAT(part.name) from els_system_allocated_parts_barcode join received_parts_barcode_list on received_parts_barcode_list.id=els_system_allocated_parts_barcode.barcode_id join received_purchase_order_parts_list on received_purchase_order_parts_list.id=received_parts_barcode_list.received_part_id join part on part.id=received_purchase_order_parts_list.part_id where els_system_id = els_system_info_details.id  and received_parts_barcode_list.deleted_at IS NULL  and els_system_allocated_parts_barcode.deleted_at IS NULL)  as part_name,(select GROUP_CONCAT(received_parts_barcode_list.barcode) from els_system_allocated_parts_barcode join received_parts_barcode_list on received_parts_barcode_list.id=els_system_allocated_parts_barcode.barcode_id join received_purchase_order_parts_list on received_purchase_order_parts_list.id=received_parts_barcode_list.received_part_id join part on part.id=received_purchase_order_parts_list.part_id where els_system_id = els_system_info_details.id  and received_parts_barcode_list.deleted_at IS NULL  and els_system_allocated_parts_barcode.deleted_at IS NULL)  as pin_name,
		    (select GROUP_CONCAT(received_purchase_order_parts_list.price) from els_system_allocated_parts_barcode join received_parts_barcode_list on received_parts_barcode_list.id=els_system_allocated_parts_barcode.barcode_id join received_purchase_order_parts_list on received_purchase_order_parts_list.id=received_parts_barcode_list.received_part_id where els_system_id = els_system_info_details.id and received_parts_barcode_list.deleted_at IS NULL and els_system_allocated_parts_barcode.deleted_at IS NULL)  as part_price,

		      (select GROUP_CONCAT(els_system_extra_expence.title) from els_system_extra_expence where els_system_id = `els_system_info_details`.`id`) as extra_expence,round((select sum(amount) from els_system_extra_expence where els_system_id = `els_system_info_details`.`id`),2) as extra_amount,els_system_allocated_engineer.id as allocated_id,round(els_system_info_details.mrp,2) as old_price,round((els_system_info_details.mrp +  IF((select round(SUM(received_purchase_order_parts_list.price),2) from els_system_allocated_parts_barcode join received_parts_barcode_list on received_parts_barcode_list.id=els_system_allocated_parts_barcode.barcode_id join received_purchase_order_parts_list on received_purchase_order_parts_list.id=received_parts_barcode_list.received_part_id where els_system_id = els_system_info_details.id), (select round(SUM(received_purchase_order_parts_list.price),2) from els_system_allocated_parts_barcode join received_parts_barcode_list on received_parts_barcode_list.id=els_system_allocated_parts_barcode.barcode_id join received_purchase_order_parts_list on received_purchase_order_parts_list.id=received_parts_barcode_list.received_part_id where els_system_id = els_system_info_details.id  and received_parts_barcode_list.deleted_at IS NULL AND els_system_allocated_parts_barcode.deleted_at IS NULL), 0) + IF((select sum(amount) from els_system_extra_expence where els_system_id = `els_system_info_details`.`id`), (select sum(amount) from els_system_extra_expence where els_system_id = `els_system_info_details`.`id`), 0)),2) as new_price,els_product_status.name status')->get();




        $i = 1;
        $setData = '';
        if (!$data->isEmpty()) {
            foreach ($data as $value) {
                $part_name = explode(',', $value->part_name);
                $part_price = explode(',', $value->part_price);
                $part_price = explode(',', $value->part_price);
                $pin_name = explode(',', $value->pin_name);

                for ($ff = 0; $ff < count($part_name); $ff++) {

                    $rowData = '';
                    $rowData .= '"' . $i . '"' . "\t";
                    $rowData .= '"' . $value->barcode . '"' . "\t";
                    $rowData .= '"' . $pin_name[$ff] . '"' . "\t";

                    $rowData .= '"' . $value->bname . '"' . "\t";
                    $rowData .= '"' . $value->mname . '"' . "\t";
                    $rowData .= '"' . $value->colour_name . '"' . "\t";
                    $rowData .= '"' . $value->name . '"' . "\t";
                    $rowData .= '"' . $part_name[$ff] . '"' . "\t";
                    $rowData .= '"' . $part_price[$ff] . '"' . "\t";
                    $rowData .= '"' . $value->extra_expence . '"' . "\t";
                    $rowData .= '"' . $value->extra_amount . '"' . "\t";
                    $rowData .= '"' . $value->old_price . '"' . "\t";
                    $rowData .= '"' . $value->new_price . '"' . "\t";
                    $rowData .= '"' . $value->status . '"' . "\t";
                    $setData .= trim($rowData) . "\n";
                    $i++;
                }
            }
        }
        echo ucwords($columnHeader) . "\n" . $setData . "\n";
    }


    public function viewProductWarranty(Request $request)
    {
        if (\Helper::checkMenuElegible()) {
            $title = "Product Warranty List";
            $data = \App\Models\ELSProductWarranty::join('els_system_info_details', function ($join) {
                $join->on('els_product_warranty.els_system_id', 'els_system_info_details.id');
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
            })->whereNull('els_product_warranty.deleted_at')->groupBy('els_system_info_details.id')->orderBy('els_product_warranty.id', 'DESC')->select('els_product_warranty.*', 'els_system_info_details.barcode', 'els_system_info_details.imei_1', 'els_system_info_details.imei_2', 'brand.bname', 'model.mname', 'colour.name as colour_name', 'ram.name as ram', 'rom.name as rom', 'grade.name as grade')->get();
            return view('els_product/product_warranty_list', compact('title', 'data'));
        }
        abort(403, "Don't have permission to access.");
    }

    public function addProductWarranty(Request $request)
    {
        $data = [];
        $title = "Add Product Warranty";
        return view('els_product/product_warranty_form', compact('title', 'data'));
    }

    public function editProductWarranty(Request $request, $id)
    {
        $title = "Edit Product Warranty";
        $data = \App\Models\ELSProductWarranty::join('els_system_info_details', function ($join) {
            $join->on('els_product_warranty.els_system_id', 'els_system_info_details.id');
        })->where('els_product_warranty.id', $id)->select('els_product_warranty.*', 'els_system_info_details.barcode')->first();
        return view('els_product/product_warranty_form', compact('title', 'data'));
    }

    public function saveWarrantyDetails(Request $request)
    {

        if ($request->start_date == '') {
            $start = NULL;
        } else {
            $start = date('Y-m-d', strtotime($request->start_date));
        }



        // echo date('Y-m-d',strtotime($request->start_date));
        //  print_r($request->start_date);die('hi');
        if ($start == NULL) {
            $end_date = NULL;
        } else {
            $end_date = date('Y-m-d', strtotime('+' . $request->duration . ' ' . $request->type, strtotime($request->start_date)));
        }


        // echo $request->start_date;
        // echo $start; die('hii22');
        $save = \App\Models\ELSProductWarranty::updateOrCreate([
            'id' => $request->id,
        ], [
            'els_system_id' => $request->barcode_id,
            'duration' => $request->duration,
            'type' => $request->type,
            'start_date' => $start,
            'end_date' => $end_date,
            'remark' => $request->remark,
        ]);
        return redirect()->route('manage-warranty');
    }

    public function deleteWarrantyDetails(Request $request)
    {
        \App\Models\ELSProductWarranty::where('id', $request->id)->delete();
        $arr = array(
            "code" => 200,
            "message" => "Delete Successfully",
            "data" => ''
        );
        return \Response::json($arr);
    }

    public function downloadWarrantyReport(Request $request)
    {
        header("Content-Type: application/xls");
        header("Content-Disposition: attachment; filename=ProductWarrantyReport.xls");
        header("Pragma: no-cache");
        header("Expires: 0");

        $columnHeader = "Sr NO" . "\t" . "UIN" . "\t" . "Brand" . "\t" . "Model" . "\t" . "Colour" . "\t" . "IMEI 1" . "\t" . "IMEI 2" . "\t" . "RAM" . "\t" . "ROM" . "\t" . "Grade" . "\t" . "Start Date" . "\t" . "Expiry Date" . "\t" . "Left Duration" . "\t" . "Remark" . "\t";
        $data = \App\Models\ELSProductWarranty::join('els_system_info_details', function ($join) {
            $join->on('els_product_warranty.els_system_id', 'els_system_info_details.id');
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
        })->whereNull('els_product_warranty.deleted_at')->select('els_product_warranty.*', 'els_system_info_details.barcode', 'els_system_info_details.imei_1', 'els_system_info_details.imei_2', 'brand.bname', 'model.mname', 'colour.name as colour_name', 'ram.name as ram', 'rom.name as rom', 'grade.name as grade')->get();
        $i = 1;
        $setData = '';
        if (!$data->isEmpty()) {
            foreach ($data as $value) {
                $left_time = '';
                $date1 = date_create(date('Y-m-d'));
                $date2 = date_create($value->end_date);
                $diff = date_diff($date1, $date2);
                if ($diff->y) {
                    if ($diff->y == 1) {
                        $left_time .= $diff->y . ' Year ';
                    } else {
                        $left_time .= $diff->y . ' Years ';
                    }
                }
                if ($diff->m) {
                    if ($diff->m == 1) {
                        $left_time .= $diff->m . ' Month ';
                    } else {
                        $left_time .= $diff->m . ' Months ';
                    }
                }
                if ($diff->d) {
                    if ($diff->d == 1) {
                        $left_time .= $diff->d . ' Day ';
                    } else {
                        $left_time .= $diff->d . ' Days ';
                    }
                }
                $rowData = '';
                $rowData .= '"' . $i . '"' . "\t";
                $rowData .= '"' . $value->barcode . '"' . "\t";
                $rowData .= '"' . $value->bname . '"' . "\t";
                $rowData .= '"' . $value->mname . '"' . "\t";
                $rowData .= '"' . $value->colour_name . '"' . "\t";
                $rowData .= '"' . $value->imei_1 . '"' . "\t";
                $rowData .= '"' . $value->imei_2 . '"' . "\t";
                $rowData .= '"' . $value->ram . '"' . "\t";
                $rowData .= '"' . $value->rom . '"' . "\t";
                $rowData .= '"' . $value->grade . '"' . "\t";
                $rowData .= '"' . date('m/d/Y', strtotime($value->start_date)) . '"' . "\t";
                $rowData .= '"' . date('m/d/Y', strtotime($value->end_date)) . '"' . "\t";
                $rowData .= '"' . $left_time . '"' . "\t";
                $rowData .= '"' . $value->remark . '"' . "\t";
                $setData .= trim($rowData) . "\n";
                $i++;
            }
        }
        echo ucwords($columnHeader) . "\n" . $setData . "\n";
    }

    public function uploadBulkWarranty(Request $request)
    {
        set_time_limit(0);
        $file = $request->file('file_csv');
        if (in_array(strtoupper($file->getClientOriginalExtension()), ['CSV'])) {
            $fileD = fopen($file, "r");
            $column = fgetcsv($fileD);
            if ($column) {
                foreach ($column as $k => $d) {
                    if ($d == 'UIN') {
                        $barcode_key = $k;
                    }
                    if ($d == 'Start Date') {
                        $start_date_key = $k;
                    }
                    if ($d == 'Warranty Dutation') {
                        $warranty_key = $k;
                    }
                    if ($d == 'Remark') {
                        $remark_key = $k;
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
                    $system = \App\Models\ElsSystemInfoDtailsModel::where('barcode', $rowData[$barcode_key])->first();
                    if ($system) {
                        $row['els_system_id'] = $system->id;
                    } else {
                        $msg .= 'Barcode,';
                        $error = true;
                    }
                }
                if (trim($rowData[$start_date_key]) != '') {
                    $row['start_date'] = date('Y-m-d', strtotime($rowData[$start_date_key]));
                } else {
                    $row['start_date'] = null;
                }
                if (isset($rowData[$warranty_key]) && trim($rowData[$start_date_key]) == '') {
                    list($duration, $type) = explode(' ', $rowData[$warranty_key]);
                    $type = strtolower(preg_replace('/\s+/', '', $type));
                    if (in_array($type, ['Year', 'year', 'years'])) {
                        $type = 'year';
                    }
                    if (in_array($type, ['month', 'Month', 'months'])) {
                        $type = 'month';
                    }
                    if (in_array($type, ['Day', 'day', 'days'])) {
                        $type = 'day';
                    }
                    $row['duration'] = preg_replace('/\s+/', '', $duration);
                    $row['type'] = $type;
                } else {
                    $row['duration'] = preg_replace('/\s+/', '', @$duration);
                    $row['type'] = @$type;
                    $end_date = null;
                }
                if (isset($rowData[$remark_key])) {
                    $row['remark'] = $rowData[$remark_key];
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
                    if ($end_date != null) {
                        $end_date = date('Y-m-d', strtotime('+' . $d->duration . ' ' . $d->type, strtotime($d->start_date)));
                    }
                    $addData['start_date'] = $d->start_date;
                    $addData['duration'] = $d->duration;
                    $addData['type'] = $d->type;
                    $addData['end_date'] = $end_date;
                    $addData['remark'] = $d->remark;
                    $addData['created_by'] = Auth::user()->id;
                    $save = \App\Models\ELSProductWarranty::updateOrCreate(['els_system_id' => $d->els_system_id], $addData);
                }
            }
            return response()->json(['status' => true, 'data' => [], 'code' => 200, 'message' => 'Product Warranty successfully imported.']);
        }
        return response()->json(['status' => false, 'data' => [], 'code' => 401, 'message' => 'Please choose a correct formate.']);
    }

    public function qualityCheckProductList(Request $request)
    {
        if (\Helper::checkMenuElegible()) {
            $data = [];
            $title = "Product Quality Check List";
            $status = \App\Models\QcStatus::whereNull('deleted_at')->where('status', '1')->pluck('name', 'id');
            return view('els_product/quality_check_product_list', compact('title', 'status'));
        }
        abort(403, "Don't have permission to access.");
    }

    public function getQualityCheckProductList(Request $request)
    {
        $data = \App\Models\ElsSystemInfoDtailsModel::join('els_system_allocated_qc_engineer', function ($join) {
            $join->on('els_system_info_details.id', 'els_system_allocated_qc_engineer.els_system_id');
            $join->where('els_system_allocated_qc_engineer.active', '1');
            $join->whereNull('els_system_allocated_qc_engineer.deleted_at');
        })->join('brand', function ($join) {
            $join->on('els_system_info_details.brand_id', 'brand.id');
        })->join('model', function ($join) {
            $join->on('els_system_info_details.model_id', 'model.id');
        })->join('colour', function ($join) {
            $join->on('els_system_info_details.colour_id', 'colour.id');
        })->join('users', function ($join) {
            $join->on('els_system_allocated_qc_engineer.engineer_id', 'users.id');
        })->leftjoin('els_system_allocated_parts_barcode', function ($join) {
            $join->on('els_system_info_details.id', 'els_system_allocated_parts_barcode.els_system_id');
            $join->where('els_system_allocated_parts_barcode.status', '1');
            $join->whereNull('els_system_allocated_parts_barcode.deleted_at');
        })->leftjoin('quality_check_status', function ($join) {
            $join->on('quality_check_status.id', 'els_system_allocated_qc_engineer.status');
        })->whereNull('els_system_info_details.deleted_at')->groupBy('els_system_info_details.id');

        $where_like = false;
        $columnsArr = ['els_system_info_details.barcode', 'els_system_info_details.imei_1', 'els_system_info_details.imei_2', 'users.name', 'brand.bname', 'model.mname', 'colour.name'];
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
        $data = $data->selectRaw('els_system_info_details.id,els_system_info_details.barcode,els_system_info_details.imei_1,els_system_info_details.imei_2,brand.bname,model.mname,users.name,colour.name as colour_name,count(distinct els_system_allocated_parts_barcode.id) as consumed_part,els_system_allocated_qc_engineer.id as allocated_id,els_system_allocated_qc_engineer.status as status_id,els_system_allocated_qc_engineer.remark,quality_check_status.name status')->get();
        return response()->json(['status' => true, 'data' => $data, 'recordsTotal' => $totalRecord, 'recordsFiltered' => $totalRecord, 'code' => 200, 'message' => 'Quality check product listed successfully']);
    }

    public function allocateCheckProduct(Request $request)
    {
        $data = [];
        $title = "Allocated Quality Check";
        return view('els_product/allocated_check_product', compact('title'));
    }

    public function getELSProductDetails(Request $request)
    {
        $data = \App\Models\ElsSystemInfoDtailsModel::join('brand', function ($join) {
            $join->on('els_system_info_details.brand_id', 'brand.id');
        })->join('model', function ($join) {
            $join->on('els_system_info_details.model_id', 'model.id');
        })->join('colour', function ($join) {
            $join->on('els_system_info_details.colour_id', 'colour.id');
        })->leftjoin('els_system_allocated_qc_engineer', function ($join) {
            $join->on('els_system_info_details.id', 'els_system_allocated_qc_engineer.els_system_id');
            $join->where('els_system_allocated_qc_engineer.active', '1');
            $join->whereNull('els_system_allocated_qc_engineer.deleted_at');
        })->where(['barcode' => $request->barcode])->select('els_system_info_details.*', 'brand.bname', 'model.mname', 'colour.name as colour_name', 'els_system_allocated_qc_engineer.engineer_id')->first();
        if ($data) {

            $details_html = view('els_product/barcode_product_details', compact('data'))->render();
            $engineer = \App\Models\User::join('role', function ($join) {
                $join->on('users.role', 'role.id');
            })->where('role.role_name', 'engineer')->pluck('users.name', 'users.id');
            $product_parts_html = view('els_product/els_product_qc_check_form', compact('data', 'engineer'))->render();
            return response()->json(['status' => true, 'data' => [], 'details_html' => \Helper::compressHtml($details_html), 'product_parts_html' => \Helper::compressHtml($product_parts_html), 'code' => 200, 'message' => 'Order request list']);
        }
        return response()->json(['status' => false, 'data' => [], 'code' => 401, 'message' => 'No Data Found.']);
    }

    public function assignProductForChecking(Request $request)
    {
        \App\Models\AssignQcEngineer::where('els_system_id', $request->els_system_id)->update(['active' => '0']);
        \App\Models\AssignQcEngineer::updateOrCreate([
            'els_system_id' => $request->els_system_id,
            'engineer_id' => $request->engineer_id,
        ], ['active' => '1']);
        return redirect()->route('quality-check-product-list');
    }

    public function saveQcStatus(Request $request)
    {
        $save = \App\Models\AssignQcEngineer::updateOrCreate([
            'id' => $request->id,
        ], [
            'status' => $request->status,
            'remark' => $request->remark,
        ]);
        return response()->json(['status' => true, 'data' => [], 'code' => 200, 'message' => 'Status update successfully']);
    }


    public function viewQcStatus(Request $request)
    {
        if (\Helper::checkMenuElegible()) {
            $data = [];
            $title = "Status List";
            return view('els_product/qc_status_list', compact('title'));
        }
        abort(403, "Don't have permission to access.");
    }

    public function getQcStatusList(Request $request)
    {
        $data = \App\Models\QcStatus::whereNull('deleted_at');

        $where_like = false;
        $columnsArr = ['quality_check_status.name'];
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
        $data = $data->selectRaw('quality_check_status.id,quality_check_status.name,quality_check_status.status')->get();
        return response()->json(['status' => true, 'data' => $data, 'recordsTotal' => $totalRecord, 'recordsFiltered' => $totalRecord, 'code' => 200, 'message' => 'QC Status listed successfully']);
    }

    public function addQcStatus(Request $request)
    {
        $data = [];
        $title = "Add Status";
        return view('els_product/qc_status_form', compact('title'));
    }

    public function editQcStatus(Request $request, $id)
    {
        $data = [];
        $title = "Edit Status";
        $data = \App\Models\QcStatus::find($id);
        return view('els_product/qc_status_form', compact('title', 'data'));
    }

    public function saveQcStatusDetails(Request $request)
    {
        $data = \App\Models\QcStatus::updateOrCreate(['id' => $request->id], ['name' => $request->name]);
        return response()->json(['status' => true, 'data' => [], 'code' => 200, 'message' => 'Status successfully saved']);
    }

    public function updateQcStatusDetails(Request $request)
    {
        $data = \App\Models\QcStatus::updateOrCreate(['id' => $request->id], ['status' => $request->status]);
        return response()->json(['status' => true, 'data' => [], 'code' => 200, 'message' => 'Status successfully updated']);
    }

    public function qualityCheckReport(Request $request)
    {
        if (\Helper::checkMenuElegible()) {
            $title = "Product Quality Check Report List";
            $data = \App\Models\User::join('role', function ($join) {
                $join->on('role.id', 'users.role');
                $join->whereNull('role.deleted_at');
            })->leftjoin('els_system_allocated_qc_engineer', function ($join) {
                $join->on('els_system_allocated_qc_engineer.engineer_id', 'users.id');
                $join->whereNull('els_system_allocated_qc_engineer.deleted_at');
            })->where('role.role_name', 'engineer')->groupBy('users.id')->orderBy('users.name', 'ASC')->select('users.*')->get();

            $status = \App\Models\QcStatus::whereNull('deleted_at')->where('status', '1')->get();
            return view('els_product/product_quality_check_report', compact('title', 'status', 'data'));
        }
        abort(403, "Don't have permission to access.");
    }

    public function downloadQualityCheckReport(Request $request)
    {
        header("Content-Type: application/xls");
        header("Content-Disposition: attachment; filename=ProductQualityCheckReport.xls");
        header("Pragma: no-cache");
        header("Expires: 0");
        $status = \App\Models\QcStatus::whereNull('deleted_at')->where('status', '1')->get();

        $columnHeader = "Sr NO" . "\t";
        $columnHeader .= "Engneer Name" . "\t";
        foreach ($status as $s) {
            $columnHeader .= $s->name . "\t";
        }
        $columnHeader .= "Total issued" . "\t";
        $columnHeader .= "Target" . "\t";
        $columnHeader .= "Variance" . "\t";


        $data = \App\Models\User::join('role', function ($join) {
            $join->on('role.id', 'users.role');
            $join->whereNull('role.deleted_at');
        })->leftjoin('els_system_allocated_qc_engineer', function ($join) {
            $join->on('els_system_allocated_qc_engineer.engineer_id', 'users.id');
            $join->whereNull('els_system_allocated_qc_engineer.deleted_at');
        })->where('role.role_name', 'engineer')->groupBy('users.id')->orderBy('users.name', 'ASC')->select('users.*')->get();

        if (session()->get('start_date') && session()->get('end_date')) {
            $start_date = session()->get('start_date');
            $end_date = date('Y-m-d', strtotime('+1 day', strtotime(session()->get('end_date'))));
        } else {
            $start_date = date('Y-m-d', strtotime('-29 day'));
            $end_date = date('Y-m-d', strtotime('+1 day'));
        }
        $date1 = date_create($end_date);
        $date2 = date_create($start_date);
        $diff = date_diff($date1, $date2);
        $days = $diff->days;

        $i = 1;
        $setData = '';
        if (!$data->isEmpty()) {
            foreach ($data as $value) {
                $total = $variance = 0;
                $rowData = '';
                $rowData .= '"' . $i . '"' . "\t";
                $rowData .= '"' . $value->name . '"' . "\t";
                foreach ($status as $v) {
                    $count = \Helper::getAssignedQcSystemCount($value->id, $v->id);
                    $rowData .= '"' . $count . '"' . "\t";
                    if (!in_array($v->id, [1])) {
                        $total += $count;
                    }
                    $target = $days * $value->target;
                    $variance = $total - $target;
                }

                $rowData .= '"' . $total . '"' . "\t";
                $rowData .= '"' . $target . '"' . "\t";
                $rowData .= '"' . $variance . '"' . "\t";
                $setData .= trim($rowData) . "\n";
                $i++;
            }
        }
        echo ucwords($columnHeader) . "\n" . $setData . "\n";
    }

    public function stockOutProductList(Request $request)
    {
        if (\Helper::checkMenuElegible()) {
            $title = "ELS Product List";

            $data = \App\Models\ElsSystemInfoDtailsModel::join('brand', function ($join) {
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
                $join->whereNull('els_product_status.deleted_at');
            })->join('els_product_sub_status', function ($join) {
                $join->on('els_system_info_details.status', 'els_product_sub_status.id');
                $join->whereNull('els_product_sub_status.deleted_at');
            })->leftjoin('els_system_status_log', function ($join) {
                $join->on('els_system_allocated_engineer.id', 'els_system_status_log.els_system_allocation_id');
                $join->on('els_system_info_details.status', 'els_system_status_log.status');
                $join->on('els_system_allocated_engineer.els_system_id', 'els_system_status_log.els_system_id');
                $join->whereNull('els_system_status_log.deleted_at');
            })->leftjoin('vendor', 'vendor.id', '=', 'els_system_info_details.vendor_id')
                ->leftjoin('vendor as out', 'out.id', '=', 'els_system_status_log.vendor_id')
                ->whereNull('els_system_info_details.deleted_at')->where('els_system_info_details.status', 2)->orderBy('els_system_info_details.resived_date', 'DESC')->groupBy('els_system_info_details.id');

            $data = $data->selectRaw('els_system_info_details.id,els_system_info_details.resived_date,els_system_info_details.grn_no,ram.name as ram,rom.name as rom,grade.name as grade,els_system_info_details.remark,els_system_info_details.barcode,els_system_info_details.mrp,els_system_info_details.imei_1,els_system_info_details.imei_2,brand.bname,model.mname,vendor.vname,colour.name as colour_name,els_product_status.name as status,els_product_sub_status.name as sub_status,els_system_status_log.created_at as status_date,out.vname as out_vendor')->get();

            return view('els_product.els_stock_out_product_list', compact('title', 'data'));
        }
        abort(403, "Don't have permission to access.");
    }

    public function stockOutProductReport(Request $request)
    {
        header("Content-Type: application/xls");
        header("Content-Disposition: attachment; filename=StockOutProductReport.xls");
        header("Pragma: no-cache");
        header("Expires: 0");

        $columnHeader = "Sr NO" . "\t" . "Date" . "\t" . "Brand" . "\t" . "Model" . "\t" . "Colour" . "\t" . "UIN" . "\t" . "GRN No" . "\t" . "IMEI 1" . "\t" . "IMEI 2" . "\t" . "RAM" . "\t" . "ROM" . "\t" . "Incoming Grade" . "\t";
        if (\App\Models\RoleModel::find(\Auth::user()->role)->role_name == 'admin') {
            $columnHeader .= "Cost" . "\t";
        }
        $columnHeader .= "Remark" . "\t" . "In Vendor Name" . "\t" . "Status" . "\t" . "Sub Status" . "\t" . "Out Vendor Name" . "\t";

        $data = \App\Models\ElsSystemInfoDtailsModel::join('brand', function ($join) {
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
            $join->whereNull('els_product_status.deleted_at');
        })->join('els_product_sub_status', function ($join) {
            $join->on('els_system_info_details.status', 'els_product_sub_status.id');
            $join->whereNull('els_product_sub_status.deleted_at');
        })->leftjoin('els_system_status_log', function ($join) {
            $join->on('els_system_allocated_engineer.id', 'els_system_status_log.els_system_allocation_id');
            $join->on('els_system_info_details.status', 'els_system_status_log.status');
            $join->on('els_system_allocated_engineer.els_system_id', 'els_system_status_log.els_system_id');
            $join->whereNull('els_system_status_log.deleted_at');
        })->leftjoin('vendor', 'vendor.id', '=', 'els_system_info_details.vendor_id')
            ->leftjoin('vendor as out', 'out.id', '=', 'els_system_status_log.vendor_id')
            ->whereNull('els_system_info_details.deleted_at')->where('els_system_info_details.status', 2)->orderBy('els_system_info_details.resived_date', 'DESC')->groupBy('els_system_info_details.id');

        $data = $data->selectRaw('els_system_info_details.id,els_system_info_details.resived_date,els_system_info_details.grn_no,ram.name as ram,rom.name as rom,grade.name as grade,els_system_info_details.remark,els_system_info_details.barcode,els_system_info_details.mrp,els_system_info_details.imei_1,els_system_info_details.imei_2,brand.bname,model.mname,vendor.vname,colour.name as colour_name,els_product_status.name as status,els_product_sub_status.name as sub_status,els_system_status_log.created_at as status_date,out.vname as out_vendor')->get();

        $i = 1;
        $setData = '';
        if (!$data->isEmpty()) {
            foreach ($data as $value) {
                $rowData = '';
                $rowData .= '"' . $i . '"' . "\t";
                $rowData .= '"' . date('d-M-Y', strtotime($value->resived_date)) . '"' . "\t";
                $rowData .= '"' . $value->bname . '"' . "\t";
                $rowData .= '"' . $value->mname . '"' . "\t";
                $rowData .= '"' . $value->colour_name . '"' . "\t";
                $rowData .= '"' . $value->barcode . '"' . "\t";
                $rowData .= '"' . $value->grn_no . '"' . "\t";
                $rowData .= '"' . $value->imei_1 . '"' . "\t";
                $rowData .= '"' . $value->imei_2 . '"' . "\t";
                $rowData .= '"' . $value->ram . '"' . "\t";
                $rowData .= '"' . $value->rom . '"' . "\t";
                $rowData .= '"' . $value->grade . '"' . "\t";
                if (\App\Models\RoleModel::find(\Auth::user()->role)->role_name == 'admin') {
                    $rowData .= '"' . $value->mrp . '"' . "\t";
                }
                $rowData .= '"' . $value->remark . '"' . "\t";
                $rowData .= '"' . $value->vname . '"' . "\t";
                if ($value->status_date) {
                    $rowData .= '"' . $value->sub_status . '(' . date('d-M-Y', strtotime($value->status_date)) . ')' . '"' . "\t";
                } else {
                    $rowData .= '"' . $value->sub_status . '"' . "\t";
                }

                $in_stock = \Helper::getActiveInwardDate($value->id);
                if ($in_stock) {
                    $rowData .= '"Not assign"' . "\t";
                } elseif ($value->status) {
                    $rowData .= '"' . $value->status . '"' . "\t";
                } else {
                    $rowData .= '"Not assign"' . "\t";
                }
                $rowData .= '"' . $value->out_vendor . '"' . "\t";
                $setData .= trim($rowData) . "\n";
                $i++;
            }
        }
        echo ucwords($columnHeader) . "\n" . $setData . "\n";
    }

    public function uploadBulkDeviceStatus(Request $request)
    {
        set_time_limit(0);
        $file = $request->file('file_csv');
        if (in_array(strtoupper($file->getClientOriginalExtension()), ['CSV'])) {
            $fileD = fopen($file, "r");
            $column = fgetcsv($fileD);
            if ($column) {
                foreach ($column as $k => $d) {
                    if ($d == 'UIN') {
                        $barcode_key = $k;
                    }
                    // 	if($d == 'Status'){
                    // 		$status_key = $k;
                    // 	}
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
                $rowData = fgetcsv($fileD);
                if (isset($rowData[$barcode_key])) {
                    $vendor_id = null;
                    $status = '0';
                    // 	$s = \App\Models\ELSProductStatus::where('name',$rowData[$status_key])->first();
                    if (isset($rowData[$vendor_key])) {
                        $v = \App\Models\MasterVendorModel::where('vname', $rowData[$vendor_key])->first();
                        if ($v) {
                            $vendor_id = $v->id;
                        }
                    }
                    $data = \App\Models\ElsSystemInfoDtailsModel::where('barcode', $rowData[$barcode_key])->first();
                    if ($data) {
                        \App\Models\ElsSystemInfoDtailsModel::where('id', $data->id)->update(['stock_in' => 0, 'status' => 0, 'current_stage' => 1, 'current_status' => 0, 'vendor_out' => $rowData[$vendor_key]]);

                        \App\Models\InwardDate::where('els_system_id', $data->id)->update(['status' => '0']);
                    }
                }
            }
        }
        return redirect()->back();
    }




    public function uploadBulkChangeStatusss(Request $request)
    {

        $result = DB::select("SELECT id,status,remark,received_date FROM `received_parts_barcode_list_old` WHERE `updated_at` LIKE '%2022-07-28%' ORDER BY `updated_at` DESC");



        foreach ($result as $value) {
            echo $id = $value->id;
            echo $status = $value->status;
            echo $remark = $value->remark;
            echo $received_date = $value->received_date;

            echo '<br>';


            //  	DB::table('received_parts_barcode_list')->where('id', $id)->update(array('received_date'=>$received_date));

        }




        //     ini_set('max_execution_time', '30000');
        // 		set_time_limit(0);
        // 		$file = $request->file('file_csv');
        // 		if(in_array(strtoupper($file->getClientOriginalExtension()),['CSV'])){
        // 			$fileD = fopen($file,"r");
        // 			$column=fgetcsv($fileD);
        // 			if($column){
        // 				foreach($column as $k => $d){
        // 					if($d == 'pin'){
        // 						$barcode_key = $k;
        // 					}
        // 					if($d == 'status'){
        // 						$status_key = $k;
        // 					}

        // 				}
        // 			}

        // 			$i = 2;
        // 			$error = false;
        // 			$massage = '';
        // 			$dataArr = [];
        // 			while(!feof($fileD)){
        // 				$rowData = fgetcsv($fileD);

        // 				if(isset($rowData[$barcode_key])){


        // 				  if(trim($rowData[$status_key])=='Not In Used'){

        // 				    if(trim($rowData[$status_key])=='Not In Used')
        // 				    {
        // 				      $status='2';
        // 				    }
        // 				    echo $rowData[$barcode_key];


        // 				DB::table('received_parts_barcode_list')->where('barcode', $rowData[$barcode_key])->update(array('status' => $status));
        // 			  }
        // 				  }


        // 			}
        // 		}
        // 		return redirect()->back();
    }




    public function uploadBulkChangeStatus(Request $request)
    {
        set_time_limit(0);
        $file = $request->file('file_csv');
        if (in_array(strtoupper($file->getClientOriginalExtension()), ['CSV'])) {
            $fileD = fopen($file, "r");
            $column = fgetcsv($fileD);
            if ($column) {
                foreach ($column as $k => $d) {
                    if ($d == 'UIN') {
                        $barcode_key = $k;
                    }
                    if ($d == 'Status') {
                        $status_key = $k;
                    }
                }
            }
            $i = 2;
            $error = false;
            $massage = '';
            $dataArr = [];
            while (!feof($fileD)) {
                $rowData = fgetcsv($fileD);
                if (isset($rowData[$barcode_key])) {
                    $vendor_id = null;
                    $status = '0';
                    $s = \App\Models\ELSProductStatus::where('name', $rowData[$status_key])->first();

                    $data = \App\Models\ElsSystemInfoDtailsModel::where('barcode', $rowData[$barcode_key])->first();
                    if ($data) {
                        \App\Models\ElsSystemInfoDtailsModel::where('id', $data->id)->update(['status' => $s->id, 'current_status' => $s->id]);
                    }
                }
            }
        }
        return redirect()->back();
    }





    public function updateSKU()
    {
        $data = \App\Models\ElsSystemInfoDtailsModel::whereNull('deleted_at')->get();
        foreach ($data as $d) {
            $dataArr['sku_no'] = \Helper::getDeviceSKUNumber($d->brand_id, $d->model_id, $d->colour_id);
            $save = \App\Models\ElsSystemInfoDtailsModel::updateOrCreate([
                'id'   => $d->id,
            ], $dataArr);
        }
    }

    //16/7/2021........Device Dispatch.......................
    public function viewDispatchDevice(Request $request)
    {
        if (\Helper::checkMenuElegible()) {
            $title = "Device Dispatch List";
            return view('els_product/device_dispatch', compact('title'));
        }
        abort(403, "Don't have permission to access.");
    }

    public function getDispatchDeviceList(Request $request)
    {
        $data = \App\Models\ElsSystemInfoDtailsModel::join('brand', function ($join) {
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
        })->leftjoin('vendor', 'vendor.id', '=', 'els_system_info_details.vendor_id')
            ->whereNull('els_system_info_details.deleted_at')->where('els_system_info_details.stock_in', 0)->groupBy('els_system_info_details.id');

        $where_like = false;
        $columnsArr = ['brand.bname', 'model.mname', 'els_system_info_details.barcode', 'colour.name', 'vendor.vname', 'ram.name', 'rom.name', 'els_system_info_details.imei_1', 'els_system_info_details.remark'];
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
        if ($request->filter_date) {
            list($start_date, $end_date) = explode('-', $request->get('filter_date'));
            $s_date = date('Y-m-d', strtotime(str_replace('/', '-', trim($start_date))));
            $e_date = date('Y-m-d', strtotime(str_replace('/', '-', trim($end_date)) . " + 1 day"));
            $data = $data->whereBetween('els_system_info_details.updated_at', [$s_date, $e_date]);
        }

        $totalRecord = count($data->get());

        if ($request->get('length')) {
            $data = $data->skip($request->get('start'))->take($request->get('length'));
        }

        $data = $data->selectRaw('els_system_info_details.id,els_system_info_details.resived_date,els_system_info_details.grn_no,ram.name as ram,rom.name as rom,grade.name as grade,els_system_info_details.remark,els_system_info_details.barcode,els_system_info_details.mrp,els_system_info_details.imei_1,els_system_info_details.imei_2,brand.bname,model.mname,vendor.vname,colour.name as colour_name,els_system_info_details.vendor_out,DATE_FORMAT(els_system_info_details.updated_at, "%d-%b-%Y") as updated_ats')->get();
        return response()->json(['status' => true, 'data' => $data, 'recordsTotal' => $totalRecord, 'recordsFiltered' => $totalRecord, 'code' => 200, 'message' => 'Dispatch device listed successfully']);
    }






    public function DeviceDispatchReportd(Request $request)
    {
        header("Content-Type: application/xls");
        header("Content-Disposition: attachment; filename=DeviceDispatchReport.xls");
        header("Pragma: no-cache");
        header("Expires: 0");

        $columnHeader = "Sr NO" . "\t" . "Date" . "\t" . "Brand" . "\t" . "Model" . "\t" . "Colour" . "\t" . "UIN" . "\t" . "IMEI 1" . "\t" . "IMEI 2" . "\t" . "RAM" . "\t" . "ROM" . "\t" . "Incoming Grade" . "\t" . "Dispatch Vendor" . "\t" . "Remark" . "\t";

        $data = \App\Models\ElsSystemInfoDtailsModel::join('brand', function ($join) {
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
        })->leftjoin('vendor', 'vendor.id', '=', 'els_system_info_details.vendor_id')
            ->whereNull('els_system_info_details.deleted_at')->where('els_system_info_details.stock_in', 0)->groupBy('els_system_info_details.id');

        if ($request->filter_date) {
            list($start_date, $end_date) = explode('-', $request->get('filter_date'));
            $s_date = date('Y-m-d', strtotime(str_replace('/', '-', trim($start_date))));
            $e_date = date('Y-m-d', strtotime(str_replace('/', '-', trim($end_date)) . " + 1 day"));
            $data = $data->whereBetween('els_system_info_details.updated_at', [$s_date, $e_date]);
        }



        $data = $data->selectRaw('els_system_info_details.id,els_system_info_details.resived_date,els_system_info_details.grn_no,ram.name as ram,rom.name as rom,grade.name as grade,els_system_info_details.remark,els_system_info_details.barcode,els_system_info_details.mrp,els_system_info_details.imei_1,els_system_info_details.imei_2,brand.bname,model.mname,vendor.vname,colour.name as colour_name,els_system_info_details.vendor_out,DATE_FORMAT(els_system_info_details.updated_at, "%d-%b-%Y") as updated_ats')->get();

        $i = 1;
        $setData = '';
        if (!$data->isEmpty()) {
            foreach ($data as $value) {
                $rowData = '';
                $rowData .= '"' . $i . '"' . "\t";
                $rowData .= '"' . $value->updated_ats . '"' . "\t";
                $rowData .= '"' . $value->bname . '"' . "\t";
                $rowData .= '"' . $value->mname . '"' . "\t";
                $rowData .= '"' . $value->colour_name . '"' . "\t";
                $rowData .= '"' . $value->barcode . '"' . "\t";
                $rowData .= '"' . $value->imei_1 . '"' . "\t";
                $rowData .= '"' . $value->imei_2 . '"' . "\t";
                $rowData .= '"' . $value->ram . '"' . "\t";
                $rowData .= '"' . $value->rom . '"' . "\t";
                $rowData .= '"' . $value->grade . '"' . "\t";
                $rowData .= '"' . $value->vendor_out . '"' . "\t";
                $rowData .= '"' . $value->remark . '"' . "\t";
                $setData .= trim($rowData) . "\n";
                $i++;
            }
        }
        echo ucwords($columnHeader) . "\n" . $setData . "\n";
    }





    public function DeviceDispatchReport(Request $request)
    {
        header("Content-Type: application/xls");
        header("Content-Disposition: attachment; filename=DeviceDispatchReport.xls");
        header("Pragma: no-cache");
        header("Expires: 0");

        $columnHeader = "Sr NO" . "\t" . "Date" . "\t" . "Brand" . "\t" . "Model" . "\t" . "Colour" . "\t" . "UIN" . "\t" . "IMEI 1" . "\t" . "IMEI 2" . "\t" . "RAM" . "\t" . "ROM" . "\t" . "Incoming Grade" . "\t" . "Status" . "\t" . "Dispatch Vendor" . "\t" . "Remark" . "\t";

        $data = \App\Models\ElsSystemInfoDtailsModel::join('brand', function ($join) {
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
            $join->whereNull('els_product_status.deleted_at');
        })->join('els_product_sub_status', function ($join) {
            $join->on('els_system_info_details.status', 'els_product_sub_status.id');
            $join->whereNull('els_product_sub_status.deleted_at');
        })->leftjoin('els_system_status_log', function ($join) {
            $join->on('els_system_allocated_engineer.id', 'els_system_status_log.els_system_allocation_id');
            $join->on('els_system_info_details.status', 'els_system_status_log.status');
            $join->on('els_system_allocated_engineer.els_system_id', 'els_system_status_log.els_system_id');
            $join->whereNull('els_system_status_log.deleted_at');
        })->leftjoin('vendor', 'vendor.id', '=', 'els_system_info_details.vendor_id')
            ->leftjoin('vendor as out', 'out.id', '=', 'els_system_status_log.vendor_id')
            ->whereNull('els_system_info_details.deleted_at')->where('els_system_info_details.status', 4)->orderBy('els_system_status_log.id', 'DESC')->groupBy('els_system_info_details.id');

        if ($request->filter_date) {
            list($start_date, $end_date) = explode('-', $request->get('filter_date'));
            $s_date = date('Y-m-d', strtotime(str_replace('/', '-', trim($start_date))));
            $e_date = date('Y-m-d', strtotime(str_replace('/', '-', trim($end_date)) . " + 1 day"));
            $data = $data->whereBetween('els_system_info_details.updated_at', [$s_date, $e_date]);
        }

        $data = $data->selectRaw('els_system_info_details.id,els_system_info_details.resived_date,els_system_info_details.grn_no,ram.name as ram,rom.name as rom,grade.name as grade,els_system_info_details.remark,els_system_info_details.barcode,els_system_info_details.mrp,els_system_info_details.imei_1,els_system_info_details.imei_2,brand.bname,model.mname,vendor.vname,colour.name as colour_name,els_product_status.name as status,els_product_sub_status.name as sub_status,DATE_FORMAT(els_system_info_details.updated_at,"%d/%m/%Y") as dispatch_date,els_system_info_details.vendor_out as out_vendor')->get();

        $i = 1;
        $setData = '';
        if (!$data->isEmpty()) {
            foreach ($data as $value) {
                $rowData = '';
                $rowData .= '"' . $i . '"' . "\t";
                $rowData .= '"' . $value->dispatch_date . '"' . "\t";
                $rowData .= '"' . $value->bname . '"' . "\t";
                $rowData .= '"' . $value->mname . '"' . "\t";
                $rowData .= '"' . $value->colour_name . '"' . "\t";
                $rowData .= '"' . $value->barcode . '"' . "\t";
                $rowData .= '"' . $value->imei_1 . '"' . "\t";
                $rowData .= '"' . $value->imei_2 . '"' . "\t";
                $rowData .= '"' . $value->ram . '"' . "\t";
                $rowData .= '"' . $value->rom . '"' . "\t";
                $rowData .= '"' . $value->grade . '"' . "\t";
                $rowData .= '"' . $value->sub_status . '"' . "\t";
                $rowData .= '"' . $value->out_vendor . '"' . "\t";
                $rowData .= '"' . $value->remark . '"' . "\t";
                $setData .= trim($rowData) . "\n";
                $i++;
            }
        }
        echo ucwords($columnHeader) . "\n" . $setData . "\n";
    }
    public function DownloadChallan(Request $request)
    {
        $data = \App\Models\AssignEngineer::join('els_system_status_log', function ($join) {
            $join->on('els_system_status_log.els_system_allocation_id', 'els_system_allocated_engineer.id');
            $join->where('els_system_allocated_engineer.active', '1');
        })->leftjoin('els_product_status', function ($join) {
            $join->on('els_system_allocated_engineer.status', 'els_product_status.id');
            $join->whereNull('els_product_status.deleted_at');
        })->join('vendor', function ($join) {
            $join->on('els_system_status_log.vendor_id', 'vendor.id');
            $join->whereNull('vendor.deleted_at');
        })->join('els_system_info_details', function ($join) {
            $join->on('els_system_info_details.id', 'els_system_status_log.els_system_id');
        })->join('brand', function ($join) {
            $join->on('els_system_info_details.brand_id', 'brand.id');
        })->join('model', function ($join) {
            $join->on('els_system_info_details.model_id', 'model.id');
        })->leftjoin('ram', function ($join) {
            $join->on('els_system_info_details.ram', 'ram.id');
        })->leftjoin('rom', function ($join) {
            $join->on('els_system_info_details.rom', 'rom.id');
        })
            ->whereNotNull('els_system_status_log.vendor_id')->where('els_system_allocated_engineer.els_system_id', $request->challan_id)->orderBy('els_system_status_log.id', 'DESC')->selectRaw('els_system_info_details.id,els_system_info_details.barcode,els_system_info_details.imei_1,els_system_info_details.imei_2,els_system_info_details.resived_date,els_system_info_details.grn_no,ram.name as ram,rom.name as rom,brand.bname,model.mname,vendor.*,els_system_allocated_engineer.els_system_id,els_product_status.name as status')->first();

        $parts = \App\Models\AllocatedBarcode::join('received_parts_barcode_list', function ($join) {
            $join->on('els_system_allocated_parts_barcode.barcode_id', 'received_parts_barcode_list.id');
            $join->whereNull('els_system_allocated_parts_barcode.deleted_at');
        })->join('received_purchase_order_parts_list', function ($join) {
            $join->on('received_parts_barcode_list.received_part_id', 'received_purchase_order_parts_list.id');
            $join->whereNull('received_purchase_order_parts_list.deleted_at');
        })->join('brand', function ($join) {
            $join->on('received_purchase_order_parts_list.brand_id', 'brand.id');
        })->join('part', function ($join) {
            $join->on('received_purchase_order_parts_list.part_id', 'part.id');
        })->join('model', function ($join) {
            $join->on('received_purchase_order_parts_list.model_id', 'model.id');
        })->where('els_system_allocated_parts_barcode.els_system_id', $request->challan_id)->get();
        if ($data) {
            $pdf = \PDF::loadView('els_product/challan', compact('data', 'parts'));
            return $pdf->download('challan-' . $request->challan_id . '.pdf');
        }
        return redirect('device-dispatching');
    }


    //
    public function distributorPartsProductList(Request $request)
    {
        if (\Helper::checkMenuElegible()) {
            $data = [];
            $title = "Consumed Parts Product List";
            $status = \App\Models\ELSProductStatus::whereNull('deleted_at')->where('status', '1')->pluck('name', 'id');
            $vendor = \App\Models\MasterVendorModel::whereNull('deleted_at')->where('status', '1')->pluck('vname', 'id');
            return view('els_product/distributor_parts_product_list', compact('title', 'status', 'vendor'));
        }
        abort(403, "Don't have permission to access.");
    }

    public function get_engg_activity_log(Request $request)
    {
        $id = $request->id;

        $data = DB::select("select els_system_allocated_engineer.*,users.name as  ename,els_product_status.name as sname,els_product_sub_status.name  as sss,els_system_info_details.created_at as acre from `els_system_allocated_engineer` inner join `users` on `users`.`id` = `els_system_allocated_engineer`.`engineer_id` inner join `els_product_status` on `els_system_allocated_engineer`.`status` = `els_product_status`.`id` inner join `els_product_sub_status` on `els_product_sub_status`.`id` = `els_product_status`.`sub_status_id` inner join els_system_info_details on els_system_info_details.id=els_system_allocated_engineer.els_system_id  where `els_system_allocated_engineer`.`els_system_id` = " . $id . " order by id desc");


        $arr = array(
            "code" => 200,
            "data" => $data,
        );
        return \Response::json($arr);
    }

    public function getdistributorPartProductList(Request $request)
    {
        $data = \App\Models\ElsSystemInfoDtailsModel::join('els_system_allocated_engineer', function ($join) {
            $join->on('els_system_info_details.id', 'els_system_allocated_engineer.els_system_id');
            $join->where('els_system_allocated_engineer.active', '1');
            $join->whereNull('els_system_allocated_engineer.deleted_at');
        })->join('brand', function ($join) {
            $join->on('els_system_info_details.brand_id', 'brand.id');
        })->join('model', function ($join) {
            $join->on('els_system_info_details.model_id', 'model.id');
        })->join('colour', function ($join) {
            $join->on('els_system_info_details.colour_id', 'colour.id');
        })->join('users', function ($join) {
            $join->on('els_system_allocated_engineer.engineer_id', 'users.id');
        })->leftjoin('els_product_status', function ($join) {
            $join->on('els_product_status.id', 'els_system_allocated_engineer.status');
        })->leftjoin('els_system_allocated_parts_barcode', function ($join) {
            $join->on('els_system_info_details.id', 'els_system_allocated_parts_barcode.els_system_id');
            $join->where('els_system_allocated_parts_barcode.status', '1');
            $join->whereNull('els_system_allocated_parts_barcode.deleted_at');
        })->leftjoin('received_parts_barcode_list', function ($join) {
            $join->on('els_system_allocated_parts_barcode.barcode_id', 'received_parts_barcode_list.id');
            $join->whereNull('received_parts_barcode_list.deleted_at');
        })->whereNull('els_system_info_details.deleted_at')->where('els_system_info_details.current_stage', 1)->groupBy('els_system_info_details.id');

        $where_like = false;
        $columnsArr = ['els_system_info_details.barcode', 'els_system_info_details.imei_1', 'els_system_info_details.imei_2', 'users.name', 'brand.bname', 'model.mname', 'colour.name'];
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
        $data = $data->selectRaw('els_system_info_details.id,els_system_info_details.barcode,els_system_info_details.imei_1,els_system_info_details.imei_2,brand.bname,model.mname,users.name,colour.name as colour_name,count(distinct els_system_allocated_parts_barcode.id) as consumed_part,els_system_allocated_engineer.id as allocated_id,els_system_allocated_engineer.status as status_id,els_product_status.name status,els_system_info_details.remark,els_system_allocated_engineer.created_at')->get();
        return response()->json(['status' => true, 'data' => $data, 'recordsTotal' => $totalRecord, 'recordsFiltered' => $totalRecord, 'code' => 200, 'message' => 'Part consumed product listed successfully']);
    }

    public function uploaddistributorAllocationEnginner(Request $request)
    {
        set_time_limit(0);
        $file = $request->file('file_csv');
        if (in_array(strtoupper($file->getClientOriginalExtension()), ['CSV'])) {
            $fileD = fopen($file, "r");
            $column = fgetcsv($fileD);
            if ($column) {
                foreach ($column as $k => $d) {
                    if ($d == 'UIN') {
                        $uin_key = $k;
                    }
                    if ($d == 'Enginner') {
                        $enginner_key = $k;
                    }
                    if ($d == 'Part Name') {
                        $part_key = $k;
                    }
                    if ($d == 'PIN') {
                        $pin_key = $k;
                    }
                    if ($d == 'Remark') {
                        $remark_key = $k;
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
                    if (isset($rowData[$enginner_key])) {
                        $engineer = \App\Models\User::join('role', function ($join) {
                            $join->on('users.role', 'role.id');
                            $join->where('role.role_name', 'engineer');
                        })->where('users.is_active', '1')->where('users.name', $rowData[$enginner_key])->select('users.*')->first();
                        if ($engineer) {
                            $row['engineer_id'] = $engineer->id;
                        } else {
                            $msg .= 'Engineer,';
                            $error = true;
                        }
                    }

                    if (isset($rowData[$uin_key])) {
                        $els_system = \App\Models\ElsSystemInfoDtailsModel::where('deleted_at')->where('barcode', $rowData[$uin_key])->first();
                        if ($els_system) {
                            $row['els_system_id'] = $els_system->id;
                        } else {
                            $msg .= 'UIN,';
                            $error = true;
                        }
                    }

                    if (($rowData[$part_key])) {
                        $part = \App\Models\MasterPartModel::whereNull('deleted_at')->where('status', '1')->where('name', $rowData[$part_key])->first();
                        if ($part) {
                            $row['part_id'] = $part->id;
                        }
                    }

                    if (($rowData[$pin_key]) && ($rowData[$part_key])) {
                        $pin = \App\Models\ReceivedPurchaseOrderPartsBarcode::join('received_purchase_order_parts_list', function ($join) {
                            $join->on('received_parts_barcode_list.received_part_id', 'received_purchase_order_parts_list.id');
                        })->where('received_parts_barcode_list.deleted_at')->where('received_parts_barcode_list.iqc_status', '1')->where('received_parts_barcode_list.status', '2')->where('received_parts_barcode_list.barcode', $rowData[$pin_key])->where('received_purchase_order_parts_list.part_id', $row['part_id'])->select('received_parts_barcode_list.id', 'received_purchase_order_parts_list.brand_id', 'received_purchase_order_parts_list.model_id', 'received_purchase_order_parts_list.part_id', 'received_purchase_order_parts_list.colour_id')->first();
                        if ($pin) {
                            $row['brand_id'] = $pin->brand_id;
                            $row['model_id'] = $pin->model_id;
                            $row['colour_id'] = $pin->colour_id;
                            $row['barcode_id'] = $pin->id;
                        } else {
                            $msg .= 'PIN,';
                            $error = true;
                        }
                    }

                    if (($rowData[$remark_key])) {
                        $row['remark'] = $rowData[$remark_key];
                    }

                    $dataArr[] = $row;
                    if ($error) $massage .= $msg . ' Not Found. Please Correct The Details.';
                    $i++;
                }
            }

            if ($error) {
                return response()->json(['status' => false, 'data' => [], 'code' => 401, 'message' => $massage]);
            }
            if ($dataArr) {
                foreach ($dataArr as $val) {
                    $d = (object) $val;
                    $day = \App\Models\AssignEngineer::updateOrCreate([
                        'els_system_id' => $d->els_system_id,
                        'engineer_id' => $d->engineer_id,
                    ], [
                        'active' => '1',
                    ]);
                    if (isset($d->barcode_id)) {
                        $product_type_id = 3;
                        $spare_part_price_id = 0;
                        $spare_part_price = \App\Models\SparePartPriceList::join('spare_part_list', function ($join) {
                            $join->on('spare_part_price_list.series_id', 'spare_part_list.id');
                        })->where('spare_part_list.brand_id', $d->brand_id)->where('spare_part_list.model_id', $d->model_id)->where('spare_part_list.type_id', $product_type_id)->where('spare_part_list.part_id', $d->part_id)->where('spare_part_price_list.colour_id', $d->colour_id)->select('spare_part_price_list.id')->first();
                        if ($spare_part_price) {
                            $spare_part_price_id = $spare_part_price->id;
                        }

                        $order_request = \App\Models\OrderRequest::updateOrCreate(['els_system_id' => $d->els_system_id]);

                        \App\Models\OrderRequestPart::updateOrCreate(['part_id' => $d->part_id], [
                            'request_order_id' => $order_request->id,
                            'brand_id' => $d->brand_id,
                            'model_id' => $d->model_id,
                            'part_type_id' => $product_type_id,
                            'colour_id' => $d->colour_id,
                            'quantity'    => 1,
                            'spare_part_price_id' => $spare_part_price_id,
                            'status' => '0',
                        ]);

                        \App\Models\AllocatedBarcode::updateOrCreate([
                            'els_system_id' => $d->els_system_id,
                            'barcode_id' => $d->barcode_id,
                        ], [
                            'remark' => $d->remark,
                        ]);
                        \App\Models\ReceivedPurchaseOrderPartsBarcode::where('id', $d->barcode_id)->update(['status' => '3']);
                        \App\Models\InwardDate::where('els_system_id', $d->els_system_id)->update(['status' => '0']);
                    }
                }
            }
            // return response()->json(['status'=>true, 'data' => [], 'code'=>200, 'message'=> 'Product successfully imported.']);
        }
        // return response()->json(['status'=>false, 'data' => [], 'code'=>401, 'message'=> 'Please choose a correct formate.']);
        return redirect()->back();
    }

    public function distributorProductPart(Request $request)
    {
        $data = [];
        $title = "Allocated Product Part";
        return view('els_product/distributor_product_part', compact('title'));
    }

    public function distributorELSProductPartsDetails(Request $request)
    {
        $data = \App\Models\ElsSystemInfoDtailsModel::join('brand', function ($join) {
            $join->on('els_system_info_details.brand_id', 'brand.id');
        })->join('model', function ($join) {
            $join->on('els_system_info_details.model_id', 'model.id');
        })->join('colour', function ($join) {
            $join->on('els_system_info_details.colour_id', 'colour.id');
        })->leftjoin('els_system_allocated_engineer', function ($join) {
            $join->on('els_system_info_details.id', 'els_system_allocated_engineer.els_system_id');
            $join->where('els_system_allocated_engineer.active', '1');
            $join->whereNull('els_system_allocated_engineer.deleted_at');
        })->where(['barcode' => $request->barcode])->whereIn('els_system_info_details.current_stage', ['0', '1'])->select('els_system_info_details.*', 'brand.bname', 'model.mname', 'colour.name as colour_name', 'els_system_allocated_engineer.engineer_id')->first();

        if ($data) {
            $parts = \App\Models\OrderRequest::join('els_order_request_parts', function ($join) {
                $join->on('els_order_request.id', 'els_order_request_parts.request_order_id');
                $join->whereNull('els_order_request_parts.deleted_at');
            })->leftjoin('received_purchase_order_parts_list', function ($join) {
                $join->on('els_order_request_parts.brand_id', 'received_purchase_order_parts_list.brand_id');
                $join->on('els_order_request_parts.model_id', 'received_purchase_order_parts_list.model_id');
                $join->on('els_order_request_parts.part_type_id', 'received_purchase_order_parts_list.product_type_id');
                $join->on('els_order_request_parts.part_id', 'received_purchase_order_parts_list.part_id');
                $join->whereNull('received_purchase_order_parts_list.deleted_at');
            })->leftjoin('received_parts_barcode_list', function ($join) {
                $join->on('received_purchase_order_parts_list.id', 'received_parts_barcode_list.received_part_id');
            })->where('els_order_request.els_system_id', $data->id)->groupBy('els_order_request_parts.id')->select('els_order_request_parts.*', 'els_order_request.els_system_id')->get();
            $part_list = \App\Models\MasterPartModel::where('status', '1')->whereNull('deleted_at')->pluck('name', 'id');
            $details_html = view('els_product/barcode_distributor_details', compact('data'))->render();
            $engineer = \App\Models\User::join('role', function ($join) {
                $join->on('users.role', 'role.id');
            })->where('role.role_name', 'engineer')
                // 			->where('users.id',$data->engineer_id)
                ->pluck('users.name', 'users.id');


            $current_phase = $data->current_status;

            $statuss = DB::table('els_relation_status')->where('phase', $current_phase)->where('type', 1)->pluck('status');


            $statuss = DB::table('els_product_status')->whereIn('id', $statuss)->whereNull('deleted_at')->pluck('name', 'id');
            $product_parts_html = view('els_product/els_distributor_parts_form', compact('data', 'parts', 'part_list', 'engineer', 'statuss'))->render();
            return response()->json(['status' => true, 'data' => [], 'details_html' => \Helper::compressHtml($details_html), 'product_parts_html' => \Helper::compressHtml($product_parts_html), 'code' => 200, 'message' => 'Order request list']);
        }
        return response()->json(['status' => false, 'data' => [], 'code' => 401, 'message' => 'No Data Found.Also check in contribute']);
    }

    public function distributorBarcodeDetails(Request $request)
    {
        $data = \App\Models\ReceivedPurchaseOrderPartsBarcode::join('received_purchase_order_parts_list', function ($join) {
            $join->on('received_parts_barcode_list.received_part_id', 'received_purchase_order_parts_list.id');
            $join->whereNull('received_purchase_order_parts_list.deleted_at');
        })->where('received_purchase_order_parts_list.model_id', $request->model_id)->where('received_purchase_order_parts_list.part_id', $request->part_id)->where('received_parts_barcode_list.barcode', $request->barcode)->where('received_parts_barcode_list.iqc_status', '1')->where('received_parts_barcode_list.status', '2')->count();
        if ($data) {
            return response()->json(['status' => true, 'data' => [], 'code' => 200, 'message' => 'Barcode Exist']);
        }
        return response()->json(['status' => false, 'data' => [], 'code' => 401, 'message' => 'Barcode is not valid.']);
    }

    public function distributorProductPartPerBarcode(Request $request)
    {
        $product_type_id = 3;
        //print_r($request->els_system_id);
        //print_r($request->engineer_id);
        print_r($request->remark);
        //print_r($request->status);
        //die("hi");

        $els_system_info_details = DB::table('els_system_info_details')->where('id', $request->els_system_id)->first();

        if ($els_system_info_details->current_stage == 1 || $els_system_info_details->current_stage == 0) {

            \App\Models\AssignEngineer::where('els_system_id', $request->els_system_id)->update(['active' => '0']);
            // 		die('hii');
            $savid = \App\Models\AssignEngineer::Create([
                'els_system_id' => $request->els_system_id,
                'engineer_id' => $request->engineer_id,
                'status' => $request->status,
                'remark' => $request->remark,
                'active' => '1',
            ]);

            $save = \App\Models\ELSProductStatusLog::updateOrCreate([
                'els_system_allocation_id' => $request->engineer_id,
                'vendor_id' => NULL,
                'els_system_id' => $request->els_system_id,
                'status' => $request->status,
            ]);



            DB::table('els_system_allocated_engineer')
                ->where('id', $savid->id)
                ->update(['remark' => $request->remark, 'type' => 1, 'user_id' => Auth()->user()->id]);


            DB::table('els_system_info_details')
                ->where('id', $request->els_system_id)
                ->update(['current_stage' => '2', 'status' => $request->status, 'current_status' => $request->status, 'current_phase' => '1']);

            if ($request->status == 31) {
                DB::table('els_system_info_details')
                    ->where('id', $request->els_system_id)
                    ->update(['stock_in' => 0]);
            }



            return redirect()->route('distributor-parts-product-list');
        } else {
            return response()->json(['status' => false, 'data' => [], 'code' => 401, 'message' => 'ELS IS IN COLLECT BACK PANEL']);
        }
    }


    public function addMoredistributor(Request $request)
    {
        $data = \App\Models\ElsSystemInfoDtailsModel::where('barcode', $request->barcode)->first();
        if ($data) {
            $part_list = \App\Models\MasterPartModel::join('spare_part_list', function ($join) {
                $join->on('part.id', 'spare_part_list.part_id');
                $join->whereNull('spare_part_list.deleted_at');
            })->where('spare_part_list.model_id', $data->model_id)->where('part.status', '1')->whereNull('part.deleted_at')->pluck('part.name', 'part.id');
            $key = $request->id;
            $html = view('els_product/add-more-distributor', compact('data', 'part_list', 'key'))->render();
            return response()->json(['status' => true, 'data' => [], 'html' => \Helper::compressHtml($html), 'code' => 200, 'message' => 'Add more parts']);
        }
    }

    public function getdistributorColours(Request $request)
    {
        $data = \App\Models\SparePartPriceList::join('spare_part_list', function ($join) {
            $join->on('spare_part_list.id', 'spare_part_price_list.series_id');
            $join->whereNull('spare_part_list.deleted_at');
        })->join('colour', function ($join) {
            $join->on('colour.id', 'spare_part_price_list.colour_id');
            $join->whereNull('colour.deleted_at');
        })->where('spare_part_list.model_id', $request->model_id)->where('spare_part_list.part_id', $request->part_id)->where('colour.status', '1')->select('colour.name', 'colour.id')->groupBy('colour.id')->get();
        return response()->json(['status' => true, 'data' => $data, 'code' => 200, 'message' => 'colour listed successfully']);
    }

    public function distributorProductStatus(Request $request)
    {
        $els_system = \App\Models\AssignEngineer::find($request->id);
        if ($els_system) {
            $els_system_id = $els_system->els_system_id;
            $s = \App\Models\ELSProductStatus::find($request->status);
            $data = \App\Models\ElsSystemInfoDtailsModel::where('id', $els_system_id)->update(['status' => $s->sub_status_id]);

            $data = \App\Models\AssignEngineer::join('els_system_allocated_parts_barcode', function ($join) {
                $join->on('els_system_allocated_engineer.els_system_id', 'els_system_allocated_parts_barcode.els_system_id');
                $join->whereNull('els_system_allocated_parts_barcode.deleted_at');
            })->where('els_system_allocated_engineer.id', $request->id)->select('els_system_allocated_parts_barcode.els_system_id', 'els_system_allocated_parts_barcode.barcode_id')->get()->toArray();
            if ($data) {
                $id = array_unique(array_column($data, 'barcode_id'));
                $status = '0';
                if ($s->sub_status_id != 1) {
                    $stockUpdate = \App\Models\ReceivedPurchaseOrderPartsBarcode::whereIn('id', $id)->update(['status' => $status]);
                }
            }
            $save = \App\Models\AssignEngineer::updateOrCreate([
                'id' => $request->id,
            ], [
                'status' => $request->status,
            ]);
            if ($s->sub_status_id != 1) {
                $save = \App\Models\ELSProductStatusLog::updateOrCreate([
                    'els_system_allocation_id' => $els_system->id,
                    'vendor_id' => $request->vendor_id,
                    'els_system_id' => $els_system_id,
                    'status' => $s->sub_status_id,
                ]);
            }
        }
        return response()->json(['status' => true, 'data' => [], 'code' => 200, 'message' => 'Status update successfully']);
    }

    public function removedistributorBarcode(Request $request)
    {
        if ($request->request_part_id) {
            \App\Models\OrderRequestPart::where('id', $request->request_part_id)->delete();
        }
        if ($request->barcode) {
            $data = \App\Models\ReceivedPurchaseOrderPartsBarcode::where('barcode', $request->barcode)->first();
            if ($data) {
                \App\Models\AllocatedBarcode::where('barcode_id', $data->id)->delete();
                \App\Models\ReceivedPurchaseOrderPartsBarcode::where('id', $data->id)->update(['status' => '2', 'remark' => 'Returned parts']);
            }
        }
        return response()->json(['status' => true, 'data' => [], 'code' => 200, 'message' => 'Barcode remove successfully']);
    }


    //








    /////

    public function collectPartsProductList(Request $request)
    {
        if (\Helper::checkMenuElegible()) {
            $data = [];
            $title = "Consumed Parts Product List";
            $status = \App\Models\ELSProductStatus::whereNull('deleted_at')->where('status', '1')->pluck('name', 'id');
            $vendor = \App\Models\MasterVendorModel::whereNull('deleted_at')->where('status', '1')->pluck('vname', 'id');
            return view('els_product/collect_parts_product_list', compact('title', 'status', 'vendor'));
        }
        abort(403, "Don't have permission to access.");
    }

    public function getcollectPartProductList(Request $request)
    {
        $data = \App\Models\ElsSystemInfoDtailsModel::join('els_system_allocated_engineer', function ($join) {
            $join->on('els_system_info_details.id', 'els_system_allocated_engineer.els_system_id');
            $join->where('els_system_allocated_engineer.active', '1');
            $join->whereNull('els_system_allocated_engineer.deleted_at');
        })->join('brand', function ($join) {
            $join->on('els_system_info_details.brand_id', 'brand.id');
        })->join('model', function ($join) {
            $join->on('els_system_info_details.model_id', 'model.id');
        })->join('colour', function ($join) {
            $join->on('els_system_info_details.colour_id', 'colour.id');
        })->join('users', function ($join) {
            $join->on('els_system_allocated_engineer.engineer_id', 'users.id');
        })->leftjoin('els_product_status', function ($join) {
            $join->on('els_product_status.id', 'els_system_allocated_engineer.status');
        })->leftjoin('els_system_allocated_parts_barcode', function ($join) {
            $join->on('els_system_info_details.id', 'els_system_allocated_parts_barcode.els_system_id');
            $join->where('els_system_allocated_parts_barcode.status', '1');
            $join->whereNull('els_system_allocated_parts_barcode.deleted_at');
        })->leftjoin('received_parts_barcode_list', function ($join) {
            $join->on('els_system_allocated_parts_barcode.barcode_id', 'received_parts_barcode_list.id');
            $join->whereNull('received_parts_barcode_list.deleted_at');
        })->whereNull('els_system_info_details.deleted_at')->where('els_system_info_details.current_stage', 2)->groupBy('els_system_info_details.id');

        $where_like = false;
        $columnsArr = ['els_system_info_details.barcode', 'els_system_info_details.imei_1', 'els_system_info_details.imei_2', 'users.name', 'brand.bname', 'model.mname', 'colour.name'];
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
        $data = $data->selectRaw('els_system_info_details.id,els_system_info_details.barcode,els_system_info_details.imei_1,els_system_info_details.imei_2,brand.bname,model.mname,users.name,colour.name as colour_name,count(distinct els_system_allocated_parts_barcode.id) as consumed_part,els_system_allocated_engineer.id as allocated_id,els_system_allocated_engineer.status as status_id,els_product_status.name status,els_system_info_details.remark,els_system_allocated_engineer.created_at')->get();
        return response()->json(['status' => true, 'data' => $data, 'recordsTotal' => $totalRecord, 'recordsFiltered' => $totalRecord, 'code' => 200, 'message' => 'Part consumed product listed successfully']);
    }

    public function uploadcollectAllocationEnginner(Request $request)
    {
        set_time_limit(0);
        $file = $request->file('file_csv');
        if (in_array(strtoupper($file->getClientOriginalExtension()), ['CSV'])) {
            $fileD = fopen($file, "r");
            $column = fgetcsv($fileD);
            if ($column) {
                foreach ($column as $k => $d) {
                    if ($d == 'UIN') {
                        $uin_key = $k;
                    }
                    if ($d == 'Enginner') {
                        $enginner_key = $k;
                    }
                    if ($d == 'Part Name') {
                        $part_key = $k;
                    }
                    if ($d == 'PIN') {
                        $pin_key = $k;
                    }
                    if ($d == 'Remark') {
                        $remark_key = $k;
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
                    if (isset($rowData[$enginner_key])) {
                        $engineer = \App\Models\User::join('role', function ($join) {
                            $join->on('users.role', 'role.id');
                            $join->where('role.role_name', 'engineer');
                        })->where('users.is_active', '1')->where('users.name', $rowData[$enginner_key])->select('users.*')->first();
                        if ($engineer) {
                            $row['engineer_id'] = $engineer->id;
                        } else {
                            $msg .= 'Engineer,';
                            $error = true;
                        }
                    }

                    if (isset($rowData[$uin_key])) {
                        $els_system = \App\Models\ElsSystemInfoDtailsModel::where('deleted_at')->where('barcode', $rowData[$uin_key])->first();
                        if ($els_system) {
                            $row['els_system_id'] = $els_system->id;
                        } else {
                            $msg .= 'UIN,';
                            $error = true;
                        }
                    }

                    if (($rowData[$part_key])) {
                        $part = \App\Models\MasterPartModel::whereNull('deleted_at')->where('status', '1')->where('name', $rowData[$part_key])->first();
                        if ($part) {
                            $row['part_id'] = $part->id;
                        }
                    }

                    if (($rowData[$pin_key]) && ($rowData[$part_key])) {
                        $pin = \App\Models\ReceivedPurchaseOrderPartsBarcode::join('received_purchase_order_parts_list', function ($join) {
                            $join->on('received_parts_barcode_list.received_part_id', 'received_purchase_order_parts_list.id');
                        })->where('received_parts_barcode_list.deleted_at')->where('received_parts_barcode_list.iqc_status', '1')->where('received_parts_barcode_list.status', '2')->where('received_parts_barcode_list.barcode', $rowData[$pin_key])->where('received_purchase_order_parts_list.part_id', $row['part_id'])->select('received_parts_barcode_list.id', 'received_purchase_order_parts_list.brand_id', 'received_purchase_order_parts_list.model_id', 'received_purchase_order_parts_list.part_id', 'received_purchase_order_parts_list.colour_id')->first();
                        if ($pin) {
                            $row['brand_id'] = $pin->brand_id;
                            $row['model_id'] = $pin->model_id;
                            $row['colour_id'] = $pin->colour_id;
                            $row['barcode_id'] = $pin->id;
                        } else {
                            $msg .= 'PIN,';
                            $error = true;
                        }
                    }

                    if (($rowData[$remark_key])) {
                        $row['remark'] = $rowData[$remark_key];
                    }

                    $dataArr[] = $row;
                    if ($error) $massage .= $msg . ' Not Found. Please Correct The Details.';
                    $i++;
                }
            }

            if ($error) {
                return response()->json(['status' => false, 'data' => [], 'code' => 401, 'message' => $massage]);
            }
            if ($dataArr) {
                foreach ($dataArr as $val) {
                    $d = (object) $val;
                    $day = \App\Models\AssignEngineer::updateOrCreate([
                        'els_system_id' => $d->els_system_id,
                        'engineer_id' => $d->engineer_id,
                    ], [
                        'active' => '1',
                    ]);
                    if (isset($d->barcode_id)) {
                        $product_type_id = 3;
                        $spare_part_price_id = 0;
                        $spare_part_price = \App\Models\SparePartPriceList::join('spare_part_list', function ($join) {
                            $join->on('spare_part_price_list.series_id', 'spare_part_list.id');
                        })->where('spare_part_list.brand_id', $d->brand_id)->where('spare_part_list.model_id', $d->model_id)->where('spare_part_list.type_id', $product_type_id)->where('spare_part_list.part_id', $d->part_id)->where('spare_part_price_list.colour_id', $d->colour_id)->select('spare_part_price_list.id')->first();
                        if ($spare_part_price) {
                            $spare_part_price_id = $spare_part_price->id;
                        }

                        $order_request = \App\Models\OrderRequest::updateOrCreate(['els_system_id' => $d->els_system_id]);

                        \App\Models\OrderRequestPart::updateOrCreate(['part_id' => $d->part_id], [
                            'request_order_id' => $order_request->id,
                            'brand_id' => $d->brand_id,
                            'model_id' => $d->model_id,
                            'part_type_id' => $product_type_id,
                            'colour_id' => $d->colour_id,
                            'quantity'    => 1,
                            'spare_part_price_id' => $spare_part_price_id,
                            'status' => '0',
                        ]);

                        \App\Models\AllocatedBarcode::updateOrCreate([
                            'els_system_id' => $d->els_system_id,
                            'barcode_id' => $d->barcode_id,
                        ], [
                            'remark' => $d->remark,
                        ]);
                        \App\Models\ReceivedPurchaseOrderPartsBarcode::where('id', $d->barcode_id)->update(['status' => '3']);
                        \App\Models\InwardDate::where('els_system_id', $d->els_system_id)->update(['status' => '0']);
                    }
                }
            }
            // return response()->json(['status'=>true, 'data' => [], 'code'=>200, 'message'=> 'Product successfully imported.']);
        }
        // return response()->json(['status'=>false, 'data' => [], 'code'=>401, 'message'=> 'Please choose a correct formate.']);
        return redirect()->back();
    }

    public function collectProductPart(Request $request)
    {
        $data = [];
        $title = "Allocated Product Part";
        return view('els_product/collect_product_part', compact('title'));
    }

    public function collectELSProductPartsDetails(Request $request)
    {
        $data = \App\Models\ElsSystemInfoDtailsModel::join('brand', function ($join) {
            $join->on('els_system_info_details.brand_id', 'brand.id');
        })->join('model', function ($join) {
            $join->on('els_system_info_details.model_id', 'model.id');
        })->join('colour', function ($join) {
            $join->on('els_system_info_details.colour_id', 'colour.id');
        })->leftjoin('els_system_allocated_engineer', function ($join) {
            $join->on('els_system_info_details.id', 'els_system_allocated_engineer.els_system_id');
            $join->where('els_system_allocated_engineer.active', '1');
            $join->whereNull('els_system_allocated_engineer.deleted_at');
        })->where(['barcode' => $request->barcode])->whereIn('els_system_info_details.current_stage', ['2'])->select('els_system_info_details.*', 'brand.bname', 'model.mname', 'colour.name as colour_name', 'els_system_allocated_engineer.engineer_id')->first();

        if ($data) {
            $parts = \App\Models\OrderRequest::join('els_order_request_parts', function ($join) {
                $join->on('els_order_request.id', 'els_order_request_parts.request_order_id');
                $join->whereNull('els_order_request_parts.deleted_at');
            })->leftjoin('received_purchase_order_parts_list', function ($join) {
                $join->on('els_order_request_parts.brand_id', 'received_purchase_order_parts_list.brand_id');
                $join->on('els_order_request_parts.model_id', 'received_purchase_order_parts_list.model_id');
                $join->on('els_order_request_parts.part_type_id', 'received_purchase_order_parts_list.product_type_id');
                $join->on('els_order_request_parts.part_id', 'received_purchase_order_parts_list.part_id');
                $join->whereNull('received_purchase_order_parts_list.deleted_at');
            })->leftjoin('received_parts_barcode_list', function ($join) {
                $join->on('received_purchase_order_parts_list.id', 'received_parts_barcode_list.received_part_id');
            })->where('els_order_request.els_system_id', $data->id)->groupBy('els_order_request_parts.id')->select('els_order_request_parts.*', 'els_order_request.els_system_id')->get();
            $part_list = \App\Models\MasterPartModel::where('status', '1')->whereNull('deleted_at')->pluck('name', 'id');
            $details_html = view('els_product/barcode_collect_details', compact('data'))->render();
            $engineer = \App\Models\User::join('role', function ($join) {
                $join->on('users.role', 'role.id');
            })->where('role.role_name', 'engineer')
                ->where('users.id', $data->engineer_id)
                ->pluck('users.name', 'users.id');


            $current_phase = $data->current_status;

            $statuss = DB::table('els_relation_status')->where('phase', $current_phase)->where('type', 2)->pluck('status');


            $statuss = DB::table('els_product_status')->whereIn('id', $statuss)->whereNull('deleted_at')->pluck('name', 'id');
            $product_parts_html = view('els_product/els_collect_parts_form', compact('data', 'parts', 'part_list', 'engineer', 'statuss'))->render();
            return response()->json(['status' => true, 'data' => [], 'details_html' => \Helper::compressHtml($details_html), 'product_parts_html' => \Helper::compressHtml($product_parts_html), 'code' => 200, 'message' => 'Order request list']);
        }
        return response()->json(['status' => false, 'data' => [], 'code' => 401, 'message' => 'No Data Found.Also check in Distributor']);
    }

    public function collectBarcodeDetails(Request $request)
    {
        $data = \App\Models\ReceivedPurchaseOrderPartsBarcode::join('received_purchase_order_parts_list', function ($join) {
            $join->on('received_parts_barcode_list.received_part_id', 'received_purchase_order_parts_list.id');
            $join->whereNull('received_purchase_order_parts_list.deleted_at');
        })->where('received_purchase_order_parts_list.model_id', $request->model_id)->where('received_purchase_order_parts_list.part_id', $request->part_id)->where('received_parts_barcode_list.barcode', $request->barcode)->where('received_parts_barcode_list.iqc_status', '1')->where('received_parts_barcode_list.status', '2')->count();
        if ($data) {
            return response()->json(['status' => true, 'data' => [], 'code' => 200, 'message' => 'Barcode Exist']);
        }
        return response()->json(['status' => false, 'data' => [], 'code' => 401, 'message' => 'Barcode is not valid.']);
    }

    public function collectProductPartPerBarcode(Request $request)
    {
        $product_type_id = 3;
        //print_r($request->els_system_id);
        //print_r($request->engineer_id);
        print_r($request->remark);
        //print_r($request->status);
        //die("hi");

        $els_system_info_details = DB::table('els_system_info_details')->where('id', $request->els_system_id)->first();

        if ($els_system_info_details->current_stage == 2) {

            \App\Models\AssignEngineer::where('els_system_id', $request->els_system_id)->update(['active' => '0']);

            $savid = \App\Models\AssignEngineer::Create([
                'els_system_id' => $request->els_system_id,
                'engineer_id' => $request->engineer_id,
                'status' => $request->status,
                'remark' => $request->remark,
                'active' => '1',
            ]);

            $save = \App\Models\ELSProductStatusLog::updateOrCreate([
                'els_system_allocation_id' => $request->engineer_id,
                'vendor_id' => NULL,
                'els_system_id' => $request->els_system_id,
                'status' => $request->status,
            ]);


            DB::table('els_system_allocated_engineer')
                ->where('id', $savid->id)
                ->update(['remark' => $request->remark, 'type' => 2, 'user_id' => Auth()->user()->id]);


            DB::table('els_system_info_details')
                ->where('id', $request->els_system_id)
                ->update(['current_stage' => '1', 'status' => $request->status, 'current_status' => $request->status, 'current_phase' => '2']);

            if ($request->status == 31) {
                DB::table('els_system_info_details')
                    ->where('id', $request->els_system_id)
                    ->update(['stock_in' => 0]);
            }




            return redirect()->route('collect-parts-product-list');
        } else {
            return response()->json(['status' => false, 'data' => [], 'code' => 401, 'message' => 'ELS IS IN DISTRIBUTOR PANEL']);
        }
    }


    public function addMorecollect(Request $request)
    {
        $data = \App\Models\ElsSystemInfoDtailsModel::where('barcode', $request->barcode)->first();
        if ($data) {
            $part_list = \App\Models\MasterPartModel::join('spare_part_list', function ($join) {
                $join->on('part.id', 'spare_part_list.part_id');
                $join->whereNull('spare_part_list.deleted_at');
            })->where('spare_part_list.model_id', $data->model_id)->where('part.status', '1')->whereNull('part.deleted_at')->pluck('part.name', 'part.id');
            $key = $request->id;
            $html = view('els_product/add-more-collect', compact('data', 'part_list', 'key'))->render();
            return response()->json(['status' => true, 'data' => [], 'html' => \Helper::compressHtml($html), 'code' => 200, 'message' => 'Add more parts']);
        }
    }

    public function getcollectColours(Request $request)
    {
        $data = \App\Models\SparePartPriceList::join('spare_part_list', function ($join) {
            $join->on('spare_part_list.id', 'spare_part_price_list.series_id');
            $join->whereNull('spare_part_list.deleted_at');
        })->join('colour', function ($join) {
            $join->on('colour.id', 'spare_part_price_list.colour_id');
            $join->whereNull('colour.deleted_at');
        })->where('spare_part_list.model_id', $request->model_id)->where('spare_part_list.part_id', $request->part_id)->where('colour.status', '1')->select('colour.name', 'colour.id')->groupBy('colour.id')->get();
        return response()->json(['status' => true, 'data' => $data, 'code' => 200, 'message' => 'colour listed successfully']);
    }

    public function collectProductStatus(Request $request)
    {
        $els_system = \App\Models\AssignEngineer::find($request->id);
        if ($els_system) {
            $els_system_id = $els_system->els_system_id;
            $s = \App\Models\ELSProductStatus::find($request->status);
            $data = \App\Models\ElsSystemInfoDtailsModel::where('id', $els_system_id)->update(['status' => $s->sub_status_id]);

            $data = \App\Models\AssignEngineer::join('els_system_allocated_parts_barcode', function ($join) {
                $join->on('els_system_allocated_engineer.els_system_id', 'els_system_allocated_parts_barcode.els_system_id');
                $join->whereNull('els_system_allocated_parts_barcode.deleted_at');
            })->where('els_system_allocated_engineer.id', $request->id)->select('els_system_allocated_parts_barcode.els_system_id', 'els_system_allocated_parts_barcode.barcode_id')->get()->toArray();
            if ($data) {
                $id = array_unique(array_column($data, 'barcode_id'));
                $status = '0';
                if ($s->sub_status_id != 1) {
                    $stockUpdate = \App\Models\ReceivedPurchaseOrderPartsBarcode::whereIn('id', $id)->update(['status' => $status]);
                }
            }
            $save = \App\Models\AssignEngineer::updateOrCreate([
                'id' => $request->id,
            ], [
                'status' => $request->status,
            ]);
            if ($s->sub_status_id != 1) {
                $save = \App\Models\ELSProductStatusLog::updateOrCreate([
                    'els_system_allocation_id' => $els_system->id,
                    'vendor_id' => $request->vendor_id,
                    'els_system_id' => $els_system_id,
                    'status' => $s->sub_status_id,
                ]);
            }
        }
        return response()->json(['status' => true, 'data' => [], 'code' => 200, 'message' => 'Status update successfully']);
    }

    public function removecollectBarcode(Request $request)
    {
        if ($request->request_part_id) {
            \App\Models\OrderRequestPart::where('id', $request->request_part_id)->delete();
        }
        if ($request->barcode) {
            $data = \App\Models\ReceivedPurchaseOrderPartsBarcode::where('barcode', $request->barcode)->first();
            if ($data) {
                \App\Models\AllocatedBarcode::where('barcode_id', $data->id)->delete();
                \App\Models\ReceivedPurchaseOrderPartsBarcode::where('id', $data->id)->update(['status' => '2', 'remark' => 'Returned parts']);
            }
        }
        return response()->json(['status' => true, 'data' => [], 'code' => 200, 'message' => 'Barcode remove successfully']);
    }


    //




    //////

    public function ShowGoodReceiveNotes(Request $request)
    {
        $data = [];
        $data['items'] = DB::table('part')->get();
        $data['colours'] = DB::table('colour')->get();
        $data['models'] = DB::table('model')->get();
        // dd($data['models']);
        $data['vendors'] = MasterVendorModel::all();
        $data['customers'] = User::get();
        // if (\Helper::checkMenuElegible()) {
        $data['title'] = "Good Re3ceive Notes";
        return view('good-receive-notes', $data);
        // }
        abort(403, "Don't have permission to access.");
    }
    public function renewPins()
    {
        return view('renew_pin');
    }

    public function StoreGoodReceiveNotes(Request $request)
    {
        dd($request->all());
    }
}
