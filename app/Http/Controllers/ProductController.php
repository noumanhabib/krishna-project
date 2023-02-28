<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use DB;

class ProductController extends Controller
{
     public function __construct()
    {
        $this->middleware('auth');
    }
     public function ShowProductList(Request $request)
     {
         $title="Product List";
         $elsBrd = DB::table('brand')->where('bstatus','1')->get()->toarray();
         $elsVen = DB::table('vendor')->where('status','1')->get()->toarray();
         $elsUsr = DB::table('users')->where('is_active','1')->get()->toarray();
         return view("product_list",compact('title'),['elsVen' => $elsVen,'elsUsr' => $elsUsr,'elsBrd' => $elsBrd]);
     }
     public function AddProduct(Request $request)
     {
         $elsBrd = DB::table('brand')->where('bstatus','1')->get()->toarray();
         $elsVen = DB::table('vendor')->where('status','1')->get()->toarray();
         $elsUsr = DB::table('users')->where('is_active','1')->get()->toarray();
         $elsClr =  DB::table('colour')->where('status','1')->get()->toarray();
       // dd($elsClr);
         return view('add_product',['elsVen' => $elsVen,'elsUsr' => $elsUsr,'elsBrd' => $elsBrd,'elsClr' => $elsClr]);
     }
     /*Fetch list of categories...............................*/
     public function FetchProductList(Request $request)
     {
          $product_list = DB::table('product')
          ->join('model', 'product.model', '=', 'model.id')
          ->join('brand', 'product.brand', '=', 'brand.id')
          ->select('product.*', 'brand.bname', 'model.mname')
          ->get();
          $arr = array("code" => 200, "message" =>'success', "data" => $product_list);
                      return \Response::json($arr);
     }
     public function FetchModel(Request $request)
     {
          $id=$request->id;
          $product_list = DB::table('model')->where('brand_id',$id)->get();
          $arr = array("code" => 200, "message" =>'success', "data" => $product_list);
                      return \Response::json($arr);
     } 
     /*--------Save Category..............................*/
     public function SaveProduct(Request $request)
     {
         //dd($request->all());
              $pbrand=$request->pbrand;
              $pmodel=$request->pmodel;
              $ptype=$request->ptype;
              $pname=$request->pname;
              $pcolor=$request->pcolor;
              $pquantity=$request->pquantity;
              $pprice=$request->pprice;
              $psku=$request->psku;
              $pentry=$request->pentry;
              $pvendor=$request->pvendor;
              //$series_num=$request->series_num;
             /* $check_exist_category= DB::table('product')->where('sku', $psku)->get()->toArray();
              if(count($check_exist_category)>0)
              {
                $response['code']       = 502;
                $response['error']      = False;
                $response['message']    = "Sku Already Exist";   
                $res  = json_encode($response);     
                echo $res; die;
              }
             else
             { */

                    $data = array(
                   "name"         => $pname,
                   "model"        => $pmodel,
                   "brand"        => $pbrand,
                   "color"        => $pcolor,
                   "entry"        => $pentry,
                   "quantity"     => $pquantity,
                   "type"         => $ptype,
                   "price"        => $pprice,
                   "vendor"        => $pvendor,
                   "sku"          => $psku,
                   "is_active"    => 1,
                   "created_on"   => date('Y-m-d h:i:s'),
                    );
                    DB::table('product')->insert($data); 
                 //$result= DB::table('product')->insert($data);  
                   // $sparepart_id = DB::table('product')->insertGetId($result);
                    $sparepart_id = DB::getPdo()->lastInsertId();
                   foreach($request->series as $key => $value)
                   { 
                        $seriesdata= array(
                              "sparepart_id" =>$sparepart_id,
                              "series_num"   => $request['series'][$key],
                        );
                        DB::table('sparepart_product_series_tb')->insert($seriesdata); 

                   } 
                /*} */     
                    
              return redirect()->route('product_list');
     }
     /*.....Update category status.................................*/
     public function  UpdateProductStatus(Request $request)
     {
          $category_id = $request->category_id;
          $status = $request->status;
          $result = DB::table('product')->where('id',$category_id)->update(['is_active' => $status]); 
          $get_update_status= DB::table('product')->where('id', $category_id)->first();
            if($get_update_status->is_active==1)
            {
                $arr = array("code" => 200, "message" => "Product is Active Successfully", "data" => array());
                    return \Response::json($arr);
            }
            elseif($get_update_status->is_active==0)
            {
                $arr = array("code" => 200, "message" => "Product is InActive Successfully", "data" => array());
                    return \Response::json($arr);
            }
            else
            {
                $arr = array("code" => 400, "message" => "Fail to update status", "data" => array());
                    return \Response::json($arr);
            }
     }
   /*.............Edit Category data................................*/
     public function EditProduct(Request $request)
     {
         $id = $request->id;
         $data = DB::table('product')
          ->join('model', 'product.model', '=', 'model.id')
          ->join('brand', 'product.brand', '=', 'brand.id')
          ->select('product.*', 'brand.bname', 'model.mname')
          ->where('product.id', $id)->first();
        $arr = array(
            "code" => 200,
            "message" => "Success",
            "data" => $data
        );

        return \Response::json($arr);
     }
     
    /*update Category data......................................*/
    public function UpdateProduct(Request $request)
    {
        $obj = array();
        $id = $request->id;
        $obj['brand'] = $request->editbrand;
        $obj['price'] = $request->editprice;
        $obj['name'] = $request->editproduct;
        $obj['model'] = $request->editmodel;
        $obj['color'] = $request->editcolor;
        $obj['quantity'] = $request->editquantity;
        $obj['entry'] = $request->editperson;
        $obj['vendor'] = $request->editvendor;
         $data = DB::table('product')->where('id', $id)->update($obj);
                    $arr = array(
                        "code" => 200,
                        "message" => "Product has been Updated Successfully",
                        "data" => array()
                    );
                    return \Response::json($arr);      
    }
    /*Soft Delete Category ..............................*/
    public function DeleteProduct(Request $request)
    {
        $id = $request->id;
        $soft_delete=date('Y-m-d h:i:s');
        $obj = array();
        $obj['soft_delete'] = $soft_delete;

       DB::table('product')->where('id', $id)->delete();
        $arr = array(
            "code" => 200,
            "message" => "Delete Successfully",
            "data" => ''
        );
        return \Response::json($arr);
    }

    /*---------------------Auto Serach-------------------*/
    public function selectSearch(request $request)
    {
         dd($request->all());
    }
}
