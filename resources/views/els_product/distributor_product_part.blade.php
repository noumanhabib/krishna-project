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
      <small>Distributor Product Parts</small>
   </h1>
   <ol class="breadcrumb">
      <li><a href="{{url('/home')}}">Home</a></li>
      <li><a href="{{url('distributor-parts-product-list')}}">Distributor Product Parts</a></li>
      <li class="active">Distributor Product Parts</li>
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
                              <div class="title">Distributor Product Parts</div>
                           </div>
                        </div>
                        <div class="panel-body">
                           {!! Form::open(['url' =>'distributor_product_parts', 'class' => 'form-horizontal', 'role' => 'form','id' => 'barcode-allocation-form','onsubmit'=>'return false']) !!}
							
							<div class="form-group">
                              <div class="row">
                                 <div class="col-md-12">
                                    <div class="col-sm-6">
                                       {!! Form::label('title','UIN', ['class' => 'control-label']) !!} 
                                       {!! Form::text('barcode','', ['class'=>'form-control','id'=>'barcode','placeholder'=>'Enter Barcode','tabindex'=>$tabindex++,'onkeypress'=>'enterKeyPress(event)']) !!}  
                                    </div>									
									<div class="col-sm-2"><br>
									{{ Form::button('Search', ['onclick'=>'getBarcodeDetailsList()','class'=>'btn btn-info']) }}
									</div>
                                 </div>
                              </div>
							</div>
							<div class="form-group">							
								<div class="col-md-6" id="barcode_details_table"></div>
							</div>
							<div id="parts-list"></div>
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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script type="text/javascript">   
getBarcodeDetailsList=function()
{
	var barcode=$('#barcode').val();
	if(barcode){
		$.ajax({
			type: "POST",
			url: "{{url(route('distributor-product-parts-details'))}}",
			data:{barcode:barcode},
			dataType:'JSON',
			success: function(d){
				if(d.status){	
					$('#barcode_details_table').html(d.details_html);
					$('#parts-list').html(d.product_parts_html);
					$('#barcode-allocation-form').attr('onsubmit','return true');
				}else{
					alert(d.message)
				}
			}
		});
	}
}
enterKeyPress=function(e){
	var barcode=$('#barcode').val();
	if(barcode){
		if(e.keyCode === 13){
			getBarcodeDetailsList();
		}
	}
}

buttonDisabled=function(){
	$('#save_btn').prop("disabled", true);
}

checkBarcode=function(id){
	var barcode = $('#part_barcode_'+id).val();
	var model_id = $('#model_id_'+id).val();
	var part_id = $('#part_id_'+id).val();
	var colour_id = $('#part_color_id_'+id).val();
	if(barcode && part_id){
		$.ajax({
			type: "POST",
			url: "{{url(route('distributor-barcode-details'))}}",
			data:{barcode:barcode,model_id:model_id,part_id:part_id,colour_id:colour_id},
			dataType:'JSON',
			success: function(d){				
				if(!d.status){	
					$('#save_btn').prop("disabled", true);
					$('#part_barcode_'+id).val('');
					alert(d.message)
				}
				$('#save_btn').prop("disabled", false);
			}
		});
	}
}

deleteRemove=function(e){
	var barcode=$(e).parent().parent().find('.barcode').val();
	var request_part_id=$(e).parent().parent().find('#order_request_part_id').val();
	if(confirm("Are you sure you want to delete?")){
		$(e).parent().parent().find('.barcode').val('');
		$(e).parent().parent().remove();
		if(request_part_id){			
			$.ajax({
				type: "POST",
				url: "{{url(route('remove-distributor-barcode'))}}",
				data:{request_part_id:request_part_id,barcode:barcode},
				dataType:'JSON',
				success: function(d){
					if(d.status){	
						$(e).parent().parent().find('.action').html('');					
					}else{
						alert(d.message)
					}
				}
			});
		}
	}
}

addMore=function(id){
	var barcode=$('#barcode').val();
	if(barcode){
		$.ajax({
			type: "POST",
			url: "{{url(route('add-more-distributor'))}}",
			data:{barcode:barcode,id:id},
			dataType:'JSON',
			success: function(d){				
				if(d.status){	
					$('#add-more').before(d.html);					
					$('#add_more_parts').attr('onclick','addMore('+(id+1)+')');
				}
			}
		});
	}
}

getPartColour=function(id){
	var model_id = $('#model_id_'+id).val();
	var part_id = $('#part_id_'+id).val();
	if(model_id && part_id){
		$.ajax({
			type: "POST",
			url: "{{url(route('get-distributor-colours'))}}",
			data:{model_id:model_id,part_id:part_id},
			dataType:'JSON',
			success: function(d){				
				var _option = '<option value="">-Select-</option>';
				if(d.status){
					$.each(d.data, function (key, val) {
						_option += '<option value="'+val.id+'">'+val.name+'</option>';						
					});
				}
				$('#part_color_id_'+id).html(_option);
			}
		});
	}
}
</script>
@endsection