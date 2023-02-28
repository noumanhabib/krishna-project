@php $tabindex = 10; $assign_engineer = 1; @endphp
@if(!$parts->isEmpty())
<table class="table table-responsive table-bordered">
	<thead> 
		<tr>
			<td>Part Name</td>
			<td>Series Number</td>
			<td>Part Color</td>
			<td>Status</td>
		</tr>
	</thead>
	<tbody> 
@foreach($parts as $d)
@php 
	$status = 0;
	$series = Helper::getSeriesNo($d->model_id,$d->part_type_id,$d->part_id);
	$colour = Helper::getColourList($d->series_id);
	$stock = Helper::stockInChecking($d->model_id,$d->part_type_id,$d->part_id,$d->series_id,$d->colour_id);
	if($stock){
		if($stock->total_availability >= $d->quantity){
			$status = 1;
		}
	}
	if(!$status){
		$assign_engineer = 0;
	}
@endphp
<tr>
	<td>{!! Form::select('part_id[]',$part_list, $d->part_id,['class'=>'form-control pointer-events-none','required', 'id'=>'part_id','tabindex'=>$tabindex++,'placeholder'=>'-Select-','readonly'=>'readonly']) !!} </td>
	<td>{!! Form::select('series_num[]', $series,$d->series_id, ['class'=>'form-control pointer-events-none','required', 'id'=>'series_num','placeholder'=>'Series Number','tabindex'=>$tabindex++,'placeholder'=>'-Select-','readonly'=>'readonly']) !!} </td>
	<td> {!! Form::select('part_color[]', $colour,$d->colour_id,['class'=>'form-control pointer-events-none','required', 'id'=>'part_color','tabindex'=>$tabindex++,'placeholder'=>'-select-','readonly'=>'readonly']) !!}</td>
	<td>{!! Form::select('status[]',['Out of Stock','Available'], $status, ['class'=>'form-control pointer-events-none','required', 'id'=>'status','tabindex'=>$tabindex++,'readonly'=>'readonly']) !!} </td>
</td>
{!!Form::hidden('id[]',$d->id)!!}
@endforeach
</tbody></table>
@if($assign_engineer)
<div class="form-group">
	<div class="col-sm-6">
		{!! Form::label('title', 'Assign Engineer', ['class' => 'control-label']) !!}         
		{!! Form::select('engineer_id',$engineer, $data->engineer_id, ['class'=>'form-control','required', 'id'=>'engineer_id','tabindex'=>$tabindex++,'placeholder'=>'-select-']) !!} 
		<span class="help-block text-danger error"></span>
	</div>
</div>
<div class="form-group">
  <div class="col-sm-12">
	 <button type="submit" class="btn btn-default save_btn" id="save_btn">Submit</button>
  </div>
</div>
@else
	Parts are not in stock please add the parts in sock before assign engineer.
@endif
@endif