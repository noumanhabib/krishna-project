{!! Form::open(['class' => 'form-horizontal']) !!}
	@foreach($data as $d)
	<div class="form-group">
	   
		<div class="col-md-7">
		    
			<video controls="controls" height=100  weight=100 src="{{url($d->video_path)}}">
			</video> 
			
		<a href="{{url($d->video_path)}}" download>	{{basename($d->video_path)}}</a>
			
		</div>
		<div class="col-sm-5">
			<i class="fa fa-trash-o text-danger mr-2" style="font-size: 30px;" onclick="deleteVideo(this,{{$d->id}})"></i>
		</div>	
	</div>
	@endforeach
{!! Form::close() !!}