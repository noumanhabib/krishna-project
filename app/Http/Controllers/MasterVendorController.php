<?php

namespace App\Http\Controllers;
use App\Models\MasterVendorModel;
use Illuminate\Http\Request;

class MasterVendorController extends Controller
{
    public function ShowVendorList(Request $request)
	{
		if(\Helper::checkMenuElegible()){
			$title="Vendor List";
			$data=MasterVendorModel::whereNull('deleted_at')->orderBy('id','DESC')->get();
			return view("vendor_list",compact('title','data'));
		}
		abort(403,"Don't have permission to access.");
	}
	
	public function FetchVendorList(Request $request)
	{
		$vendor_list=MasterVendorModel::where('deleted_at',null)->orderBy('id','DESC')->get();
		$arr = array("code" => 200, "message" =>'success', "data" => $vendor_list);
		return \Response::json($arr);
	}
	
	
	public function AddVendor(Request $request)
	{
		$data = [];
		$title="Add Vendor";
		return view('add_vendor',compact('title','data'));
	}
	
	public function editVendor(Request $request,$id)
	{
		$title="Edit Vendor";
		$data = MasterVendorModel::where('id', $id)->first();
		return view('add_vendor',compact('title','data'));
	}
	
	public function SaveVendor(Request $request)
	{
		$check_exist_brand=MasterVendorModel::where('id','!=',$request->id)->where('vname',$request->vendor_name)->count();
		if(!$check_exist_brand){
			$dataArr['vname']=$request->vendor_name;
			$dataArr['address']=$request->vendor_address;
			$dataArr['city']=$request->city;
			$dataArr['state']=$request->state;
			$dataArr['country']=$request->country;
			$dataArr['pincode']=$request->pincode;
			$dataArr['account_number']=$request->account_number;
			$dataArr['ifs_code']=$request->ifs_code;
			$dataArr['bank_name']=$request->bank_name;
			$dataArr['payment_mode']=$request->payment_mode;
			$dataArr['gst_no']=$request->gst_no;
			$dataArr['pan_no']=$request->pan_no;
			$dataArr['payment_terms']=$request->payment_terms;
			$save = \App\Models\MasterVendorModel::updateOrCreate(['id'=>$request->id],$dataArr);   
		}   
		return redirect('vendor_list');		
	}  

	public function  UpdateVendorstatus(Request $request)
    {
		$vendor_id = $request->vendor_id;
		$status  = $request->status;
          
		MasterVendorModel::where('id', $vendor_id)->update(['status' => $status]);
		$get_update_status=MasterVendorModel::select('status')->where('id', $vendor_id)->first();
        
		if($get_update_status['status']==1)
		{
			$arr = array("code" => 200, "message" => "Vendor is Active Successfully", "data" => array());
			return \Response::json($arr);
		}
		elseif($get_update_status['status']==0)
		{
			$arr = array("code" => 200, "message" => "Vendor is InActive Successfully", "data" => array());
			return \Response::json($arr);
		}
		else
		{
			$arr = array("code" => 400, "message" => "Fail to update status", "data" => array());
			return \Response::json($arr);
		}
	}
    
    public function DeleteVendor(Request $request)
    {
        $id = $request->id;
        $data = MasterVendorModel::where('id', $id)->delete();
        $arr = array(
            "code" => 200,
            "message" => "Delete Successfully",
            "data" => ''
        );
        return \Response::json($arr);
    }
}
