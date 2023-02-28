@extends('layouts.layout')
@section('title',$title)
@section('content')
@php $tabindex = 1; @endphp
<style type="text/css">
   .custom_width_color
   {
   width: 205px;
   margin-left: 53%;
   }
   .custom_width_price
   {
   width: 206px;
   }
   .custom-label
   {
   text-align: left!important;
   }
</style>
<div id="page-wrapper" >
<div class="header">
   <h1 class="page-header">
      <small>Add Request Order</small>
   </h1>
   <ol class="breadcrumb">
      <li><a href="{{url('/home')}}">Home</a></li>
      <li><a href="{{url('request_order_list')}}">Request Order</a></li>
      <li class="active">Add Request Order</li>
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
                              <div class="title">Add Request Order</div>
                           </div>
                        </div>
                        <div class="panel-body">
                           {!! Form::open(['url' =>'save_request_order', 'class' => 'form-horizontal', 'role' => 'form']) !!}
                           <div class="form-group">
                              <div class="row">
                                 <div class="col-md-12">
                                    <div class="col-sm-6">
                                       {!! Form::label('title','UIN', ['class' => 'control-label']) !!} 
                                       {!! Form::text('barcode','', ['class'=>'form-control','id'=>'barcode','placeholder'=>'Enter UIN','tabindex'=>$tabindex++,'onblur'=>'getBarcodeDetailsList(this)']) !!} 
                                       {!!Form::hidden('barcode_id','',['id'=>'barcode_id'])!!}
                                    </div>
                                    <div class="col-md-6" id="barcode_details_table"></div>
                                 </div>
                              </div>
                           </div>
                           <div class="form-group">
                              <div class="col-sm-12">
                                 <i class="fa fa-plus-circle fa-3x" aria-hidden="true" id="add_more" style="float: right;cursor:pointer"></i>
                              </div>
                           </div>
							<div id="add-part-section">
							   <div class="form-group">
								  <div class="col-sm-3">
									 {!! Form::label('title','Brand', ['class' => 'control-label']) !!}         
									 {!! Form::select('brand_id[]',$brand_list,(isset($data) && !empty($data)) ? $data->brand_id : '',['class' => 'form-control', 'required', 'id'=>'brand_id_0','tabindex'=>$tabindex++,'placeholder'=>'-Select-','onchange'=>'getModelList(0)','model-id'=>'model_id']) !!}  
									 <span class="help-block text-danger error"></span>
								  </div>
								  <div class="col-sm-3">
									 {!! Form::label('title', 'Model', ['class' => 'control-label']) !!}          
									 {!! Form::select('model_id[]',$model,(isset($data) && !empty($data)) ? $data->model_id : '', ['class' => 'form-control','required', 'id'=>'model_id_0','tabindex'=>$tabindex++,'placeholder'=>'-Select-']) !!} 
									 <span class="help-block text-danger error"></span>
								  </div>
								  <div class="col-sm-3">
									 {!! Form::label('title', 'Product Type', ['class' => 'control-label']) !!}         
									 {!! Form::select('product_type_id[]',$type_list,(isset($data) && !empty($data)) ? $data->type_id : '',['class'=>'form-control','required', 'id'=>'product_type_id_0','tabindex'=>$tabindex++,'placeholder'=>'-Select-']) !!} 
									 <span class="help-block text-danger error"></span>
								  </div>
								  <div class="col-sm-3">
									 {!! Form::label('title', 'Part Name', ['class' => 'control-label']) !!}          
									 {!! Form::select('part_id[]',$part_name_list,(isset($data) && !empty($data)) ? $data->part_id : '',['class'=>'form-control','required', 'id'=>'part_id_0','tabindex'=>$tabindex++,'placeholder'=>'-Select-','onchange'=>'getColourList(0)','series-id'=>'series_num']) !!}  
									 <span class="help-block text-danger error"></span>
								  </div>
								  <div class="col-sm-3">
									 {!! Form::label('title', 'Part Colour', ['class' => 'control-,label']) !!}          
									 {!! Form::select('part_color[]',[],'',['class'=>'form-control','required', 'id'=>'colour_id_0','tabindex'=>$tabindex++,'placeholder'=>'-Select-']) !!} 
									 <span class="help-block text-danger error"></span>
								  </div>
								  <div class="col-sm-3">
									 {!! Form::label('title', 'Quantity', ['class' => 'control-label']) !!}         
									 {!! Form::text('quantity[]','', ['class'=>'form-control','required', 'id'=>'quantity','placeholder'=>'Enter Quantity','tabindex'=>$tabindex++]) !!} 
									 <span class="help-block text-danger error"></span>
								  </div>
								  {!!Form::hidden('id',(isset($data) && !empty($data)) ? $data->id : '')!!}
								</div>
								<div class="after-add-more"></div>
						   </div>
                           <div class="form-group">
                              <div class="col-sm-10">
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
</div>
{!!Form::hidden('add_more',1,['id'=>'add_more_index'])!!}

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script type="text/javascript">
$("#barcode_details_table").hide();
$("#add_more").click(function(){
	var index = $('#add_more_index').val();
	var barcode = $('#barcode').val();
     var a=+index + +1;
     	$('#add_more_index').val(a);
	if(index){
		$.ajax({
			type: "POST",
			url: "{{url('get_html_multiple_request_order')}}",
			data:{index:index,barcode:barcode},
			dataType:'JSON',
			success: function(d)
			{
			        
			  
				$(".after-add-more").before(d.html);
			
			}
		});
	}
});

//onchange brand ................................
getModelList=function(index){
	var id=$("#brand_id_"+index).val();
	if(id){
		$.ajax({
			type: "POST",
			url: "{{url('fetch-model')}}",
			data:{id:id},
			dataType:'JSON',
			success: function(d)
			{
				var option = '<option value="">-Select-</option>';
				$.each(d.data, function(i,v) {
					option += '<option value="'+v.id+'">'+v.mname+'</option>';
				});
				$('#model_id_'+index).html(option);
			}
		});
	}		
}
   
   
//on change part Name Get Series List............................
getColourList=function(index){
	var model_id=$("#model_id_"+index).val();
	var type_id=$("#product_type_id_"+index).val();
	var part_id=$("#part_id_"+index).val();

	if(model_id && type_id && part_id){
		$.ajax({
			type: "POST",
			url: "{{url('fetch_colour')}}",
			data:{model_id:model_id,type_id:type_id,part_id:part_id},
			dataType:'JSON',
			success: function(d)
			{
				if(true){	
					var option = '<option value="">-Select-</option>';
					$.each(d.data, function(i,v) {
						option += '<option value="'+v.id+'">'+v.name+'</option>';
					});
					$('#colour_id_'+index).html(option);
				}
			}
		});
	}		
}
   
getBarcodeDetailsList=function(e){
	var barcode=$(e).val();
	if(barcode){
		$.ajax({
			type: "POST",
			url: "{{url('fetch_barcode_deatils')}}",
			data:{barcode:barcode},
			dataType:'JSON',
			success: function(d)
			{
				if(d.status){	
					$("#barcode_details_table").show();
					$('#barcode_details_table').html(d.product_details);
					$('#add-part-section').html(d.product_part_details);
				}
			}
		});
	}	
}
   
   
deleteOne=function(e){
	$(e).parent().parent().remove();
}

</script>
@endsection