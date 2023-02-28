{!! Form::open(['url' =>'save_extra_expence', 'class' => 'form-horizontal', 'id' => 'save-form', 'role' => 'form', 'onsubmit'=>'return false']) !!}
@if(!$data->isEmpty())
@foreach($data as $key => $d)
<div class="form-group">
	<div class="col-md-5">
		{!! Form::label('title', 'Expence Title', ['class' => 'control-label']) !!} 
		{!! Form::text('title[]',$d->title,['class' =>'form-control','required','placeholder'=>'Expence Title']) !!}    
	</div>
	<div class="col-md-5">
		{!! Form::label('title', 'Amount', ['class' => 'control-label']) !!} 
		{!! Form::text('amount[]',$d->amount,['class' =>'form-control','required','placeholder'=>'Amount','onkeypress'=>'return isNumberKey(event)']) !!}    
	</div>
	{!! Form::hidden('id[]', $d->id) !!}
	@if($key)
	<div class="col-sm-2">
		<i class="fa fa-minus-circle fa-3x" style="margin-top:18%" onclick="remove()"></i>
	</div>	
	@else
	<div class="col-sm-2">
		<i class="fa fa-plus-circle fa-3x" style="margin-top:18%" onclick="addMore()"></i>
	</div>	
	@endif
</div>
@endforeach
@else
<div class="form-group">
	<div class="col-md-5">
		{!! Form::label('title', 'Expence Title', ['class' => 'control-label']) !!} 
		{!! Form::text('title[]','',['class' =>'form-control','required','placeholder'=>'Expence Title']) !!}    
	</div>
	<div class="col-md-5">
		{!! Form::label('title', 'Amount', ['class' => 'control-label']) !!} 
		{!! Form::text('amount[]','',['class' =>'form-control','required','placeholder'=>'Amount','onkeypress'=>'return isNumberKey(event)']) !!}    
	</div>
	{!! Form::hidden('id[]', '') !!}
	<div class="col-sm-2">
		<i class="fa fa-plus-circle fa-3x" style="margin-top:18%" onclick="addMore()"></i>
	</div>	
</div>
@endif
<div class="form-group add-more-expence">
	<div class="col-md-12 " style="padding: 17px;">
		<button type="button" onclick="saveDetails()" class="btn btn-success mr-1">Submit <span class="btn-icon-right"><i class="fa fa-check"></i></span></button>&nbsp;&nbsp;
		<button type="button" class="btn btn-danger" data-dismiss="modal">Cancel <span class="btn-icon-right"><i class="fa fa-close"></i></span></button>
	</div>
</div>
{!! Form::hidden('els_system_id', $els_system_id,['id'=>'els_system_id']) !!}
{!! Form::close() !!}