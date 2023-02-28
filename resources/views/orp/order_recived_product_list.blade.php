@extends('layouts.layout')
@section('title',$title)
@section('content')
<div id="page-wrapper" >
         <div class="header"> 
			<h1 class="page-header">
				 <small>Purchase Order Recived List</small>
			</h1>
			<ol class="breadcrumb">
				  <li><a href="{{url('/home')}}">Home</a></li>
				  <li class="active">Purchase Order Received</li>
			</ol>     
         </div>
         <div id="page-inner">
            <div class="row">
                <div class="col-md-12">
                    <!-- Advanced Tables -->
                    <div class="panel panel-default">
                        <div class="panel-heading d-flex">
							<div>Purchase Order Recived List</div>
							<div class="d-flex">				   
							   {!! Form::open(['url' =>'export-purchase-order-recived-report', 'class' => 'form-horizontal', 'role' => 'form']) !!}
								<a href="javascript:;"><button class="btn btn-info" type="submit">Export</button></a> &nbsp;&nbsp;
								{!! Form::close() !!}			   
							   {!! Form::open(['url' =>'export-purchase-order-recived-barcode', 'class' => 'form-horizontal', 'role' => 'form']) !!}
								<a href="javascript:;"><button class="btn btn-info" type="submit">Export All PIN</button></a> &nbsp;&nbsp;
								{!! Form::close() !!}
								 <?php  if(in_array('20',Helper::addedPermission())){  ?>
								<a href="{{url(route('add-received-quantity'))}}"><button class="btn btn-primary" id="add_category_btn">+ Add Purchase Order Recived</button></a> 
								<?php }  ?>
							</div>
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="tableData">
                                    <thead>
                                        <tr>
											<th>Sr.No</th>
											<th>Date</th> 
											<th>PO No</th> 
											<th>Brand</th>
											<th>Model</th>
											<th>Product Type</th>
											<th>Part Name</th>
											<th>Colour</th>
											<th>Quantity</th>
											<th>Recived By</th>          
											<th>Action</th>
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


{!! Form::open(['url' =>route('download-barcode-list'), 'class' => 'form-horizontal', 'role' => 'form', 'id' => 'download-form']) !!}									
	{!! Form::hidden('id', '', ['id'=>'set-id']) !!}	
{!! Form::close() !!}

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script type="text/javascript">
    $(document).ready(function () {     
        var oTable = $('#tableData').DataTable({
			'processing': true,
			'serverSide': true,		
			"ajax":{
				"url": "{{url('fetch_reveived_purchase_list')}}"
			}, 	
			"columns": [
				{ data: "id" },
				{ data: "date" },
				{ data: "po_no" },
				{ data: "bname" },
				{ data: "mname" },
				{ data: "type" },
				{ data: "part_name" },
				{ data: "colour_name" },
				{ data: "quantity" },
				{ data: "name" },
				{ data: "id" }
			],
			"rowCallback": function (nRow, aData, iDisplayIndex) {
				var oSettings = this.fnSettings ();
				$("td:first", nRow).html(oSettings._iDisplayStart+iDisplayIndex +1);
				var actionBtn = '<a href="javascript:;" onclick="downloadBarcode('+aData.id+')"><i class="fa fa-download text-primary mr-2"></i></a>';
				$("td:last", nRow).html(actionBtn);
				return nRow;
			},
			"order": [[1, 'asc']],
			"columnDefs": [{ orderable: false, "targets": 0 }]
		});      
	});

downloadBarcode=function(id){
	if(id){
		$('#set-id').val(id);
		$('#download-form').submit();
	}
}
</script>
@endsection