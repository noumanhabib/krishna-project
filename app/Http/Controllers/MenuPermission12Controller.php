<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MenuPermissionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function ShowPermissionList()
    {
		if(\Helper::checkMenuElegible()){
			$title="Show Permission List";
			$list=\App\Models\MenuPermissionModel::select('menu_permission_tb.role_id','role.name')
			->join('role','menu_permission_tb.role_id','=','role.id')
			->distinct('menu_permission_tb.role_id')
			->whereNull('menu_permission_tb.deleted_at')->get();
			return view("menu-permission.menu-permission-list",compact('title','list'));
		}
		abort(403,"Don't have permission to access.");
    }
    public function MenuPermissionForm($role_id=null)
    {
    	$title="Permission Form";
        $data=[];
    	
        $role_list=\App\Models\RoleModel::where('IsActive','1')->whereNull('deleted_at')->pluck('name','id');
        $menu_data= \App\Models\MenuModel::leftjoin('menu_permission_tb',function($join) use ($role_id){
        	$join->on('menu.id','menu_permission_tb.menu_id_permission');
        	$join->where('role_id',$role_id);        	
        })->whereNull('menu.main_menu')->where('menu.status','1')->select('menu.*','menu_permission_tb.menu_id_permission','menu_permission_tb.id as permission_id')->orderBy('menu.id')->get();
    	return view("menu-permission/menu_permission_form",compact('title','role_list','menu_data','role_id'));
    }
	
    public function SaveMenuPermission(Request $request)
    {
    	$menu_permission=array_unique($request->permission);
		if($menu_permission){
			\App\Models\MenuPermissionModel::where('role_id',$request->role_id)->whereNotIn('menu_id_permission',$menu_permission)->delete();
			// dd($menu_permission);
			foreach ($menu_permission as $key => $value) {
				$save= \App\Models\MenuPermissionModel::updateOrCreate([
					'role_id'=> $request->role_id,
					'menu_id_permission'  => $value,
				]);
				
				// if(isset($request->permission_id[$key])){
					// $save= \App\Models\MenuPermissionModel::updateOrCreate(['id' =>  $request->permission_id[$key]],[
						// 'role_id'=> $request->role_id,
						// 'menu_id_permission'  => $value,
					// ]);
				// }else{
					// $save= \App\Models\MenuPermissionModel::updateOrCreate([
						// 'role_id'=> $request->role_id,
						// 'menu_id_permission'  => $value,
					// ]);
				// }				
			}
		}    	  
		return redirect()->route('menu_permission_list');
    }

}
