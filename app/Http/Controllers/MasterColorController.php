<?php

namespace App\Http\Controllers;
use App\Models\MasterColourModel;

use Illuminate\Http\Request;

class MasterColorController extends Controller
{
    public function ShowColorList(Request $request)
     {
		if(\Helper::checkMenuElegible()){
			$title="Colour List";
			return view("color_list",compact('title'));
		}
		abort(403,"Don't have permission to access.");
     }
      public function AddColor(Request $request)
     {
         $title="Add Color";
         return view('add_color',compact('title'));

     }
      public function FetchColorList(Request $request)
     {
          $color_list=MasterColourModel::where('deleted_at',null)->orderBy('id','DESC')->get()->toarray();
          $arr = array("code" => 200, "message" =>'success', "data" => $color_list);
                      return \Response::json($arr);
     }
     public function SaveColor(Request $request)
     {
         //dd($request->all());

            $check_exist_color=MasterColourModel::select('name')->where('name',$request->color_name)->get()->toarray();
            if(count($check_exist_color)>0)
            {
                $response['code']       = 502;
                $response['error']      = False;
                $response['message']    = "Color Already Exist";
                
                $res  = json_encode($response);
                
                echo $res; die;

            }
            else
            {

                
                $color_name=$request->color_name;
                
                $obj= new MasterColourModel;
                $obj->name=$color_name;
                $obj->status='1';
                $obj->save();
                 
                 $code             = 200;  
                 $error            = false;
                 $message          = "Color Add Successfully";
        
            }
            $response['code']       = $code;
            $response['message']    = $message;
            $response['error']      = $error;
            return json_encode($response);
        

     }
     public function  UpdateColorstatus(Request $request)
     {
     	//dd($request->all());
          $color_id = $request->color_id;
          $status        = $request->status;
          
          MasterColourModel::where('id', $color_id)->update(['status' => $status]);
          $get_update_status=MasterColourModel::select('status')->where('id', $color_id)->first();

         
            if($get_update_status['status']==1)
            {
                $arr = array("code" => 200, "message" => "Color is Active Successfully", "data" => array());
                    return \Response::json($arr);
            }
            elseif($get_update_status['status']==0)
            {
                $arr = array("code" => 200, "message" => "Color is InActive Successfully", "data" => array());
                    return \Response::json($arr);
            }
            else
            {
                $arr = array("code" => 400, "message" => "Fail to update status", "data" => array());
                    return \Response::json($arr);
            }

     }
      public function EditColor(Request $request)
     {
         $id = $request->id;

          $data = MasterColourModel::where('id', $id)->first()
            ->toarray();
         $arr = array(
            "code" => 200,
            "message" => "Success",
            "data" => $data
           );
          return \Response::json($arr);
     }
     public function UpdateColor(Request $request)
    {
        $obj = array();
        $id = $request->id;
        $obj['name'] = $request->color_name;
        
        $check_exist_Color=MasterColourModel::select('name')->where('name',$request->color_name)->get()->toarray();
            if(count($check_exist_Color)>0)
            {
                $response['code']       = 502;
                $response['error']      = False;
                $response['message']    = "Color Already Exist";
                
                $res  = json_encode($response);
                
                echo $res; die;

            }
            else
            {
                   $data = MasterColourModel::where('id', $id)->update($obj);
                    $arr = array(
                        "code" => 200,
                        "message" => "Color has been Updated Successfully",
                        "data" => array()
                    );
                    return \Response::json($arr);
            }
        
    }
    public function DeleteColor(Request $request)
    {
        $id = $request->id;
        $delete=date('Y-m-d h:i:s');
        $obj = array();
        $obj['deleted_at'] = $delete;
        $obj['status'] = '0';

        //$data = MasterColourModel::where('id', $id)->delete();
        $data = MasterColourModel::where('id', $id)->update($obj);
        $arr = array(
            "code" => 200,
            "message" => "Delete Successfully",
            "data" => ''
        );
        return \Response::json($arr);
    }
}
