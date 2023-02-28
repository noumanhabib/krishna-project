@extends('layouts.layout')
@section('title',$title)
@section('content')
<div id="page-wrapper" >
	 <div class="header"> 
					<h1 class="page-header">
						 <small>Stock Parts List</small>
					</h1>
					<ol class="breadcrumb">
						  <li><a href="{{url('/home')}}">Home</a></li>
						  <li class="active">Stock Parts List</li>
					</ol>     
	 </div>
	 <div id="page-inner">
		<div class="row">
			<div class="col-md-12">
				<!-- Advanced Tables -->
				<div class="panel panel-default">
					<div class="panel-heading d-flex">
						<div> Stock Parts List</div>
						<div class="d-flex">					   
						   {!! Form::open(['url' =>'export-spart-part-report', 'class' => 'form-horizontal', 'role' => 'form']) !!}
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
										<th>Brand</th> 
										<th>Model</th> 
										<th>Part Type</th>
										<th>SKU No</th>         
										<th>Parts Name</th>         
										<th>Colour</th>         
										<th>Required Quantity</th>           
										<th>Pendding Quantity</th>      
										<th>Recived Quantity</th>          
										<th>IQC Failed</th>         
										<th>IQC Pass</th>         
										<th>Available Stock</th>         
										<th>Allocated</th>
										<th>Consumed</th>
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
	var oTable = $('#dataTableID').DataTable({
		'processing': true,
		'serverSide': true,		
		"ajax":{
			"url": "{{url('fetch_stock_parts_list')}}"
		}, 	
		"columns": [
			{ data: "id" },
			{ data: "bname" },
			{ data: "mname" },
			{ data: "type" },
			{ data: "sku_no" },
			{ data: "part_name" },
			{ data: "colour_name" },
			{ data: "required_qty" },
			{ data: "panding_qty" },
			{ data: "total" },
			{ data: "iqc_failed" },
			{ data: "iqc_pass" },
			{ data: "available" },
			{ data: "allocated" },
			{ data: "consumed" },
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