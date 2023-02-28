@php $tabindex = 10; @endphp
<div class="form-group">
	<div class="col-sm-6">
		{!! Form::label('title', 'Assign Engineer', ['class' => 'control-label']) !!}         
		{!! Form::select('engineer_id',$engineer, $data->engineer_id, ['class'=>'form-control','required', 'id'=>'engineer_id','tabindex'=>$tabindex++]) !!} 
		<span class="help-block text-danger error"></span>
	</div>
	
	
		<div class="col-sm-6">
		{!! Form::label('title', 'Select Status', ['class' => 'control-label']) !!}         
		{!! Form::select('status',$statuss, $data->current_status, ['class'=>'form-control','required', 'id'=>'status','tabindex'=>$tabindex++]) !!} 
		<span class="help-block text-danger error"></span>
	</div>
</div>

<div class="form-group">
	<div class="col-sm-12">
		{!! Form::label('title', 'Remark', ['class' => 'control-label']) !!}         
		{!! Form::text('remark','', ['class'=>'form-control','', 'id'=>'remark','tabindex'=>$tabindex++]) !!} 
		<span class="help-block text-danger error"></span>
	</div>

</div>
<div class="form-group" id="button-html">
  <div class="col-sm-12">
	 <button type="submit" class="btn btn-default save_btn" id="save_btn">Submit</button>
  </div>
</div>