@php $tabindex = 1; @endphp
<div class="form-group">
    	<div class="col-sm-4">
		{!! Form::label('title', 'Part Name', ['class' => 'control-label']) !!}				  
		{!! Form::select('part_id[]',$part_name_list,'',['required'=>'required','class'=>'form-control','id'=>'part_id'.$id,'tabindex'=>$tabindex++,'placeholder'=>'-Select-']) !!}	
		<span class="help-block text-danger error"></span>
		</div>
	<div class="col-sm-4">
	  {!! Form::label('title', 'Part Color', ['class' => 'control-label']) !!}          
	  {!! Form::select('part_color_id[]',$color_list,'',['class'=>'form-control','id'=>'part_color_id'.$id,'onChange'=>'generateSku('.$id.')','tabindex'=>$tabindex++,'placeholder'=>'-Select-']) !!} 
	  <span class="help-block text-danger error"></span>
	</div>
	<div class="col-sm-4">
		  {!! Form::label('title', 'SKU Number', ['class' => 'control-label']) !!}          
		  {!! Form::text('sku_no[]', '',['class'=>'form-control','required', 'id'=>'sku_no'.$id,'tabindex'=>$tabindex++,'placeholder'=>'SKU Number','onBlur'=>'checkSkuNumber(this)','onKeypress'=>'submitDisable(this)','onPaste'=>'submitDisable(this)']) !!} 
		  <span class="help-block text-danger error"></span>
	</div>
</div>