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
      <small>Allocated Quality Check</small>
   </h1>
   <ol class="breadcrumb">
      <li><a href="{{url('/home')}}">Home</a></li>
      <li><a href="{{url('quality-check-product-list')}}">Quality Check Product List</a></li>
      <li class="active">Allocated Quality Check</li>
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
                              <div class="title">Allocated Quality Check</div>
                           </div>
                        </div>
                        <div class="panel-body">
                           {!! Form::open(['url' =>'allocated_product_qulaity_checking', 'class' => 'form-horizontal', 'role' => 'form','id' => 'barcode-allocation-form','onsubmit'=>'return false']) !!}
							
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
			url: "{{url(route('get-product-details'))}}",
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

</script>
@endsection