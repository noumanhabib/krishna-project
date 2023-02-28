<?php

namespace App\Http\Controllers;
use App\Models\MasterGradeModel;

use Illuminate\Http\Request;

class MasterGradeController extends Controller
{
    public function ShowGradeList(Request $request)
	{
		if(\Helper::checkMenuElegible()){
			$title="Grade List";

			return view("grade_list",compact('title'));
		}
		abort(403,"Don't have permission to access.");
	}
      public function AddGrade(Request $request)
     {
         $title="Add Grade";
         return view('add_grade',compact('title'));

     }
      public function FetchGradeList(Request $request)
     {
          $grade_list=MasterGradeModel::where('deleted_at',null)->orderBy('id','DESC')->get()->toarray();
          $arr = array("code" => 200, "message" =>'success', "data" => $grade_list);
                      return \Response::json($arr);
     }
     public function SaveGrade(Request $request)
     {
         //dd($request->all());

            $check_exist_grade=MasterGradeModel::select('name')->where('name',$request->grade_name)->get()->toarray();
            if(count($check_exist_grade)>0)
            {
                $response['code']       = 502;
                $response['error']      = False;
                $response['message']    = "Grade Already Exist";
                
                $res  = json_encode($response);
                
                echo $res; die;

            }
            else
            {

                
                $grade_name=$request->grade_name;
                
                $obj= new MasterGradeModel;
                $obj->name=$grade_name;
                $obj->status='1';
                $obj->save();
                 
                 $code             = 200;  
                 $error            = false;
                 $message          = "Grade Add Successfully";
        
            }
            $response['code']       = $code;
            $response['message']    = $message;
            $response['error']      = $error;
            return json_encode($response);
        

     }
     public function  UpdateGradestatus(Request $request)
     {
     	//dd($request->all());
          $grade_id = $request->grade_id;
          $status        = $request->status;
          
          MasterGradeModel::where('id', $grade_id)->update(['status' => $status]);
          $get_update_status=MasterGradeModel::select('status')->where('id', $grade_id)->first();

         
            if($get_update_status['status']==1)
            {
                $arr = array("code" => 200, "message" => "Grade is Active Successfully", "data" => array());
                    return \Response::json($arr);
            }
            elseif($get_update_status['status']==0)
            {
                $arr = array("code" => 200, "message" => "Grade is InActive Successfully", "data" => array());
                    return \Response::json($arr);
            }
            else
            {
                $arr = array("code" => 400, "message" => "Fail to update status", "data" => array());
                    return \Response::json($arr);
            }

     }
      public function EditGrade(Request $request)
     {
         $id = $request->id;

          $data = MasterGradeModel::where('id', $id)->first()
            ->toarray();
         $arr = array(
            "code" => 200,
            "message" => "Success",
            "data" => $data
           );
          return \Response::json($arr);
     }
     public function UpdateGrade(Request $request)
    {
        $obj = array();
        $id = $request->id;
        $obj['name'] = $request->grade_name;
        
        $check_exist_Grade=MasterGradeModel::select('name')->where('name',$request->grade_name)->get()->toarray();
            if(count($check_exist_Grade)>0)
            {
                $response['code']       = 502;
                $response['error']      = False;
                $response['message']    = "Grade Already Exist";
                
                $res  = json_encode($response);
                
                echo $res; die;

            }
            else
            {
                   $data = MasterGradeModel::where('id', $id)->update($obj);
                    $arr = array(
                        "code" => 200,
                        "message" => "Grade has been Updated Successfully",
                        "data" => array()
                    );
                    return \Response::json($arr);
            }
        
    }
    public function DeleteGrade(Request $request)
    {
        $id = $request->id;
        $delete=date('Y-m-d h:i:s');
        $obj = array();
        $obj['deleted_at'] = $delete;
        $obj['status'] = '0';

        //$data = MasterGradeModel::where('id', $id)->delete();
        $data = MasterGradeModel::where('id', $id)->update($obj);
        $arr = array(
            "code" => 200,
            "message" => "Delete Successfully",
            "data" => ''
        );
        return \Response::json($arr);
    }
}
