@extends('layouts.layout')
@section('title',$title)
@section('content')
<div id="page-wrapper" >
	 <div class="header"> 
		<h1 class="page-header">
			<small>PO Wise IQC Report List</small>
		</h1>
		<ol class="breadcrumb">
			<li><a href="{{url('/home')}}">Home</a></li>
			<li class="active">PO Wise IQC  Statistics Report</li>
		</ol>     
	 </div>
	 <div id="page-inner">
		<div class="row">
			<div class="col-md-12">
				<!-- Advanced Tables -->
				<div class="panel panel-default">
					<div class="panel-heading d-flex">
						<div>PO Wise IQC   Report </div>
						<div class="d-flex">	
							<div id="reportrange" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
								<i class="fa fa-calendar"></i>&nbsp;
								<span></span> <i class="fa fa-caret-down"></i>
							</div>&nbsp;&nbsp;
							<a href="{{url('download-po_aging')}}"><button class="btn btn-primary button_right" id="add_category_btn">Export </button></a> 
						</div>
					</div>

				
					
					<div class="panel-heading">
						PO Wise IQC   Report List				
					</div>
					<div class="panel-body">
						<div class="table-responsive">
							<table class="table table-striped table-bordered table-hover" id="dataTableID">
								<thead>
									<tr>
										<th>Sr.No</th>
										<th>PO No</th>
										<th>PO Date</th>
										<th>Brand</th> 
										<th>Model</th> 
										<th>Parts Name</th>
										<th>Colour</th> 
										<th>SKU No</th> 
									
										<th>PIN</th> 
										<th>Unit Price</th> 
											<th>GST Amount</th> 
										<th>Total Amount</th>   
										<th>IQC Engineer Name</th>      
										<th>IQC Pass/Failed</th>
										<th>Status</th>
									
										<th>Received Date</th>
										<th>After IQC Pass/Failed</th>
											<th>Uploaded Date</th>
												<th>Vendor</th>
										<!--<th>Aging (Days</th>-->
									</tr>
								</thead>
								 <tbody>
								   
								</tbody>
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
				<h4 class="modal-title">Import Stock In Parts</h4>
			</div>
		<div class="modal-body">
			{!! Form::open(['url' =>'upload-stock-in-report', 'class' => 'form-horizontal', 'id' => 'upload-form', 'role' => 'form', 'onsubmit'=>'return false','files'=>'true']) !!}
				<div class="form-group">
					<div class="col-md-12">
						{!! Form::label('title', 'Choose File', ['class' => 'control-label']) !!} 
						{!! Form::file('file_csv',['class' => 	'form-control','required', 'id'=>'file_csv','placeholder'=>'-Select-']) !!}  
					</div>					
                    <div class="col-md-12" style="padding: 17px;">
                        <button type="button" onclick="saveData(this)" data-id="upload-form" class="btn btn-success mr-1">Submit <span class="btn-icon-right"><i class="fa fa-check"></i></span></button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel <span class="btn-icon-right"><i class="fa fa-close"></i></span></button>
						<a href="{{url('assets\files\stock_in_parts.csv')}}" class="btn btn-info light" download>Download Sample File</span></a>
                    </div>
				</div>
                </div>
            {!! Form::close() !!}
		</div>
      
    </div>
</div>


<!-- Remark Data Modal -->
<div id="remarkModel" class="modal fade" role="dialog">
	<div class="modal-dialog">
    <!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Remark</h4>
			</div>
		<div class="modal-body">
			{!! Form::open(['url' =>'remark-stock-in-barcode', 'class' => 'form-horizontal', 'id' => 'remark-form', 'role' => 'form', 'onsubmit'=>'return false']) !!}
				<div class="form-group">
					<div class="col-md-12">
						{!! Form::label('title', 'Remark', ['class' => 'control-label']) !!} 
						{!! Form::text('remark','',['class' => 	'form-control','required', 'id'=>'remark','placeholder'=>'Remark']) !!}  
						{!! Form::hidden('id','',['id'=>'remark_id']) !!} 
					</div>					
                    <div class="col-md-12" style="padding: 17px;">
                        <button type="button" onclick="saveData(this)" data-id="remark-form" class="btn btn-success mr-1">Submit <span class="btn-icon-right"><i class="fa fa-check"></i></span></button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel <span class="btn-icon-right"><i class="fa fa-close"></i></span></button>
                    </div>
				</div>
                </div>
            {!! Form::close() !!}
		</div>
      
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script type="text/javascript">
$(document).ready(function () {     
	importData=function(){
		$('#importModel').modal('show');
	}
	
	var oTable = $('#dataTableID').DataTable({
		'processing': true,
		'serverSide': true,		
		"ajax":{
			"url": "{{url('fetch_stock_in_parts_listpp')}}"
		}, 	
		"columns": [
			{ data: "id" },
			{ data: "po_no" },
			{ data: "formatted_dobp" },
			{ data: "bname" },
			{ data: "mname" },
				{ data: "part_name" },
			{ data: "colour_name" },
			{ data: "sku_no" },
		
			{ data: "barcode" },
			{ data: "prices" },
			{ data: "price_amount" },
			{ data: "price" },

			{ data: "engineer_name" },
			{ data: "iqc_status" },
			{ data: "current_status" },
			{ data: "received_date" },
			{ data: "iqc_status_one" },
			{ data: "formatted_dob" },
			{ data: "vname" },
// 			{ data: "age" },
		],
	
		"rowCallback": function (nRow, aData, iDisplayIndex) {
			var oSettings = this.fnSettings ();
			$("td:first", nRow).html(oSettings._iDisplayStart+iDisplayIndex +1);
			var actionBtn = '';
				var actionBtn1 = '';
			if(aData.status=='2'){
				if(aData.iqc_status == '1'){
					actionBtn1 += 'Pass';
				}else{
					actionBtn1 += 'Failed';
				}
			}
			
			$("td:eq(13)", nRow).html(actionBtn1);
			
			if(aData.status=='2'){
				if(aData.iqc_status_one == '1'){
					actionBtn += 'Pass';		
					}else{
					  	actionBtn += 'Failed';
					}
			}
			
			$("td:eq(16)", nRow).html(actionBtn);
			return nRow;
		},
		"order": [[1, 'asc']],
		"columnDefs": [{ orderable: false, "targets": 0 }]
	});  
	
	saveData=function(e){
		var form = $('#'+$(e).attr('data-id'));
		var url = form.attr('action');
		var formData = new FormData(form[0]);
		$.ajax({
			type: "POST",
			url: url,
			contentType: false,
			processData: false,
			data: formData,
			dataType:'JSON',
			success: function(d)
			{
				if(d.status){
					alert(d.message);
					$('.modal').modal('hide');
					oTable.ajax.reload(); 
				}else{
					alert(d.message);
				}
			}
		});
	}
	
	setStatus=function(id,status){
		if(id){
			$.ajax({
				type: "POST",
				url: "{{url(route('set-iqc-status'))}}",
				data:{id:id,status:status},
				dataType:'JSON',
				success: function(d){
					if(d.status){	
						if(!status){
							$('#remark_id').val(id);
							$('#remarkModel').modal('show');
						}
						oTable.ajax.reload(); 
					}else{
						alert(d.message)
					}
				}
			});
		}
	}
		
	addBarcode=function(){
		var barcode = $('#barcode').val();
		if(barcode){
			$.ajax({
				type: "POST",
				url: "{{url(route('add-barcode-in-stock'))}}",
				data:{barcode:barcode},
				dataType:'JSON',
				success: function(d){
					if(d.status){	
						$('#barcode').val('');
						oTable.ajax.reload(); 
					}else{
						alert(d.message)
					}
				}
			});
		}
	}
	
	enterKeyPress=function(e){
		if(e.keyCode === 13){
			addBarcode();
		}
	}
});

</script>
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
		url: "{{url('set_date_range_filterpp')}}",
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
		url: "{{url('set_date_range_filterpp')}}",
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