@extends('layouts.layout')
@section('title',$title)
@section('content')

<div id="page-wrapper">
	<div class="header"> 
		<h1 class="page-header">
			Dashboard
		</h1>
		<ol class="breadcrumb">
			  <li><a href="javascript:;">Home</a></li>
			  <li class="active">Dashboard</li>
		</ol> 								
	</div>
	<div id="page-inner">
		<!-- /. ROW  -->
		<div class="row">
			<div class="col-md-12">
				
				<div class="panel panel-default">
					<div class="panel-heading d-flex">
						<div>Devices Inventory </div>
						<div class="d-flex">	
							<a href="{{url('download-inventory-report')}}"><button class="btn btn-primary button_right" id="add_category_btn">Export </button></a> 
						</div>
					</div>
					<div class="panel-body">
						<div class="table-responsive" id="dvData">
							<table id="dataTableID_1" class="table table-striped table-bordered table-hover data_tablelist">
								<thead>
									<tr>
										<th>Sr.No</th>
										<th>Model</th>
										<th>In Stock(Qty)</th>
										<th>In Stock(Prexo)</th>
										@foreach($status as $v)
										<th>{{$v->name}}</th>  
										@endforeach	
									</tr>
								</thead>
								<tbody>
								@foreach($data as $key => $d)
								@php $total = 0; @endphp
									<tr>
										<td>{{$key+1}}</td>  
										<td>{{$d->mname}}</td>  
										<td>{{$d->in_stock}}</td>  
										<td>0</td>  
										@foreach($status as $v)
										@php
										$count = Helper::getStatusModelCount($d->id,$v->id);
										// $total += $count;		
										@endphp
											<td>{{$count}}</td>  
										@endforeach	
									</tr>
								@endforeach
								</tbody>
							</table>
						</div>
					</div>
				
				
				</div>
			
			
				<!-- Advanced Tables -->
				<div class="panel panel-default">
					<div class="panel-heading d-flex">
						<div>Device Status Wise</div>
						   <div class="d-flex">
							{!! Form::open(['url' =>'export-device-inventory-report', 'class' => 'form-horizontal', 'role' => 'form']) !!}
							<a href="javascript:;"><button class="btn btn-info" type="submit">Export</button></a> &nbsp;&nbsp;
							{!! Form::close() !!}
						</div>						
					</div>	

					<div class="panel-body">
						<div class="table-responsive" id="dvData">
							<table id="dataTableID" class="table table-striped table-bordered table-hover data_tablelist">
								<thead>
									<tr>
										<th>Sr.No</th>
										<th>Received Date</th>                                          
										<th>Barcode</th>           
										<th>SKU Number</th>           
										<th>Brand</th>
										<th>Model</th>
										<th>Colour</th>                                          
										<th>Current Status</th>  
										<th>RAM</th>
										<th>ROM</th>
										<th>Grade</th>
									</tr>
								</thead>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<!-- Advanced Tables -->
				<div class="panel panel-default">
					<div class="panel-heading">
						Device Backward Tracking					
					</div>

					<div class="panel-body">
					{!! Form::open(['url' =>'download-device-backward-tracking', 'class' => 'form-horizontal', 'role' => 'form', 'id' => 'download-device', 'onsubmit'=>'return false']) !!}
						<div class="form-group">
							<div class="col-sm-2">
							{!! Form::label('title', 'Barcode', ['class' => 'control-label']) !!}
							</div>
							<div class="col-sm-6">
							{!! Form::text('barcode', '', ['class' => 'form-control required', 'id'=>'barcode', 'placeholder'=>'Barcode','onkeypress'=>'enterKeyPress(event)']) !!}
							<span class="help-block text-danger error"></span>
							</div>
							<div class="col-sm-4">
							{{ Form::button('View', ['onclick'=>'viewDeviceBarcode()','class'=>'btn btn-info']) }}
							{{ Form::button('Download', ['onclick'=>'downloadDevicelog()','class'=>'btn btn-info hide','id'=>'download-btn']) }}
							</div>
						</div>
						<div class="form-group">
							<div class="col-sm-12" id="device_backward"></div>
						</div>
					{!! Form::close() !!}
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<!-- Advanced Tables -->
				<div class="panel panel-default">
					<div class="panel-heading">
						Spare Part Backward Tracking					
					</div>

					<div class="panel-body">
					{!! Form::open(['url' =>'download-spare-part-backward-tracking', 'class' => 'form-horizontal', 'role' => 'form', 'id' => 'download-spare-part', 'onsubmit'=>'return false']) !!}
						<div class="form-group">
							<div class="col-sm-2">
							{!! Form::label('title', 'Barcode', ['class' => 'control-label']) !!}
							</div>
							<div class="col-sm-6">
							{!! Form::text('spare_barcode', '', ['class' => 'form-control required', 'id'=>'spare_barcode', 'placeholder'=>'Barcode','onkeypress'=>'enterKeyPressS(event)']) !!}
							<span class="help-block text-danger error"></span>
							</div>
							<div class="col-sm-4">
							{{ Form::button('View', ['onclick'=>'viewSparePartBarcode()','class'=>'btn btn-info']) }}
							{{ Form::button('Download', ['onclick'=>'downloadSparePartlog()','class'=>'btn btn-info hide','id'=>'download-spare-btn']) }}
							</div>
						</div>
						<div class="form-group">
							<div class="col-sm-12" id="spare_part_backward"></div>
						</div>
					{!! Form::close() !!}
					</div>
				</div>
			</div>
		</div>
	
	</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> 
<script type="text/javascript">
$(document).ready(function () { 
	$('#dataTableID_1').DataTable();
	var oTable = $('#dataTableID').DataTable({
		"bDestroy": true,
		'processing': true,
		'serverSide': true,		
		"ajax":{
			"url": "{{url('get-device-inventory-list')}}"
		}, 	
		"columns": [
			{ data: "id" },
			{ data: "resived_date" },
			{ data: "barcode" },
			{ data: "sku_no" },
			{ data: "bname" },
			{ data: "mname" },
			{ data: "colour_name" },
			{ data: "current_status" },
			{ data: "ram" },
			{ data: "rom" },
			{ data: "grade" },
		],
		"rowCallback": function (nRow, aData, iDisplayIndex) {
			var oSettings = this.fnSettings ();
			$("td:first", nRow).html(oSettings._iDisplayStart+iDisplayIndex +1);
			// if(aData.video_id){
				// var actionBtn = '<a href="javascript:;" onclick="downloadVideo('+aData.id+')"><i class="fa fa-download text-primary mr-2"></i></a> &nbsp;&nbsp;';
				// actionBtn += '<a href="javascript:;" onclick="deleteVideo('+aData.id+')"><i class="fa fa-trash-o text-danger mr-2"></i></a>';
			// }else{
				// var actionBtn = '<a href="javascript:;" onclick="uploadVideo('+aData.id+')"><i class="fa fa-upload text-primary mr-2"></i></a>';
			// }			
			// $("td:last", nRow).html(actionBtn);
			return nRow;
		},
		"order": [[1, 'asc']],
		"columnDefs": [{ orderable: false, "targets": 0 }]
	});
	
	viewDeviceBarcode=function(){
		var barcode = $('#barcode').val();
		if(barcode){
			$.ajax({
				type: "POST",
				url: "{{url(route('get-device-backward-tracking'))}}",
				data:{barcode:barcode},
				dataType:'JSON',
				success: function(d){
					if(d.status){	
						$('#device_backward').html(d.html);
						$('#download-btn').removeClass('hide');
					}else{
						alert(d.message)
					}
				}
			});
		}
	}
	
	enterKeyPress=function(e){
		if(e.keyCode === 13){
			viewDeviceBarcode();
		}else{
			$('#device_backward').html('');
			$('#download-btn').addClass('hide');
		}
	}
	
	
	downloadDevicelog=function(){
		$('#download-device').attr('onsubmit','return true');
		$('#download-device').submit();
		$('#download-device').attr('onsubmit','return false');
	}
	
	viewSparePartBarcode=function(){
		var barcode = $('#spare_barcode').val();
		if(barcode){
			$.ajax({
				type: "POST",
				url: "{{url(route('get-spare-part-backward-tracking'))}}",
				data:{barcode:barcode},
				dataType:'JSON',
				success: function(d){
					if(d.status){	
						$('#spare_part_backward').html(d.html);
						$('#download-spare-btn').removeClass('hide');
					}else{
						alert(d.message)
					}
				}
			});
		}
	}
	
	enterKeyPressS=function(e){
		if(e.keyCode === 13){
			viewSparePartBarcode();
		}else{
			$('#spare_part_backward').html('');
			$('#download-spare-btn').addClass('hide');
		}
	}
	
	downloadSparePartlog=function(){
		$('#download-spare-part').attr('onsubmit','return true');
		$('#download-spare-part').submit();
		$('#download-spare-part').attr('onsubmit','return false');
	}
});
</script>	
@endsection
