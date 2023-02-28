<?php

namespace App\Http\Controllers;
use App\Models\MasterRomModel;

use Illuminate\Http\Request;

class MasterROMController extends Controller
{
    public function ShowROMList(Request $request)
	{
		if(\Helper::checkMenuElegible()){
			$title="ROM List";
			return view("rom_list",compact('title'));
		}
		abort(403,"Don't have permission to access.");
	}
      public function AddROM(Request $request)
     {
         $title="Add ROM";
         return view('add_rom',compact('title'));

     }
      public function FetchROMList(Request $request)
     {
          $rom_list=MasterRomModel::where('deleted_at',null)->orderBy('id','DESC')->get()->toarray();
          $arr = array("code" => 200, "message" =>'success', "data" => $rom_list);
                      return \Response::json($arr);
     }
     public function SaveROM(Request $request)
     {
         //dd($request->all());

            $check_exist_rom=MasterRomModel::whereNull('deleted_at')->where('name',$request->rom_name)->get();
            if(count($check_exist_rom)>0)
            {
                $response['code']       = 502;
                $response['error']      = False;
                $response['message']    = "ROM Already Exist";
                
                $res  = json_encode($response);
                
                echo $res; die;

            }
            else
            {

                
                $rom_name=$request->rom_name;
                
                $obj= new MasterRomModel;
                $obj->name=$rom_name;
                $obj->status='1';
                $obj->save();
                 
                 $code             = 200;  
                 $error            = false;
                 $message          = "ROM Add Successfully";
        
            }
            $response['code']       = $code;
            $response['message']    = $message;
            $response['error']      = $error;
            return json_encode($response);
        

     }
     public function  UpdateROMstatus(Request $request)
     {
     	//dd($request->all());
          $rom_id = $request->rom_id;
          $status        = $request->status;
          
          MasterRomModel::where('id', $rom_id)->update(['status' => $status]);
          $get_update_status=MasterRomModel::select('status')->where('id', $rom_id)->first();

         
            if($get_update_status['status']==1)
            {
                $arr = array("code" => 200, "message" => "ROM is Active Successfully", "data" => array());
                    return \Response::json($arr);
            }
            elseif($get_update_status['status']==0)
            {
                $arr = array("code" => 200, "message" => "ROM is InActive Successfully", "data" => array());
                    return \Response::json($arr);
            }
            else
            {
                $arr = array("code" => 400, "message" => "Fail to update status", "data" => array());
                    return \Response::json($arr);
            }

     }
      public function EditROM(Request $request)
     {
         $id = $request->id;

          $data = MasterRomModel::where('id', $id)->first()
            ->toarray();
         $arr = array(
            "code" => 200,
            "message" => "Success",
            "data" => $data
           );
          return \Response::json($arr);
     }
     public function UpdateROM(Request $request)
    {
        $obj = array();
        $id = $request->id;
        $obj['name'] = $request->rom_name;
        
        $check_exist_ROM=MasterRomModel::select('name')->where('name',$request->rom_name)->get()->toarray();
            if(count($check_exist_ROM)>0)
            {
                $response['code']       = 502;
                $response['error']      = False;
                $response['message']    = "ROM Already Exist";
                
                $res  = json_encode($response);
                
                echo $res; die;

            }
            else
            {
                   $data = MasterRomModel::where('id', $id)->update($obj);
                    $arr = array(
                        "code" => 200,
                        "message" => "ROM has been Updated Successfully",
                        "data" => array()
                    );
                    return \Response::json($arr);
            }
        
    }
    public function DeleteROM(Request $request)
    {
        $id = $request->id;
        $delete=date('Y-m-d h:i:s');
        $obj = array();
        $obj['deleted_at'] = $delete;
        $obj['status'] = '0';

        //$data = MasterRomModel::where('id', $id)->delete();
        $data = MasterRomModel::where('id', $id)->update($obj);
        $arr = array(
            "code" => 200,
            "message" => "Delete Successfully",
            "data" => ''
        );
        return \Response::json($arr);
    }
}
