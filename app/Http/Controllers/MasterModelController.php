<?php

namespace App\Http\Controllers;
use App\Models\MasterModel;
use App\Models\MasterBrandModel;
use Illuminate\Http\Request;
use DB;


class MasterModelController extends Controller
{
    public function ShowModelList(Request $request)
	{
		if(\Helper::checkMenuElegible()){
			$title="Model List";
			return view("model_list",compact('title'));
		}
		abort(403,"Don't have permission to access.");
	}
     public function GetActiveBrandList()
     {
         $brand_list=MasterBrandModel::where('deleted_at',null)->where('bstatus','1')->get()->toarray();
          $arr = array("code" => 200, "message" =>'success', "data" => $brand_list);
                      return \Response::json($arr);
     }
      public function AddModel(Request $request)
     {
         $title="Add Model";
         return view('add_model',compact('title'));

     }
      public function FetchModelList(Request $request)
     {
          $model_list=DB::table('model as a')
                             ->select('a.*','b.bname')
                            ->leftJoin('brand as b', 'a.brand_id', '=', 'b.id')
                            ->where('a.deleted_at',null)
                            ->orderBy('a.id', 'DESC')
                            ->get()->toarray();;
          $arr = array("code" => 200, "message" =>'success', "data" => $model_list);
                      return \Response::json($arr);
     }
     public function SaveModel(Request $request)
     {
         //dd($request->all());

            $check_exist_model=MasterModel::select('mname')->where('mname',$request->model_name)->get()->toarray();
            if(count($check_exist_model)>0)
            {
                $response['code']       = 502;
                $response['error']      = False;
                $response['message']    = "Model Already Exist";
                
                $res  = json_encode($response);
                
                echo $res; die;

            }
            else
            {

                
                $model_name=$request->model_name;
                $brand_id=$request->brand_id;
                $obj= new MasterModel;
                $obj->mname=$model_name;
                $obj->brand_id=$brand_id;
                $obj->mstatus='1';
                $obj->save();
                 
                 $code             = 200;  
                 $error            = false;
                 $message          = "Model Add Successfully";
        
            }
            $response['code']       = $code;
            $response['message']    = $message;
            $response['error']      = $error;
            return json_encode($response);
        

     }
     public function  UpdateModelstatus(Request $request)
     {
     	//dd($request->all());
          $model_id = $request->model_id;
          $status        = $request->status;
          
          MasterModel::where('id', $model_id)->update(['mstatus' => $status]);
          $get_update_status=MasterModel::select('mstatus')->where('id', $model_id)->first();

         
            if($get_update_status['mstatus']==1)
            {
                $arr = array("code" => 200, "message" => "Model is Active Successfully", "data" => array());
                    return \Response::json($arr);
            }
            elseif($get_update_status['mstatus']==0)
            {
                $arr = array("code" => 200, "message" => "Model is InActive Successfully", "data" => array());
                    return \Response::json($arr);
            }
            else
            {
                $arr = array("code" => 400, "message" => "Fail to update status", "data" => array());
                    return \Response::json($arr);
            }

     }
      public function EditModel(Request $request)
     {
         $id = $request->id;

          $data = MasterModel::where('id', $id)->first()
            ->toarray();
         $arr = array(
            "code" => 200,
            "message" => "Success",
            "data" => $data
           );
          return \Response::json($arr);
     }
     public function UpdateModel(Request $request)
    {
        $obj = array();
        $id = $request->id;
        $obj['mname'] = $request->model_name;
         $obj['brand_id'] = $request->brand_id;
        
                   $data = MasterModel::where('id', $id)->update($obj);
                    $arr = array(
                        "code" => 200,
                        "message" => "Model has been Updated Successfully",
                        "data" => array()
                    );
                    return \Response::json($arr);
          
        
    }
    public function DeleteModel(Request $request)
    {
        $id = $request->id;
        $delete=date('Y-m-d h:i:s');
        $obj = array();
        $obj['deleted_at'] = $delete;
        $obj['mstatus'] = '0';

        
        $data = MasterModel::where('id', $id)->update($obj);
        $arr = array(
            "code" => 200,
            "message" => "Delete Successfully",
            "data" => ''
        );
        return \Response::json($arr);
    }
}
