<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function ShowMenuList(Request $request)
    {
        if (Helper::checkMenuElegible()) {
            $title = "Menu List";
            $menu_data = \App\Models\MenuModel::orderBy('id', 'DESC')->get()->toarray();
            return view("menu.menu_list", compact('title', 'menu_data'));
        }
        abort(403, "Don't have permission to access.");
    }
    public function MenuForm(Request $request, $id = null)
    {
        $data = [];
        if ($id) {
            $title = "Edit Menu";
            $data = \App\Models\MenuModel::where('id', $id)->first();
        } else {
            $title = "Add Menu";
        }
        $menu_list = \App\Models\MenuModel::whereNull('main_menu')->where('status', '1')->pluck('menu', 'id');
        return view("menu.menu_form", compact('title', 'data', 'menu_list'));
    }
    public function SaveMenu(Request $request)
    {
        $check_exist_menu = \App\Models\MenuModel::select('menu')->where('id', '!=', $request->id)->where('menu', $request->menu)->exists();
        if ($check_exist_menu) {
            session()->flash('msg', 'Menu Already exist');
            return redirect()->back();
        }

        $save = \App\Models\MenuModel::updateOrCreate(['id' =>  $request->id], [
            'main_menu' => $request->main_menu,
            'menu'  => $request->menu,
            'slug'  => $request->slug,
            'status' => "1",
        ]);
        return redirect()->route('menu_list');
    }
    public function  UpdateMenuStatus(Request $request)
    {
        $id = $request->id;
        $status = $request->status;

        $result = \App\Models\MenuModel::where('id', $id)->update(['status' => $status]);
        $get_update_status = \App\Models\MenuModel::where('id', $id)->first();


        if ($get_update_status->status == 1) {
            $arr = array("code" => 200, "message" => "Menu is Active Successfully", "data" => array());
            return \Response::json($arr);
        } elseif ($get_update_status->status == 0) {
            $arr = array("code" => 200, "message" => "Menu is InActive Successfully", "data" => array());
            return \Response::json($arr);
        } else {
            $arr = array("code" => 400, "message" => "Fail to update status", "data" => array());
            return \Response::json($arr);
        }
    }
    public function DeleteMenu(Request $request)
    {
        $id = $request->id;

        \App\Models\MenuModel::where('id', $id)->delete();
        $arr = array(
            "code" => 200,
            "message" => "Delete Successfully",
            "data" => ''
        );

        return \Response::json($arr);
    }

    /*----------------------Submenu-------------------------------*/
    /* public function ShowSubMenuList(Request $request)
     {
     	$title="SubMenu List";
     	$data=\App\Models\SubMenuModel::select('submenu.*','menu.menu')
     	       ->leftjoin('menu','submenu.menu_id','=','menu.id')->get()->toarray();
     	return view("menu.submenu_list",compact('title','data'));
     }
     public function SubMenuForm(Request $request,$id=null)
     {
     	$data=[];
     	if($id)
     	{
           $title="Edit SubMenu Form";
           $data=\App\Models\SubMenuModel::where('id',$id)->first();
     	}
     	else
     	{
     		$title="Add SubMenu Form";
     	}
     	
     	$menu_list=\App\Models\MenuModel::where('status','1')->pluck('menu','id');
     	return view("menu.submenu_form",compact('title','menu_list','data'));
     	
     }
     public function SaveSubMenu(Request $request)
     {
       $check_exist_submenu=\App\Models\SubMenuModel::select('submenu')->where('submenu',$request->submenu)->exists();
     	if($check_exist_submenu)
     	{
     		 session()->flash('msg', 'SubMenu Already exist');
     		 return redirect()->back();
     	}
     	$save= \App\Models\SubMenuModel::updateOrCreate(['id' =>  $request->id],[
	    	'menu_id'  =>$request->menu_id,
	    	'submenu'  =>$request->submenu,
		    'slug'  =>$request->slug,
		    'status' =>"1",
		]);
		return redirect()->route('submenu_list');
     }
      public function  UpdateSubMenuStatus(Request $request)
     {
     	//dd("hfd");
          $id = $request->id;
          $status = $request->status;
        
           $result = \App\Models\SubMenuModel::where('id',$id)->update(['status' => $status]); 
           $get_update_status= \App\Models\SubMenuModel::where('id', $id)->first();

         
            if($get_update_status->status==1)
            {
                $arr = array("code" => 200, "message" => "SubMenu is Active Successfully", "data" => array());
                    return \Response::json($arr);
            }
            elseif($get_update_status->status==0)
            {
                $arr = array("code" => 200, "message" => "SubMenu is InActive Successfully", "data" => array());
                    return \Response::json($arr);
            }
            else
            {
                $arr = array("code" => 400, "message" => "Fail to update status", "data" => array());
                   return \Response::json($arr);
            }

     }
     public function DeleteSubMenu(Request $request)
     {
         $id = $request->id;
             
         \App\Models\SubMenuModel::where('id', $id)->delete();
         $arr = array(
            "code" => 200,
            "message" => "Delete Successfully",
            "data" => ''
        );
       
        return \Response::json($arr);
     }*/
}