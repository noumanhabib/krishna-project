@extends('layouts.layout')
@section('title',$title)
@section('content')
@php $tabindex = 1; @endphp
<div id="page-wrapper" >
<div class="header">
   <h1 class="page-header">
      <small>Add ELS Product</small>
   </h1>
   <ol class="breadcrumb">
      <li><a href="{{url('/home')}}">Home</a></li>
      <li><a href="{{url('els_product_list')}}">ELS Product List</a></li>
      <li class="active">Add ELS Product</li>
   </ol>
</div>
<div id="page-inner">
<div class="row">
   <div class="col-md-12">
      <!-- Advanced Tables -->
      <div class="panel panel-default">
         <div class="panel-body">
            <div class="row">
               <div class="col-xs-12">
                  <div class="panel panel-default">
                     <div class="panel-heading">
                        <div class="card-title">
                           <div class="title">Add ELS Product</div>
                        </div>
                     </div>
                     <div class="panel-body" id="imeiexist">
                        {!! Form::open(['url' =>'save_elsproduct', 'class' => 'form-horizontal', 'role' => 'form']) !!}
							<div class="form-group">
							   <div class="col-md-3">
								  {!! Form::label('title', 'Brand', ['class' => 'control-label']) !!} 
								  {!! Form::select('brand_id',$brand_list,(isset($data) && !empty($data)) ? $data->brand_id : '',['class' => 'form-control','required', 'id'=>'brand_id','tabindex'=>$tabindex++,'placeholder'=>'-Select-']) !!}  
							   </div>
							   <div class="col-sm-3">
								  {!! Form::label('title', 'Model', ['class' => 'control-label']) !!}         
								  {!! Form::select('model_id',$model,(isset($data) && !empty($data)) ? $data->model_id : '', ['class' => 'form-control','required', 'id'=>'model_id','tabindex'=>$tabindex++,'placeholder'=>'-Select-']) !!} 
								  <span class="help-block text-danger error"></span>
							   </div>
							   <div class="col-sm-3">
								  {!! Form::label('title', 'Colour', ['class' => 'control-label']) !!}          
								  {!! Form::select('color_id',$color_list,(isset($data) && !empty($data)) ? $data->colour_id : '',['class'=>'form-control','required', 'id'=>'color_id','tabindex'=>$tabindex++,'placeholder'=>'-Select-']) !!} 
								  <span class="help-block text-danger error"></span>
							  </div>
							 <div class="col-sm-3">
									{!! Form::label('title','GRN No',['class' => 'control-label']) !!}         
									{!! Form::text('grn_no',(isset($data) && !empty($data)) ? $data->grn_no : '',['class'=>'form-control','required', 'id'=>'grn_no','placeholder'=>'GRN No','tabindex'=>$tabindex++]) !!} 
									<span class="help-block text-danger error"></span>
							 </div>
								 <?php  if(@$data->id!=''){  ?>
							 	 <div class="col-sm-3">
									{!! Form::label('title','IMEI 1',['class' => 'control-label']) !!}         
									{!! Form::text('imei_1',(isset($data) && !empty($data)) ? $data->imei_1 : '',['class'=>'form-control', 'id'=>'imei_1','placeholder'=>'IMEI 1','tabindex'=>$tabindex++]) !!} 
									<span class="help-block text-danger error"></span>
							 </div>
							 <?Php  } else {     ?>
							 <div class="col-sm-3">
									{!! Form::label('title','IMEI 1',['class' => 'control-label']) !!}         
									{!! Form::text('imei_1',(isset($data) && !empty($data)) ? $data->imei_1 : '',['class'=>'form-control', 'id'=>'imei_1','onchange'=>'imeifun();','placeholder'=>'IMEI 1','tabindex'=>$tabindex++]) !!} 
									<span class="help-block text-danger error"></span>
							 </div>
							 <?Php  }   ?>
							 <div class="col-sm-3">
									{!! Form::label('title','IMEI 2',['class' => 'control-label']) !!}         
									{!! Form::text('imei_2',(isset($data) && !empty($data)) ? $data->imei_2 : '',['class'=>'form-control', 'id'=>'imei_2','placeholder'=>'IMEI 2','tabindex'=>$tabindex++]) !!} 
									<span class="help-block text-danger error"></span>
							 </div>
							
							 <div class="col-sm-3">
									{!! Form::label('title','RAM',['class' => 'control-label']) !!}         
									{!! Form::select('ram', $ram, (isset($data) && !empty($data)) ? $data->ram : '',['class'=>'form-control', 'id'=>'ram','placeholder'=>'-Select-','tabindex'=>$tabindex++]) !!} 
									<span class="help-block text-danger error"></span>
							 </div>
							 <div class="col-sm-3">
									{!! Form::label('title','ROM',['class' => 'control-label']) !!}         
									{!! Form::select('rom', $rom, (isset($data) && !empty($data)) ? $data->rom : '',['class'=>'form-control', 'id'=>'rom','placeholder'=>'-Select-','tabindex'=>$tabindex++]) !!} 
									<span class="help-block text-danger error"></span>
							 </div>
							 <div class="col-sm-3">
									{!! Form::label('title','Incoming Grade',['class' => 'control-label']) !!}         
									{!! Form::select('grade', $grade, (isset($data) && !empty($data)) ? $data->grade : '',['class'=>'form-control','id'=>'grade','placeholder'=>'-Select-','tabindex'=>$tabindex++]) !!} 
									<span class="help-block text-danger error"></span>
							 </div>
							 <div class="col-sm-3" @if(!in_array(\App\Models\RoleModel::find(\Auth::user()->role)->role_name,['admin','finance'])) style="display:none" @endif>
									{!! Form::label('title','Cost',['class' => 'control-label']) !!}         
									{!! Form::text('mrp',(isset($data) && !empty($data)) ? $data->mrp : '',['class'=>'form-control', 'id'=>'mrp','placeholder'=>'Cost','tabindex'=>$tabindex++,'onkeypress'=>'return isNumberKey(event)']) !!} 
									<span class="help-block text-danger error"></span>
							 </div>
							  <div class="col-sm-3">
									{!! Form::label('title','Remark',['class' => 'control-label']) !!}         
									{!! Form::text('remark',(isset($data) && !empty($data)) ? $data->remark : '',['class'=>'form-control','id'=>'remark','placeholder'=>'REMARK','tabindex'=>$tabindex++]) !!} 
									<span class="help-block text-danger error"></span>
							 </div>
							 @if((isset($data) && !empty($data)))
							  <div class="col-sm-3">
									{!! Form::label('title','Date',['class' => 'control-label']) !!}         
									{!! Form::date('resived_date',(isset($data) && !empty($data)) ? $data->resived_date : '',['class'=>'form-control','id'=>'resived_date','placeholder'=>'Date','tabindex'=>$tabindex++]) !!} 
									<span class="help-block text-danger error"></span>
							 </div>
							 @endif
							  <div class="col-sm-3">
									{!! Form::label('title','Vendor',['class' => 'control-label']) !!}         
									{!! Form::select('vendor_id',$vendor,(isset($data) && !empty($data)) ? $data->vendor_id : '',['class'=>'form-control','id'=>'vendor_id','placeholder'=>'-Select-','tabindex'=>$tabindex++]) !!} 
									<span class="help-block text-danger error"></span>
							 </div>
							 {!!Form::hidden('id',(isset($data) && !empty($data)) ? $data->id : '')!!}
							 
							</div>
							<div class="form-group">
								<div class="col-sm-2">
									<button type="submit" class="btn btn-default save_btn" id="save_btn">Submit</button>
								</div>
							</div>
					    {!! Form::close() !!}
                        
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
function imeifun(){
 var imei_1=document.getElementById('imei_1').value;
   var csrfToken = "{{ csrf_token() }}";
 $.post('https://renewhub.controlz.world/els_imei', { _token:csrfToken, imei_1: imei_1}, function(data,status){
   alert(status);
     
      if(data!=''){
        //   alert(data);
        
        if(status=='success'){
        document.getElementById('imeiexist').innerHTML=data;
        } else
        {
            //  location.reload();
        }
      }
      else
      {
    
      }
      
        });

       }
</script>
<script type="text/javascript">
	isNumberKey=function(evt){
		var charCode = (evt.which) ? evt.which : event.keyCode
		if (charCode > 31 && (charCode < 48 || charCode > 57))
			return false;
		return true;
	}
   //onchange brand ................................
    $("#brand_id").change(function(){
         // var id = $("#brand_id").val();
          var id=$(this).val();
           var csrfToken = "{{ csrf_token() }}";
           var Url = "{{url('fetch-model')}}";
           $.ajax({
               url: Url,
               headers: {
                   "X-CSRF-Token": csrfToken,
                  
               },
               type: "POST",
               data: "id=" + id,
           }).done(function (response)
           {
               var obj = response.data;
                if (response.code == 200) {
               var len = 0;
               if (response["data"] != null) {
               len = response["data"].length;
               }
               var num=1;
                $("#model_id").html('<option value="">Select Model</option>'); 
                if(len > 0) 
               {  
              for(var i = 0; i < len; i++) 
               {
                   var id = response["data"][i].id;
                   var mname = response["data"][i].mname;
                   tr_str="<option value="+id+">"+mname+"</option>";
   
                   $("#model_id").append(tr_str);       
                  }  
                }
              }
           });
   });
    /*-----------------------------*/

  var max_fields    = 11; 
  var wrapper       = $(".input_fields_wrap"); //Fields wrapper
  var add_button    = $("#add_more"); //Add button ID
  var x = 1; //initlal text box count
  $(add_button).click(function(e){ //on add input button click
    e.preventDefault();
    if(x < max_fields){ //max input box allowed
      x++; //text box increment
      
      $(wrapper).append('<tr class="element" id="row_'+x+'"><td><input type="text" class="form-control" ></td><td><input type="text" class="form-control"></td><td><button class="btn btn-danger remove_field" data-id="'+x+'" value="'+x+'">X</button></td></tr>'); 
    }
  });
  
  $(wrapper).on("click",".remove_field", function(e){ //user click on remove text
    e.preventDefault(); 
    var id = $(this).val();
    $("#row_"+id).remove();
    x--;
  })

</script>
@endsection