@php
$current_in_stock = 0;
$inwrd = Helper::getInwardDate($data->id);
$or = Helper::getOrderRequest($data->id);
$bar = Helper::getBarcodeAllocation($data->id);
$warrenty = Helper::getDeviceWarrenty($data->id);
$allocation = Helper::getEngineerAllocationDetails($data->id);
$status = Helper::getStatusLog($data->id); 
@endphp
<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover">
		<tbody>
			@foreach($inwrd as $key => $d)
			@php
				if($d->status){
					$current_in_stock = 1;
				}
			@endphp
			<tr>
				<td>Inward date {{($key+1)}}</td>
				<td>{{date('d/m/Y',strtotime($d->received_date))}}</td>
			</tr>
			@endforeach
			@if(!$or->isEmpty())
			@foreach($or as $v)
			<tr>
				<td>Order Request Date</td>
				<td>{{date('d/m/Y',strtotime($v->created_at))}}</td>
			</tr>
			<tr>
				<td>Order Request Parts</td>
				<td>{{$v->part_name}}</td>
			</tr>
			@endforeach		
			@endif
			@if($bar)			
			<tr>
				<td>Allocated Barcode</td>
				<td>{{$bar->barcode}}</td>
			</tr>
			@endif
			@foreach($allocation as $v)
			<tr>
				<td>Enginner Allocation Date</td>
				<td>{{date('d/m/Y',strtotime($v->created_at))}}</td>
			</tr>
			<tr>
				<td>Enginner Name</td>
				<td>{{$v->name}}</td>
			</tr>
			<tr>
				<td>Status</td>
				<td>{{$v->status}}</td>
			</tr>
			@endforeach			
			@if($current_in_stock)			
			<tr>
				<td>Current Status</td>
				<td>In Stock</td>
			</tr>
			@endif
			@if($data->vname)			
			<tr>
				<td>In Vandor</td>
				<td>{{$data->vname}}</td>
			</tr>
			@endif		
			@if($data->sub_status)	
				@foreach($status as $sd)
				<tr>
					<td>Status</td>
					<td>{{$sd->name}} ({{date('d/m/Y',strtotime($sd->created_at))}})</td>
				</tr>
				@if($sd->vname)
				<tr>
					<td>Out Vendor</td>
					<td>{{$sd->vname}}</td>
				</tr>
				@endif
				@endforeach
			@endif
			@if($warrenty)
			<tr>
				<td>Warrenty Start Date</td>
				<td>{{date('d/m/Y',strtotime($warrenty->start_date))}}</td>
			</tr>
			<tr>
				<td>Warrenty End Date</td>
				<td>{{date('d/m/Y',strtotime($warrenty->end_date))}}</td>
			</tr>
			@endif
		</tbody>
	</table>
</div>