@extends('layouts.layout')
@section('title',$title)
@section('content')
@php $tabindex = 1; @endphp
<div id="page-wrapper" >
         <div class="header"> 
			<h1 class="page-header">
				 <small>Add Purchase</small>
			</h1>
			<ol class="breadcrumb">
				  <li><a href="{{url('/home')}}">Home</a></li>
				  <li><a href="{{url('order-purchase-list')}}">Purchase Order List</a></li>
				  <li class="active">Add Purchase Order</li>
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
                                        <div class="title">Add Purchase Order</div>
                                    </div>
                                </div>
                                <div class="panel-body">
									{!! Form::open(['url' =>route('save-purchase-order'), 'class' => 'form-horizontal', 'role' => 'form', 'id' => 'save-purchase-order']) !!}
										<!--
										<div class="form-group">
											<div class="col-sm-2">
											{!! Form::label('name', 'GRN No', ['class' => 'control-label']) !!}
											</div>
											<div class="col-sm-6">
											{!! Form::text('grn_no', '', ['class' => 'form-control required', 'id'=>'grn_no', 'placeholder'=>'GRN No','tabindex'=>$tabindex++,'onkeypress'=>'enterKeyPress(event)']) !!}
											<span class="help-block text-danger error"></span>
											</div>
											<div class="col-sm-2">
											{{ Form::submit('Submit', ['onclick'=>'getRequestList()','class'=>'btn btn-info']) }}
											</div>
										</div>-->
                                       
										<div id="request-list" style="overflow-x:auto;">
											@php $tabindex = 1; @endphp
											@if(!$data->isEmpty()) 
											<table class="table table-bordered">
												<thead>
													<tr>
													  <th scope="col">Brand</th>
													  <th scope="col">Model</th>
													  <th scope="col" width="20px">Product Type</th>
													  <th scope="col" width="150px">Part Name</th>
													  <th scope="col" width="150px">Part Color</th>
													  <th scope="col" width="100px">HSN Code</th>
													  <th scope="col" width="100px">Unit Price</th>
													  <th scope="col" width="50px">GST</th>
													  <th scope="col">Request Quantity</th>
													  <th scope="col">Order Quantity</th>
													  <th scope="col">Amount</th>
													  <th scope="col">GST Amount</th>
													  <th scope="col">Total Amount</th>
													  <th scope="col">Vendor</th>
													  <th scope="col">Action</th>
													</tr>
												</thead>
												<tbody>
											@foreach($data as $key => $d)	
												<tr>
													<td scope="row">
													{!! Form::hidden('request_order_parts_id[]', $d->id) !!}	
													{!! Form::hidden('request_order_id[]', $d->request_order_id) !!}	
													{!! Form::select('brand_id[]', $brand, $d->brand_id, ['class' => 'form-control required pointer-events-none', 'id'=>'brand_id_'.$key,'tabindex'=>$tabindex++,'placeholder'=>'-Select-','readonly'=>'readonly']) !!}	
													<span class="help-block text-danger error"></span>
													</td>
													<td>
													@php $model = Helper::getModel($d->brand_id); @endphp
													{!! Form::select('model_id[]', $model, $d->model_id, ['class' => 'form-control required pointer-events-none', 'id'=>'model_id_'.$key,'tabindex'=>$tabindex++,'placeholder'=>'-Select-','readonly'=>'readonly']) !!}	
													<span class="help-block text-danger error"></span>
													</td>
													<td>
													{!! Form::select('product_type_id[]', $product_type, $d->part_type_id, ['class' => 'form-control required pointer-events-none', 'id'=>'product_type_id_'.$key,'tabindex'=>$tabindex++,'placeholder'=>'-Select-','readonly'=>'readonly']) !!}	
													<span class="help-block text-danger error"></span>
													</td>
													<td>
													{!! Form::select('part_id[]', $parts, $d->part_id, ['class' => 'form-control required pointer-events-none', 'id'=>'part_id_'.$key,'tabindex'=>$tabindex++,'placeholder'=>'-Select-','readonly'=>'readonly']) !!}	
													<span class="help-block text-danger error"></span>
													</td>
													@php $colour = Helper::getPartColourList($d->model_id,$d->part_type_id,$d->part_id); @endphp
													<td>
													{!! Form::select('colour_id[]', $colour, $d->colour_id, ['class' => 'form-control required', 'id'=>'colour_id_'.$key,'tabindex'=>$tabindex++,'placeholder'=>'-Select-','onchange'=>'getPrice('.$key.')','required'=>'required']) !!}	
													<span class="help-block text-danger error"></span>
													</td>
													@php  		
													$gst = 18;
													$amount = 0;
													$gst_amount = $amount*($gst/100);
													$total_amount = $amount + $gst_amount;
													@endphp
													<td>
													{!! Form::text('hsn_code[]', 8517, ['class' => 'form-control required', 'id'=>'hsn_code_'.$key,'tabindex'=>$tabindex++,'placeholder'=>'HSN Code','required']) !!}	
													<span class="help-block text-danger error"></span>
													</td>
													<td>
													{!! Form::text('price[]', 0, ['class' => 'form-control required', 'id'=>'price_'.$key,'tabindex'=>$tabindex++,'placeholder'=>'Price','onchange'=>'getAmountDetails('.$key.')','required']) !!}	
													<span class="help-block text-danger error"></span>
													</td>
													<td>
													{!! Form::select('gst[]', ['18'=>'18% GST','12'=>'12% GST','5'=>'5% GST','0'=>'0% GST'],$gst, ['class' => 'form-control', 'id'=>'gst_'.$key,'tabindex'=>$tabindex++,'onchange'=>'getAmountDetails('.$key.')','required']) !!}	
													<span class="help-block text-danger error"></span>
													</td>
													<td>
													{!! Form::text('request_quantity[]', $d->order_qunatity, ['class' => 'form-control required pointer-events-none', 'id'=>'request_quntity','tabindex'=>$tabindex++,'placeholder'=>'Request Quantity','readonly'=>'readonly']) !!}	
													<span class="help-block text-danger error"></span>
													</td>
													<td>
													{!! Form::text('quantity[]', $d->order_qunatity, ['class' => 'form-control required','required'=>'required', 'id'=>'quantity_'.$key,'tabindex'=>$tabindex++,'placeholder'=>'Quantity','onchange'=>'getAmountDetails('.$key.')']) !!}	
													<span class="help-block text-danger error"></span>
													</td>
													<td>{!! Form::text('amount[]', $amount, ['class' => 'form-control required pointer-events-none', 'id'=>'amount_'.$key,'tabindex'=>$tabindex++,'placeholder'=>'Amount','readonly'=>'readonly']) !!}	</td>
													<td>{!! Form::text('gst_amount[]', $gst_amount, ['class' => 'form-control required pointer-events-none', 'id'=>'gst_amount_'.$key,'tabindex'=>$tabindex++,'placeholder'=>'GST Amount','readonly'=>'readonly']) !!}	</td>
													<td>{!! Form::text('total_amount[]', $total_amount, ['class' => 'form-control required pointer-events-none', 'id'=>'total_amount_'.$key,'tabindex'=>$tabindex++,'placeholder'=>'Total Amount','readonly'=>'readonly']) !!}	</td>
													<td>
													{!! Form::select('vendor_id[]', $vendor, '', ['class' => 'form-control required', 'id'=>'role_id','tabindex'=>$tabindex++,'placeholder'=>'-Select-','required'=>'required','required'=>'required']) !!}	
													<span class="help-block text-danger error"></span>
													</td>
													<td><i class="fa fa-trash-o fa-3x" aria-hidden="true" onclick="deleteRow(this)"></i></td>
												</tr>
											@endforeach
												</tbody>
											</table>
											<div class="form-group">
												<div class="col-sm-offset-2 col-sm-10">
													<button type="submit" class="btn btn-default save_btn">Submit</button>
												</div>
											</div>
											@else
											 No Pending RO Found.
											@endif
                                   
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

deleteRow=function(e){
	$(e).parent().parent().remove()
}

// savePurchaseOrder=function(){
	// var form = $('#save-purchase-order');
	// var url = form.attr('action');
	// var formData = new FormData(form[0]);
	// $.ajax({
		// type: "POST",
		// url: url,
		// contentType: false,
		// processData: false,
		// data: formData,
		// dataType:'JSON',
		// success: function(d)
		// {
			// if(d.status){
				// location.href = '{{url("order-purchase-list")}}';
			// }else{
			// }
		// }
	// });
// }

</script>
@endsection