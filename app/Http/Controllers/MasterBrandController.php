<?php

namespace App\Http\Controllers;
use App\Models\MasterBrandModel;
use Illuminate\Http\Request;

class MasterBrandController extends Controller
{
	public function ShowBrandList(Request $request)
	{
		if(\Helper::checkMenuElegible()){
			$title="Brand List";
			return view("brand_list",compact('title'));
		}
		abort(403,"Don't have permission to access.");
	}
      public function AddBrand(Request $request)
     {
         $title="Add Brand";
         return view('add_brand',compact('title'));

     }
      public function FetchBrandList(Request $request)
     {
          $brand_list=MasterBrandModel::where('deleted_at',null)->orderBy('id','DESC')->get()->toarray();
          $arr = array("code" => 200, "message" =>'success', "data" => $brand_list);
                      return \Response::json($arr);
     }
     public function SaveBrand(Request $request)
     {
         //dd($request->all());

            $check_exist_brand=MasterBrandModel::select('bname')->where('bname',$request->brand_name)->get()->toarray();
            if(count($check_exist_brand)>0)
            {
                $response['code']       = 502;
                $response['error']      = False;
                $response['message']    = "Brand Already Exist";
                
                $res  = json_encode($response);
                
                echo $res; die;

            }
            else
            {

                
                $brand_name=$request->brand_name;
                
                $obj= new MasterBrandModel;
                $obj->bname=$brand_name;
                $obj->bstatus='1';
                $obj->save();
                 
                 $code             = 200;  
                 $error            = false;
                 $message          = "Brand Add Successfully";
        
            }
            $response['code']       = $code;
            $response['message']    = $message;
            $response['error']      = $error;
            return json_encode($response);
        

     }
     public function  UpdateBrandstatus(Request $request)
     {
     	//dd($request->all());
          $brand_id = $request->brand_id;
          $bstatus        = $request->status;
          
          MasterBrandModel::where('id', $brand_id)->update(['bstatus' => $bstatus]);
          $get_update_bstatus=MasterBrandModel::select('bstatus')->where('id', $brand_id)->first();

         
            if($get_update_bstatus['bstatus']==1)
            {
                $arr = array("code" => 200, "message" => "Brand is Active Successfully", "data" => array());
                    return \Response::json($arr);
            }
            elseif($get_update_bstatus['bstatus']==0)
            {
                $arr = array("code" => 200, "message" => "Brand is InActive Successfully", "data" => array());
                    return \Response::json($arr);
            }
            else
            {
                $arr = array("code" => 400, "message" => "Fail to update bstatus", "data" => array());
                    return \Response::json($arr);
            }

     }
      public function EditBrand(Request $request)
     {
         $id = $request->id;

          $data = MasterBrandModel::where('id', $id)->first()
            ->toarray();
         $arr = array(
            "code" => 200,
            "message" => "Success",
            "data" => $data
           );
          return \Response::json($arr);
     }
     public function UpdateBrand(Request $request)
    {
        $obj = array();
        $id = $request->id;
        $obj['bname'] = $request->brand_name;
        
        $check_exist_Brand=MasterBrandModel::select('bname')->where('bname',$request->brand_name)->get()->toarray();
            if(count($check_exist_Brand)>0)
            {
                $response['code']       = 502;
                $response['error']      = False;
                $response['message']    = "Brand Already Exist";
                
                $res  = json_encode($response);
                
                echo $res; die;

            }
            else
            {
                   $data = MasterBrandModel::where('id', $id)->update($obj);
                    $arr = array(
                        "code" => 200,
                        "message" => "Brand has been Updated Successfully",
                        "data" => array()
                    );
                    return \Response::json($arr);
            }
        
    }
    public function DeleteBrand(Request $request)
    {
        $id = $request->id;
        $delete=date('Y-m-d h:i:s');
        $obj = array();
        $obj['deleted_at'] = $delete;
        $obj['bstatus'] = '0';

        //$data = MasterBrandModel::where('id', $id)->delete();
        $data = MasterBrandModel::where('id', $id)->update($obj);
        $arr = array(
            "code" => 200,
            "message" => "Delete Successfully",
            "data" => ''
        );
        return \Response::json($arr);
    }
}
