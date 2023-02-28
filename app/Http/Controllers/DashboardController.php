<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use DB;

class DashboardController extends Controller
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

     public function ShowUserList (Request $request)
     {
     	$title="User List";

     	return view("user_list",compact('title'));
     }
     public function AddUser(Request $request,$id=null)
     { 
         $data=[];
         
         if($id)
         {
             $title="Edit User";
             $data=\App\Models\User::where('id',$id)->first();
         }
         else
         {
           
            $title="Add User";
         }

          $rolelist=\App\Models\RoleModel::where('IsActive','1')->whereNull('deleted_at')->pluck('name','id');
         
         return view('add_user',compact('title','data','rolelist'));

     }
     /*Fetch list of categories...............................*/
	public function FetchUserList(Request $request)
	{
		$user_list=User::join('role',function($join){
			$join->on('users.role','role.id');  
		})->orderBy('users.id','DESC')->selectRaw('users.*,role.name as role')->get();
		$arr = array("code" => 200, "message" =>'success', "data" => $user_list);
        return \Response::json($arr);
	}
	 
    public function SaveUser(Request $request)
    {
        if($request->new_password!=null)
        {
            $new_password=$request->new_password;
            $password=Hash::make($new_password);
            $updateData['password'] = $password;
        }
        
		$updateData['name'] = $request->username;
		$updateData['email'] = $request->email;
		$updateData['mobile_number'] = $request->mobile;
		$updateData['role'] = $request->role_id;
		$updateData['target'] = $request->target;
		$save= \App\Models\User::updateOrCreate(['id' => $request->id],$updateData);
		return redirect()->route('user_list');
    }
     /*.....Update category status.................................*/
     public function  UpdateUserStatus(Request $request)
     {
		$category_id = $request->category_id;
		$status = $request->status;
        
          
		User::where('id', $category_id)->update(['is_active' => $status]);

		$get_update_status=User::select('is_active')->where('id', $category_id)->first();

         
		if($get_update_status['is_active']==1)
		{
			$arr = array("code" => 200, "message" => "User is Active Successfully", "data" => array());
				return \Response::json($arr);
		}
		elseif($get_update_status['is_active']==0)
		{
			$arr = array("code" => 200, "message" => "User is InActive Successfully", "data" => array());
				return \Response::json($arr);
		}
		else
		{
			$arr = array("code" => 400, "message" => "Fail to update status", "data" => array());
				return \Response::json($arr);
		}
     }
   /*.............Edit Category data................................*/
     public function EditUser(Request $request)
     {
         $id = $request->id;

        $data = User::where('id', $id)->first()
            ->toarray();
        $arr = array(
            "code" => 200,
            "message" => "Success",
            "data" => $data
        );
        return \Response::json($arr);
     }
     
    /*update Category data......................................*/
    public function UpdateUser(Request $request)
    {
        $obj = array();
        $id = $request->id;
        $obj['name'] = $request->category_name;
        $check_exist_category=User::select('name')->where('name',$request->category_name)->get()->toarray();
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
                   $data = User::where('id', $id)->update($obj);
                    $arr = array(
                        "code" => 200,
                        "message" => "Category has been Updated Successfully",
                        "data" => array()
                    );
                    return \Response::json($arr);
            }
        
           
       
        
    }
    /*Soft Delete Category ..............................*/

    public function DeleteUser(Request $request)
    {
        $id = $request->id;
        $soft_delete=date('Y-m-d h:i:s');
        $obj = array();
        $obj['soft_delete'] = $soft_delete;
        if($id!=2)
         {
        $data = User::where('id', $id)->delete();
         $arr = array(
            "code" => 200,
            "message" => "Delete Successfully",
            "data" => ''
        );

         } else
         {
          $arr = array(
            "code" => 200,
            "message" => "You Cannot delete Main ADMIN",
            "data" => ''
        );
         }
        // $data = User::where('id', $id)->update($obj);
      
        return \Response::json($arr);
    }

    public Function CheckEmailExist(Request $request)
    {
        //dd($request->all());
        $email_exist=\App\Models\User::where('email',$request->email)->first();
        
             $arr = array(
            "code" => 200,
            "message" => "Email Already Exist",
            "data" => ''
            );
       return \Response::json($arr);

    }

}
