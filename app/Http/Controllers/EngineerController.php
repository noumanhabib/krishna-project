<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EngineerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function dateWiseAllocatedDevice(Request $request)
    {
		if(\Helper::checkMenuElegible()){
			$title = 'Engineer Date Wise Allocated Devices';
			return view("report/engineer-date-wise-allocation-report",compact('title'));
		}
		abort(403,"Don't have permission to access.");
    }  
     
	public function getAllocatedDeviceList(Request $request){
		$data = \App\Models\AssignEngineer::join('els_system_info_details',function($join){
			$join->on('els_system_allocated_engineer.els_system_id','els_system_info_details.id');
		})->join('brand',function($join){
			$join->on('els_system_info_details.brand_id','brand.id');
		})->join('model',function($join){
			$join->on('els_system_info_details.model_id','model.id');
		})->join('colour',function($join){
			$join->on('els_system_info_details.colour_id','colour.id');
		})->join('users',function($join){
			$join->on('els_system_allocated_engineer.engineer_id','users.id');
		});
		$where_like = false;		
		$columnsArr = ['els_system_info_details.barcode','els_system_info_details.imei_1','els_system_info_details.imei_2','users.name','brand.bname','model.mname','colour.name'];
		foreach($columnsArr as $columns){
			if($request->get('search')['value']){
				if($where_like){
					$where_like.= ' OR '.$columns.' like "%'.$request->get('search')['value'].'%"';
				}else{
					$where_like.= $columns.' like "%'.$request->get('search')['value'].'%"';
				}
			}
		}		
		if($where_like){
			$data = $data->whereRaw('('.$where_like.')');
		}
		if($request->filter_date){
			$data = $data->whereDate('els_system_allocated_engineer.created_at',$request->filter_date);
		}
		$totalRecord = $data->count();	
		if($request->get('length')){
			$data = $data->skip($request->get('start'))->take($request->get('length'));
		}
		$data = $data->selectRaw('els_system_info_details.id,els_system_info_details.barcode,els_system_info_details.imei_1,els_system_info_details.imei_2,brand.bname,model.mname,users.name,colour.name as colour_name,DATE_FORMAT(els_system_allocated_engineer.created_at,"%d/%m/%Y") as date,(SELECT GROUP_CONCAT(CONCAT(part.name,"(",received_parts_barcode_list.barcode,")")) FROM `els_system_allocated_parts_barcode` JOIN received_parts_barcode_list ON received_parts_barcode_list.id=els_system_allocated_parts_barcode.barcode_id JOIN received_purchase_order_parts_list ON received_purchase_order_parts_list.id=received_parts_barcode_list.received_part_id JOIN part ON part.id=received_purchase_order_parts_list.part_id WHERE els_system_allocated_parts_barcode.els_system_id=els_system_info_details.id AND els_system_allocated_parts_barcode.deleted_at IS NULL) as part_barcode')->get();
		return response()->json(['status'=>true, 'data' => $data,'recordsTotal'=>$totalRecord,'recordsFiltered'=>$totalRecord, 'code'=>200, 'message'=> 'Engineer date wise allocated device listed successfully']);		
	}
	
	public function exportAllocatedDeviceList(Request $request){
		header("Content-Type: application/xls");    
		header("Content-Disposition: attachment; filename=EngineerDateWiseAllocatedDevice.xls");  
		header("Pragma: no-cache"); 
		header("Expires: 0");

		$columnHeader = "Sr NO"."\t"."Date"."\t"."Assignd Engineer"."\t"."Consumed Parts"."\t"."UIN"."\t"."IMEI 1"."\t"."IMEI 2"."\t"."Brand"."\t"."Model"."\t"."Colour"."\t";
		
		$data = \App\Models\AssignEngineer::join('els_system_info_details',function($join){
			$join->on('els_system_allocated_engineer.els_system_id','els_system_info_details.id');
		})->join('brand',function($join){
			$join->on('els_system_info_details.brand_id','brand.id');
		})->join('model',function($join){
			$join->on('els_system_info_details.model_id','model.id');
		})->join('colour',function($join){
			$join->on('els_system_info_details.colour_id','colour.id');
		})->join('users',function($join){
			$join->on('els_system_allocated_engineer.engineer_id','users.id');
		});		
		if($request->filter_date){
			$data = $data->whereDate('els_system_allocated_engineer.created_at',$request->filter_date);
		}
		$data = $data->selectRaw('els_system_info_details.id,els_system_info_details.barcode,els_system_info_details.imei_1,els_system_info_details.imei_2,brand.bname,model.mname,users.name,colour.name as colour_name,DATE_FORMAT(els_system_allocated_engineer.created_at,"%d/%m/%Y") as date,(SELECT GROUP_CONCAT(CONCAT(part.name,"(",received_parts_barcode_list.barcode,")")) FROM `els_system_allocated_parts_barcode` JOIN received_parts_barcode_list ON received_parts_barcode_list.id=els_system_allocated_parts_barcode.barcode_id JOIN received_purchase_order_parts_list ON received_purchase_order_parts_list.id=received_parts_barcode_list.received_part_id JOIN part ON part.id=received_purchase_order_parts_list.part_id WHERE els_system_allocated_parts_barcode.els_system_id=els_system_info_details.id AND els_system_allocated_parts_barcode.deleted_at IS NULL) as part_barcode')->get();

		$i = 1;
		$setData='';	
		if(!$data->isEmpty())
		{
			foreach($data as $value)
			{
				$rowData = '';
				$rowData .= '"' . $i . '"' . "\t";
				$rowData .= '"' . $value->date . '"' . "\t";
				$rowData .= '"' . $value->name . '"' . "\t";
				$rowData .= '"' . $value->part_barcode . '"' . "\t";
				$rowData .= '"' . $value->barcode . '"' . "\t";
				$rowData .= '"' . $value->imei_1 . '"' . "\t";
				$rowData .= '"' . $value->imei_2 . '"' . "\t";
				$rowData .= '"' . $value->bname . '"' . "\t";
				$rowData .= '"' . $value->mname . '"' . "\t";
				$rowData .= '"' . $value->colour_name . '"' . "\t";
				$setData .= trim($rowData)."\n";
				$i++;
			}			
		}
		echo ucwords($columnHeader)."\n".$setData."\n";		
	}
	
	public function viewDispatchParts(Request $request){
		if(\Helper::checkMenuElegible()){
			$title="Device Dispatch List";
			return view('report/dispatch_parts',compact('title'));
		}
		abort(403,"Don't have permission to access.");
	}
	
	public function getDispatchParts(Request $request){
		$data = \App\Models\ReceivedPurchaseOrderPartsBarcode::join('received_purchase_order_parts_list',function($join){
			$join->on('received_parts_barcode_list.received_part_id','received_purchase_order_parts_list.id');
		})->join('purchase_order_list',function($join){
			$join->on('received_purchase_order_parts_list.purchase_order_id','purchase_order_list.id');
		})->join('part',function($join){
			$join->on('received_purchase_order_parts_list.part_id','part.id');
		})->join('brand',function($join){
			$join->on('received_purchase_order_parts_list.brand_id','brand.id');
		})->join('model',function($join){
			$join->on('received_purchase_order_parts_list.model_id','model.id');
		})->join('colour',function($join){
			$join->on('received_purchase_order_parts_list.colour_id','colour.id');
		})->join('vendor',function($join){
			$join->on('purchase_order_list.vendor_id','vendor.id');
		})->whereNotNull('received_parts_barcode_list.dispatch_date');
		
		$where_like = false;		
		$columnsArr = ['brand.bname','model.mname','colour.name','part.name','vendor.vname','received_parts_barcode_list.dispatch_date','received_parts_barcode_list.remark'];
		foreach($columnsArr as $columns){
			if($request->get('search')['value']){
				if($where_like){
					$where_like.= ' OR '.$columns.' like "%'.$request->get('search')['value'].'%"';
				}else{
					$where_like.= $columns.' like "%'.$request->get('search')['value'].'%"';
				}
			}
		}		
		if($where_like){
			$data = $data->whereRaw('('.$where_like.')');
		}
		if($request->filter_date){
			list($start_date, $end_date) = explode('-',$request->get('filter_date'));
			$s_date= date('Y-m-d',strtotime(str_replace('/', '-', trim($start_date))));
			$e_date = date('Y-m-d',strtotime(str_replace('/', '-', trim($end_date)) . " + 1 day"));
			$data = $data->whereBetween('received_parts_barcode_list.dispatch_date', [$s_date, $e_date]);
		}
		$totalRecord = $data->count();	
		if($request->get('length')){
			$data = $data->skip($request->get('start'))->take($request->get('length'));
		}
		$data = $data->selectRaw('received_parts_barcode_list.id,brand.bname,model.mname,vendor.vname,colour.name as colour_name,part.name as part_name,received_parts_barcode_list.remark,received_parts_barcode_list.vendor_name,received_parts_barcode_list.barcode,DATE_FORMAT(received_parts_barcode_list.dispatch_date,"%d/%m/%Y") as dispatch_date')->orderBy('received_parts_barcode_list.dispatch_date','ASC')->get();
		return response()->json(['status'=>true, 'data' => $data,'recordsTotal'=>$totalRecord,'recordsFiltered'=>$totalRecord, 'code'=>200, 'message'=> 'Dispatch parts listed successfully']);
	}
	
	public function uploadDispatchParts(Request $request){
// 		set_time_limit(0);
		$file = $request->file('file_csv');
		if(in_array(strtoupper($file->getClientOriginalExtension()),['CSV'])){
			$fileD = fopen($file,"r");
			$column=fgetcsv($fileD);
			if($column){
				foreach($column as $k => $d){
					if($d == 'PIN'){
						$barcode_key = $k;
					}
						if($d == 'VENDOR'){
						$Veondor_key = $k;
					}
				}
			}
			$i = 2;
			$error = false;
			$massage = '';
			$dataArr = [];
			
			$datetime = date('Y-m-d',strtotime("tomorrow"));
			while(!feof($fileD)){	
				$rowData = fgetcsv($fileD);	
				if(isset($rowData[$barcode_key])){
					\App\Models\ReceivedPurchaseOrderPartsBarcode::where('barcode',$rowData[$barcode_key])->update(['dispatch_date'=>$datetime,'status'=>'4','vendor_name'=>$rowData[$Veondor_key]]);
				}
			}			
		}
		return redirect()->back();
	}
	
		public function exportDispatchParts(Request $request){
		header("Content-Type: application/xls");    
		header("Content-Disposition: attachment; filename=DispatchPartList.xls");  
		header("Pragma: no-cache"); 
		header("Expires: 0");

		$columnHeader = "Sr NO"."\t"."Date"."\t"."Brand"."\t"."Model"."\t"."Colour"."\t"."Parts Name"."\t"."Vendor"."\t"."Pin"."\t"."Remark"."\t"."Dispatch Vendor"."\t";
				
		$data = \App\Models\ReceivedPurchaseOrderPartsBarcode::join('received_purchase_order_parts_list',function($join){
			$join->on('received_parts_barcode_list.received_part_id','received_purchase_order_parts_list.id');
		})->join('purchase_order_list',function($join){
			$join->on('received_purchase_order_parts_list.purchase_order_id','purchase_order_list.id');
		})->join('part',function($join){
			$join->on('received_purchase_order_parts_list.part_id','part.id');
		})->join('brand',function($join){
			$join->on('received_purchase_order_parts_list.brand_id','brand.id');
		})->join('model',function($join){
			$join->on('received_purchase_order_parts_list.model_id','model.id');
		})->join('colour',function($join){
			$join->on('received_purchase_order_parts_list.colour_id','colour.id');
		})->join('vendor',function($join){
			$join->on('purchase_order_list.vendor_id','vendor.id');
		})->whereNotNull('received_parts_barcode_list.dispatch_date');
		
		if($request->filter_date){
			list($start_date, $end_date) = explode('-',$request->get('filter_date'));
			$s_date= date('Y-m-d',strtotime(str_replace('/', '-', trim($start_date))));
			$e_date = date('Y-m-d',strtotime(str_replace('/', '-', trim($end_date)) . " + 1 day"));
			$data = $data->whereBetween('received_parts_barcode_list.dispatch_date', [$s_date, $e_date]);
		}
		$data = $data->selectRaw('received_parts_barcode_list.id,brand.bname,model.mname,vendor.vname,colour.name as colour_name,part.name as part_name,received_parts_barcode_list.remark,received_parts_barcode_list.barcode,received_parts_barcode_list.vendor_name,DATE_FORMAT(received_parts_barcode_list.dispatch_date,"%d/%m/%Y") as dispatch_date')->orderBy('received_parts_barcode_list.dispatch_date','ASC')->get();

		$i = 1;
		$setData='';	
		if(!$data->isEmpty())
		{
			foreach($data as $value)
			{
				$rowData = '';
				$rowData .= '"' . $i . '"' . "\t";
				$rowData .= '"' . $value->dispatch_date . '"' . "\t";
				$rowData .= '"' . $value->bname . '"' . "\t";
				$rowData .= '"' . $value->mname . '"' . "\t";
				$rowData .= '"' . $value->colour_name . '"' . "\t";
				$rowData .= '"' . $value->part_name . '"' . "\t";				
				$rowData .= '"' . $value->vname . '"' . "\t";
				$rowData .= '"' . $value->barcode . '"' . "\t";
				$rowData .= '"' . $value->remark . '"' . "\t";
				$rowData .= '"' . $value->vendor_name . '"' . "\t";
				$setData .= trim($rowData)."\n";
				$i++;
			}			
		}
		echo ucwords($columnHeader)."\n".$setData."\n";	
	}

}
