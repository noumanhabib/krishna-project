<?php

namespace App\Http\Controllers;
use App\Models\MasterPartModel;

use Illuminate\Http\Request;

class MasterPartController extends Controller
{
    public function ShowPartList(Request $request)
     {
		if(\Helper::checkMenuElegible()){
			$title="Part List";

			return view("part_list",compact('title'));
		}
		abort(403,"Don't have permission to access.");
     }
      public function AddPart(Request $request)
     {
         $title="Add Part";
         return view('add_part',compact('title'));

     }
      public function FetchPartList(Request $request)
     {
          $part_list=MasterPartModel::where('deleted_at',null)->orderBy('id','DESC')->get()->toarray();
          $arr = array("code" => 200, "message" =>'success', "data" => $part_list);
                      return \Response::json($arr);
     }
     public function SavePart(Request $request)
     {
         //dd($request->all());

            $check_exist_part=MasterPartModel::select('name')->where('name',$request->part_name)->get()->toarray();
            if(count($check_exist_part)>0)
            {
                $response['code']       = 502;
                $response['error']      = False;
                $response['message']    = "Part Already Exist";
                
                $res  = json_encode($response);
                
                echo $res; die;

            }
            else
            {

                
                $part_name=$request->part_name;
                
                $obj= new MasterPartModel;
                $obj->name=$part_name;
                $obj->status='1';
                $obj->save();
                 
                 $code             = 200;  
                 $error            = false;
                 $message          = "Part Add Successfully";
        
            }
            $response['code']       = $code;
            $response['message']    = $message;
            $response['error']      = $error;
            return json_encode($response);
        

     }
     public function  UpdatePartstatus(Request $request)
     {
     	//dd($request->all());
          $part_id = $request->part_id;
          $status        = $request->status;
          
          MasterPartModel::where('id', $part_id)->update(['status' => $status]);
          $get_update_status=MasterPartModel::select('status')->where('id', $part_id)->first();

         
            if($get_update_status['status']==1)
            {
                $arr = array("code" => 200, "message" => "Part is Active Successfully", "data" => array());
                    return \Response::json($arr);
            }
            elseif($get_update_status['status']==0)
            {
                $arr = array("code" => 200, "message" => "Part is InActive Successfully", "data" => array());
                    return \Response::json($arr);
            }
            else
            {
                $arr = array("code" => 400, "message" => "Fail to update status", "data" => array());
                    return \Response::json($arr);
            }

     }
      public function EditPart(Request $request)
     {
         $id = $request->id;

          $data = MasterPartModel::where('id', $id)->first()
            ->toarray();
         $arr = array(
            "code" => 200,
            "message" => "Success",
            "data" => $data
           );
          return \Response::json($arr);
     }
     public function UpdatePart(Request $request)
    {
        $obj = array();
        $id = $request->id;
        $obj['name'] = $request->part_name;
        
        $check_exist_Part=MasterPartModel::select('name')->where('name',$request->part_name)->get()->toarray();
            if(count($check_exist_Part)>0)
            {
                $response['code']       = 502;
                $response['error']      = False;
                $response['message']    = "Part Already Exist";
                
                $res  = json_encode($response);
                
                echo $res; die;

            }
            else
            {
                   $data = MasterPartModel::where('id', $id)->update($obj);
                    $arr = array(
                        "code" => 200,
                        "message" => "Part has been Updated Successfully",
                        "data" => array()
                    );
                    return \Response::json($arr);
            }
        
    }
    public function DeletePart(Request $request)
    {
        $id = $request->id;
        $delete=date('Y-m-d h:i:s');
        $obj = array();
        $obj['deleted_at'] = $delete;
        $obj['status'] = '0';

        //$data = MasterPartModel::where('id', $id)->delete();
        $data = MasterPartModel::where('id', $id)->update($obj);
        $arr = array(
            "code" => 200,
            "message" => "Delete Successfully",
            "data" => ''
        );
        return \Response::json($arr);
    }
}
