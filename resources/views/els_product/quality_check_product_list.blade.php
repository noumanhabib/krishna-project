@extends('layouts.layout')
@section('title',$title)
@section('content')
<div id="page-wrapper" >
	 <div class="header"> 
		<h1 class="page-header">
			 <small>Quality Check Product List</small>
		</h1>
		<ol class="breadcrumb">
			  <li><a href="{{url('/home')}}">Home</a></li>
			  <li class="active"> Quality Check Product List</li>
		</ol>     
	 </div>
	 <div id="page-inner">
		<div class="row">
			<div class="col-md-12">
				<!-- Advanced Tables -->
				<div class="panel panel-default">
					<div class="panel-heading">
						Quality Check Product List
						@if(Helper::actionPermission())
						 <?php  if(in_array('32',Helper::addedPermission())){  ?>
						<a href="{{url('allocate_check_product')}}"><button class="btn btn-primary button_right" id="add_category_btn">+ Allocate Check Product</button></a> 
						<?php  }  ?>
						@endif
					</div>

					<div class="panel-body">
						<div class="table-responsive" id="dvData">
							<table id="dataTableID" class="table table-striped table-bordered table-hover data_tablelist">
								<thead>
									<tr>
										<th>Sr.No</th>
										<th>UIN</th>                                          
										<th>IMEI 1</th>           
										<th>IMEI 2</th>           
										<th>Brand</th>
										<th>Model</th>
										<th>Colour</th>
										<th>QC Engineer</th>	
										<th>Status</th>
										<th>Remark</th>
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


<!-- Edit Brand Modal -->
<div id="editModel" class="modal fade" role="dialog">
	<div class="modal-dialog">
    <!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Change Status</h4>
			</div>
		<div class="modal-body">
			{!! Form::open(['url' =>'save_qc_status', 'class' => 'form-horizontal', 'id' => 'save-form', 'role' => 'form', 'onsubmit'=>'return false']) !!}
				<div class="form-group">
					<div class="col-md-12">
						{!! Form::label('title', 'Select Status', ['class' => 'control-label']) !!} 
						{!! Form::select('status',$status, '',['class' => 	'form-control','required', 'id'=>'status','placeholder'=>'-Select-']) !!}  
						{!! Form::hidden('id', '',['id'=>'id']) !!}  
					</div>	
					<div class="col-md-12">
						{!! Form::label('title', 'Remark', ['class' => 'control-label']) !!} 
						{!! Form::text('remark','',['class' => 	'form-control', 'id'=>'remark','placeholder'=>'Remark']) !!}  
					</div>				
                    <div class="col-md-12" style="padding: 17px;">
                        <button type="button" onclick="saveStatus()" class="btn btn-success mr-1">Submit <span class="btn-icon-right"><i class="fa fa-check"></i></span></button>
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
	var oTable = $('#dataTableID').DataTable({
		"bDestroy": true,
		'processing': true,
		'serverSide': true,		
		"ajax":{
			"url": "{{url('get-quality-check-product-list')}}"
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
			{ data: "status" },
			{ data: "remark" },
			{ data: "id" },
		],
		"rowCallback": function (nRow, aData, iDisplayIndex) {
			var oSettings = this.fnSettings ();
			$("td:first", nRow).html(oSettings._iDisplayStart+iDisplayIndex +1);
			<?php  if(in_array('32',Helper::editPermission())){  ?>
			var actionBtn = '<a href="javascript:;" onclick="changeStatus('+aData.allocated_id+','+aData.status_id+')"><i class="fa fa-edit text-primary mr-2"></i></a> &nbsp;&nbsp;';
			<?php  }  else {  ?>
			var actionBtn = '';
			<?php  }  ?>
			$("td:eq(10)", nRow).html(actionBtn);
			return nRow;
		},
		"order": [[1, 'asc']],
		"columnDefs": [{ orderable: false, "targets": 0 }]
	}); 
	@if(!Helper::actionPermission())
	//oTable.column(10).visible(false);
	@endif
	changeStatus=function(id,status){
		$('#id').val(id);
		$('#status').val(status);
		$('#editModel').modal('show');
	}
	
	saveStatus=function(){
		var form = $('#save-form');
		var url = form.attr('action');
		$.ajax({
			type: "POST",
			url: url,
			data: form.serialize(),
			dataType:'JSON',
			success: function(d)
			{
				if(d.status){
					oTable.ajax.reload();
					$('#editModel').modal('hide');					
				}else{
					alert(d.message);
				}
			}
		});
	}
});
</script>
@endsection