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
      <small>Assign Engineer</small>
   </h1>
   <ol class="breadcrumb">
      <li><a href="{{url('/home')}}">Home</a></li>
      <li><a href="{{url('allocated_els_product')}}">Allocate Engineer Product</a></li>
      <li class="active">Assign Engineer</li>
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
                              <div class="title">Edit Assign Engineer</div>
                           </div>
                        </div>
                        <div class="panel-body">
                           {!! Form::open(['url' =>'assign_engineer', 'class' => 'form-horizontal', 'role' => 'form']) !!}
							
							<div class="form-group">
                              <div class="row">
                                 <div class="col-md-12">
                                    Barcode : {{$data->barcode}}
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
$(document).ready(function(){
	getBarcodeDetailsList=function()
	{
		var barcode={{$data->barcode}};
		if(barcode){
			$.ajax({
				type: "POST",
				url: "{{url(route('get-barcode-product-details'))}}",
				data:{barcode:barcode},
				dataType:'JSON',
				success: function(d){
					if(d.status){	
						$('#barcode_details_table').html(d.details_html);
						$('#parts-list').html(d.product_parts_html);
					}else{
						alert(d.message)
					}
				}
			});
		}
	}
	getBarcodeDetailsList();
});
</script>
@endsection