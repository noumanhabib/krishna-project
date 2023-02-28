<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover">
		<tbody>
			<tr>
				<td>Inward date</td>
				<td>{{date('d-m-Y',strtotime($data->created_at))}}</td>
			</tr>
			<tr>
				<td>Price</td>
				<td>
					{{number_format($data->price,2)}}
				</td>
			</tr>
			<tr>
				<td>IQC Status</td>
				<td>
					@if($data->iqc_status)
					Pass
					@elseif($data->iqc_status == '0')
					Failed
					@endif
				</td>
			</tr>
			@if($data->name)
			<tr>
				<td>Tester Name</td>
				<td>{{$data->name}}</td>
			</tr>
			@endif
			@php $status = '';
			$ab = Helper::getAllocatedBarcode($data->id);
			if($data->status == '2'){
			$status = 'Available';
			}
			if($data->status == '3'){
			$status = 'Allocated';
			}
			if($data->status == '0'){
			$status = 'Used';
			}
			@endphp
			@if(!$ab->isEmpty())
			@foreach($ab as $v)
			<tr>
				<td>Assigned Date</td>
				<td>{{date('d-m-Y',strtotime($v->created_at))}}</td>
			</tr>
			<tr>
				<td>Assigned Device Barcode</td>
				<td>{{$v->barcode}}</td>
			</tr>
			@endforeach
			@endif
			@if($status)
			<tr>
				<td>Current Status</td>
				<td>{{$status}} @if($data->remark) ({{$data->remark}}) @endif</td>
			</tr>
			@endif
			@if($data->vname)
			<tr>
				<td>Vandor</td>
				<td>{{$data->vname}}</td>
			</tr>
			@endif
		</tbody>
	</table>
</div>