<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use DB;

class AssignRoleController extends Controller
{
     public function __construct()
    {
        $this->middleware('auth');
    }

   

     public function AssignRoleList (Request $request)
     {
		if(\Helper::checkMenuElegible()){
			$title="Role List";
			$data=\App\Models\RoleModel::orderBy('id','DESC')->get()->toarray();
			return view("role_list",compact('title','data'));
		}
		abort(403,"Don't have permission to access.");
     }
     public function AddAssignRole(Request $request,$id=null)
     {
        $data=[];
        if($id)
        {
          $title="Edit Role";
          $data=\App\Models\RoleModel::where('id',$id)->first();
        }
        else
        {
           $title="Add Role";
        }

         return view('add_role',compact('title','data'));

     }
    
     /*--------Save Category..............................*/
     public function SaveAssignRole(Request $request)
     {
         //dd($request->all());

            $Rolename=$request->role;
         	$check_exist_role=\App\Models\RoleModel::where('name',$Rolename)->get()->toarray();

         	if($check_exist_role)
            {
                 session()->flash('msg', 'Role Already exist');
                 return redirect()->back();
            }
            else
            {
                
                 $save= \App\Models\RoleModel::updateOrCreate(['id' =>  $request->id],[
                    'name'  =>$request->role,
                    'IsActive' =>"1",
                ]);
                return redirect()->route('assign_role_list');
              

            }

       
    	     
    	     	
     	

     }
     /*.....Update category status.................................*/
     public function  UpdateAssignRoleStatus(Request $request)
     {
         $id = $request->id;
         $status = $request->status;
        
          
         $result = \App\Models\RoleModel::where('id',$id)->update(['IsActive' => $status]); 
         $get_update_status= \App\Models\RoleModel::where('id',$id)->first();

         
            if($get_update_status['IsActive']==1)
            {
                $arr = array("code" => 200, "message" => "Role is Active Successfully", "data" => array());
                    return \Response::json($arr);
            }
            elseif($get_update_status['IsActive']==0)
            {
                $arr = array("code" => 200, "message" => "Role is InActive Successfully", "data" => array());
                    return \Response::json($arr);
            }
            else
            {
                $arr = array("code" => 400, "message" => "Fail to update status", "data" => array());
                    return \Response::json($arr);
            }

     }
   /*.............Edit Category data................................*/
     public function EditAssignRole(Request $request)
     {
         $id = $request->id;

        $data = DB::table('role')->where('id', $id)->first();
        $arr = array(
            "code" => 200,
            "message" => "Success",
            "data" => $data
        );
        return \Response::json($arr);
     }
     
    /*update Category data......................................*/
    public function UpdateAssignRole(Request $request)
    {
        $obj = array();
        $id = $request->id;
        $obj['name'] = $request->category_name;



        $check_exist_category=DB::table('role')->where('name',$request->category_name)->get()->toarray();
            if(count($check_exist_category)>0)
            {
                $response['code']       = 502;
                $response['error']      = False;
                $response['message']    = "Role Name Already Exist";
                
                $res  = json_encode($response);
                
                echo $res; die;

            }
            else
            {
                    $data = DB::table('role')->where('id', $id)->update($obj);
                    $arr = array(
                        "code" => 200,
                        "message" => "Role has been Updated Successfully",
                        "data" => array()
                    );
                    return \Response::json($arr);
            }
        
           
       
        
    }
    /*Soft Delete Category ..............................*/

    public function DeleteRoleProduct (Request $request)
    {
        $id = $request->id;
        $soft_delete=date('Y-m-d h:i:s');
        $obj = array();
        $obj['soft_delete'] = $soft_delete;
       
       DB::table('role')->where('id', $id)->delete();
         $arr = array(
            "code" => 200,
            "message" => "Delete Successfully",
            "data" => ''
        );

        // $data = MasterCategoryModel::where('id', $id)->update($obj);
      
        return \Response::json($arr);
    }

}
