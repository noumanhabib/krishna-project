@extends('layouts.layout')
@section('title',$title)
@section('content')
<div id="page-wrapper" >
	 <div class="header"> 
		<h1 class="page-header">
			 <small>Dispatch Device List</small>
		</h1>
		<ol class="breadcrumb">
			  <li><a href="{{url('/home')}}">Home</a></li>
			  <li class="active">Dispatch Device List</li>
		</ol>     
	 </div>
	 <div id="page-inner">
		<div class="row">
			<div class="col-md-12">
				<!-- Advanced Tables -->
				<div class="panel panel-default">
					<div class="panel-heading d-flex">
					    <div>Dispatch Device List</div>
					    <div class="d-flex">
							{!! Form::open(['url' =>'export-device-dispatch-reportt', 'class' => 'form-inline', 'role' => 'form']) !!}
							<div id="reportrange" class="form-group" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc;">
								<i class="fa fa-calendar"></i>&nbsp;
								<span></span> <i class="fa fa-caret-down"></i>
							</div>&nbsp;&nbsp;
							{!! Form::hidden('filter_date', null, ['class' => 'form-control', 'id'=>'filter_date','onchange'=>'getData()']) !!}
							 &nbsp;&nbsp;							
							<a href="javascript:;"><a class="btn btn-info" onclick="importData()">Import Status</a></a>&nbsp;&nbsp;
							<a href="javascript:;"><button class="btn btn-info" type="submit">Export</button></a>&nbsp;&nbsp;
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
										<th>ELS Brand</th>
										<th>ELS Model</th>
										<th>Color</th>
										<th>UIN</th>
										<th>IMEI 1</th>
										<th>IMEI 2</th>
										<th>RAM</th>
										<th>ROM</th>
										<th>Incoming Grade</th>   
										         
										<th>Dispatch Vendor</th>  
										<th>Remark</th>    
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

<!-- Import Data Modal -->
<div id="importModel" class="modal fade" role="dialog">
	<div class="modal-dialog">
    <!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Import Devices Status</h4>
			</div>
		<div class="modal-body">
			{!! Form::open(['url' =>'upload-device-status', 'class' => 'form-horizontal', 'id' => 'save-form', 'role' => 'form','files'=>'true']) !!}
				<div class="form-group">
					<div class="col-md-12">
						{!! Form::label('title', 'Choose Devices Price File', ['class' => 'control-label']) !!} 
						{!! Form::file('file_csv',['class' => 	'form-control','required', 'id'=>'file_csv','placeholder'=>'-Select-']) !!}  
					</div>					
                    <div class="col-md-12" style="padding: 17px;">
                        <button type="submit" class="btn btn-success mr-1">Submit <span class="btn-icon-right"><i class="fa fa-check"></i></span></button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel <span class="btn-icon-right"><i class="fa fa-close"></i></span></button>
						<a href="{{url('assets\files\els_product_status.csv')}}" class="btn btn-info light" download>Download Sample File</span></a>
                    </div>
				</div>
                </div>
            {!! Form::close() !!}
		</div>
      
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> 
<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<script type="text/javascript">
var start = moment().subtract(0, 'days');
var end = moment();
var filterData = {};
filterData['filter_date'] = (start.format('DD/MM/YYYY') + ' - ' + end.format('DD/MM/YYYY'));
function cb(start, end) {
    $('#filter_date').val(start.format('DD/MM/YYYY') + ' - ' + end.format('DD/MM/YYYY'));
    $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
	setTimeout(function() {
		filterData['filter_date'] = (start.format('DD/MM/YYYY') + ' - ' + end.format('DD/MM/YYYY'));
        $("#filter_date").trigger("change");
    },1000);	
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
}, cb);

cb(start, end);

$(document).ready(function () { 	
	importData=function(){
		$('#importModel').modal('show');
	}

	downloadPO=function(id){
		if(id){
			$('#challan_id').val(id);
			$('#download-challan').submit();
		}
	}    
	var oTable = $('#dataTableID').DataTable({
		"bDestroy": true,
		'processing': true,
		'serverSide': true,		
		"ajax":{
			"url": "{{url('fetch_dipatch_device_list')}}",
			"data": function (d) {
				$.each(filterData, function(key,val) {
					d[key] = val;
				});
			}
		}, 	
		"columns": [
			{ data: "id" },
			{ data: "updated_ats"},
			{ data: "bname" },
			{ data: "mname" },
			{ data: "colour_name" },
			{ data: "barcode" },
			{ data: "imei_1" },
			{ data: "imei_2" },
			{ data: "ram" },
			{ data: "rom" },
			{ data: "grade" },
			{ data: "vendor_out" },
			{ data: "remark" },
		],
		"rowCallback": function (nRow, aData, iDisplayIndex) {
			var oSettings = this.fnSettings ();
			$("td:first", nRow).html(oSettings._iDisplayStart+iDisplayIndex +1);
			return nRow;
		},
		"order": [[0, 'asc']],
		"columnDefs": [{ orderable: false, "targets": 0 }]
	});	
	
	getData=function(){
		if(filterData['filter_date']){
			oTable.draw();
		}	
	} 
	
});
</script>
@endsection