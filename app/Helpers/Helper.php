<?php

namespace App\Helpers;

use DB;
use App;
use Auth;

class Helper
{

    public static function compressHtml($html)
    {
        $response = '';;
        $response = $html;
        if (strpos($response, '<pre>') !== false) {
            $replace = array(
                '/<!--[^\[](.*?)[^\]]-->/s' => '',
                "/<\?php/" => '<?php ',
                "/\r/" => '',
                "/>\n</" => '><',
                "/>\s+\n</" => '><',
                "/>\n\s+</" => '><',
                "/'/" => '`',
            );
        } else {
            $replace = array(
                '/<!--[^\[](.*?)[^\]]-->/s' => '',
                "/<\?php/" => '<?php ',
                "/\n([\S])/" => '$1',
                "/\r/" => '',
                "/\n/" => '',
                "/\t/" => '',
                "/ +/" => ' ',
                "/'/" => '`',
            );
        }
        $response = preg_replace(array_keys($replace), array_values($replace), $response);
        return $response;
    }

    public static function getModel($brand_id)
    {
        $data = \App\Models\MasterModel::where('brand_id', $brand_id)->whereNull('deleted_at')->pluck('mname', 'id');
        return $data;
    }

    public static function getSeriesNo($model_id, $type_id, $part_id)
    {
        $data = \App\Models\SparePartListModel::where('model_id', $model_id)->where('type_id', $type_id)->where('part_id', $part_id)->whereNull('deleted_at')->pluck('series_no', 'id');
        return $data;
    }

    public static function getColourList($series_id)
    {
        $data = \App\Models\MasterColourModel::join('spare_part_price_list', function ($join) {
            $join->on('spare_part_price_list.colour_id', 'colour.id');
            $join->whereNull('spare_part_price_list.deleted_at');
        })->where('spare_part_price_list.series_id', $series_id)->pluck('colour.name', 'colour.id');
        return $data;
    }

    public static function getPartColourList($model_id, $type_id, $part_id)
    {
        $data = \App\Models\MasterColourModel::join('spare_part_price_list', function ($join) {
            $join->on('spare_part_price_list.colour_id', 'colour.id');
            $join->whereNull('spare_part_price_list.deleted_at');
        })->join('purchase_order_parts_list', function ($join) {
            $join->on('purchase_order_parts_list.colour_id', 'spare_part_price_list.colour_id');
        })->where('purchase_order_parts_list.model_id', $model_id)

            // ->where('purchase_order_parts_list.type_id', $type_id)
            ->where('purchase_order_parts_list.part_id', $part_id)->groupBy('colour.id')->pluck('colour.name', 'colour.id');
        return $data;
    }


    public static function getPartColourListt($model_id, $type_id, $part_id)
    {
        $data = \App\Models\MasterColourModel::join('spare_part_price_list', function ($join) {
            $join->on('spare_part_price_list.colour_id', 'colour.id');
            $join->whereNull('spare_part_price_list.deleted_at');
        })->join('spare_part_list', function ($join) {
            $join->on('spare_part_list.id', 'spare_part_price_list.series_id');
        })->where('spare_part_list.model_id', $model_id)

            ->where('spare_part_list.type_id', $type_id)
            ->where('spare_part_list.part_id', $part_id)->groupBy('colour.id')->pluck('colour.name', 'colour.id');
        return $data;
    }


    public static function getPrice($model_id, $type_id, $part_id, $colour_id)
    {
        $data = \App\Models\MasterColourModel::join('spare_part_price_list', function ($join) {
            $join->on('spare_part_price_list.colour_id', 'colour.id');
            $join->whereNull('spare_part_price_list.deleted_at');
        })->join('spare_part_list', function ($join) {
            $join->on('spare_part_list.id', 'spare_part_price_list.series_id');
        })->where('spare_part_list.model_id', $model_id)->where('spare_part_list.type_id', $type_id)->where('spare_part_list.part_id', $part_id)->where('spare_part_price_list.colour_id', $colour_id)->groupBy('colour.id')->first();
        return $data;
    }

    public static function stockInChecking($model_id, $part_type_id, $part_id, $series_id, $colour_id)
    {
        $data = \App\Models\ReceivedPurchaseOrderParts::join('received_parts_barcode_list', function ($join) {
            $join->on('received_parts_barcode_list.received_part_id', 'received_purchase_order_parts_list.id');
            $join->where('received_parts_barcode_list.status', '2');
            $join->whereNull('received_parts_barcode_list.deleted_at');
        })->where('received_purchase_order_parts_list.model_id', $model_id)->where('received_purchase_order_parts_list.product_type_id', $part_type_id)->where('received_purchase_order_parts_list.part_id', $part_id)->where('received_purchase_order_parts_list.series_id', $series_id)->where('received_purchase_order_parts_list.colour_id', $colour_id)->whereNull('received_purchase_order_parts_list.deleted_at')->selectRaw('count(received_parts_barcode_list.id) as total_availability')->first();
        return $data;
    }

    public static function subMenuList($menu_id, $role_id)
    {
        $menu_data = \App\Models\MenuModel::leftjoin('menu_permission_tb', function ($join) use ($role_id) {
            $join->on('menu.id', 'menu_permission_tb.menu_id_permission');
            $join->where('role_id', $role_id);
        })->where('menu.main_menu', $menu_id)->where('menu.status', '1')->select('menu.*', 'menu_permission_tb.menu_id_permission', 'menu_permission_tb.id as permission_id', 'menu_permission_tb.view', 'menu_permission_tb.added', 'menu_permission_tb.deleted', 'menu_permission_tb.edit')->orderBy('menu.id')->get();
        return $menu_data;
    }

    /* public static function menu()
      {
      $menu_string = '';
      $menu_list=\App\Models\MenuModel::join('menu_permission_tb',function($join){
      $join->on('menu.id','menu_permission_tb.menu_id_permission');
      $join->whereNull('menu_permission_tb.deleted_at');
      })->whereNull('menu.main_menu')->where('menu.status','1')->where('menu_permission_tb.role_id',\Auth::user()->role)->select('menu.*')->groupBy('menu.id')->get();
      if(!$menu_list->isEmpty()){
      foreach($menu_list as $menu){
      $sub_menu_list = \App\Models\MenuModel::join('menu_permission_tb',function($join){
      $join->on('menu.id','menu_permission_tb.menu_id_permission');
      $join->whereNull('menu_permission_tb.deleted_at');
      })->where('menu.main_menu',$menu->id)->where('menu.status','1')->where('menu_permission_tb.role_id',\Auth::user()->role)->select('menu.*')->groupBy('menu.id')->get();
      if($sub_menu_list->isEmpty()){
      if((url()->current() == url($menu->slug))){
      $menu_string .= '<li><a href="'.url($menu->slug).'" class="active-menu"><i class="fa fa-dashboard"></i>'.$menu->menu.'</a></li>';
      }else{
      $menu_string .= '<li><a href="'.url($menu->slug).'"><i class="fa fa-dashboard"></i>'.$menu->menu.'</a></li>';
      }
      }else{
      $sub_menu_string = '';
      $active_sub_menu = false;
      foreach($sub_menu_list as $sub_menu){
      if((url()->current() == url($sub_menu->slug))){
      $active_sub_menu = true;
      $sub_menu_string .= '<li><a href="'.url($sub_menu->slug).'" class="active-menu"><i class="fa fa-dashboard"></i>'.$sub_menu->menu.'</a></li>';
      }else{
      $sub_menu_string .= '<li><a href="'.url($sub_menu->slug).'"><i class="fa fa-dashboard"></i>'.$sub_menu->menu.'</a></li>';
      }
      }
      if($active_sub_menu){
      $menu_string .= '<li class="active"><a href="javascript:;"><i class="fa fa-sitemap"></i>'.$menu->menu.'<span class="fa arrow"></span></a><ul class="nav nav-second-level collapse" aria-expanded="false">';
      }else{
      $menu_string .= '<li><a href="javascript:;"><i class="fa fa-sitemap"></i>'.$menu->menu.'<span class="fa arrow"></span></a><ul class="nav nav-second-level collapse" aria-expanded="false">';
      }

      $menu_string .= $sub_menu_string;
      $menu_string .= '</ul></li>';
      }
      }
      }
      return $menu_string;
      }
     */

    public static function menu()
    {
        // $string_menu='';
        $menu_string = '';
        $menu_list = \App\Models\MenuModel::join('menu_permission_tb', function ($join) {
            $join->on('menu.id', 'menu_permission_tb.menu_id_permission');
            $join->whereNull('menu_permission_tb.deleted_at');
        })->whereNull('menu.main_menu')->whereNull('menu.child_menu')->where('menu.status', '1')->where('menu_permission_tb.role_id', \Auth::user()->role)->select('menu.*')->groupBy('menu.id')->get();
        if (!$menu_list->isEmpty()) {
            foreach ($menu_list as $menu) {
                $sub_menu_list = \App\Models\MenuModel::join('menu_permission_tb', function ($join) {
                    $join->on('menu.id', 'menu_permission_tb.menu_id_permission');
                    $join->whereNull('menu_permission_tb.deleted_at');
                })->where('menu.main_menu', $menu->id)->whereNull('menu.child_menu')->where('menu.status', '1')->where('menu_permission_tb.role_id', \Auth::user()->role)->select('menu.*')->groupBy('menu.id')->get();
                if ($sub_menu_list->isEmpty()) {
                    if ((url()->current() == url($menu->slug))) {
                        $menu_string .= '<li><a href="' . url($menu->slug) . '" class="active-menu"><i class="fa fa-dashboard"></i>' . $menu->menu . '</a></li>';
                    } else {
                        $menu_string .= '<li><a href="' . url($menu->slug) . '"><i class="fa fa-dashboard"></i>' . $menu->menu . '</a></li>';
                    }
                } else {

                    $sub_menu_string = '';
                    $active_sub_menu = false;
                    foreach ($sub_menu_list as $sub_menu) {
                        $child_menu_string = '';
                        $active_child_menu = false;
                        $child_menu_list = \App\Models\MenuModel::join('menu_permission_tb', function ($join) {
                            $join->on('menu.id', 'menu_permission_tb.menu_id_permission');
                            $join->whereNull('menu_permission_tb.deleted_at');
                        })->where('menu.child_menu', $sub_menu->id)->whereNull('menu.main_menu')->where('menu.status', '1')->where('menu_permission_tb.role_id', \Auth::user()->role)->select('menu.*')->groupBy('menu.id')->get();
                        if (!$child_menu_list->isEmpty()) {

                            foreach ($child_menu_list as $child_menu) {
                                if ((url()->current() == url($child_menu->slug))) {
                                    $active_child_menu = true;
                                    $child_menu_string .= '<li><a href="' . url($child_menu->slug) . '" class="active-menu"><i class="fa fa-dashboard"></i>' . $child_menu->menu . '</a></li>';
                                } else {
                                    $child_menu_string .= '<li><a href="' . url($child_menu->slug) . '"><i class="fa fa-dashboard"></i>' . $child_menu->menu . '</a></li>';
                                }
                            }

                            if ($active_child_menu) {

                                $sub_menu_string .= '<li class="active"><a href="javascript:;"><i class="fa fa-sitemap"></i>' . $sub_menu->menu . '<span class="fa arrow"></span></a><ul class="nav nav-third-level collapse" aria-expanded="false">';
                            } else {

                                $sub_menu_string .= '<li><a href="javascript:;"><i class="fa fa-sitemap"></i>' . $sub_menu->menu . '<span class="fa arrow"></span></a><ul class="nav nav-third-level collapse" aria-expanded="false">';
                            }

                            $sub_menu_string .= "<li><a href='status_list'><i class='fa fa-dashboard'></i>Status</a></li>";


                            $sub_menu_string .= $child_menu_string;
                            $sub_menu_string .= '</ul></li>';
                        } else {

                            if ((url()->current() == url($sub_menu->slug))) {
                                $active_sub_menu = true;
                                $sub_menu_string .= '<li><a href="' . url($sub_menu->slug) . '" class="active-menu"><i class="fa fa-dashboard"></i>' . $sub_menu->menu . '</a></li>';
                            } else {
                                $sub_menu_string .= '<li><a href="' . url($sub_menu->slug) . '"><i class="fa fa-dashboard"></i>' . $sub_menu->menu . '</a></li>';
                            }
                        }
                    }
                    if ($active_sub_menu) {
                        $menu_string .= '<li class="active"><a href="javascript:;"><i class="fa fa-sitemap"></i>' . $menu->menu . '<span class="fa arrow"></span></a><ul class="nav nav-second-level collapse" aria-expanded="false">';
                    } else {
                        $menu_string .= '<li><a href="javascript:;"><i class="fa fa-sitemap"></i>' . $menu->menu . '<span class="fa arrow"></span></a><ul class="nav nav-second-level collapse" aria-expanded="false">';
                    }

                    $menu_string .= $sub_menu_string;

                    $menu_string .= '</ul></li>';
                }
            }
            $menu_string .= "<li><a href='goods-receive-notes'><i class='fa fa-dashboard'></i>Goods Received Notes</a></li>";
            $menu_string .= "<li><a href='renew-pins'><i class='fa fa-dashboard'></i>Renew Pins</a></li>";

            // dd($menu_string);

        }
        return $menu_string;
    }

    public static function checkMenuElegible()
    {
        if (Auth::user()) {
            $path = explode(url(''), url()->current());
            $menu = \App\Models\MenuModel::join('menu_permission_tb', function ($join) {
                $join->on('menu.id', 'menu_permission_tb.menu_id_permission');
                $join->whereNull('menu_permission_tb.deleted_at');
            })
                ->where('menu.slug', substr($path[1], 1))
                ->where('menu_permission_tb.role_id', Auth::user()->role)
                ->whereNull('menu.deleted_at')
                ->first();
            if ($menu) {
                return true;
            }
        }
        return false;
    }

    public static function getAssignedSystemCount($engineer_id, $status)
    {
        if (session()->get('start_date') && session()->get('end_date')) {
            $start_date = session()->get('start_date');
            $end_date = date('Y-m-d', strtotime('+1 day', strtotime(session()->get('end_date'))));
        } else {
            $start_date = date('Y-m-d', strtotime('-29 day'));
            $end_date = date('Y-m-d', strtotime('+1 day'));
        }
        $data = \App\Models\AssignEngineer::whereNull('deleted_at')->whereBetween('created_at', [$start_date, $end_date])->where('engineer_id', $engineer_id)->where('status', $status)->count();
        return $data;
    }

    public static function getAssignedQcSystemCount($engineer_id, $status)
    {
        if (session()->get('start_date') && session()->get('end_date')) {
            $start_date = session()->get('start_date');
            $end_date = date('Y-m-d', strtotime('+1 day', strtotime(session()->get('end_date'))));
        } else {
            $start_date = date('Y-m-d', strtotime('-29 day'));
            $end_date = date('Y-m-d', strtotime('+1 day'));
        }
        $data = \App\Models\AssignQcEngineer::whereNull('deleted_at')->whereBetween('created_at', [$start_date, $end_date])->where('engineer_id', $engineer_id)->where('status', $status)->count();
        return $data;
    }

    public static function getDeviceSKUNumber($brand_id, $model_id, $colour_id)
    {
        $brand_key = $model_key = $colour_key = '';
        $brand = \App\Models\MasterBrandModel::where('id', $brand_id)->first();
        if ($brand) {
            if (strlen($brand->bname) > 4) {
                $brand_key = substr(preg_replace('/\s+/', '', $brand->bname), 0, 4);
            } else {
                $brand_key = preg_replace('/\s+/', '', $brand->bname);
            }
        }
        $model = \App\Models\MasterModel::where('id', $model_id)->first();
        if ($model) {
            if (strlen($model->mname) > 4) {
                $model_key = substr(preg_replace('/\s+/', '', $model->mname), 0, 4);
            } else {
                $model_key = preg_replace('/\s+/', '', $model->mname);
            }
        }
        $colour = \App\Models\MasterColourModel::where('id', $colour_id)->first();
        if ($colour) {
            if (strlen($colour->name) > 2) {
                $colour_key = substr(preg_replace('/\s+/', '', $colour->name), 0, 2);
            } else {
                $colour_key = preg_replace('/\s+/', '', $colour->name);
            }
        }
        return str_replace("-", "_", strtoupper($brand_key) . '/' . strtoupper($model_key) . '/' . strtoupper($colour_key));
    }

    public static function getPartsSKUNumber($model_id, $part_id, $colour_id)
    {
        $model = \App\Models\MasterModel::where('id', $model_id)->first();
        if (strlen($model->mname) > 4) {
            $model_key = substr(preg_replace('/\s+/', '', $model->mname), 0, 4);
        } else {
            $model_key = preg_replace('/\s+/', '', $model->mname);
        }
        $parts = \App\Models\MasterPartModel::where('id', $part_id)->first();
        if (strlen($parts->name) > 4) {
            $parts_key = substr(preg_replace('/\s+/', '', $parts->name), 0, 4);
        } else {
            $parts_key = preg_replace('/\s+/', '', $parts->name);
        }
        $colour = \App\Models\MasterColourModel::where('id', $colour_id)->first();
        if (strlen($colour->name) > 2) {
            $colour_key = substr(preg_replace('/\s+/', '', $colour->name), 0, 2);
        } else {
            $colour_key = preg_replace('/\s+/', '', $colour->name);
        }
        return str_replace("-", "_", strtoupper($model_key) . '/' . strtoupper($parts_key) . '/' . strtoupper($colour_key));
    }

    public static function getSKUID($brand_id, $model_id, $type_id, $part_id, $colour_id)
    {
        $data = \App\Models\SparePartListModel::join('spare_part_price_list', function ($join) {
            $join->on('spare_part_list.id', 'spare_part_price_list.series_id');
        })->where('spare_part_list.brand_id', $brand_id)->where('spare_part_list.model_id', $model_id)->where('spare_part_list.type_id', $type_id)->where('spare_part_list.part_id', $part_id)->where('spare_part_price_list.colour_id', $colour_id)->select('spare_part_price_list.*')->first();
        if (@$data->id != '') {
            return @$data->id;
        } else {
            return 0;
        }
    }

    public static function getRequiredQuantity($brand_id, $model_id, $part_type_id, $part_id, $colour_id)
    {
        $data = \App\Models\OrderRequestPart::whereNull('deleted_at')->where('brand_id', $brand_id)->where('model_id', $model_id)->where('part_type_id', $part_type_id)->where('part_id', $part_id)->where('colour_id', $colour_id)->selectRaw('SUM(quantity) as quantity')->first();
        return $data->quantity;
    }

    public static function getInwardDate($els_system_id)
    {
        $data = \App\Models\InwardDate::where('els_system_id', $els_system_id)->get();
        return $data;
    }

    public static function getActiveInwardDate($els_system_id)
    {
        $data = \App\Models\InwardDate::where('els_system_id', $els_system_id)->where('status', '1')->count();
        return $data;
    }

    public static function getOrderRequest($els_system_id)
    {
        $data = \App\Models\OrderRequest::join('els_order_request_parts', function ($join) {
            $join->on('els_order_request.id', 'els_order_request_parts.request_order_id');
            $join->whereNull('els_order_request_parts.deleted_at');
        })->join('part', function ($join) {
            $join->on('els_order_request_parts.part_id', 'part.id');
        })->whereNull('els_order_request.deleted_at')->where('els_order_request.els_system_id', $els_system_id)->selectRaw('els_order_request.created_at,GROUP_CONCAT(part.name) as part_name')->groupBy('els_order_request.id')->orderBy('els_order_request.created_at')->get();
        return $data;
    }

    public static function getBarcodeAllocation($els_system_id)
    {


        // select GROUP_CONCAT(part.name) from els_system_allocated_parts_barcode join received_parts_barcode_list on received_parts_barcode_list.id=els_system_allocated_parts_barcode.barcode_id join received_purchase_order_parts_list on received_purchase_order_parts_list.id=received_parts_barcode_list.received_part_id join part on part.id=received_purchase_order_parts_list.part_id where els_system_id = els_system_info_details.id


        $data = \App\Models\AllocatedBarcodes::join('received_parts_barcode_list', function ($join) {
            $join->on('els_system_allocated_parts_barcode.barcode_id', 'received_parts_barcode_list.id');
        })->join('received_purchase_order_parts_list', function ($join) {
            $join->on('received_purchase_order_parts_list.id', 'received_parts_barcode_list.received_part_id');
        })->join('part', function ($join) {
            $join->on('received_purchase_order_parts_list.part_id', 'part.id');
        })->where('els_system_allocated_parts_barcode.els_system_id', $els_system_id)->selectRaw('GROUP_CONCAT(received_parts_barcode_list.barcode) as barcode')->first();
        return $data;
    }

    public static function getEngineerAllocationDetails($els_system_id)
    {
        $data = \App\Models\AssignEngineer::join('users', function ($join) {
            $join->on('els_system_allocated_engineer.engineer_id', 'users.id');
        })->join('els_product_status', function ($join) {
            $join->on('els_system_allocated_engineer.status', 'els_product_status.id');
        })->where('els_system_allocated_engineer.els_system_id', $els_system_id)->selectRaw('els_system_allocated_engineer.created_at,users.name,els_product_status.name as status')->orderBy('els_system_allocated_engineer.id', 'ASC')->get();
        return $data;
    }

    public static function getDeviceWarrenty($els_system_id)
    {
        $data = \App\Models\ELSProductWarranty::where('els_system_id', $els_system_id)->first();
        return $data;
    }

    public static function getAllocatedBarcode($barcode_id)
    {
        $data = \App\Models\AllocatedBarcode::join('els_system_info_details', function ($join) {
            $join->on('els_system_info_details.id', 'els_system_allocated_parts_barcode.els_system_id');
        })->where('barcode_id', $barcode_id)->select('els_system_info_details.barcode', 'els_system_allocated_parts_barcode.*')->get();
        return $data;
    }

    public static function getSystemAllocatedBarcode($els_system_id, $model_id, $part_type_id, $part_id, $id)
    {
        $data = \App\Models\AllocatedBarcode::join('els_system_info_details', function ($join) {
            $join->on('els_system_info_details.id', 'els_system_allocated_parts_barcode.els_system_id');
        })->join('received_parts_barcode_list', function ($join) {
            $join->on('received_parts_barcode_list.id', 'els_system_allocated_parts_barcode.barcode_id');
            $join->whereNull('received_parts_barcode_list.deleted_at');
        })->join('els_order_request', function ($join) {
            $join->on('els_order_request.els_system_id', 'els_system_allocated_parts_barcode.els_system_id');
        })->join('els_order_request_parts', function ($join) {
            $join->on('els_order_request.id', 'els_order_request_parts.request_order_id');
        })->join('received_purchase_order_parts_list', function ($join) {
            $join->on('received_purchase_order_parts_list.id', 'received_parts_barcode_list.received_part_id');
            $join->whereNull('received_purchase_order_parts_list.deleted_at');
        })
            ->where('els_system_info_details.id', $els_system_id)
            ->where('received_purchase_order_parts_list.part_id', $part_id)
            ->where('received_purchase_order_parts_list.model_id', $model_id)
            ->where('received_purchase_order_parts_list.product_type_id', $part_type_id)
            ->where('els_order_request_parts.id', $id)
            ->whereNull('els_system_allocated_parts_barcode.deleted_at')->select('received_parts_barcode_list.barcode')->first();
        if (!empty($data)) {
            // dd($data);
            return $data->barcode;
        }
        return '';
    }

    public static function getStatusModelCount($model_id, $status)
    {
        $data = \App\Models\ElsSystemInfoDtailsModel::join('els_system_allocated_engineer', function ($join) {
            $join->on('els_system_info_details.id', 'els_system_allocated_engineer.els_system_id');
            $join->whereNull('els_system_allocated_engineer.deleted_at');
        })->whereNull('els_system_info_details.deleted_at')->where('els_system_info_details.model_id', $model_id)->where('els_system_allocated_engineer.status', $status)->count();
        return $data;
    }

    public static function actionPermission()
    {
        $data = \App\Models\RoleModel::find(\Auth::user()->role);
        if (in_array($data->role_name, ['admin', 'super_admin'])) {
            return true;
        }
        return false;
    }
    public static function addedPermission()
    {
        $data =  DB::table('menu_permission_tb')->where('role_id', \Auth::user()->role)->get();
        foreach ($data as $key => $value) {
            $arr[] = $value->added;
        }
        return $arr;
    }

    public static function editPermission()
    {
        $data =  DB::table('menu_permission_tb')->where('role_id', \Auth::user()->role)->get();
        foreach ($data as $key => $value) {
            $arr[] = $value->edit;
        }
        return $arr;
    }


    public static function deletedPermission()
    {
        $data =  DB::table('menu_permission_tb')->where('role_id', \Auth::user()->role)->get();
        foreach ($data as $key => $value) {
            $arr[] = $value->deleted;
        }
        return $arr;
    }

    public static function getStatusLog($els_system_id)
    {
        $data = \App\Models\ELSProductStatusLog::join('els_product_sub_status', function ($join) {
            $join->on('els_product_sub_status.id', 'els_system_status_log.status');
        })->leftjoin('vendor', function ($join) {
            $join->on('vendor.id', 'els_system_status_log.vendor_id');
        })->whereNull('els_system_status_log.deleted_at')->where('els_system_status_log.els_system_id', $els_system_id)->select('els_product_sub_status.name', 'vendor.vname', 'els_system_status_log.created_at')->orderBy('els_system_status_log.id', 'ASC')->get();
        return $data;
    }
}
