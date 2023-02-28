<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MasterCategoryModel;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use DB;

class AssignMenuRoleController extends Controller
{
     public function __construct()
    {
        $this->middleware('auth');
    }

    /*
        Module:categories;
        descp:show categories list...........
        date:16/03/2021
    */

     public function ShowMenuRoleList (Request $request)
     {
		if(\Helper::checkMenuElegible()){
			$title="Menu Role List Wise";	
			return view("menurole_list",compact('title'));
		}
		abort(403,"Don't have permission to access.");
     }
     public function AddMenuRole(Request $request)
     {
         $title="Add Menu Role Wise";
         return view('add_menurole',compact('title'));

     }
     /*Fetch list of categories...............................*/
     public function FetchMenuRoleList(Request $request)
     {

          $user_list=DB::table('menu')->get()->toarray();
          $arr = array("code" => 200, "message" =>'success', "data" => $user_list);
                      return \Response::json($arr);
     }
     /*--------Save Category..............................*/
     public function SaveMenuRole(Request $request)
     {
         //dd($request->all());

              $name=$request->name;
              $link=$request->link;
              $number=$request->number;
              $is_active=$request->is_active;
            
            $check_exist_category=DB::table('menu')->where('name',$name)->get()->toarray();


         	if(count($check_exist_category)>0)
            {
                $response['code']       = 502;
                $response['error']      = False;
                $response['message']    = "Menu Already Exist";
                
                $res  = json_encode($response);
                
                echo $res; die;

            }
            else
            {
                

              

           $data = array(
          "name"          => $name,
          "link"         => $link,
          "number" => $number,
          "is_active"     => 1,
        );

        //echo "<pre>";print_r($data);die;
        $result = DB::table('menu')->insert($data);      
    	    
    	     
    	     	 
    	     	 $code             = 200;  
                 $error            = false;
                 $message          = "Menu Add Successfully";
    	
            }
            $response['code']       = $code;
            $response['message']    = $message;
            $response['error']      = $error;
            return json_encode($response);
     	

     }
     /*.....Update category status.................................*/
     public function  UpdateMenuRoleStatus(Request $request)
     {
         $category_id = $request->category_id;
          $status = $request->status;
        
           $result = DB::table('menu')->where('id',$category_id)->update(['is_active' => $status]); 
         $get_update_status= DB::table('menu')->where('id', $category_id)->first();

         
            if($get_update_status->is_active==1)
            {
                $arr = array("code" => 200, "message" => "Menu is Active Successfully", "data" => array());
                    return \Response::json($arr);
            }
            elseif($get_update_status->is_active==0)
            {
                $arr = array("code" => 200, "message" => "Menu is InActive Successfully", "data" => array());
                    return \Response::json($arr);
            }
            else
            {
                $arr = array("code" => 400, "message" => "Fail to update status", "data" => array());
                    return \Response::json($arr);
            }

     }
   /*.............Edit Category data................................*/
     public function EditMenuRole(Request $request)
     {
         $id = $request->id;

       $data = DB::table('menu')->where('id', $id)->first();
        $arr = array(
            "code" => 200,
            "message" => "Success",
            "data" => $data
        );
        return \Response::json($arr);
     }
     
    /*update Category data......................................*/
    public function UpdateMenuRole(Request $request)
    {
        $obj = array();
        $id = $request->id;
        $obj['name'] = $request->name;
           $obj['link'] = $request->link;
              $obj['number'] = $request->number;




     $data = DB::table('menu')->where('id', $id)->update($obj);
                    $arr = array(
                        "code" => 200,
                        "message" => "Menu has been Updated Successfully",
                        "data" => array()
                    );
                    return \Response::json($arr);
         
           
       
        
    }
    /*Soft Delete Category ..............................*/

    public function DeleteMenuRole(Request $request)
    {
        $id = $request->id;
        $soft_delete=date('Y-m-d h:i:s');
        $obj = array();
        $obj['soft_delete'] = $soft_delete;
             
       DB::table('menu')->where('id', $id)->delete();
         $arr = array(
            "code" => 200,
            "message" => "Delete Successfully",
            "data" => ''
        );
        // $data = MasterCategoryModel::where('id', $id)->update($obj);
      
        return \Response::json($arr);
    }

}
