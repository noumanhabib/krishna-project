@extends('layouts.layout')
@section('title',$title)
@section('content')
<div id="page-wrapper" >
	 <div class="header"> 
		<h1 class="page-header">
			 <small>Product List</small>
		</h1>
		<ol class="breadcrumb">
			  <li><a href="{{url('/home')}}">Home</a></li>
			  <li class="active">Product List</li>
		</ol>     
	 </div>
	 <div id="page-inner">
		<div class="row">
			<div class="col-md-12">
				<!-- Advanced Tables -->
				<div class="panel panel-default">
					<div class="panel-heading">
						Product List					
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
										<th>Assignd Engineer</th>		
										<th>Status</th>
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


<!-- Edit Video Modal -->
<div id="editModel" class="modal fade" role="dialog">
	<div class="modal-dialog">
    <!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Upload Video</h4>
			</div>
			<div class="modal-body">
				{!! Form::open(['url' =>'upload_elsproduct_video', 'class' => 'form-horizontal', 'id' => 'save-form', 'role' => 'form','files'=>'true']) !!}
					<div class="form-group">
						<div class="col-md-9">
							{!! Form::label('title', 'Choose Video', ['class' => 'control-label']) !!} 
							{!! Form::file('video_file[]',['class' => 	'form-control','required','placeholder'=>'-Select-']) !!}    
						</div>
						<div class="col-sm-3">
							<i class="fa fa-plus-circle fa-3x" style="margin-top:18%" onclick="addMore()"></i>
						</div>	
					</div>
					<div class="form-group add-more-video">
						<div class="col-md-12 " style="padding: 17px;">
							<button type="submit" class="btn btn-success mr-1">Submit <span class="btn-icon-right"><i class="fa fa-check"></i></span></button>
							<button type="button" class="btn btn-danger" data-dismiss="modal">Cancel <span class="btn-icon-right"><i class="fa fa-close"></i></span></button>
						</div>
					</div>
				{!! Form::hidden('els_system_id', '',['id'=>'els_system_id']) !!}
				{!! Form::close() !!}
			</div>
		</div>
      
    </div>
</div>


<!-- Delete Video Model -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="DeleteMenuCategory">
    <div class="modal-dialog modal-dialog-centered modal-min" role="document">
        <div class="modal-content">
            <div class="modal-body text-center">
                <i class="fa fa-ban" style="font-size: 130px; color: #ff8800;"></i>
                <h3>Are you sure want to delete it?</h3>
                {!! Form::open(['url' =>route('delete-upload-video'), 'class' => 'form-horizontal', 'id' => 'video-form', 'role' => 'form', 'onsubmit'=>'return false']) !!}
                    <input type="hidden" name="id" id="video_id" value="">
                    <button type="button" class="btn btn-success light" onclick="deleteDetails()" data-id="">Yes, delete it! <span class="btn-icon-right"><i class="fa fa-check"></i></span></button>
                    <button type="button" class="btn btn-danger light" data-dismiss="modal">Cancel <span class="btn-icon-right"><i class="fa fa-close"></i></span></button>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>

<!-- Delete Video Model -->
<div id="videoModel" class="modal fade" role="dialog">
	<div class="modal-dialog">
    <!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Video List For Delete</h4>
			</div>
			<div id="upload-video" class="modal-body"></div>
		</div>
      
    </div>
</div>

{!! Form::open(['url' =>route('download-upload-video'), 'class' => 'form-horizontal', 'id' => 'video-download', 'role' => 'form']) !!}
{!! Form::hidden('els_system_id', '',['id'=>'system_id']) !!}
{!! Form::close() !!}

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> 
<script type="text/javascript">
$(document).ready(function () {     
	var oTable = $('#dataTableID').DataTable({
		"bDestroy": true,
		'processing': true,
		'serverSide': true,		
		"ajax":{
			"url": "{{url('get-video-upload-product-list')}}"
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
			{ data: "id" },
		],
		"rowCallback": function (nRow, aData, iDisplayIndex) {
			var oSettings = this.fnSettings ();
			$("td:first", nRow).html(oSettings._iDisplayStart+iDisplayIndex +1);
			var actionBtn = '';
			 <?php  if(in_array('24',Helper::addedPermission())){  ?>
			actionBtn += '<a href="javascript:;" onclick="uploadVideo('+aData.id+')"><i class="fa fa-upload text-primary mr-2"></i></a> &nbsp;&nbsp;';
			<?php }  ?>
			if(aData.video_id){	
				// actionBtn += '<a href="javascript:;" onclick="downloadVideo('+aData.id+')"><i class="fa fa-download text-primary mr-2"></i></a> &nbsp;&nbsp;';
					 <?php  if(in_array('24',Helper::addedPermission())){  ?>
				actionBtn += '<a href="javascript:;" onclick="uploadVideoList('+aData.id+')"><i class="fa fa-download -o text-danger mr-2"></i></a>';
				<?php  }   ?>
			}		
			$("td:last", nRow).html(actionBtn);
			return nRow;
		},
		"order": [[1, 'asc']],
		"columnDefs": [{ orderable: false, "targets": 0 }]
	}); 
	
	addMore=function(){
		$.ajax({
			type: "GET",
			url: "{{url(route('add-more-video'))}}",
			dataType:'JSON',
			success: function(d)
			{
				if(d.status){
					$('.add-more-video').before(d.html);	
				}else{
					alert(d.message);
				}
			}
		});
	}
	
	remove=function(e){
		$(e).parent().parent().remove();
	}

	uploadVideo=function(id,status){
		$('#els_system_id').val(id);
		$('#editModel').modal('show');
	}
	
	saveDetails=function(){
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
					oTable.ajax.reload();
					$('.modal').modal('hide');					
				}else{
					alert(d.message);
				}
			}
		});
	}
	
	downloadVideo=function(id){
		$('#system_id').val(id);
		$('#video-download').submit();
	}
	
	deleteDetails=function(){
		var form = $('#video-form');
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
					oTable.ajax.reload();
					$('.modal').modal('hide');					
				}else{
					alert(d.message);
				}
			}
		});
	}
	
	uploadVideoList=function(id){	
		if(id){
			$.ajax({
				type: "POST",
				url: "{{url(route('view-video-list'))}}",
				data: {id:id},
				dataType:'JSON',
				success: function(d)
				{
					if(d.status){
						$('#upload-video').html(d.html);
						$('#videoModel').modal('show');		
					}else{
						alert(d.message);
					}
				}
			});
		}
	}
	
	deleteVideo=function(e,id){	
		if(id){
			if(confirm("Are you sure you want to delete?")){
				$.ajax({
					type: "POST",
					url: "{{url(route('delete-upload-video'))}}",
					data: {id:id},
					dataType:'JSON',
					success: function(d)
					{
						if(d.status){
							$(e).parent().parent().remove();
							oTable.ajax.reload();
						}else{
							alert(d.message);
						}
					}
				});
			}
		}
	}
});
</script>
@endsection