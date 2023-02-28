@php $tabindex = 1; @endphp
<div class="form-group">
	<div class="col-sm-3">
	  {!! Form::label('title', 'Part Color', ['class' => 'control-label']) !!}          
	  {!! Form::select('part_color_id[]',$color_list,'',['class'=>'form-control','tabindex'=>$tabindex++,'id'=>'part_color_id'.$id,'placeholder'=>'-Select-','onChange'=>'generateSku('.$id.')']) !!} 
	  <span class="help-block text-danger error"></span>
	</div>
	<div class="col-sm-3">
		  {!! Form::label('title', 'SKU Number', ['class' => 'control-label']) !!}          
		  {!! Form::text('sku_no[]', '',['class'=>'form-control','required', 'id'=>'sku_no'.$id,'tabindex'=>$tabindex++,'readonly','placeholder'=>'SKU Number','onBlur'=>'checkSkuNumber(this)','onKeypress'=>'submitDisable(this)','onPaste'=>'submitDisable(this)']) !!} 
		  <span class="help-block text-danger error"></span>
	</div>
</div>