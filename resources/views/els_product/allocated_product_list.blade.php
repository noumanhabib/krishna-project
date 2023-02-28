@extends('layouts.layout')
@section('title',$title)
@section('content')
<div id="page-wrapper" >
	 <div class="header"> 
					<h1 class="page-header">
						 <small>Allocate Engineer Product List</small>
					</h1>
					<ol class="breadcrumb">
						  <li><a href="{{url('/home')}}">Home</a></li>
						  <li class="active"> Allocate Engineer Product </li>
					</ol>     
	 </div>
	 <div id="page-inner">
		<div class="row">
			<div class="col-md-12">
				<!-- Advanced Tables -->
				<div class="panel panel-default">
					<div class="panel-heading">
						Allocate Engineer Product List
						<a href="{{url('add_engineer_in_product')}}"><button class="btn btn-primary button_right" id="add_category_btn">+ Allocate Engineer </button></a> 
					
					</div>

					<div class="panel-body">
						<div class="table-responsive" id="dvData">
							<table id="dataTableID" class="table table-striped table-bordered table-hover data_tablelist">
								<thead>
									<tr>
										<th>Sr.No</th>
										<th>Barcode</th>                                          
										<th>IMEI 1</th>           
										<th>IMEI 2</th>           
										<th>Brand</th>
										<th>Model</th>
										<th>Colour</th>
										<th>Assignd Engineer</th>
										<th>Action</th>
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
		"bDestroy": true,
		'processing': true,
		'serverSide': true,		
		"ajax":{
			"url": "{{url('fetch_assign_product_list')}}"
		}, 	
		"columns": [
			{ data: "id" },
			{ data: "barcode" },
			{ data: "imei_1" },
			{ data: "imei_2" },
			{ data: "bname" },
			{ data: "mname" },
			{ data: "colour_name" },
			{ data: "name" },
			{ data: "id" }
		],
		"rowCallback": function (nRow, aData, iDisplayIndex) {
			var oSettings = this.fnSettings ();
			$("td:first", nRow).html(oSettings._iDisplayStart+iDisplayIndex +1);
			var actionBtn = '<a href="{{url("edit_assigned_engineer")}}/'+aData.id+'"><i class="fa fa-edit text-primary mr-2"></i></a>';
			$("td:last", nRow).html(actionBtn);
			return nRow;
		},
		"order": [[1, 'asc']],
		"columnDefs": [{ orderable: false, "targets": 0 }]
	});      
});
</script>
@endsection