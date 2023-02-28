<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\MasterCategoryModel;
use Illuminate\Support\Facades\DB;

class ApiController extends Controller
{
    
    public function __construct()
    {
        //$this->middleware('auth');
    }
    
   function FetchWarrenty(Request $request)
	{
	$imei=$request->imei;
// 	$imei='359473074091618';
	$els_system = DB::table('els_system_info_details')
    ->select('id','model_id')
    ->where('imei_1', $imei)
    ->whereNull('deleted_at')
    // ->where('status', 1)
    ->first(); 
    // print_r($els_system);
   
    if(!empty($els_system))
    {
    $els_id=$els_system->id;
    $model_id=$els_system->model_id;
    
    $modeln = DB::table('model')
    ->select('mname')
    ->where('id', $model_id)
    ->first();
    
    $els_systems = DB::table('els_product_warranty')
    ->select('start_date','end_date','duration','type','id','remark')
    ->where('els_system_id', $els_id)
    ->first();

    
    if(!empty($els_systems))
    {
            
    $arr['start_date']=$els_systems->start_date;
    $arr['end_date']=$els_systems->end_date;
    $arr['duration']=$els_systems->duration;
    $arr['type']=$els_systems->type;
    $arr['model']=$modeln->mname;
     $arr['id']=$els_systems->id;
      $arr['remark']=$els_systems->remark;
        // array_push($els_systems,$modeln);
     	 return json_encode($arr);    
    }
    else
    {
        return json_encode(array('msg'=>'Imei not found in warrenty'));
    }
    }
    else
    {
         return json_encode(array('msg'=>'Imei does not exist in portal'));
    }
	}
	
	 function UpdateWarrenty(Request $request)
	 {
	     
// 	     	$request->start_date="2021-12-16";
// 	$request->type="day";
// 	$request->duration=5;
	     
	     $end_date = date('Y-m-d', strtotime('+'.$request->duration.' '.$request->type, strtotime($request->start_date)));;
		
			$imei=$request->imei;
// 	$imei='359473074091618';
	$els_system = DB::table('els_system_info_details')
    ->select('id')
    ->where('imei_1', $imei)
    ->whereNull('deleted_at')
    // ->where('status', 1)
    ->first(); 
    

	
	if(!empty($els_system))
    {	
	
		DB::table('els_product_warranty')
            ->where('id', $request->id)
            ->update(['els_system_id' => $els_system->id,'duration' => $request->duration,'type'=>$request->type,'start_date'=>date('Y-m-d',strtotime($request->start_date)),'end_date'=>$end_date,
			'remark'=>$request->remark]);
		
		
	 return json_encode(array('msg'=>'Update Warrenty Sucessfully!'));
	 
    }
     else
    {
    return json_encode(array('msg'=>'Imei does not exist in portal'));
    }
	
	     
	 }
	
	 function AddWarrenty(Request $request)
	{
	    
	      if($request->start_date=='')
	    {
	      $start=NULL;
	        
	    }
	    else
	    {
	        $start=date('Y-m-d',strtotime($request->start_date));
	    } 
	    
	    	  if($start==NULL){
	    	      	$end_date =NULL;
	    	  }
	    	  else
	    	  {
	$end_date = date('Y-m-d', strtotime('+'.$request->duration.' '.$request->type, strtotime($request->start_date)));;
	    	  }
			$imei=$request->imei;
// 	$imei='359473074091618';
	$els_system = DB::table('els_system_info_details')
    ->select('id')
    ->where('imei_1', $imei)
    ->whereNull('deleted_at')
    // ->where('status', 1)
    ->first(); 
    
		
	if(!empty($els_system))
    {
         
    $els_systems = DB::table('els_product_warranty')
    ->select('start_date','end_date','duration','type')
    ->where('els_system_id', $els_system->id)
    ->first();
		if(empty($els_systems)){
		$save = \App\Models\ELSProductWarranty::updateOrCreate([
			'id'=>$request->id,
		],[
			'els_system_id'=>$els_system->id,
			'duration'=>$request->duration,
			'type'=>$request->type,
			'start_date'=>$start,
			'end_date'=>$end_date,
			'remark'=>$request->remark,
		]);
	 return json_encode(array('msg'=>'Add Warrenty Sucessfully!','id'=>$save->id));
		}
		else
		{
	return json_encode(array('msg'=>'Already Added Warrenty!You need to update'));	    
		}
	 
    }
     else
    {
    return json_encode(array('msg'=>'Imei does not exist in portal'));
    }
	    
	}

	

  
}