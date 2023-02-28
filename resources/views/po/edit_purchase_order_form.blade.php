@extends('layouts.layout')
@section('title',$title)
@section('content')
@php $tabindex = 1; @endphp
<div id="page-wrapper" >
         <div class="header"> 
                        <h1 class="page-header">
                             <small>Edit Purchase Order</small>
                        </h1>
                        <ol class="breadcrumb">
                              <li><a href="{{url('/home')}}">Home</a></li>
                              <li><a href="{{url('order-purchase-list')}}">Purchase Order List</a></li>
                              <li class="active">Edit Purchase Order</li>
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
                                        <div class="title">Edit Purchase Order</div>
                                    </div>
                                </div>
                                <div class="panel-body">
									{!! Form::open(['url' =>route('update-purchase-order'), 'class' => 'form-horizontal', 'role' => 'form', 'id' => 'save-purchase-order']) !!}
									
										<div class="form-group">
											<div class="col-sm-3">
											PO No : PO-{{$id}}
											</div>
										</div>
                                       
										<div id="request-list" style="overflow-x:auto;">
										@if(!$data->isEmpty()) 
										<table class="table table-bordered">
											<thead>
												<tr>
												  <th scope="col">Brand</th>
												  <th scope="col">Model</th>
												  <th scope="col">Product Type</th>
												  <th scope="col">Part Name</th>
												  <th scope="col">Part Color</th>
												  <th scope="col">HSN Code</th>
												  <th scope="col">Price</th>
												  <th scope="col">GST</th>
												  <th scope="col">Quantity</th>
												  <th scope="col">Amount</th>
												  <th scope="col">GST Amount</th>
												  <th scope="col">Total Amount</th>
												  <th  scope="col">Action</th>
												</tr>
											</thead>
											<tbody>
										@foreach($data as $key => $d)	
											<tr>
												<td scope="row">
												{!! Form::hidden('purchase_order_parts_id[]', $d->id) !!}	
												{!! Form::hidden('purchase_order_id[]', $d->purchase_order_id) !!}	
												{!! Form::hidden('request_order_id[]', $d->request_order_id) !!}
												
												<?php if($d->status==1){     ?>
													{!! Form::select('brand_id[]', $brand, $d->brand_id, ['class' => 'form-control required', 'id'=>'brand_id_'.$key,'tabindex'=>$tabindex++,'placeholder'=>'-Select-','onchange'=>'brand()']) !!}
												<?php  }   else {  ?>
												{!! Form::select('brand_id[]', $brand, $d->brand_id, ['class' => 'form-control required  pointer-events-none', 'id'=>'brand_id_'.$key,'tabindex'=>$tabindex++,'placeholder'=>'-Select-','onchange'=>'brand()','readonly'=>'readonly']) !!}	
												<?php  }  ?>
												<span class="help-block text-danger error"></span>
												</td>
												@php $model = Helper::getModel($d->brand_id); @endphp
												<td>
													<?php if($d->status==1){     ?>    
												{!! Form::select('model_id[]', $model, $d->model_id, ['class' => 'form-control required', 'id'=>'model_id_'.$key,'tabindex'=>$tabindex++,'placeholder'=>'-Select-']) !!}	
													<?php  }   else {  ?>
													{!! Form::select('model_id[]', $model, $d->model_id, ['class' => 'form-control required  pointer-events-none', 'id'=>'model_id_'.$key,'tabindex'=>$tabindex++,'placeholder'=>'-Select-','readonly'=>'readonly']) !!}	
												
												<?php  }  ?>
												<span class="help-block text-danger error"></span>
												</td>
												<td>
												{!! Form::select('product_type_id[]', $product_type, $d->product_type_id, ['class' => 'form-control required pointer-events-none', 'id'=>'product_type_id_'.$key,'tabindex'=>$tabindex++,'placeholder'=>'-Select-','readonly'=>'readonly']) !!}	
												<span class="help-block text-danger error"></span>
												</td>
												<td>
												<?php if($d->status==1){     ?>     
												{!! Form::select('part_id[]', $parts, $d->part_id, ['class' => 'form-control required', 'id'=>'part_id_'.$key,'tabindex'=>$tabindex++,'placeholder'=>'-Select-']) !!}	
													<?php  }   else {  ?>
													{!! Form::select('part_id[]', $parts, $d->part_id, ['class' => 'form-control required  pointer-events-none', 'id'=>'part_id_'.$key,'tabindex'=>$tabindex++,'placeholder'=>'-Select-','readonly'=>'readonly']) !!}	
													
												<?php  }  ?>
												<span class="help-block text-danger error"></span>
												</td>
												@php $colour = Helper::getPartColourList($d->model_id,$d->product_type_id,$d->part_id); @endphp
												<td>
	                                            	<?php if($d->status==1){     ?>											    
												{!! Form::select('colour_id[]', $colour, $d->colour_id, ['class' => 'form-control required', 'id'=>'colour_id_'.$key,'tabindex'=>$tabindex++,'placeholder'=>'-Select-','onchange'=>'getPrice('.$key.')','required'=>'required']) !!}	
													<?php  }   else {  ?>
														{!! Form::select('colour_id[]', $colour, $d->colour_id, ['class' => 'form-control required pointer-events-none', 'id'=>'colour_id_'.$key,'tabindex'=>$tabindex++,'placeholder'=>'-Select-','onchange'=>'getPrice('.$key.')','required'=>'required','readonly'=>'readonly']) !!}	
											
												<?php  }  ?>
												<span class="help-block text-danger error"></span>
												</td>
												<td>
												{!! Form::text('hsn_code[]', $d->hsn_code, ['class' => 'form-control required', 'id'=>'hsn_code_'.$key,'tabindex'=>$tabindex++,'placeholder'=>'HSN Code','required'=>'required']) !!}	
												<span class="help-block text-danger error"></span>
												</td>
												<td>
												<?php if($d->status==1){     ?>	
												
													
												{!! Form::text('price[]', $d->price, ['class' => 'form-control required', 'id'=>'price_'.$key,'tabindex'=>$tabindex++,'placeholder'=>'Price','required'=>'required']) !!}	
												
													<?php  }   else {  ?>
												{!! Form::text('price[]', $d->price, ['class' => 'form-control required  pointer-events-none', 'id'=>'price_'.$key,'tabindex'=>$tabindex++,'placeholder'=>'Price','required'=>'required','readonly'=>'readonly']) !!}	
												
												<?php  }  ?>
												
												
												<span class="help-block text-danger error"></span>
												</td>
												<td>
												{!! Form::select('gst[]', ['18'=>'18% GST','12'=>'12% GST','5'=>'5% GST','0'=>'0% GST',],$d->gst, ['class' => 'form-control required', 'id'=>'gst_'.$key,'tabindex'=>$tabindex++,'onchange'=>'getAmountDetails('.$key.')']) !!}	
												<span class="help-block text-danger error"></span>
												</td>
												<td>
													<?php if($d->status==1){     ?>	    
												{!! Form::text('quantity[]', $d->quantity, ['class' => 'form-control required', 'id'=>'quantity_'.$key,'tabindex'=>$tabindex++,'placeholder'=>'Quantity','required'=>'required','onchange'=>'getAmountDetails('.$key.')']) !!}	
											
											
												<?php  }   else {  ?>
													{!! Form::text('quantity[]', $d->quantity, ['class' => 'form-control required  pointer-events-none', 'id'=>'quantity_'.$key,'tabindex'=>$tabindex++,'placeholder'=>'Quantity','required'=>'required','onchange'=>'getAmountDetails('.$key.')','readonly'=>'readonly']) !!}	
											
												<?php  }  ?>
											
												<span class="help-block text-danger error"></span>
												</td>												
												@php 		
												$gst = $d->gst;
												$amount = $d->price * $d->quantity;
												$gst_amount = $amount*($gst/100);
												$total_amount = $amount + $gst_amount;
												@endphp
												<td>{!! Form::text('amount[]', $amount, ['class' => 'form-control required pointer-events-none', 'id'=>'amount_'.$key,'tabindex'=>$tabindex++,'placeholder'=>'Amount','readonly'=>'readonly']) !!}	</td>
												<td>{!! Form::text('gst_amount[]', $gst_amount, ['class' => 'form-control required pointer-events-none', 'id'=>'gst_amount_'.$key,'tabindex'=>$tabindex++,'placeholder'=>'GST Amount','readonly'=>'readonly']) !!}	</td>
												<td>{!! Form::text('total_amount[]', $total_amount, ['class' => 'form-control required pointer-events-none', 'id'=>'total_amount_'.$key,'tabindex'=>$tabindex++,'placeholder'=>'Total Amount','readonly'=>'readonly']) !!}	</td>
										<td> <?php if($d->status==1){     ?>
										    <a href="#"  data-toggle="modal" data-target="#DeleteCategoryModal" onclick="delete_menucategory(<?=$d->id?>)"><i class="fa fa-trash text-danger mr-2"></i>Delete</a>
										      <?php  } ?> </td>
											</tr>
											
											
											<script>
											
//onchange brand ................................
    function brand(){
         var id = $("#brand_id_<?=$key?>").val();
        //   var id=$(this).val();
        alert(id);
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
                $("#model_id_<?=$key?>").html('<option value="">Select Model</option>'); 
                if(len > 0) 
               {  
              for(var i = 0; i < len; i++) 
               {
                   var id = response["data"][i].id;
                   var mname = response["data"][i].mname;
                   tr_str="<option value="+id+">"+mname+"</option>";
   
                   $("#model_id_<?=$key?>").append(tr_str);       
                  }  
                }
              }
           });
    }
   
											</script>
											
											
											
											
											
											
											
										@endforeach
											</tbody>
										</table>
										@endif
										<div class="form-group">
											<div class="col-sm-offset-2 col-sm-10">
												<button type="submit" class="btn btn-default save_btn">Submit</button>
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
        
        
<!-- Delete Category Model -->
<div class="modal fade" id="DeleteCategoryModal" tabindex="-1" role="dialog" aria-labelledby="DeleteMenuCategory">
    <div class="modal-dialog modal-dialog-centered modal-min" role="document">
        <div class="modal-content">
            <div class="modal-body text-center">
                <i class="fa fa-ban" style="font-size: 130px; color: #ff8800;"></i>
                <h3>Are you sure want to delete it?</h3>
                <form method="post" style="padding: 24px;">
                    @csrf
                    <input type="hidden" name="delete_category_id" id="delete_category_id" value="">
                    <button type="button" class="btn btn-success light" id="delete_btn" data-id="">Yes, delete it! <span class="btn-icon-right"><i class="fa fa-check"></i></span></button>
                    <button type="button" class="btn btn-danger light" data-dismiss="modal">Cancel <span class="btn-icon-right"><i class="fa fa-close"></i></span></button>
                </form>
            </div>
        </div>
    </div>
</div>        
        
        

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script type="text/javascript">

getPrice=function(key){
	var model_id = $('#model_id_'+key).val();
	var type_id = $('#product_type_id_'+key).val();
	var part_id = $('#part_id_'+key).val();
	var colour_id = $('#colour_id_'+key).val();
	if(model_id && type_id && part_id && colour_id){
		$.ajax({
			type: "POST",
			url: "{{url(route('get-series-unit-price'))}}",
			data:{model_id:model_id,type_id:type_id,part_id:part_id,colour_id:colour_id},
			dataType:'JSON',
			success: function(d)
			{
				if(d.status){	
					$('#price_'+key).val(d.data.price);
					$("#price_"+key).trigger('change');
				}
			}
		});
	}
}

getAmountDetails=function(key){
	var unit_price = $('#price_'+key).val();
	var gst = $('#gst_'+key).val();
	var quantity = $('#quantity_'+key).val();
	if(unit_price && gst && quantity){
		var amount = unit_price * quantity;
		var gst_amount = amount*(gst/100);
		var total_amount = amount + gst_amount;
		$('#amount_'+key).val(amount);
		$('#gst_amount_'+key).val(gst_amount);
		$('#total_amount_'+key).val(total_amount);
	}	 
}

/*..............Delete Category.........................................*/
 function delete_menucategory(id)
    {
        $("#delete_category_id").val(id);
    }
$("#delete_btn").on("click", function () {
        toastr.options = {
            progressBar: "true",
            positionClass: "toast-top-right",
        };  
        var id = $("#delete_category_id").val();
        var csrfToken = "{{ csrf_token() }}";
        var Url = "{{url('delete-purchase-order-part')}}";
        $.ajax({
            url: Url,
            headers: {
                "X-CSRF-Token": csrfToken,
               
            },
            type: "POST",
            data: "id=" + id,
        }).done(function (response) {       
            if (response.code == 200) {
                 $("#delete_btn").prop("disabled",true);
                toastr.success(response.message);
                setTimeout(function () {
                    window.location.reload();
                }, 2000);
            }
        });
    });




</script>
@endsection