@php $tabindex = 10; @endphp
<div class="form-group">
	<div class="col-sm-6">
		{!! Form::label('title', 'QC Engineer', ['class' => 'control-label']) !!}         
		{!! Form::select('engineer_id',$engineer, $data->engineer_id, ['class'=>'form-control','required', 'id'=>'engineer_id','tabindex'=>$tabindex++,'placeholder'=>'-select-']) !!} 
		<span class="help-block text-danger error"></span>
	</div>
</div>
<div class="form-group" id="button-html">
  <div class="col-sm-12">
	 <button type="submit" class="btn btn-default save_btn" id="save_btn">Submit</button>
  </div>
</div>