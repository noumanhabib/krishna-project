@extends('layouts.layout')
@section('title',$title)
@section('content')
<div id="page-wrapper" >
	 <div class="header"> 
		<h1 class="page-header">
			 <small>Engineer Date Wise Allocated Devices</small>
		</h1>
		<ol class="breadcrumb">
			  <li><a href="{{url('/home')}}">Home</a></li>
			  <li class="active">Engineer Date Wise Allocated Devices</li>
		</ol>     
	 </div>
	 <div id="page-inner">
		<div class="row">
			<div class="col-md-12">
				<!-- Advanced Tables -->
				<div class="panel panel-default">
					<div class="panel-heading d-flex">
					    <div>Engineer Date Wise Allocated Devices List</div>
					    <div class="d-flex">	
							{!! Form::open(['url' =>'export-enginner-date-wise-allocated-device', 'class' => 'form-inline', 'role' => 'form']) !!}							
							{!! Form::date('filter_date', now(), ['class' => 'form-control', 'id'=>'filter_date','onchange'=>'getData()']) !!}
							 &nbsp;&nbsp;
							<a href="javascript:;"><button class="btn btn-info" type="submit">Export</button></a>
							{!! Form::close() !!}
						</div>							
					</div>
					<div class="panel-body">
						<div class="table-responsive" id="dvData">
							<table id="dataTableID" class="table table-striped table-bordered table-hover data_tablelist">
								<thead>
									<tr>
										<th>Sr.No</th>
										<th>Date</th>
										<th>Assignd Engineer</th>										
										<th>Consumed Parts</th>
										<th>UIN</th>                                          
										<th>IMEI 1</th>           
										<th>IMEI 2</th>           
										<th>Brand</th>
										<th>Model</th>
										<th>Colour</th>
									</tr>
								</thead>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> 
<script type="text/javascript">

$(document).ready(function () {     
	var filterData = {};
	filterData['filter_date'] = $('#filter_date').val();
	var oTable = $('#dataTableID').DataTable({
		"bDestroy": true,
		'processing': true,
		'serverSide': true,		
		"ajax":{
			"url": "{{url('enginner-date-wise-allocated-device-list')}}",
			"data": function (d) {
				$.each(filterData, function(key,val) {
					d[key] = val;
				});
			}
		}, 	
		"columns": [
			{ data: "id" },
			{ data: "date" },
			{ data: "name" },
			{ data: "part_barcode" },
			{ data: "barcode" },
			{ data: "imei_1" },
			{ data: "imei_2" },
			{ data: "bname" },
			{ data: "mname" },
			{ data: "colour_name" },
		],
		"rowCallback": function (nRow, aData, iDisplayIndex) {
			var oSettings = this.fnSettings ();
			$("td:first", nRow).html(oSettings._iDisplayStart+iDisplayIndex +1);			
			// var actionBtn = '<a href="javascript:;" onclick="changeStatus('+aData.allocated_id+','+aData.status_id+')"><i class="fa fa-edit text-primary mr-2"></i></a> &nbsp;&nbsp;';
			// $("td:eq(10)", nRow).html(actionBtn);
			return nRow;
		},
		"order": [[1, 'asc']],
		"columnDefs": [{ orderable: false, "targets": 0 }]
	});	
	
	getData=function(){
		filterData['filter_date'] = $('#filter_date').val();
		oTable.draw();
	} 
	
});
</script>
@endsection