@php $tabindex = 1; @endphp
<div class="form-group">
  <div class="col-sm-3" style="display:none">
	 {!! Form::label('title','Brand', ['class' => 'control-label']) !!}         
	 {!! Form::select('brand_id[]',$brand_list,(isset($data) && !empty($data)) ? $data->brand_id : '',['class' => 'form-control', 'required', 'id'=>'brand_id_0','tabindex'=>$tabindex++,'placeholder'=>'-Select-','onchange'=>'getModelList(0)','model-id'=>'model_id']) !!}  
	 <span class="help-block text-danger error"></span>
  </div>
  <div class="col-sm-3" style="display:none">
	 {!! Form::label('title', 'Model', ['class' => 'control-label']) !!}          
	 {!! Form::select('model_id[]',$model,(isset($data) && !empty($data)) ? $data->model_id : '', ['class' => 'form-control','required', 'id'=>'model_id_0','tabindex'=>$tabindex++,'placeholder'=>'-Select-']) !!} 
	 <span class="help-block text-danger error"></span>
  </div>
  <div class="col-sm-3" style="display:none">
	 {!! Form::label('title', 'Product Type', ['class' => 'control-label']) !!}         
	 {!! Form::select('product_type_id[]',$type_list,$product_type_id,['class'=>'form-control','required', 'id'=>'product_type_id_0','tabindex'=>$tabindex++,'placeholder'=>'-Select-']) !!} 
	 <span class="help-block text-danger error"></span>
  </div>
  <div class="col-sm-3">
	 {!! Form::label('title', 'Part Name', ['class' => 'control-label']) !!}          
	 {!! Form::select('part_id[]',$part_name_list, '',['class'=>'form-control','required', 'id'=>'part_id_0','tabindex'=>$tabindex++,'placeholder'=>'-Select-','onchange'=>'getColourList(0)','series-id'=>'series_num']) !!}  
	 <span class="help-block text-danger error"></span>
  </div>
  <div class="col-sm-3">
	 {!! Form::label('title', 'Part Colour', ['class' => 'control-,label']) !!}          
	 {!! Form::select('part_color[]',[],'',['class'=>'form-control','required', 'id'=>'colour_id_0','tabindex'=>$tabindex++,'placeholder'=>'-Select-']) !!} 
	 <span class="help-block text-danger error"></span>
  </div>
  <div class="col-sm-3" style="display:none">
	 {!! Form::label('title', 'Quantity', ['class' => 'control-label']) !!}         
	 {!! Form::text('quantity[]',1, ['class'=>'form-control','required', 'id'=>'quantity','placeholder'=>'Enter Quantity','tabindex'=>$tabindex++]) !!} 
	 <span class="help-block text-danger error"></span>
  </div>
  {!!Form::hidden('id','')!!}
</div>
<div class="after-add-more"></div>