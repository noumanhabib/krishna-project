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
                             <small>Edit Request Order</small>
                        </h1>
                        <ol class="breadcrumb">
                              <li><a href="{{url('/home')}}">Home</a></li>
                              <li><a href="{{url('request_order_list')}}">Request Order</a></li>
                              <li class="active">Edit Request Order</li>
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
                                        <div class="title">Edit Request Order</div>
                                    </div>
                                </div>
									<div class="panel-body">
									  {!! Form::open(['url' =>'save_request_order', 'class' => 'form-horizontal', 'role' => 'form']) !!}
									  
										<div class="form-group">
											<div class="col-sm-6">
											   RO No : RO-{{$parts[0]->request_order_id}} 
											</div>
										</div>
										<div class="form-group">
										@if($data)
										<div class="col-md-6" id="barcode_details_table">
											<table class="table table-responsive table-bordered">
											  <thead>
												 <tr>
													<td colspan="2" style="text-align: center;"><b>Details</b></td>
												 </tr>
											  </thead>
											  <tbody>
												 <tr>
													<td><b>Brand</b></td>
													<td id="brand">{{$data->bname}}</td>
												 </tr>
												 <tr>
													<td><b>Model</b></td>
													<td id="model">{{$data->mname}}</td>
												 </tr>
												 <tr>
													<td><b>Colour</b></td>
													<td id="color">{{$data->name}}</td>
												 </tr>
												 <tr>
													<td><b>IMEI 1</b></td>
													<td id="imei_1">{{$data->imei_1}}</td>
												 </tr>
												 <tr>
													<td><b>IMEI 2</b></td>
													<td id="imei_2">{{$data->imei_2}}</td>
												 </tr>
											  </tbody>
											</table>
											
										</div>
										@endif
									  </div>
									  {!!Form::hidden('barcode',isset($data) ? $data->barcode : '',['id'=>'barcode'])!!}
									  <div class="form-group">
											<div class="col-sm-12">
												 <i class="fa fa-plus-circle fa-3x" aria-hidden="true" id="add_more" style="margin-top:10%;float: right;cursor:pointer"></i>
											</div>
									  </div>
									  @if(!$parts->isEmpty())
									 
										@foreach($parts as $key => $d)				                   
										<div class="form-group">
										  <div class="col-sm-3" @if(isset($data) && !empty($data)) style="display:none" @endif>
										  {!! Form::label('title','Brand', ['class' => 'control-label']) !!}          
										  {!! Form::select('brand_id[]',$brand_list,(isset($d) && !empty($d)) ? $d->brand_id : '',['class' => 'form-control', 'required', 'id'=>'brand_id_'.$key,'tabindex'=>$tabindex++,'placeholder'=>'-Select-','onchange'=>'getModelList('.$key.')']) !!}  
										  <span class="help-block text-danger error"></span>
										  </div>
										  @php $model = $colour = [];
										  if((isset($d) && !empty($d))){
											  $model = Helper::getModel($d->brand_id); 
											  $colour = Helper::getPartColourListt($d->model_id,$d->part_type_id,$d->part_id); 
										  }
										  @endphp
										  <div class="col-sm-3" @if(isset($data) && !empty($data)) style="display:none" @endif>
										  {!! Form::label('title', 'Model', ['class' => 'control-label']) !!}         
										  {!! Form::select('model_id[]',$model,(isset($d) && !empty($d)) ? $d->model_id : '', ['class' => 'form-control','required', 'id'=>'model_id_'.$key,'tabindex'=>$tabindex++,'placeholder'=>'-Select-']) !!} 
										  <span class="help-block text-danger error"></span>
										  </div>
										  <div class="col-sm-3" @if(isset($data) && !empty($data)) style="display:none" @endif>
										  {!! Form::label('title', 'Product Type', ['class' => 'control-label']) !!}          
										  {!! Form::select('product_type_id[]',$type_list,(isset($d) && !empty($d)) ? $d->part_type_id : '',['class'=>'form-control','required', 'id'=>'product_type_id_'.$key,'tabindex'=>$tabindex++,'placeholder'=>'-Select-']) !!}  
										  <span class="help-block text-danger error"></span>
										  </div>
										  <div class="col-sm-3">
										  {!! Form::label('title', 'Part Name', ['class' => 'control-label']) !!}         
										  {!! Form::select('part_id[]',$part_name_list,(isset($d) && !empty($d)) ? $d->part_id : '',['class'=>'form-control','required', 'id'=>'part_id_'.$key,'tabindex'=>$tabindex++,'placeholder'=>'-Select-','onchange'=>'getColourList('.$key.')']) !!}  
										  <span class="help-block text-danger error"></span>
										  </div>
										   <div class="col-sm-3">
										  {!! Form::label('title', 'Part Color', ['class' => 'control-,label']) !!}          
										  {!! Form::select('part_color[]',$colour,$d->colour_id,['class'=>'form-control','required', 'id'=>'colour_id_'.$key,'tabindex'=>$tabindex++,'placeholder'=>'-Select-']) !!} 
										  <span class="help-block text-danger error"></span>
										  </div>
											<div class="col-sm-3" @if(isset($data) && !empty($data)) style="display:none" @endif>
												{!! Form::label('title', 'Quantity', ['class' => 'control-label']) !!}         
											   {!! Form::text('quantity[]',$d->quantity, ['class'=>'form-control','required', 'id'=>'quantity','placeholder'=>'Enter Quantity','tabindex'=>$tabindex++]) !!} 
												<span class="help-block text-danger error"></span>
											</div>	
											{!!Form::hidden('order_part_id[]',(isset($d) && !empty($d)) ? $d->id : '')!!}  
											<div class="col-sm-3">									
												 <i class="fa fa-minus-circle fa-3x" aria-hidden="true" id="add_more" style="margin-top:10%;float: right;cursor:pointer" onclick="deleteOne(this)"></i>
											</div>
											{!!Form::hidden('request_order_id',(isset($d) && !empty($d)) ? $d->request_order_id : '')!!}
										   </div>
											  @endforeach
											@endif
										<div class="after-add-more"></div>
										<div class="form-group">
											<div class="col-sm-10">
												<button type="submit" class="btn btn-default save_btn" id="save_btn">Submit</button>
											</div>
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
{!!Form::hidden('add_more',count($parts)+1,['id'=>'add_more_index'])!!}
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script type="text/javascript">
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
			 
			 //   alert(a);
			    
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
   
   
	deleteOne=function(e){
		$(e).parent().parent().remove();
	}
</script>
@endsection