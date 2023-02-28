@extends('layouts.layout')
@section('title',$title)
@section('content')
<div id="page-wrapper" >
	 <div class="header"> 
					<h1 class="page-header">
						 <small>Stock In Parts PIN List</small>
					</h1>
					<ol class="breadcrumb">
						  <li><a href="{{url('/home')}}">Home</a></li>
						  <li class="active">Stock In Parts PIN List</li>
					</ol>     
	 </div>
	 <div id="page-inner">
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-default">
					<div class="panel-heading">Add PIN</div> 						  
					<div class="panel-body"> 
						<div class="form-group">
							<div class="col-sm-2">
							{!! Form::label('title', 'PIN', ['class' => 'control-label']) !!}
							</div>
							<div class="col-sm-6">
							{!! Form::text('barcode', '', ['class' => 'form-control required', 'id'=>'barcode', 'placeholder'=>'Barcode','onkeypress'=>'enterKeyPress(event)']) !!}
							<span class="help-block text-danger error"></span>
							</div>
							<div class="col-sm-2">
							{{ Form::submit('Add', ['onclick'=>'addBarcode()','class'=>'btn btn-info']) }}
							</div>
						</div>
					</div>
				</div>
			</div>						
		</div>
		<div class="row">
			<div class="col-md-12">
				<!-- Advanced Tables -->
				<div class="panel panel-default">
					<div class="panel-heading d-flex">
						<div>Stock In Parts PIN List</div>
						<div class="d-flex">	
						   <a href="javascript:;"><button class="btn btn-info" onclick="importData()">Import</button></a> &nbsp;&nbsp;					   
						   {!! Form::open(['url' =>'export-spart-part-barcode-report', 'class' => 'form-horizontal', 'role' => 'form']) !!}
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
										<th>Brand</th> 
										<th>Model</th> 
										<th>Colour</th> 
										<th>SKU No</th> 
										<th>Parts Name</th>
										<th>PIN</th>   
										<th>Price</th>   
										<th>IQC Engineer Name</th>      
										<th>IQC Pass/Failed</th>
										<th>Status</th>
										<th>Received Date</th>
										<th>Remark</th>
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
			"url": "{{url('fetch_stock_in_parts_list')}}"
		}, 	
		"columns": [
			{ data: "id" },
			{ data: "po_no" },
			{ data: "bname" },
			{ data: "mname" },
			{ data: "colour_name" },
			{ data: "sku_no" },
			{ data: "part_name" },
			{ data: "barcode" },
			{ data: "price" },
			{ data: "engineer_name" },
			{ data: "iqc_status" },
			{ data: "current_status" },
			{ data: "received_date" },
			{ data: "remark" },
		],
		"rowCallback": function (nRow, aData, iDisplayIndex) {
			var oSettings = this.fnSettings ();
			$("td:first", nRow).html(oSettings._iDisplayStart+iDisplayIndex +1);
			var actionBtn = '';
			if(aData.status=='2'){
				if(aData.iqc_status == 1){
					actionBtn += '<label class="switch"><input type="checkbox" value="0" onclick="setStatus('+aData.id+',0)" class="statuscheckbox" checked=""><span class="slider round"></span></label>';
				}else{
					actionBtn += '<label class="switch"><input type="checkbox" value="1" onclick="setStatus('+aData.id+',1)" class="statuscheckbox"><span class="slider round"></span></label>';
				}
			}
			
			$("td:eq(10)", nRow).html(actionBtn);
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
@endsection