@extends('layouts.layout')
@section('title',$title)
@section('content')
<div id="page-wrapper" >
	 <div class="header"> 
					<h1 class="page-header">
						 <small>Final Quality Check Report List</small>
					</h1>
					<ol class="breadcrumb">
						  <li><a href="{{url('/home')}}">Home</a></li>
						  <li class="active">Final Quality Check Report List</li>
					</ol>     
	 </div>
	 <div id="page-inner">
		<div class="row">
			<div class="col-md-12">
				<!-- Advanced Tables -->
				<div class="panel panel-default">
					<div class="panel-heading d-flex">
						<div>Final Quality Check Report List</div>
						<div class="d-flex">						   
						   {!! Form::open(['url' =>'download-final-quality-check-report', 'class' => 'form-horizontal', 'role' => 'form']) !!}
							<a href="javascript:;"><button class="btn btn-info" type="submit">Export</button></a> &nbsp;&nbsp;
							{!! Form::close() !!}
						</div>
					</div>
					<div class="panel-body">
						<div class="table-responsive">
							<table class="table table-striped table-bordered table-hover" id="dataTableID">
								<thead>
									<tr>
										<th>Sr.No</th>
										<th>PO No</th> 
										<th>SKU No</th> 
										<th>Parts Name</th>
										<th>PIN</th>   
										<th>IQC Engineer Name</th>      
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


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script type="text/javascript">

$(document).ready(function () {     
	var oTable = $('#dataTableID').DataTable({
		'processing': true,
		'serverSide': true,		
		"ajax":{
			"url": "{{url('fetch_final_quality_report_list')}}"
		}, 	
		"columns": [
			{ data: "id" },
			{ data: "po_no" },
			{ data: "sku_no" },
			{ data: "part_name" },
			{ data: "barcode" },
			{ data: "engineer_name" },
		],
		"rowCallback": function (nRow, aData, iDisplayIndex) {
			var oSettings = this.fnSettings ();
			$("td:first", nRow).html(oSettings._iDisplayStart+iDisplayIndex +1);
			return nRow;
		},
		"order": [[1, 'asc']],
		"columnDefs": [{ orderable: false, "targets": 0 }]
	});	
});

</script>
@endsection