@extends('layouts.layout')
@section('title',$title)
@section('content')
<div id="page-wrapper" >
         <div class="header"> 
			<h1 class="page-header">
				<small>ELS Product Warranty List</small>
			</h1>
			<ol class="breadcrumb">
				<li><a href="{{url('/home')}}">Home</a></li>
				<li class="active">ELS Product Warranty</li>
			</ol>     
         </div>
         <div id="page-inner">
            <div class="row">
                <div class="col-md-12">
                    <!-- Advanced Tables -->
                    <div class="panel panel-default">
                        <div class="panel-heading d-flex">
                           <div>ELS Product Warranty List</div>
                           <div class="d-flex">
						    @if(Helper::actionPermission())
						    <?php  if(in_array('47',Helper::addedPermission())){  ?>
							<a href="javascript:;"><button class="btn btn-info" onclick="importData()">Import</button></a> &nbsp;&nbsp;
							<?php  }   ?>
							@endif
							{!! Form::open(['url' =>'export-warranty-report', 'class' => 'form-horizontal', 'role' => 'form']) !!}
							<a href="javascript:;"><button class="btn btn-info" type="submit">Export</button></a> &nbsp;&nbsp;
							{!! Form::close() !!}
							@if(Helper::actionPermission())
								    <?php  if(in_array('47',Helper::addedPermission())){  ?>
                            <a href="{{url('add_product_warranty')}}"><button class="btn btn-primary" id="add_category_btn">+ Add ELS Product Warranty</button></a> 
                            	<?php  }   ?>
							@endif
                          </div>
							
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover data_tablelist">
                                    <thead>
                                        <tr>
                                            <th>Sr.No</th>
                                            <th>UIN</th>
                                            <th>ELS Brand</th>
                                            <th>ELS Model</th>
                                            <th>Colour</th>
                                            <th>IMEI 1</th>
                                            <th>IMEI 2</th>
                                            <th>RAM</th>
                                            <th>ROM</th>
                                            <th>Grade</th>     
                                            <th>Start Date</th>       
                                            <th>Expiry Date</th> 
                                            <th>Left Duration</th>
                                            <th>Remark</th> 
											@if(Helper::actionPermission())
                                            <th>Action</th>
											@endif
                                        </tr>
                                    </thead>
                                     <tbody>
                                        @php $i=1;@endphp
                                       @if(!empty($data))
                                           @foreach ($data as $row)
										    @php $left_time = '';
											$date1=date_create(date('Y-m-d'));
											$date2=date_create($row['end_date']);
											$diff=date_diff($date1,$date2);
											if($diff->y){
												if($diff->y==1){
													$left_time .= $diff->y.' Year ';
												}else{
													$left_time .= $diff->y.' Years ';
												}											
											}
											if($diff->m){
												if($diff->m==1){
													$left_time .= $diff->m.' Month ';
												}else{
													$left_time .= $diff->m.' Months ';
												}											
											}
											if($diff->d){
												if($diff->d==1){
													$left_time .= $diff->d.' Day ';
												}else{
													$left_time .= $diff->d.' Days ';
												}											
											}
											@endphp
                                            <tr>
                                                <td>{{$i++}}</td>
                                                <td>{{$row['barcode']}}</td>
                                                <td>{{$row['bname']}}</td>
                                                <td>{{$row['mname']}}</td>
                                                <td>{{$row['colour_name']}}</td>
                                                <td>{{$row['imei_1']}}</td>
                                                <td>{{$row['imei_2']}}</td>
                                                <td>{{$row['ram']}}</td>
                                                <td>{{$row['rom']}}</td>
                                                <td>{{$row['grade']}}</td>
                                                <td><?php  if($row['start_date']!=''){  ?>{{date('m/d/Y',strtotime($row['start_date']))}}<?php  }   ?></td>
                                                <td><?php  if($row['start_date']!=''){  ?>{{date('m/d/Y',strtotime($row['end_date']))}}<?php  }   ?></td>
                                                <td>{{$left_time}}</td>
                                                <td>{{$row['remark']}}</td>
												@if(Helper::actionPermission())
                                                <td>
                                                 <?php  if(in_array('47',Helper::editPermission())){  ?>    <a href='{{url("edit_product_warranty")}}/{{$row['id']}}'><i class='fa fa-edit text-danger mr-2'></i></a><?Php  }   ?> 
                                                /   <?php  if(in_array('47',Helper::deletedPermission())){  ?><a href='#'  data-toggle='modal' data-target='#DeleteModal' onclick='delete_data("{{$row['id']}}")'><i class='fa fa-trash text-danger mr-2'></i>Delete</a>
                                                <?php  }   ?>
                                                </td>
												@endif
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


<!-- Delete Category Model -->
<div class="modal fade" id="DeleteModal" tabindex="-1" role="dialog" aria-labelledby="DeleteModal">
    <div class="modal-dialog modal-dialog-centered modal-min" role="document">
        <div class="modal-content">
            <div class="modal-body text-center">
                <i class="fa fa-ban" style="font-size: 130px; color: #ff8800;"></i>
                <h3>Are you sure want to delete it?</h3>
                <form method="post" style="padding: 24px;">
                    @csrf
                    <input type="hidden" name="delete_id" id="delete_id" value="">
                    <button type="button" class="btn btn-success light" id="delete_btn" data-id="">Yes, delete it! <span class="btn-icon-right"><i class="fa fa-check"></i></span></button>
                    <button type="button" class="btn btn-danger light" data-dismiss="modal">Cancel <span class="btn-icon-right"><i class="fa fa-close"></i></span></button>
                </form>
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
				<h4 class="modal-title">Import Request Order</h4>
			</div>
		<div class="modal-body">
			{!! Form::open(['url' =>'upload-bulk-warranty', 'class' => 'form-horizontal', 'id' => 'save-form', 'role' => 'form', 'onsubmit'=>'return false','files'=>'true']) !!}
				<div class="form-group">
					<div class="col-md-12">
						{!! Form::label('title', 'Choose Product File', ['class' => 'control-label']) !!} 
						{!! Form::file('file_csv',['class' => 	'form-control','required', 'id'=>'file_csv','placeholder'=>'-Select-']) !!}  
					</div>					
                    <div class="col-md-12" style="padding: 17px;">
                        <button type="button" onclick="saveData()" class="btn btn-success mr-1">Submit <span class="btn-icon-right"><i class="fa fa-check"></i></span></button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel <span class="btn-icon-right"><i class="fa fa-close"></i></span></button>
						<a href="{{url('assets\files\els_product_warranty.csv')}}" class="btn btn-info light" download>Download Sample File</span></a>
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

saveData=function(){
	var form = $('#save-form');
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
				location.reload();
			}else{
				alert(d.message);
			}
		}
	});
}

function delete_data(id)
{
    $("#delete_id").val(id);
}
$("#delete_btn").on("click", function () 
{
    toastr.options = {
        progressBar: "true",
        positionClass: "toast-top-right",
    };
   
        var id = $("#delete_id").val();
        var csrfToken = "{{ csrf_token() }}";

        var Url = "{{url('delete_elsproduct_warranty')}}";
        $.ajax({
            url: Url,
            headers: {
                "X-CSRF-Token": csrfToken,
            },
            type: "POST",
            data: "id=" + id,
        }).done(function (response) {
            
            if (response.code == 200) 
            {
                $("#delete_btn").prop("disabled",true);
                toastr.success(response.message);
                setTimeout(function () {
                    window.location.reload();
                }, 2000);
            }
        });
    });

</script>
@endsection