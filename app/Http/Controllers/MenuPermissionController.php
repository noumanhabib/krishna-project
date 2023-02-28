<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

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
        })->whereNull('menu.main_menu')->where('menu.status','1')->select('menu.*','menu_permission_tb.menu_id_permission','menu_permission_tb.id as permission_id','menu_permission_tb.view','menu_permission_tb.added','menu_permission_tb.deleted','menu_permission_tb.edit')->orderBy('menu.id')->get();
    	return view("menu-permission/menu_permission_form",compact('title','role_list','menu_data','role_id'));
    }
	
    public function SaveMenuPermission(Request $request)
    {
    	$menu_permission=array_unique($request->permission);
    	$view=$request->view;
    	$edit=$request->edit;
    	$added=$request->added;
    	$deleted=$request->deleted;
    	
    
    	
    	
		if($menu_permission){
			\App\Models\MenuPermissionModel::where('role_id',$request->role_id)->whereNotIn('menu_id_permission',$menu_permission)->delete();
			// dd($menu_permission);
			$i=0;
			foreach ($menu_permission as $key => $value) {
				$save= \App\Models\MenuPermissionModel::updateOrCreate([
					'role_id'=> $request->role_id,
					'menu_id_permission'  => $value,
				]);
				
				$idd=$save->id;
	
				
					if (isset($view[0]))
  {		
				
				if (in_array($value, $view))
                 {
        DB::table('menu_permission_tb')
                ->where('id', $idd)
                 ->where('role_id', $request->role_id)
                  ->where('menu_id_permission', $value)
                ->update(['view'=>$value]);
                 }
               else
               {
            DB::table('menu_permission_tb')
                ->where('id', $idd)
                 ->where('role_id', $request->role_id)
                  ->where('menu_id_permission', $value)
                ->update(['view'=>0]);
               }
               
  }  else
 {
       DB::table('menu_permission_tb')
                ->where('id', $idd)
                 ->where('role_id', $request->role_id)
                  ->where('menu_id_permission', $value)
                ->update(['view'=>0]);
               }
 
               
               				if (isset($edit[0]))
  {		
               	if (in_array($value, $edit))
                 {
        DB::table('menu_permission_tb')
                ->where('id', $idd)
                 ->where('role_id', $request->role_id)
                  ->where('menu_id_permission', $value)
                ->update(['edit'=>$value]);
                 }
               else
               {
            DB::table('menu_permission_tb')
                ->where('id', $idd)
                 ->where('role_id', $request->role_id)
                  ->where('menu_id_permission', $value)
                ->update(['edit'=>0]);
               }
  }
                else
               {
            DB::table('menu_permission_tb')
                ->where('id', $idd)
                 ->where('role_id', $request->role_id)
                  ->where('menu_id_permission', $value)
                ->update(['edit'=>0]);
               }
                          				if (isset($added[0]))
  {	
               	if (in_array($value, $added))
                 {
        DB::table('menu_permission_tb')
                ->where('id', $idd)
                 ->where('role_id', $request->role_id)
                  ->where('menu_id_permission', $value)
                ->update(['added'=>$value]);
                 }
               else
               {
            DB::table('menu_permission_tb')
                ->where('id', $idd)
                 ->where('role_id', $request->role_id)
                  ->where('menu_id_permission', $value)
                ->update(['added'=>0]);
               }
  }
               else
               {
            DB::table('menu_permission_tb')
                ->where('id', $idd)
                 ->where('role_id', $request->role_id)
                  ->where('menu_id_permission', $value)
                ->update(['added'=>0]);
               }
               
               	if (isset($deleted[0]))
  {	
               	if (in_array($value, $deleted))
                 {
        DB::table('menu_permission_tb')
                ->where('id', $idd)
                 ->where('role_id', $request->role_id)
                  ->where('menu_id_permission', $value)
                ->update(['deleted'=>$value]);
                 }
               else
               {
            DB::table('menu_permission_tb')
                ->where('id', $idd)
                 ->where('role_id', $request->role_id)
                  ->where('menu_id_permission', $value)
                ->update(['deleted'=>0]);
               }
  }
               else
               {
            DB::table('menu_permission_tb')
                ->where('id', $idd)
                 ->where('role_id', $request->role_id)
                  ->where('menu_id_permission', $value)
                ->update(['deleted'=>0]);
               }
				 
                
                
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
				$i++;
			}
		}    	  
		return redirect()->route('menu_permission_list');
    }

}
