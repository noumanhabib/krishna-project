@extends('layouts.layout')
@section('title',$title)
@section('content')
<div id="page-wrapper" >
	<div class="header"> 
		<h1 class="page-header">
			 <small>Stock Out Product List</small>
		</h1>
		<ol class="breadcrumb">
			  <li><a href="{{url('/home')}}">Home</a></li>
			  <li class="active">Stock Out Product List</li>
		</ol>     
	</div>
	<div id="page-inner">
		<div class="row">
			<div class="col-md-12">
				<!-- Advanced Tables -->
				<div class="panel panel-default">
					<div class="panel-heading d-flex">
					   <div>Stock Out Product List</div>
					   <div class="d-flex">					    
						<a href="javascript:;"><button class="btn btn-info" onclick="importData()">Import Status</button></a> &nbsp;&nbsp;
						{!! Form::open(['url' =>'export-stock-out-product-report', 'class' => 'form-horizontal', 'role' => 'form']) !!}
						<a href="javascript:;"><button class="btn btn-info" type="submit">Export</button></a> &nbsp;&nbsp;
						{!! Form::close() !!}
					  </div>							
					</div>
					
					<div class="panel-body">
						<div class="table-responsive">
							<table class="table table-striped table-bordered table-hover data_tablelist">
								<thead>
									<tr>
										<th>Sr.No</th>
										<th>Date</th>
										<th>ELS Brand</th>
										<th>ELS Model</th>
										<th>Color</th>
										<th>UIN</th>
										<th>GRN No.</th>
										<th>IMEI 1</th>
										<th>IMEI 2</th>
										<th>RAM</th>
										<th>ROM</th>
										<th>Incoming Grade</th>
										@if(\App\Models\RoleModel::find(\Auth::user()->role)->role_name == 'admin')
										<th>Cost</th> 
										@endif
										<th>Remark</th>    
										<th>In Vendor Name</th>    
									   <!-- <th>Quantity</th> -->    
										<th>Status</th>           
										<th>Sub Status</th>
										<th>Out Vendor Name</th>  
									</tr>
								</thead>
								 <tbody>
									@php $i=1;@endphp
								   @if(!empty($data))
									   @foreach ($data as $row)
										<tr>
											<td>{{$i++}}</td>
											<td>{{date('d-m-Y',strtotime($row['resived_date']))}}</td>
											<td>{{$row['bname']}}</td>
											<td>{{$row['mname']}}</td>
											<td>{{$row['colour_name']}}</td>
											<td>{{$row['barcode']}}</td>
											<td>{{$row['grn_no']}}</td>
											<td>{{$row['imei_1']}}</td>
											<td>{{$row['imei_2']}}</td>
											<td>{{$row['ram']}}</td>
											<td>{{$row['rom']}}</td>
											<td>{{$row['grade']}}</td>
											@if(\App\Models\RoleModel::find(\Auth::user()->role)->role_name == 'admin')
											<td>{{$row['mrp']}}</td>
											@endif
											<td>{{$row['remark']}}</td>
											<td>{{$row['created_by']}}</td>
											<td>{{$row['sub_status']}} @if($row['status_date']) ({{date('d-m-Y',strtotime($row['status_date']))}}) @endif</td>
											<td>
											@php $in_stock = Helper::getActiveInwardDate($row['id']); @endphp
											@if($in_stock)
											Not assign
											@elseif($row['status'])
											{{$row['status']}}
											@else
											Not assign
											@endif
											</td>
											<td>{{$row['out_vendor']}}</td>
										</tr>
									  @endforeach
								   @endif
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
<script type="text/javascript">
importData=function(){
	$('#importModel').modal('show');
}
</script>
@endsection