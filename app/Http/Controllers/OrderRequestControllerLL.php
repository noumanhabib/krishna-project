<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use DB;
class OrderRequestController extends Controller
{
	 public function __construct()
	{
	  $this->middleware('auth');
	}
	public function ShowOrderRequestList (Request $request)
	{
		if(\Helper::checkMenuElegible()){
			$title="Order Request List";
			$product_list = DB::table('els_order_request')->leftjoin('system_info_details','system_info_details.id','=','els_order_request.barcode_id')->select('els_order_request.*','system_info_details.barcode')->orderBy('id','Desc')->get()->toarray();
			return view("orderrequest_list",compact('title','product_list'));
		}
		abort(403,"Don't have permission to access.");
	}
	public function AddOrderRequest(Request $request)
	{
	  $title="Add Order Request List";
	  $elsCat = DB::table('category')->where('status','1')->whereNull('deleted_at')->get()->toarray();
	  $elsTitle = DB::table('product')->select('name','vendor','category_id','id')->where('is_active','1')->get()->toarray();
	  $system_info_details = DB::table('system_info_details')->select('barcode','id')->where('is_active','1')->get()->toarray();

	  for($i=0;$i<count($elsTitle);$i++) 
	  {
		  $title_arr_final[]= $elsTitle[$i]->name;
	  }
	  // print_r($title_arr_final);die();
	  $model=DB::table('model')->select('id','mname')->where('mstatus','1')->get()->toarray();
	 // dd($model);
	  $colour=DB::table('colour')->select('id','name')->where('status','1')->get()->toarray();
	  return view('add_orderrequest',['elsTitle'=>$title_arr_final,'elsCat' =>$elsCat,'elsOrder'=>$elsTitle,'system_info_details'=>$system_info_details,'model'=>$model,'colour'=>$colour]);
	}
	/*Fetch list of categories...............................*/
	public function FetchelsProductListt(Request $request)
	{
	  $data = \App\Models\SystemInfo::whereNull('deleted_at');
	  $columnsArr = ['els_brand','els_model','barcode','color','imei_1','imei_2','ram','rom','grade','mrp','vendor','grn','remark','quantity','entry'];
	  $where_like = false;
	  foreach($columnsArr as $columns){
	   if($request->search['value']){
		if($where_like){
		 $where_like.= ' OR '.$columns.' like "%'.$request->search['value'].'%"';
	   }else{
		 $where_like.= $columns.' like "%'.$request->search['value'].'%"';
	   }
	 }
	}		
	if($where_like){
	 $data = $data->whereRaw('('.$where_like.')');
	}
	if($request->order[0]['column']){
	 $data = $data->orderby($columnsArr[$request->order[0]['column']+1],$request->order[0]['dir']);
	}
	$totalRecord = $data->count();
	if($request->get('length')){
		$data = $data->skip($request->get('start'))->take($request->get('length'));
	}
	$data = $data->get();
	return response()->json(['status'=>true, 'data' => $data,'recordsTotal'=>$totalRecord,'recordsFiltered'=>$totalRecord, 'code'=>200, 'message'=> 'Application Logs listed successfully']);
	}
	 public function FetchOrderRequest(Request $request)
		 {
			  /*$product_list = DB::table('order_request')
			  ->join('product', 'product.id', '=', 'order_request.sparepart_id')
			  ->select('order_request.*', 'product.name')
			  ->get();*/
			  $product_list = DB::table('els_order_request')->leftjoin('system_info_details','system_info_details.id','=','els_order_request.barcode_id')->select('els_order_request.*','system_info_details.barcode')->orderBy('id','Desc')->get()->toarray();
			  $arr = array("code" => 200, "message" =>'success', "data" => $product_list);
						  return \Response::json($arr);
		 }

	/*--------Save Category..............................*/
	public function SaveOrderRequest(Request $request)
	{
	  //dd($request->all());
	 

	  $barcode=$request->input('barcode');
	  $grn_no=$request->input('grn_no');
	  
	  $els_order_request=array(
		'grn_no'     => $grn_no,
		'barcode_id' => $barcode,
		'status'     => '0',
		'date'       => $request->date_val,
		'model'      => $request->model,
		'description'  => $request->description,
		'colour'        =>$request->colour,
		'availability'  => $request->availability,
		'req_quantity'  => $request->req_quantity,
		'nos'                => $request->nos,
		'recieved_quantity'  => $request->recieved_quantity,
		'recieved_date'      => $request->recieved_date,
		'remarks'             => $request->remark,

	  );

	  $insertdata=DB::table('els_order_request')->insert($els_order_request);
	  //dd($insertdata->id);
	  $ro_id = DB::getPdo()->lastInsertId();
	  //$getGrn_no=DB::table('els_order_request')->select('grn_no')->where('id',$id)->first();
	  //dd($getGrn_no->grn_no);
	  //$grn_num=$getGrn_no->grn_no;
	 
	  $title=$request->input('required');
	  $input=$request->input('input');
	  $dataemt = array();

	foreach ($title as $key => $value) 
	{
		for($f=0;$f<count($value);$f++)
		{

		  $data=array(
				'category_id'=>$key,
				'sparepart_id'=>$value[$f],
				'quantity'=>$input[$key][$f],
				'ro_id' =>$ro_id,
		   );

		$info_id = DB::table('order_request')->insert($data);


		}
	}
	return redirect('order-request-list')->with(['tr_msg' => 'Insert Order Request Report Successfully.']);
	}
	/*.....Update category status.................................*/
	public function  UpdateOrderRequestStatus(Request $request)
	{
	 $category_id = $request->category_id;
	 $status = $request->status;    
			  // MasterCategoryModel::where('id', $category_id)->update();
	 $result = DB::table('elsproduct')->where('id',$category_id)->update(['is_active' => $status]); 
	 $get_update_status= DB::table('elsproduct')->where('id', $category_id)->first();
	 if($get_update_status->is_active==1)
	 {
	  $arr = array("code" => 200, "message" => "Els Product is Active Successfully", "data" => array());
	  return \Response::json($arr);
	}
	elseif($get_update_status->is_active==0)
	{
	  $arr = array("code" => 200, "message" => "Els Product is InActive Successfully", "data" => array());
	  return \Response::json($arr);
	}
	else
	{
	  $arr = array("code" => 400, "message" => "Fail to update status", "data" => array());
	  return \Response::json($arr);
	}
	}
	/*.............Edit Category data................................*/
	public function EditOrderRequest(Request $request)
	{
	 $id = $request->id;
	 $data = DB::table('elsproduct')->where('id', $id)->first();
	 $arr = array(
	  "code" => 200,
	  "message" => "Success",
	  "data" => $data
	);
	 return \Response::json($arr);
	}

	/*update Category data......................................*/
	public function UpdateOrderRequest(Request $request)
	{
		/// dd($request->all());
		  $obj = array();
		  $id = $request->hidden_id;
		  $obj['grn_no']            = $request->grn_no;
		  $obj['barcode_id']        = $request->barcode;
		  $obj['date']              = $request->date_val;
		  $obj['model']             = $request->model;
		  $obj['description']       = $request->description;
		  $obj['colour']            = $request->colour;
		  $obj['availability']      = $request->availability;
		  $obj['req_quantity']      = $request->req_quantity;
		  $obj['nos']               = $request->nos;
		  $obj['recieved_quantity'] = $request->recieved_quantity;
		  $obj['recieved_date']     = $request->recieved_date;
		  $obj['remarks']           = $request->remark;

	   
		   $data = DB::table('els_order_request')->where('id',$id)->update($obj);
	  
	  $title=$request->input('required');
	  $input=$request->input('input');
	  $dataemt = array();

	 $data = DB::table('order_request')->where('ro_id', $id)->get()->toarray();
	 $ro_count=count($data);
	$hidden_qnty_id=$request->input('hidden_qnty_id');
	foreach ($title as $key => $value) 
	{
		for($f=0;$f<count($value);$f++)
		{
		 
		 
		  $data=array(
				'category_id'=>$key,
				'sparepart_id'=>$value[$f],
				'quantity'=>$input[$key][$f],
				'ro_id' =>$id,
		   );

		  if($f<$ro_count)
		  {
			$po_id= $hidden_qnty_id[$f];
			  $info_id = DB::table('order_request')->where('id',$po_id)->update($data);
		  }
		   else{
			   $info_id = DB::table('order_request')->insert($data);
		   }

		}
	}
		  
		   return redirect()->route('order-request-list');
		
	}
	/*Soft Delete Category ..............................*/

	public function DeleteOrderRequest(Request $request)
	{
	  $id = $request->id;
	  $soft_delete=date('Y-m-d h:i:s');
	  $obj = array();
	  $obj['soft_delete'] = $soft_delete;

	  DB::table('elsproduct')->where('id', $id)->delete();
	  $arr = array(
		"code" => 200,
		"message" => "Delete Successfully",
		"data" => ''
	  );
	  return \Response::json($arr);
	}
	//------------Auto select js-----------


	public function autocomplete(Request $request)
	{
		 //dd($request->query);
		 $search=$request['query'];
		 //dd($search);
		   $data=DB::table('system_info_details')->select('barcode','id')->where('barcode', 'LIKE', "%$search%")->where('is_active','1')->get()->toarray();
			 //dd($data);
			return response()->json($data);
	}
	/*-----Product List-------------*/
	public function GetProductListByGrnNum(Request $request)
	{
		$id=$request->id;
		$product_list = DB::table('order_request')->select('order_request.*', 'product.name')
												  ->join('product', 'product.id', '=', 'order_request.sparepart_id')
												  ->where('order_request.ro_id',$id)->get()->toarray();
		/*$product_list = DB::table('order_request')
			  ->join('product', 'product.id', '=', 'order_request.sparepart_id')
			  ->select('order_request.*', 'product.name')
			  ->get();*/
		$arr = array("code" => 200, "message" =>'success', "data" => $product_list);
		return \Response::json($arr);
	}

	/*----------------------------15/04/2021--------------------------------*/
	public function EditRequestOrderView(Request $request)
	{
		$title="Edit Request Order";
		$elsCat = DB::table('category')->where('status','1')->whereNull('deleted_at')->get()->toarray();
	  $elsTitle = DB::table('product')->select('name','vendor','category_id','id')->where('is_active','1')->get()->toarray();
	  $system_info_details = DB::table('system_info_details')->select('barcode','id')->where('is_active','1')->get()->toarray();

	  for($i=0;$i<count($elsTitle);$i++) 
	  {
		  $title_arr_final[]= $elsTitle[$i]->name;
	  }
	  // print_r($title_arr_final);die();
	  $model=DB::table('model')->select('id','mname')->where('mstatus','1')->get()->toarray();
	 // dd($model);
	  $colour=DB::table('colour')->select('id','name')->where('status','1')->get()->toarray();


		$id=$request->id;
		$data_list = DB::table('els_order_request')->where('id',$id)->first();
		$quantity_list=DB::table('order_request')->where('ro_id',$id)->get()->toarray();
	   //dd($quantity_list);

	  //dd($data_list);
	  return view('edit_request_order_list',['elsTitle'=>$title_arr_final,'elsCat' =>$elsCat,'elsOrder'=>$elsTitle,'system_info_details'=>$system_info_details,'model'=>$model,'colour'=>$colour,'data_list'=>$data_list,'quantity_list'=>$quantity_list]);
	   
	}
	public function GetModelAndColourByBarcode(Request $request)
	{
	   //dd($request->all());
	   $barcode=$request->barcode;
	   $data=DB::table('system_info_details')->select('system_info_details.els_model','system_info_details.sparepart_product_sku','system_info_details.color', 'model.mname','colour.name')->join('model','system_info_details.els_model', '=', 'model.id')
										->join('colour', 'system_info_details.color', '=', 'colour.id')
										->where('system_info_details.barcode',$barcode)
										->get();
			  //dd($data);
			  $arr = array("code" => 200, "message" =>'success', "data" => $data);
						  return \Response::json($arr);

	}
	public function GetSparepartProductList(Request $request)
	{
			$sku=$request->sku;
			$data=DB::table('product')->select('id','name')->where('sku',$sku)
									  ->get()->toarray();
			  //dd($data);
			  $arr = array("code" => 200, "message" =>'success', "data" => $data);
						  return \Response::json($arr);


	}

}
