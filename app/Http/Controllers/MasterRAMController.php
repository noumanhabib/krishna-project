<?php

namespace App\Http\Controllers;
use App\Models\MasterRamModel;

use Illuminate\Http\Request;

class MasterRAMController extends Controller
{
    public function ShowRAMList(Request $request)
     {
		if(\Helper::checkMenuElegible()){
			$title="RAM List";
			return view("ram_list",compact('title'));
		}
		abort(403,"Don't have permission to access.");
     }
      public function AddRAM(Request $request)
     {
         $title="Add RAM";
         return view('add_ram',compact('title'));

     }
      public function FetchRAMList(Request $request)
     {
          $ram_list=MasterRamModel::where('deleted_at',null)->orderBy('id','DESC')->get()->toarray();
          $arr = array("code" => 200, "message" =>'success', "data" => $ram_list);
                      return \Response::json($arr);
     }
     public function SaveRAM(Request $request)
     {
         //dd($request->all());
			$check_exist_ram=MasterRamModel::whereNull('deleted_at')->where('name',$request->ram_name)->get();
            if(count($check_exist_ram)>0)
            {
                $response['code']       = 502;
                $response['error']      = False;
                $response['message']    = "RAM Already Exist";
                
                $res  = json_encode($response);
                
                echo $res; die;

            }
            else
            {

                
                $ram_name=$request->ram_name;
                
                $obj= new MasterRamModel;
                $obj->name=$ram_name;
                $obj->status='1';
                $obj->save();
                 
                 $code             = 200;  
                 $error            = false;
                 $message          = "RAM Add Successfully";
        
            }
            $response['code']       = $code;
            $response['message']    = $message;
            $response['error']      = $error;
            return json_encode($response);
        

     }
     public function  UpdateRAMstatus(Request $request)
     {
     	//dd($request->all());
          $ram_id = $request->ram_id;
          $status        = $request->status;
          
          MasterRamModel::where('id', $ram_id)->update(['status' => $status]);
          $get_update_status=MasterRamModel::select('status')->where('id', $ram_id)->first();

         
            if($get_update_status['status']==1)
            {
                $arr = array("code" => 200, "message" => "RAM is Active Successfully", "data" => array());
                    return \Response::json($arr);
            }
            elseif($get_update_status['status']==0)
            {
                $arr = array("code" => 200, "message" => "RAM is InActive Successfully", "data" => array());
                    return \Response::json($arr);
            }
            else
            {
                $arr = array("code" => 400, "message" => "Fail to update status", "data" => array());
                    return \Response::json($arr);
            }

     }
      public function EditRAM(Request $request)
     {
         $id = $request->id;

          $data = MasterRamModel::where('id', $id)->first()
            ->toarray();
         $arr = array(
            "code" => 200,
            "message" => "Success",
            "data" => $data
           );
          return \Response::json($arr);
     }
     public function UpdateRAM(Request $request)
    {
        $obj = array();
        $id = $request->id;
        $obj['name'] = $request->ram_name;
        
        $check_exist_RAM=MasterRamModel::select('name')->where('name',$request->ram_name)->get()->toarray();
            if(count($check_exist_RAM)>0)
            {
                $response['code']       = 502;
                $response['error']      = False;
                $response['message']    = "RAM Already Exist";
                
                $res  = json_encode($response);
                
                echo $res; die;

            }
            else
            {
                   $data = MasterRamModel::where('id', $id)->update($obj);
                    $arr = array(
                        "code" => 200,
                        "message" => "RAM has been Updated Successfully",
                        "data" => array()
                    );
                    return \Response::json($arr);
            }
        
    }
    public function DeleteRAM(Request $request)
    {
        $id = $request->id;
        $delete=date('Y-m-d h:i:s');
        $obj = array();
        $obj['deleted_at'] = $delete;
        $obj['status'] = '0';

        //$data = MasterRamModel::where('id', $id)->delete();
        $data = MasterRamModel::where('id', $id)->update($obj);
        $arr = array(
            "code" => 200,
            "message" => "Delete Successfully",
            "data" => ''
        );
        return \Response::json($arr);
    }
}
