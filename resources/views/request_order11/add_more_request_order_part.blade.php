@php $tabindex = 1; @endphp
<div class="form-group">
	<div class="col-sm-3" @if(isset($data) && !empty($data)) style="display:none" @endif>
		{!! Form::label('title','Brand', ['class' => 'control-label']) !!}				  
		{!! Form::select('brand_id[]',$brand_list,(isset($data) && !empty($data)) ? $data->brand_id : '',['class' => 'form-control', 'required', 'id'=>'brand_id_'.$index,'tabindex'=>$tabindex++,'placeholder'=>'-Select-','onchange'=>'getModelList('.$index.')','model-id'=>'model_id_'.$tabindex]) !!}	
		<span class="help-block text-danger error"></span>
	</div>
	<div class="col-sm-3" @if(isset($data) && !empty($data)) style="display:none" @endif>
		{!! Form::label('title', 'Model', ['class' => 'control-label']) !!}				  
		{!! Form::select('model_id[]',$model,(isset($data) && !empty($data)) ? $data->model_id : '', ['class' => 'form-control','required', 'id'=>'model_id_'.$index,'tabindex'=>$tabindex++,'placeholder'=>'-Select-']) !!}	
		<span class="help-block text-danger error"></span>
	</div>
	<div class="col-sm-3" @if(isset($data) && !empty($data)) style="display:none" @endif>
		{!! Form::label('title', 'Product Type', ['class' => 'control-label']) !!}				  
		{!! Form::select('product_type_id[]',$type_list,(isset($data) && !empty($data)) ? $data->type_id : '',['class'=>'form-control','required', 'id'=>'product_type_id_'.$index,'tabindex'=>$tabindex++,'placeholder'=>'-Select-']) !!}	
		<span class="help-block text-danger error"></span>
	</div>
	<div class="col-sm-3">
		{!! Form::label('title', 'Part Name', ['class' => 'control-label']) !!}				  
		{!! Form::select('part_id[]',$part_name_list,(isset($data) && !empty($data)) ? $data->part_id : '',['class'=>'form-control','required', 'id'=>'part_id_'.$index,'tabindex'=>$tabindex++,'placeholder'=>'-Select-','onchange'=>'getColourList('.$index.')','series-id'=>'series_num_'.$tabindex]) !!}	
		<span class="help-block text-danger error"></span>
	</div>
	<div class="col-sm-3">
		{!! Form::label('title', 'Part Color', ['class' => 'control-,label']) !!}          
		 {!! Form::select('part_color[]',[],'',['class'=>'form-control','required', 'id'=>'colour_id_'.$index,'tabindex'=>$tabindex++,'placeholder'=>'-Select-']) !!}   
		<span class="help-block text-danger error"></span>
	</div>
	<div class="col-sm-3" @if(isset($data) && !empty($data)) style="display:none" @endif>
		{!! Form::label('title', 'Quantity', ['class' => 'control-label']) !!}         
		{!! Form::text('quantity[]', (isset($data) && !empty($data)) ? $data->quantity : '', ['class'=>'form-control','required', 'id'=>'quantity','placeholder'=>'Enter Quantity','tabindex'=>$tabindex++]) !!} 
		<span class="help-block text-danger error"></span>
	</div>
	<div class="col-sm-3">
		 <i class="fa fa-minus-circle fa-3x" aria-hidden="true" id="add_more" style="margin-top:10%;float: right;cursor:pointer" onclick="deleteOne(this)"></i>
	</div>
</div>