<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
class RequestOrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
	public function RequestOrderList()
	{
		if(\Helper::checkMenuElegible()){
			$title="Request Order List";

			$data=\App\Models\OrderRequest::leftjoin('els_system_info_details',function($join){ 
					$join->on('els_order_request.els_system_id','els_system_info_details.id');
				})->orderBy('els_order_request.id','DESC')->select('els_order_request.*','els_system_info_details.barcode')->get();
			return view('request_order/request_order_list',compact('title','data'));
		}
		abort(403,"Don't have permission to access.");
	}
    public function RequestOrderForm(Request $request , $id=null)
    {
		$data =[];
		$model=[];
		$part_name_list=\App\Models\MasterPartModel::where('status','1')->whereNull('deleted_at')->pluck('name','id');
		$brand_list=\App\Models\MasterBrandModel::where('bstatus','1')->whereNull('deleted_at')->pluck('bname','id');
		$color_list=\App\Models\MasterColourModel::where('status','1')->whereNull('deleted_at')->pluck('name','id');
		$type_list=\App\Models\ProductTypeModel::where('status','1')->whereNull('deleted_at')->pluck('type','id');
		if($id){
            $title="Edit Request Order";
            $data=\App\Models\OrderRequest::join('els_system_info_details',function($join){ 
				$join->on('els_order_request.els_system_id','els_system_info_details.id');
			})->join('brand',function($join){
				$join->on('els_system_info_details.brand_id','brand.id');
			})->join('model',function($join){
				$join->on('els_system_info_details.model_id','model.id');
			})->join('colour',function($join){
				$join->on('els_system_info_details.colour_id','colour.id');
			})->where('els_order_request.id',$id)->first();
            $parts=\App\Models\OrderRequestPart::where('request_order_id',$id)->get();             
			return view('request_order/edit_request_order',compact('title','brand_list','color_list','type_list','part_name_list','data','model','parts'));
		}else{
			$title="Request Order List";               
			return view('request_order/request_order_form',compact('title','brand_list','color_list','type_list','part_name_list','model'));
		}	
    }

    public function FetchBarcodeDeatils(Request $request)
    {
		$product_type_id = 3;
        $barcode= $request->barcode;
        $data=\App\Models\ElsSystemInfoDtailsModel::select('els_system_info_details.*','brand.bname','model.mname','colour.name as color_name')
		->join('brand','els_system_info_details.brand_id','=','brand.id')
		->join('model','els_system_info_details.model_id','=','model.id')
		->join('colour','els_system_info_details.colour_id','=','colour.id')
		->where('els_system_info_details.barcode','=',$barcode)->first();
		$part_name_list=\App\Models\MasterPartModel::where('status','1')->whereNull('deleted_at')->pluck('name','id');
    	$brand_list=\App\Models\MasterBrandModel::where('bstatus','1')->whereNull('deleted_at')->pluck('bname','id');
    	$model=\App\Models\MasterModel::where('mstatus','1')->whereNull('deleted_at')->pluck('mname','id');
    	$type_list=\App\Models\ProductTypeModel::where('status','1')->whereNull('deleted_at')->pluck('type','id');
		$product_details = view('request_order/product_details',compact('data'))->render();
		$product_part_details = view('request_order/request_order_part_form',compact('data','part_name_list','brand_list','type_list','model','product_type_id'))->render();
		return response()->json(['status'=>true, 'data' => [], 'product_details' => \Helper::compressHtml($product_details), 'product_part_details' => \Helper::compressHtml($product_part_details), 'code'=>200, 'message'=> 'Order request list']); 
    }

    public function GetHtmlMultipleRequestOrder(Request $request)
    {
    	$data =	$model = [];
    	$index = $request->index;
    	$part_name_list=\App\Models\MasterPartModel::where('status','1')->whereNull('deleted_at')->pluck('name','id');
    	$brand_list=\App\Models\MasterBrandModel::where('bstatus','1')->whereNull('deleted_at')->pluck('bname','id');
    	$type_list=\App\Models\ProductTypeModel::where('status','1')->whereNull('deleted_at')->pluck('type','id');
		if($request->barcode){
			$data=\App\Models\ElsSystemInfoDtailsModel::join('brand','els_system_info_details.brand_id','=','brand.id')
			->join('model','els_system_info_details.model_id','=','model.id')
			->join('colour','els_system_info_details.colour_id','=','colour.id')
			->where('els_system_info_details.barcode','=',$request->barcode)->selectRaw('els_system_info_details.*,3 as type_id,1 as quantity')->first();
			$model=\App\Models\MasterModel::where('mstatus','1')->whereNull('deleted_at')->pluck('mname','id');
		}
        $html = view('request_order/add_more_request_order_part',compact('data','part_name_list','brand_list','type_list','model','index'));
         return response()->json(['html' => \Helper::compressHtml($html)]);
    }

    public function FetchSeriesList(Request $request)
    {
         //dd($request->all());
    	 $series=\App\Models\SparePartListModel::select('id','series_no')->where('model_id',$request->model_id)->where('type_id',$request->type_id)->where('part_id',$request->part_id)->get()->toarray();
    	 
    	 $arr = array("code" => 200, "message" =>'success', "data" => $series);
         return \Response::json($arr);
    }
    public function FetchColourList(Request $request)
    {
    	$series_id=$request->id;
		$data=\App\Models\SparePartListModel::join('spare_part_price_list',function($join){
			$join->on('spare_part_list.id','spare_part_price_list.series_id');
			$join->whereNull('spare_part_price_list.deleted_at');
		})->join('colour',function($join){
			$join->on('spare_part_price_list.colour_id','colour.id');
		})->where('spare_part_list.model_id',$request->model_id)->where('spare_part_list.type_id',$request->type_id)->where('spare_part_list.part_id',$request->part_id)->groupBy('spare_part_price_list.id')->select('colour.*')->get();		
		$arr = array("code" => 200, "message" =>'success', "data" => $data);
		return \Response::json($arr);
    }
    public function SaveRequestOrder(Request $request)
    {
    	if($request->request_order_id)
    	{
    		$request_order_id=$request->request_order_id;
    	}
    	else
    	{
	        $save= \App\Models\OrderRequest::Create([
		    	'els_system_id'=>$request->barcode_id,
			    
			]);
	        $request_order_id=$save->id;
	    }
        $brand_id=$request->brand_id;
		if($request->order_part_id){
// 			\App\Models\OrderRequestPart::whereNotIn('id',$request->order_part_id)->delete();
		}
        foreach ($brand_id as $key => $value) 
        {
			$order_part_id = null;
			if(isset($request->order_part_id[$key])){
				$order_part_id = $request->order_part_id[$key];
			}
			$spare_part_price_id = 0;
			$spare_part_price = \App\Models\SparePartPriceList::join('spare_part_list',function($join){
				$join->on('spare_part_price_list.series_id','spare_part_list.id');
			})->where('spare_part_list.brand_id',$request['brand_id'][$key])->where('spare_part_list.model_id',$request['model_id'][$key])->where('spare_part_list.type_id',$request['product_type_id'][$key])->where('spare_part_list.part_id',$request['part_id'][$key])->where('spare_part_price_list.colour_id',$request['part_color'][$key])->select('spare_part_price_list.id')->first();
			if($spare_part_price){
				$spare_part_price_id = $spare_part_price->id;
			}
			
			$save_series = \App\Models\OrderRequestPart::updateOrCreate(['id' =>$order_part_id],[
				'request_order_id' =>$request_order_id,
				'brand_id' =>$request['brand_id'][$key],
				'model_id' =>$request['model_id'][$key],
				'part_type_id' =>$request['product_type_id'][$key],
				'part_id' =>$request['part_id'][$key],
				'colour_id' =>$request['part_color'][$key],
				'quantity'    =>$request['quantity'][$key],
				'spare_part_price_id' =>$spare_part_price_id,
			]);
        }
       return redirect()->route('request_order_list');
    }
    Public function DeleteRequestOrder(Request $request)
    {
    	$id = $request->id;
    //   \App\Models\OrderRequest::where('id', $id)->delete();
        $arr = array(
            "code" => 200,
            "message" => "Delete Successfully",
            "data" => ''
        );
        return \Response::json($arr);
    }
    public function GetPartListById(Request $request)
    {
        $id=$request->id;
    
        $data = DB::select("select `els_order_request_parts`.*, `brand`.`bname`, `model`.`mname`, `product_type`.`type`, `part`.`name`, `colour`.`name` as `color_name` from `els_order_request_parts` inner join `brand` on `els_order_request_parts`.`brand_id` = `brand`.`id` inner join `model` on `els_order_request_parts`.`model_id` = `model`.`id` inner join `product_type` on `els_order_request_parts`.`part_type_id` = `product_type`.`id` inner join `part` on `els_order_request_parts`.`part_id` = `part`.`id` inner join `colour` on `els_order_request_parts`.`colour_id` = `colour`.`id` where `els_order_request_parts`.`request_order_id` = '".$id."'");
        
        
    //     $data=\App\Models\OrderRequestPart::select('els_order_request_parts.*','brand.bname','model.mname','product_type.type','part.name','colour.name as color_name')
		  //  ->join('brand','els_order_request_parts.brand_id','=','brand.id')
		  //  ->join('model','els_order_request_parts.model_id','=','model.id')
		  //  ->join('product_type','els_order_request_parts.part_type_id','=','product_type.id')
		  //  ->join('part','els_order_request_parts.part_id','=','part.id')
		  //  ->join('colour','els_order_request_parts.colour_id','=','colour.id')
		  //  ->where('els_order_request_parts.request_order_id', $id)
		  //  ->get();

		$arr = array(
            "code" => 200,
            "data" => $data,
        );
        return \Response::json($arr);
    }
    
	
	public function uploadRequestOrder(Request $request){
		set_time_limit(0);
		$file = $request->file('ro_file');
		if(in_array(strtoupper($file->getClientOriginalExtension()),['CSV'])){
			$fileD = fopen($file,"r");
			$column=fgetcsv($fileD);
			if($column){
				foreach($column as $k => $d){
					if($d == 'Brand'){
						$barnd_key = $k;
					}
					if($d == 'Model'){
						$model_key = $k;
					}
					if($d == 'Product Type'){
						$type_key = $k;
					}
					if($d == 'Part Name'){
						$part_key = $k;
					}
					if($d == 'Part Colour'){
						$colour_key = $k;
					}
					if($d == 'Quantity'){
						$quantity_key = $k;
					}
				}
			}
			$i = 2;
			$error = false;
			$massage = '';
			$dataArr = [];
			while(!feof($fileD)){				
				$msg = 'Row No '.$i.' ';
				$rowData = fgetcsv($fileD);
				if(isset($rowData[$barnd_key])){
					$brand = \App\Models\MasterBrandModel::where('bname',$rowData[$barnd_key])->first();
					if($brand){
						$row['brand_id'] = $brand->id;
					}else{
						$msg .= 'Brand,';
						$error = true;
					}					
				}
				if(isset($rowData[$model_key])){
					$model = \App\Models\MasterModel::where('mname',$rowData[$model_key])->first();
					if($model){
						$row['model_id'] = $model->id;
					}else{
						$msg .= 'Model,';
						$error = true;
					}
				}
				if(isset($rowData[$type_key])){
					$part_type = \App\Models\ProductTypeModel::where('type',$rowData[$type_key])->first();
					if($part_type){
						$row['part_type_id'] = $part_type->id;
					}else{
						$msg .= 'Product Type,';
						$error = true;
					}
				}
				if(isset($rowData[$part_key])){
					$part = \App\Models\MasterPartModel::where('name',$rowData[$part_key])->first();
					if($part){
						$row['part_id'] = $part->id;
					}else{
						$msg .= 'Part,';
						$error = true;
					}
				}
				if(isset($rowData[$colour_key])){
					$colour = \App\Models\MasterColourModel::where('name',$rowData[$colour_key])->first();
					if($colour){
						$row['colour_id'] = $colour->id;
					}else{
						$msg .= 'Colour';
						$error = true;
					}
				}
				
				if(isset($rowData[$quantity_key])){
					$row['quantity'] = $rowData[$quantity_key];
				}
				$dataArr[] = $row;
				if($error) $massage .= $msg.' Not Found. Please Correct The Details.';
				$i++;
			}
			if($error){
				return response()->json(['status'=>false, 'data' => [], 'code'=>401, 'message'=>$massage]);
			}else{
				$save= \App\Models\OrderRequest::Create([
					'remarks'=>'Manual Import',
					
				]);
				$request_order_id=$save->id;
			}
			
			if($dataArr){
				foreach($dataArr as $val){
					$d = (object) $val;
					$addData['brand_id'] = $d->brand_id;
					$addData['model_id'] = $d->model_id;
					$addData['part_type_id'] = $d->part_type_id;
					$addData['part_id'] = $d->part_id;
					$addData['colour_id'] = $d->colour_id;
					$addData['quantity'] = $d->quantity;
					$spare_part_price_id = 0;
					$spare_part_price = \App\Models\SparePartPriceList::join('spare_part_list',function($join){
						$join->on('spare_part_price_list.series_id','spare_part_list.id');
					})->where('spare_part_list.brand_id',$d->brand_id)->where('spare_part_list.model_id',$d->model_id)->where('spare_part_list.type_id',$d->part_type_id)->where('spare_part_list.part_id',$d->part_id)->where('spare_part_price_list.colour_id',$d->colour_id)->select('spare_part_price_list.id')->first();
					if($spare_part_price){
						$spare_part_price_id = $spare_part_price->id;
					}
					$addData['request_order_id'] = $request_order_id;
					$addData['spare_part_price_id'] = $spare_part_price_id;
					\App\Models\OrderRequestPart::updateOrCreate($addData);
				}			
			}
			return response()->json(['status'=>true, 'data' => [], 'code'=>200, 'message'=> 'Request order successfully imported.']);
		}
		return response()->json(['status'=>false, 'data' => [], 'code'=>401, 'message'=> 'Please choose a correct formate video.']);
	}
	
	public function exportRequestOrder(Request $request){
		header("Content-Type: application/xls");    
		header("Content-Disposition: attachment; filename=RequestOrderReport.xls");  
		header("Pragma: no-cache"); 
		header("Expires: 0");

		$columnHeader = "Sr NO"."\t"."Date"."\t"."RO"."\t"."UIN"."\t"."Brand"."\t"."Model"."\t"."Part Type"."\t"."Colour"."\t"."Part Name"."\t"."Quantity"."\t";
		$data=\App\Models\OrderRequestPart::join('els_order_request','els_order_request.id','=','els_order_request_parts.request_order_id')->join('brand','els_order_request_parts.brand_id','=','brand.id')->join('model','els_order_request_parts.model_id','=','model.id')->join('product_type','els_order_request_parts.part_type_id','=','product_type.id')->join('part','els_order_request_parts.part_id','=','part.id')->join('colour','els_order_request_parts.colour_id','=','colour.id')->leftjoin('els_system_info_details','els_system_info_details.id','=','els_order_request.els_system_id')->select('els_order_request_parts.*','els_system_info_details.barcode','brand.bname','model.mname','product_type.type','part.name','colour.name as colour_name')->groupBy('els_order_request_parts.id')->get();
		$i = 1;
		$setData='';	
		if(!$data->isEmpty())
		{
			foreach($data as $value)
			{
				$rowData = '';
				$rowData .= '"' . $i . '"' . "\t";
				$rowData .= '"' . date('d/m/Y',strtotime($value->created_at)) . '"' . "\t";
				$rowData .= '"' . $value->request_order_id . '"' . "\t";
				$rowData .= '"' . $value->barcode . '"' . "\t";
				$rowData .= '"' . $value->bname . '"' . "\t";
				$rowData .= '"' . $value->mname . '"' . "\t";
				$rowData .= '"' . $value->type . '"' . "\t";
				$rowData .= '"' . $value->colour_name . '"' . "\t";
				$rowData .= '"' . $value->name . '"' . "\t";
				$rowData .= '"' . $value->quantity . '"' . "\t";
				$setData .= trim($rowData)."\n";
				$i++;
			}			
		}
		echo ucwords($columnHeader)."\n".$setData."\n";	
	}
}
