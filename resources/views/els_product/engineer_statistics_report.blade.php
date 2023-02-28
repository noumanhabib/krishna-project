@extends('layouts.layout')
@section('title',$title)
@section('content')
<div id="page-wrapper" >
	 <div class="header"> 
		<h1 class="page-header">
			<small>Engineer Statistics Report List</small>
		</h1>
		<ol class="breadcrumb">
			<li><a href="{{url('/home')}}">Home</a></li>
			<li class="active">Engineer Statistics Report</li>
		</ol>     
	 </div>
	 <div id="page-inner">
		<div class="row">
			<div class="col-md-12">
				<!-- Advanced Tables -->
				<div class="panel panel-default">
					<div class="panel-heading d-flex">
						<div>Statistics Report </div>
						<div class="d-flex">	
							<div id="reportrange" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
								<i class="fa fa-calendar"></i>&nbsp;
								<span></span> <i class="fa fa-caret-down"></i>
							</div>&nbsp;&nbsp;
							<a href="{{url('download-work-report')}}"><button class="btn btn-primary button_right" id="add_category_btn">Export </button></a> 
						</div>
					</div>

					<div class="panel-body">
						<div class="table-responsive">
							<table class="table table-striped table-bordered table-hover">
								<thead>
									<tr>
										<th></th>
										@foreach($status as $v)
										<th>Total {{$v->name}}</th>  
										@endforeach	
										<th>Total issued </th>
									</tr>
								</thead>
								<tbody>
								@php $grand_total = 0;
									if(session()->get('start_date') && session()->get('end_date')){
										$start_date = session()->get('start_date');
										$end_date = date('Y-m-d', strtotime('+1 day', strtotime(session()->get('end_date'))));
									}else{
										$start_date = date('Y-m-d', strtotime('-29 day'));
										$end_date = date('Y-m-d', strtotime('+1 day'));
									}			
									$date1=date_create($end_date);
									$date2=date_create($start_date);
									$diff=date_diff($date1,$date2);
									$days = $diff->days;
								@endphp
								@foreach($data as $key => $d)
									@foreach($status as $v)
										@php
											$count = Helper::getAssignedSystemCount($d->id,$v->id);
											$countArr[$v->id][] = $count;
											$grand_total += $count;
										@endphp 
									@endforeach	
								@endforeach
								<tr>
									<td>All Engineer</td>
									@foreach($status as $v)
										<td>{{array_sum($countArr[$v->id])}}</td>  
									@endforeach	
									<td>{{$grand_total}}</td>
								</tr>
								
								</tbody>
							</table>
						</div>
					</div>
					
					<div class="panel-heading">
						Engineer Statistics Report List				
					</div>	
					<div class="panel-body">
						<div class="table-responsive" id="dvData">
							<table id="dataTableID" class="table table-striped table-bordered table-hover data_tablelist">
								<thead>
									<tr>
										<th>Sr.No</th>
										<th>Engineer</th>
										@foreach($status as $v)
										<th>Total {{$v->name}}</th>  
										@endforeach	
										<th>Total issued </th>
										<th>Target</th>
										<th>Variance</th>
									</tr>
								</thead>
								<tbody>
								@foreach($data as $key => $d)
								@php $total = 0; @endphp
									<tr>
										<td>{{$key+1}}</td>  
										<td>{{$d->name}}</td>  
										@foreach($status as $v)
										@php
										$count = Helper::getAssignedSystemCount($d->id,$v->id);
										if(!in_array($v->id,[1])){
											$total += $count;
										}										
										$target = $days*$d->target;	
										$variance = $total - $target;
										@endphp
											<td>{{$count}}</td>  
										@endforeach	
										<td>{{$total}}</td>
										<td>{{$target}}</td>
										<td>{{$variance}}</td>
									</tr>
								@endforeach
								</tbody>
							</table>
						</div>
					</div>
				
				
				</div>
			</div>
		</div>
	</div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> 
<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<script type="text/javascript">
var start = moment().subtract(29, 'days');
var end = moment();
@if(session()->get('start_date') && session()->get('end_date'))
end = moment("{{session()->get('end_date')}}");
start = moment("{{session()->get('start_date')}}");
@endif 
function cb(start, end) {
	var csrfToken = "{{ csrf_token() }}";
	$.ajax({
		type: "POST",
		url: "{{url('set_date_range_filter')}}",
		headers: {
			"X-CSRF-Token": csrfToken,
		},
		data: {start_date:start.format('YYYY-MM-DD'),end_date:end.format('YYYY-MM-DD')},
		dataType:'JSON',
		success: function(d)
		{
		}
	});
    $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
}

function cba(start, end) {
	var csrfToken = "{{ csrf_token() }}";
	$.ajax({
		type: "POST",
		url: "{{url('set_date_range_filter')}}",
		headers: {
			"X-CSRF-Token": csrfToken,
		},
		data: {start_date:start.format('YYYY-MM-DD'),end_date:end.format('YYYY-MM-DD')},
		dataType:'JSON',
		success: function(d)
		{
			location.reload();
		}
	});
    $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
}

$('#reportrange').daterangepicker({
    startDate: start,
    endDate: end,
    ranges: {
       'Today': [moment(), moment()],
       'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
       'Last 7 Days': [moment().subtract(6, 'days'), moment()],
       'Last 30 Days': [moment().subtract(29, 'days'), moment()],
       'This Month': [moment().startOf('month'), moment().endOf('month')],
       'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
    }
}, cba);

cb(start, end);

$(document).ready(function () {
	$('#dataTableID').DataTable();
	
	
	
	// var oTable = $('#dataTableID').DataTable({
		// "bDestroy": true,
		// 'processing': true,
		// 'serverSide': true,		
		// "ajax":{
			// "url": "{{url('get-enginner-work-report-list')}}"
		// }, 	
		// "columns": [
			// { data: "id" },
			// { data: "name" },
			// { data: "repair" },
			// { data: "l3" },
			// { data: "l4" },
			// { data: "fqc" },
			// { data: "fqc_fails" },
			// { data: "shrink_pack" },
			// { data: "total_system" },
		// ],
		// "rowCallback": function (nRow, aData, iDisplayIndex) {
			// var oSettings = this.fnSettings ();
			// $("td:first", nRow).html(oSettings._iDisplayStart+iDisplayIndex +1);
			// return nRow;
		// },
		// "order": [[1, 'asc']],
		// "columnDefs": [{ orderable: false, "targets": 0 }]
	// });
});
</script>
@endsection