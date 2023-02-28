<?php

namespace App\Http\Controllers;
use App\Models\MasterCategoryModel;
use Illuminate\Http\Request;

class MasterCategoryController extends Controller
{
     public function ShowCategoryList(Request $request)
     {
		if(\Helper::checkMenuElegible()){
			$title="Category List";
			return view("category_list",compact('title'));
		}
		abort(403,"Don't have permission to access.");
     }
     public function AddCategory(Request $request)
     {
         $title="Add Category";
         return view('add_category',compact('title'));

     }
     /*Fetch list of categories...............................*/
     public function FetchCategoryList(Request $request)
     {
          $category_list=MasterCategoryModel::where('deleted_at',null)->orderBy('id','DESC')->get()->toarray();
          $arr = array("code" => 200, "message" =>'success', "data" => $category_list);
                      return \Response::json($arr);
     }
     /*--------Save Category..............................*/
     public function SaveCategory(Request $request)
     {
         //dd($request->all());

            $check_exist_category=MasterCategoryModel::select('name')->where('name',$request->category_name)->get()->toarray();
            if(count($check_exist_category)>0)
            {
                $response['code']       = 502;
                $response['error']      = False;
                $response['message']    = "Category Already Exist";
                
                $res  = json_encode($response);
                
                echo $res; die;

            }
            else
            {

                
                $category_name=$request->category_name;
                
                $obj= new MasterCategoryModel;
                $obj->name=$category_name;
                $obj->status='1';
                $obj->save();
                 
                 $code             = 200;  
                 $error            = false;
                 $message          = "Category Add Successfully";
        
            }
            $response['code']       = $code;
            $response['message']    = $message;
            $response['error']      = $error;
            return json_encode($response);
        

     }
     /*.....Update category status.................................*/
     public function  UpdateCategoryStatus(Request $request)
     {
          $category_id = $request->category_id;
          $status        = $request->status;
          
          MasterCategoryModel::where('id', $category_id)->update(['status' => $status]);
          $get_update_status=MasterCategoryModel::select('status')->where('id', $category_id)->first();

         
            if($get_update_status['status']==1)
            {
                $arr = array("code" => 200, "message" => "Category is Active Successfully", "data" => array());
                    return \Response::json($arr);
            }
            elseif($get_update_status['status']==0)
            {
                $arr = array("code" => 200, "message" => "Category is InActive Successfully", "data" => array());
                    return \Response::json($arr);
            }
            else
            {
                $arr = array("code" => 400, "message" => "Fail to update status", "data" => array());
                    return \Response::json($arr);
            }

     }
   /*.............Edit Category data................................*/
     public function EditCategory(Request $request)
     {
         $id = $request->id;

          $data = MasterCategoryModel::where('id', $id)->first()
            ->toarray();
         $arr = array(
            "code" => 200,
            "message" => "Success",
            "data" => $data
           );
          return \Response::json($arr);
     }
     
    /*update Category data......................................*/
    public function UpdateCategory(Request $request)
    {
        $obj = array();
        $id = $request->id;
        $obj['name'] = $request->category_name;
        
        $check_exist_category=MasterCategoryModel::select('name')->where('name',$request->category_name)->get()->toarray();
            if(count($check_exist_category)>0)
            {
                $response['code']       = 502;
                $response['error']      = False;
                $response['message']    = "Category Already Exist";
                
                $res  = json_encode($response);
                
                echo $res; die;

            }
            else
            {
                   $data = MasterCategoryModel::where('id', $id)->update($obj);
                    $arr = array(
                        "code" => 200,
                        "message" => "Category has been Updated Successfully",
                        "data" => array()
                    );
                    return \Response::json($arr);
            }
        
           
       
        
    }
    /*Soft Delete Category ..............................*/

    public function DeleteCategory(Request $request)
    {
        $id = $request->id;
        $delete=date('Y-m-d h:i:s');
        $obj = array();
        $obj['deleted_at'] = $delete;
        $obj['status'] = '0';

        //$data = MasterCategoryModel::where('id', $id)->delete();
        $data = MasterCategoryModel::where('id', $id)->update($obj);
        $arr = array(
            "code" => 200,
            "message" => "Delete Successfully",
            "data" => ''
        );
        return \Response::json($arr);
    }
}
