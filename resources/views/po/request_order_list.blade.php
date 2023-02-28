@php $tabindex = 1; @endphp
@if(!$data->isEmpty()) 
<table class="table table-bordered">
	<thead>
		<tr>
		  <th scope="col">Brand</th>
		  <th scope="col">Model</th>
		  <th scope="col">Product Type</th>
		  <th scope="col">Part Name</th>
		  <th scope="col">Series Number</th>
		  <th scope="col">Part Color</th>
		  <th scope="col">Unit Price</th>
		  <th scope="col">GST</th>
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
		{!! Form::select('brand_id[]', $brand, $d->brand_id, ['class' => 'form-control required pointer-events-none', 'id'=>'brand_id','tabindex'=>$tabindex++,'placeholder'=>'-Select-','readonly'=>'readonly']) !!}	
		<span class="help-block text-danger error"></span>
		</td>
		<td>
		@php $model = Helper::getModel($d->brand_id); @endphp
		{!! Form::select('model_id[]', $model, $d->model_id, ['class' => 'form-control required pointer-events-none', 'id'=>'model_id','tabindex'=>$tabindex++,'placeholder'=>'-Select-','readonly'=>'readonly']) !!}	
		<span class="help-block text-danger error"></span>
		</td>
		<td>
		{!! Form::select('product_type_id[]', $product_type, $d->part_type_id, ['class' => 'form-control required pointer-events-none', 'id'=>'product_type_id','tabindex'=>$tabindex++,'placeholder'=>'-Select-','readonly'=>'readonly']) !!}	
		<span class="help-block text-danger error"></span>
		</td>
		<td>
		{!! Form::select('part_id[]', $parts, $d->part_id, ['class' => 'form-control required pointer-events-none', 'id'=>'part_id','tabindex'=>$tabindex++,'placeholder'=>'-Select-','readonly'=>'readonly']) !!}	
		<span class="help-block text-danger error"></span>
		</td>
		@php $series = Helper::getSeriesNo($d->model_id,$d->part_type_id,$d->part_id); @endphp
		<td>
		{!! Form::select('series_id[]', $series, $d->series_id, ['class' => 'form-control required', 'id'=>'series_id_'.$key,'tabindex'=>$tabindex++,'placeholder'=>'-Select-','onchange'=>'getColour(this)','next-id'=>'colour_id_'.$key]) !!}	
		<span class="help-block text-danger error"></span>
		</td>
		@php $colour = Helper::getColourList($d->series_id); @endphp
		<td>
		{!! Form::select('colour_id[]', $colour, $d->colour_id, ['class' => 'form-control required', 'id'=>'colour_id_'.$key,'tabindex'=>$tabindex++,'placeholder'=>'-Select-','onchange'=>'getPrice(this)','back-id'=>'series_id_'.$key,'next-id'=>'price_'.$key]) !!}	
		<span class="help-block text-danger error"></span>
		</td>
		@php $colour = Helper::getPrice($d->series_id,$d->colour_id); 		
		$gst = 18;
		$amount = $colour->price * $d->quantity;
		$gst_amount = $amount*($gst/100);
		$total_amount = $amount + $gst_amount;
		@endphp
		<td>
		{!! Form::text('price[]', $colour->price, ['class' => 'form-control required', 'id'=>'price_'.$key,'tabindex'=>$tabindex++,'placeholder'=>'Price','onchange'=>'getAmountDetails('.$key.')']) !!}	
		<span class="help-block text-danger error"></span>
		</td>
		<td>
		{!! Form::select('gst[]', ['18'=>'18% GST'],$gst, ['class' => 'form-control required', 'id'=>'gst_'.$key,'tabindex'=>$tabindex++]) !!}	
		<span class="help-block text-danger error"></span>
		</td>
		<td>
		{!! Form::text('request_quantity[]', $d->quantity, ['class' => 'form-control required pointer-events-none', 'id'=>'request_quntity','tabindex'=>$tabindex++,'placeholder'=>'Request Quantity','readonly'=>'readonly']) !!}	
		<span class="help-block text-danger error"></span>
		</td>
		<td>
		{!! Form::text('quantity[]', $d->quantity, ['class' => 'form-control required', 'id'=>'quantity_'.$key,'tabindex'=>$tabindex++,'placeholder'=>'Quantity','onchange'=>'getAmountDetails('.$key.')']) !!}	
		<span class="help-block text-danger error"></span>
		</td>
		<td>{!! Form::text('amount[]', $amount, ['class' => 'form-control required pointer-events-none', 'id'=>'amount_'.$key,'tabindex'=>$tabindex++,'placeholder'=>'Amount','readonly'=>'readonly']) !!}	</td>
		<td>{!! Form::text('gst_amount[]', $gst_amount, ['class' => 'form-control required pointer-events-none', 'id'=>'gst_amount_'.$key,'tabindex'=>$tabindex++,'placeholder'=>'GST Amount','readonly'=>'readonly']) !!}	</td>
		<td>{!! Form::text('total_amount[]', $total_amount, ['class' => 'form-control required pointer-events-none', 'id'=>'total_amount_'.$key,'tabindex'=>$tabindex++,'placeholder'=>'Total Amount','readonly'=>'readonly']) !!}	</td>
		<td>
		{!! Form::select('vendor_id[]', $vendor, '', ['class' => 'form-control required', 'id'=>'role_id','tabindex'=>$tabindex++,'placeholder'=>'-Select-','required'=>'required']) !!}	
		<span class="help-block text-danger error"></span>
		</td>
		<td><i class="fa fa-trash-o fa-3x" aria-hidden="true" onclick="deleteRow(this)"></i></td>
	</tr>
@endforeach
	</tbody>
</table>
@endif
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
		<button type="button" class="btn btn-default save_btn" onclick="savePurchaseOrder()">Submit</button>
	</div>
</div>
                                   