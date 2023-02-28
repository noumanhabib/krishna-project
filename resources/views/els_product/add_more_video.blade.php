<div class="form-group">
	<div class="col-md-9">
		{!! Form::label('title', 'Choose Video', ['class' => 'control-label']) !!} 
		{!! Form::file('video_file[]',['class' => 	'form-control','required','placeholder'=>'-Select-']) !!}    
	</div>
	<div class="col-sm-3">
		<i class="fa fa-minus-circle fa-3x" style="margin-top:18%" onclick="remove(this)"></i>
	</div>	
</div>