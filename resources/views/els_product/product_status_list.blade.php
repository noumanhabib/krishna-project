@extends('layouts.layout')
@section('title',$title)
@section('content')
<div id="page-wrapper" >
	 <div class="header"> 
		<h1 class="page-header">
			 <small>Product Status List</small>
		</h1>
		<ol class="breadcrumb">
			  <li><a href="{{url('/home')}}">Home</a></li>
			  <li class="active">Product Satus</li>
		</ol>     
	 </div>
	 <div id="page-inner">
		<div class="row">
			<div class="col-md-12">
				<!-- Advanced Tables -->
				<div class="panel panel-default">
					<div class="panel-heading">
						Product Status List
						@if(Helper::actionPermission())
						<a href="{{url('add-product-status')}}"><button class="btn btn-primary button_right" id="add_category_btn">+ Add Status </button></a> 
						@endif
					</div>

					<div class="panel-body">
						<div class="table-responsive" id="dvData">
							<table id="dataTableID" class="table table-striped table-bordered table-hover data_tablelist">
								<thead>
									<tr>
										<th>Sr.No</th>
										<th>Name</th> 
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


<!-- Inactive Brand Model -->
<div class="modal fade" id="StatusBrand" tabindex="-1" role="dialog" aria-labelledby="StatusBrand">
    <div class="modal-dialog modal-dialog-centered modal-min" role="document">
        <div class="modal-content">
            <div class="modal-body text-center">
                <i class="fa fa-ban" style="font-size: 130px; color: #ff8800;"></i>
                <h3>Are you sure want to Inactive it?</h3>
                
                <form method="post" style="padding: 24px;">
                    <input type="hidden" name="delete_id" id="delete_id" value="">
                    <button type="button" class="btn btn-success light" id="inactive_btn" data-id="">Yes, Inactive it! <span class="btn-icon-right"><i class="fa fa-check"></i></span></button>
                    <button type="button" class="btn btn-danger light" data-dismiss="modal" id="cancel">Cancel <span class="btn-icon-right"><i class="fa fa-close"></i></span></button>
                </form>
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
			"url": "{{url('get-els-product-status-list')}}"
		}, 	
		"columns": [
			{ data: "id" },
			{ data: "name" },
			{ data: "status" },
			{ data: "id" },
		],
		"rowCallback": function (nRow, aData, iDisplayIndex) {
			var oSettings = this.fnSettings ();
			$("td:first", nRow).html(oSettings._iDisplayStart+iDisplayIndex +1);
			if(aData.status=='1'){
				var status = '<label class="switch"><input type="checkbox" checked="" id="'+aData.id+'" value="0" onclick="setStatus(this)" class="statuscheckbox"><span class="slider round"></span></label>';
			}else{
				var status = '<label class="switch"><input type="checkbox" id="'+aData.id+'" value="1" onclick="setStatus(this)" class="statuscheckbox"><span class="slider round"></span></label>';
			}
			$('td:eq(2)', nRow).html(status);			
			var actionBtn = '<a href="{{url('edit-product-status')}}/'+aData.id+'" ><i class="fa fa-edit text-primary mr-2"></i></a> &nbsp;&nbsp;';
			$("td:eq(3)", nRow).html(actionBtn);
			return nRow;
		},
		"order": [[1, 'asc']],
		"columnDefs": [{ orderable: false, "targets": 0 }]
	}); 
	@if(!Helper::actionPermission())
	oTable.column(3).visible(false);
	@endif

	/*.............................Active and Inactive status Of Brand...............................*/
	setStatus=function(e) {
		var id = $(e).attr('id');
		var status = $(e).val();
		
		if (status == 1) {
			var csrfToken = "{{ csrf_token() }}";
			var Url="{{url('update_els_product_status')}}";
			$.ajax({
				url: Url,
				headers: {
					"X-CSRF-Token": csrfToken,
				},
				type: "POST",
				data: "id=" +id + "&status=" + status,
			}).done(function (response) {
				//console.log(response);
				if (response.code == 200) {
					toastr.success(response.message);
					$(".statuscheckbox").prop("disabled", true);
					setTimeout(function () {
						window.location.reload();
					}, 1000);
				}
			});
		} 
		else if (status == 0) {
			$("#StatusBrand").modal("show");
			$("#inactive_btn").attr("data-id", id);
		}
	}
	   
	$("#inactive_btn").on("click", function () {
		
		var id = $(this).data("id");
		var status = 0;
		var csrfToken = "{{ csrf_token() }}";
		var Url="{{url('update_els_product_status')}}";
		$.ajax({
			url: Url,
			headers: {
				"X-CSRF-Token": csrfToken,				
			},
			type: "POST",
			data: "id=" + id + "&status=" + status,
		}).done(function (response) {
			//console.log(response);
			if (response.code == 200) {
				$("#StatusBrand").modal("hide");
				toastr.success(response.message);
				$("#inactive_btn").prop("disabled", true);
				setTimeout(function () {
					window.location.reload();
				}, 1000);
			}
		});
	});

});
</script>
@endsection