<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\MasterCategoryModel;
use DB;

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
	$orders = DB::table('els_system_packaging_video')
                ->select('video_path')
                ->get();
                
        foreach($orders as $key =>$value)
        {
            $vb=$value->video_path;
             $ass=explode('/',$vb);
            $arr[]=$ass[2];
        }
		
		
        return $arr;
    }

     
    public function index()
    {
        $title="Dashboard";
		$data = \App\Models\ElsSystemInfoDtailsModel::join('model',function($join){
			$join->on('els_system_info_details.model_id','model.id');
		})
		->whereNull('els_system_info_details.deleted_at')
		->groupBy('els_system_info_details.model_id')->selectRaw('model.id,model.mname,count(distinct els_system_info_details.id)as in_stock')->get();
		$status = \App\Models\ELSProductStatus::whereNull('deleted_at')->where('status','1')->get();
        return view('dashboard/home',compact('title','status','data'));
    }
	
	public function downloadInventoryReport(Request $request){
		header("Content-Type: application/xls");    
		header("Content-Disposition: attachment; filename=DeviceInventoryReport.xls");  
		header("Pragma: no-cache"); 
		header("Expires: 0");
		
		$columnHeader = "Sr NO"."\t"."Model"."\t"."In Stock(Qty)"."\t"."In Stock(Prexo)"."\t";
		
		$status = \App\Models\ELSProductStatus::whereNull('deleted_at')->where('status','1')->get();
		foreach($status as $s){
			$columnHeader .= $s->name."\t";	
		}
		$data = \App\Models\ElsSystemInfoDtailsModel::join('model',function($join){
			$join->on('els_system_info_details.model_id','model.id');
		})->whereNull('els_system_info_details.deleted_at')->groupBy('els_system_info_details.model_id')->selectRaw('model.id,model.mname,count(distinct els_system_info_details.id)as in_stock')->get();
		
		$i = 1;
		$setData='';	
		if(!$data->isEmpty())
		{
			foreach($data as $value)
			{
				$rowData = '';
				$rowData .= '"' . $i . '"' . "\t";
				$rowData .= '"' . $value->mname . '"' . "\t";
				$rowData .= '"' . $value->in_stock . '"' . "\t";
				$rowData .= '"0"' . "\t";
				foreach($status as $s){
					$count = \Helper::getStatusModelCount($value->id,$s->id);
					$rowData .= '"' . $count . '"' . "\t";
				}				
				$setData .= trim($rowData)."\n";
				$i++;
			}			
		}
		echo ucwords($columnHeader)."\n".$setData."\n";	
	}

    public function getDeviceInventoryList(Request $request){
		$data = \App\Models\ElsSystemInfoDtailsModel::leftjoin('els_system_allocated_engineer',function($join){
			$join->on('els_system_info_details.id','els_system_allocated_engineer.els_system_id');
			$join->whereNull('els_system_allocated_engineer.deleted_at');						
			$join->where('els_system_allocated_engineer.active','1');
		})->leftjoin('els_product_status',function($join){
			$join->on('els_product_status.id','els_system_allocated_engineer.status');
		})->join('brand',function($join){
			$join->on('els_system_info_details.brand_id','brand.id');
		})->join('model',function($join){
			$join->on('els_system_info_details.model_id','model.id');
		})->join('colour',function($join){
			$join->on('els_system_info_details.colour_id','colour.id');
		})->join('ram',function($join){
			$join->on('els_system_info_details.ram','ram.id');
		})->join('rom',function($join){
			$join->on('els_system_info_details.rom','rom.id');
		})->join('grade',function($join){
			$join->on('els_system_info_details.grade','grade.id');
		})->whereNull('els_system_info_details.deleted_at')->orderBy('els_system_info_details.resived_date','DESC')->groupBy('els_system_info_details.id');
		
		$where_like = false;		
		$columnsArr = ['els_system_info_details.resived_date','els_system_info_details.sku_no','els_system_info_details.barcode','model.mname','ram.name','rom.name','grade.name','colour.name','els_product_status.name'];
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
		
		$totalRecord = $data->count();	
		if($request->get('length')){
			$data = $data->skip($request->get('start'))->take($request->get('length'));
		}
		
		$data = $data->selectRaw('els_system_info_details.*,ram.name as ram,rom.name as rom,grade.name as grade,brand.bname,model.mname,colour.name as colour_name,els_product_status.name as current_status')->get();
		return response()->json(['status'=>true, 'data' => $data,'recordsTotal'=>$totalRecord,'recordsFiltered'=>$totalRecord, 'code'=>200, 'message'=> 'Engneer work report listed successfully']);
	}
	
	public function downloadDeviceInventoryReport(Request $request){
		header("Content-Type: application/xls");    
		header("Content-Disposition: attachment; filename=DeviceStatusWiseReport.xls");  
		header("Pragma: no-cache"); 
		header("Expires: 0");
		
		$columnHeader = "Sr NO"."\t"."Received Date"."\t"."Barcode"."\t"."SKU Number"."\t"."Brand"."\t"."Model"."\t"."Colour"."\t"."Current Status"."\t"."RAM"."\t"."ROM"."\t"."Grade"."\t";		
		
		$data = \App\Models\ElsSystemInfoDtailsModel::leftjoin('els_system_allocated_engineer',function($join){
			$join->on('els_system_info_details.id','els_system_allocated_engineer.els_system_id');
			$join->whereNull('els_system_allocated_engineer.deleted_at');			
			$join->where('els_system_allocated_engineer.active','1');
		})->leftjoin('els_product_status',function($join){
			$join->on('els_product_status.id','els_system_allocated_engineer.status');
		})->join('brand',function($join){
			$join->on('els_system_info_details.brand_id','brand.id');
		})->join('model',function($join){
			$join->on('els_system_info_details.model_id','model.id');
		})->join('colour',function($join){
			$join->on('els_system_info_details.colour_id','colour.id');
		})->join('ram',function($join){
			$join->on('els_system_info_details.ram','ram.id');
		})->join('rom',function($join){
			$join->on('els_system_info_details.rom','rom.id');
		})->join('grade',function($join){
			$join->on('els_system_info_details.grade','grade.id');
		})->whereNull('els_system_info_details.deleted_at')->orderBy('els_system_info_details.resived_date','DESC')->groupBy('els_system_info_details.id');
		
		$data = $data->selectRaw('els_system_info_details.*,ram.name as ram,rom.name as rom,grade.name as grade,brand.bname,model.mname,colour.name as colour_name,els_product_status.name as current_status')->get();
		
		$i = 1;
		$setData='';	
		if(!$data->isEmpty())
		{
			foreach($data as $value)
			{
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
				$setData .= trim($rowData)."\n";
				$i++;
			}			
		}
		echo ucwords($columnHeader)."\n".$setData."\n";	
	}
	
	public function getDeviceBackwardTracking(Request $request){
		$data = \App\Models\ElsSystemInfoDtailsModel::leftjoin('vendor',function($join){
			$join->on('els_system_info_details.vendor_id','vendor.id');
		})->join('els_product_sub_status',function($join){
			$join->on('els_system_info_details.status','els_product_sub_status.id');
			$join->whereNull('els_product_sub_status.deleted_at');
		})->where('barcode',$request->barcode)->select('els_system_info_details.*','vendor.vname','els_product_sub_status.name as sub_status')->first();
		
		if($data){
			$html = view('dashboard/device_backward_tracking',compact('data'))->render();
			return response()->json(['status'=>true, 'data' => [], 'html' => \Helper::compressHtml($html), 'code'=>200, 'message'=> 'Device backward tracking']);
		}
		return response()->json(['status'=>false, 'data' => [], 'code'=>401, 'message'=> 'No Data Found']);
	}
	
	public function downloadDeviceBackwardTracking(Request $request){
		$data = \App\Models\ElsSystemInfoDtailsModel::leftjoin('vendor',function($join){
			$join->on('els_system_info_details.vendor_id','vendor.id');
		})->join('els_product_sub_status',function($join){
			$join->on('els_system_info_details.status','els_product_sub_status.id');
			$join->whereNull('els_product_sub_status.deleted_at');
		})->where('barcode',$request->barcode)->select('els_system_info_details.*','vendor.vname','els_product_sub_status.name as sub_status')->first();
		
		$i = 1;
		$setData = '';	
		if($data)
		{
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
			$setData .= trim($rowData)."\n";
			$rowData = '';	
			foreach($inwrd as $key=> $d)
			{
				if($d->status){
					$current_in_stock = 1;
				}
				$rowData = '';
				$rowData .= '"Inward date '.($key+1).'"' . "\t";
				$rowData .= '"' . date('d/m/Y',strtotime($d->received_date)) . '"' . "\t";
				$setData .= trim($rowData)."\n";
			}
			foreach($or as $v)
			{
				$rowData = '';
				$rowData .= '"Order Request Date"' . "\t";
				$rowData .= '"' . date('d/m/Y',strtotime($v->created_at)) . '"' . "\t";
				$setData .= trim($rowData)."\n";
				$rowData = '';
				$rowData .= '"Order Request Parts"' . "\t";
				$rowData .= '"' . $v->part_name . '"' . "\t";
				$setData .= trim($rowData)."\n";
			}
			if($bar){
				$rowData = '';
				$rowData .= '"Allocated Barcode"' . "\t";
				$rowData .= '"' . $bar->barcode . '"' . "\t";
				$setData .= trim($rowData)."\n";
			}			
			foreach($allocation as $v)
			{
				$rowData = '';
				$rowData .= '"Enginner Allocation Date"' . "\t";
				$rowData .= '"' . date('d/m/Y',strtotime($v->created_at)) . '"' . "\t";
				$setData .= trim($rowData)."\n";
				$rowData = '';
				$rowData .= '"Enginner Name"' . "\t";
				$rowData .= '"' . $v->name . '"' . "\t";
				$setData .= trim($rowData)."\n";
				$rowData = '';
				$rowData .= '"Status"' . "\t";
				$rowData .= '"' . $v->status . '"' . "\t";
				$setData .= trim($rowData)."\n";
			}
			if($current_in_stock){
				$rowData = '';
				$rowData .= '"Status"' . "\t";
				$rowData .= '"Not assign"' . "\t";
				$setData .= trim($rowData)."\n";
			}
			if($data->sub_status){
				foreach($status as $sd){
					$rowData = '';
					$rowData .= '"Status"' . "\t";
					$rowData .= '"' . $sd->name .'('.date("d/m/Y",strtotime($sd->created_at)).')'.'"' . "\t";
					$setData .= trim($rowData)."\n";
					
					if($sd->vname){
						$rowData = '';
						$rowData .= '"Out Vendor"' . "\t";
						$rowData .= '"' . $sd->vname . '"' . "\t";
						$setData .= trim($rowData)."\n";
					}
				}
			}			
			if($warrenty){
				$rowData = '';
				$rowData .= '"Warrenty Start Date"' . "\t";
				$rowData .= '"' . date('d/m/Y',strtotime($warrenty->start_date)) . '"' . "\t";
				$setData .= trim($rowData)."\n";
				$rowData = '';
				$rowData .= '"Warrenty End Date"' . "\t";
				$rowData .= '"' . date('d/m/Y',strtotime($warrenty->end_date)) . '"' . "\t";
				$setData .= trim($rowData)."\n";
			}
			if($data->vname){
				$rowData = '';
				$rowData .= '"Vendor"' . "\t";
				$rowData .= '"' . $data->vname . '"' . "\t";
				$setData .= trim($rowData)."\n";
			}
			echo $setData."\n";	
		}else{
			return redirect('dashboard');	
		}
	}
	
	public function getSparePartBackwardTracking(Request $request){
		$data = \App\Models\ReceivedPurchaseOrderPartsBarcode::join('received_purchase_order_parts_list',function($join){
			$join->on('received_purchase_order_parts_list.id','received_parts_barcode_list.received_part_id');
		})->join('purchase_order_list',function($join){
			$join->on('purchase_order_list.id','received_purchase_order_parts_list.purchase_order_id');
		})->join('vendor',function($join){
			$join->on('vendor.id','purchase_order_list.vendor_id');
		})->leftjoin('users',function($join){
			$join->on('users.id','received_parts_barcode_list.tester_id');
		})->where('received_parts_barcode_list.barcode',$request->barcode)->select('received_parts_barcode_list.*','users.name','vendor.vname')->first();
		if($data){
			$html = view('dashboard/spare_part_backward_tracking',compact('data'))->render();
			return response()->json(['status'=>true, 'data' => [], 'html' => \Helper::compressHtml($html), 'code'=>200, 'message'=> 'Spare Part backward tracking']);
		}
		return response()->json(['status'=>false, 'data' => [], 'code'=>401, 'message'=> 'No Data Found']);
	}
	
	public function downloadSparePartBackwardTracking(Request $request){
		$data = \App\Models\ReceivedPurchaseOrderPartsBarcode::join('received_purchase_order_parts_list',function($join){
			$join->on('received_purchase_order_parts_list.id','received_parts_barcode_list.received_part_id');
		})->join('purchase_order_list',function($join){
			$join->on('purchase_order_list.id','received_purchase_order_parts_list.purchase_order_id');
		})->join('vendor',function($join){
			$join->on('vendor.id','purchase_order_list.vendor_id');
		})->leftjoin('users',function($join){
			$join->on('users.id','received_parts_barcode_list.tester_id');
		})->where('received_parts_barcode_list.barcode',$request->spare_barcode)->select('received_parts_barcode_list.*','users.name','vendor.vname')->first();

		$i = 1;
		$setData = '';	
		if($data)
		{
			header("Content-Type: application/xls");    
			header("Content-Disposition: attachment; filename=SparePartBackwardTracking.xls");  
			header("Pragma: no-cache"); 
			header("Expires: 0");		
		
			$status = ''; 
			$ab = \Helper::getAllocatedBarcode($data->id);
			if($data->status == '2'){
				$status = 'Available';
			}
			if($data->status == '3'){
				$status = 'Allocated';
			}
			if($data->status == '0'){
				$status = 'Used';
			}
			
			$rowData = '';	
			$rowData .= '"Barcode"' . "\t";
			$rowData .= '"' . $data->barcode . '"' . "\t";
			$setData .= trim($rowData)."\n";
			$rowData = '';	
			$rowData .= '"Inward date"' . "\t";
			$rowData .= '"' . date('d/m/Y',strtotime($data->created_at)) . '"' . "\t";
			$setData .= trim($rowData)."\n";
			$rowData = '';	
			$rowData .= '"Price"' . "\t";
			$rowData .= '"' . number_format($data->price,2) . '"' . "\t";
			$setData .= trim($rowData)."\n";
			$iqc_status = '';
			if($data->iqc_status){
				$iqc_status = 'Pass';
			}elseif($data->iqc_status == '0'){
				$iqc_status = 'Failed';
			}
			$rowData = '';	
			$rowData .= '"IQC Status"' . "\t";
			$rowData .= '"' . $iqc_status . '"' . "\t";
			$setData .= trim($rowData)."\n";
			$rowData = '';	
			$rowData .= '"Tester Name"' . "\t";
			$rowData .= '"' . $data->name . '"' . "\t";
			$setData .= trim($rowData)."\n";
						
			foreach($ab as $v)
			{
				$rowData = '';
				$rowData .= '"Assigned Date"' . "\t";
				$rowData .= '"' . date('d/m/Y',strtotime($v->created_at)) . '"' . "\t";
				$setData .= trim($rowData)."\n";
				$rowData = '';
				$rowData .= '"Assigned Device Barcode"' . "\t";
				$rowData .= '"' . $v->barcode . '"' . "\t";
				$setData .= trim($rowData)."\n";
			}	
			$rowData = '';
			$rowData .= '"Current Status"' . "\t";
			$rowData .= '"' . $status . '"' . "\t";
			$setData .= trim($rowData)."\n";
			
			if($data->vname){
				$rowData = '';
				$rowData .= '"Vendor"' . "\t";
				$rowData .= '"' . $data->vname . '"' . "\t";
				$setData .= trim($rowData)."\n";
			}
			echo $setData."\n";
		}else{
			return redirect('dashboard');	
		}
	}
}
