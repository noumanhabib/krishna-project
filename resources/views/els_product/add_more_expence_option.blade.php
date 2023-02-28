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
		<i class="fa fa-minus-circle fa-3x" style="margin-top:18%" onclick="remove()"></i>
	</div>	
</div>