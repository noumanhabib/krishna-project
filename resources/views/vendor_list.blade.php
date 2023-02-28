@extends('layouts.layout')
@section('title',$title)
@section('content')
<div id="page-wrapper" >
         <div class="header"> 
                        <h1 class="page-header">
                             <small>Vendor List</small>
                        </h1>
                        <ol class="breadcrumb">
                              <li><a href="{{url('/home')}}">Home</a></li>
                              <li class="active">Vendor</li>
                        </ol>     
         </div>
         <div id="page-inner">
            <div class="row">
                <div class="col-md-12">
                    <!-- Advanced Tables -->
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Vendor List
							@if(Helper::actionPermission())
                            <a href="{{url('add_vendor')}}"><button class="btn btn-primary" id="add_btn">+ Add Vendor</button></a> 
							@endif
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="vendor_tablelist">
                                    <thead>
                                        <tr>
                                            <th>Sr.No</th>
                                            <th>Vendor Name</th>
                                            <th>Vendor Address</th>
                                            <th>City</th>
                                            <th>State</th>
                                            <th>Country</th>
                                            <th>Pincode</th>
                                            <th>Bank Name</th>
                                            <th>Account No</th>
                                            <th>IFS Code</th>
                                            <th>Payment Mode</th>
                                            <th>Status</th>
											@if(Helper::actionPermission())
                                            <th>Action</th>
											@endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @if(!$data->isEmpty())  
										@foreach($data as $key => $d)
										<tr>
											<td>{{$key+1}}</td>
											<td>{{$d->vname}}</td>
											<td>{{$d->address}}</td>
											<td>{{$d->city}}</td>
											<td>{{$d->state}}</td>
											<td>{{$d->country}}</td>
											<td>{{$d->pincode}}</td>
											<td>{{$d->bank_name}}</td>
											<td>{{$d->account_number}}</td>
											<td>{{$d->ifs_code}}</td>
											<td>{{$d->payment_mode}}</td>
											<td>
											@if($d->status)
											<label class="switch"><input type="checkbox" checked id="{{$d->id}}" value="{{$d->status}}" onclick="getstatus({{$d->id}})" class="statuscheckbox"><span class="slider round"></span></label>
											@else
											<label class="switch"><input type="checkbox" id="{{$d->id}}" value="{{$d->status}}" onclick="getstatus({{$d->id}})" class="statuscheckbox"><span class="slider round"></span></label>
											@endif
											</td>
											@if(Helper::actionPermission())
											<td><a href="{{url('edit_vendor')}}/{{$d->id}}"><i class="fa fa-edit text-primary mr-2"></i></a> / <a href="javascript:;" data-toggle="modal" data-target="#DeleteModal" onclick="delete_vendor({{$d->id}})"><span class="glyphicon glyphicon-trash"></span></a></td>
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
<!-- Inactive Category Model -->
<div class="modal fade" id="StatusCategory" tabindex="-1" role="dialog" aria-labelledby="StatusCategory">
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


<!-- Delete Category Model -->
<div class="modal fade" id="DeleteModal" tabindex="-1" role="dialog" aria-labelledby="DeleteMenuCategory">
    <div class="modal-dialog modal-dialog-centered modal-min" role="document">
        <div class="modal-content">
            <div class="modal-body text-center">
                <i class="fa fa-ban" style="font-size: 130px; color: #ff8800;"></i>
                <h3>Are you sure want to delete it?</h3>
                <form method="post" style="padding: 24px;">
                    @csrf
                    <input type="hidden" name="delete_vendor_id" id="delete_vendor_id" value="">
                    <button type="button" class="btn btn-success light" id="delete_btn" data-id="">Yes, delete it! <span class="btn-icon-right"><i class="fa fa-check"></i></span></button>
                    <button type="button" class="btn btn-danger light" data-dismiss="modal">Cancel <span class="btn-icon-right"><i class="fa fa-close"></i></span></button>
                </form>
            </div>
        </div>
    </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script type="text/javascript">

$(document).ready(function () {        
	$("#vendor_tablelist").DataTable();	
});
    /*cancel inavtivate model...............................*/
$("#cancel").on('click',function(){
  window.location.reload();
});

/*.............................Active and Inactive status Of Category...............................*/
function getstatus(id) {
   
    var id = id;
    var status = $("#" + id).val();
    if (status == 0) {
        var update_status = 1;
        var csrfToken = "{{ csrf_token() }}";

        var Url = "{{url('update_vendor_status')}}";
        $.ajax({
            url: Url,
            headers: {
                "X-CSRF-Token": csrfToken,
            },
            type: "POST",
            data: "vendor_id=" +id + "&status=" + update_status,
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
    else if (status == 1) {
        $("#StatusCategory").modal("show");
        $("#inactive_btn").attr("data-id", id);
    }
}
   
$("#inactive_btn").on("click", function () {
    
    var id = $(this).data("id");
    var status = 0;
    var csrfToken = "{{ csrf_token() }}";

    var Url = "{{url('update_vendor_status')}}";
    $.ajax({
        url: Url,
        headers: {
            "X-CSRF-Token": csrfToken,
            
        },
        type: "POST",
        data: "vendor_id=" + id + "&status=" + status,
    }).done(function (response) {
        //console.log(response);
        if (response.code == 200) {
            $("#StatusCategory").modal("hide");
            toastr.success(response.message);
            $("#inactive_btn").prop("disabled", true);
            setTimeout(function () {
                window.location.reload();
            }, 1000);
        }
    });
});


/*..............Delete Category.........................................*/
function delete_vendor(id)
{
	$("#delete_vendor_id").val(id);
}
$("#delete_btn").on("click", function () {
	toastr.options = {
		progressBar: "true",
		positionClass: "toast-top-right",
	};
   
	var id = $("#delete_vendor_id").val();
	var csrfToken = "{{ csrf_token() }}";

	var Url = "{{url('delete_vendor')}}";
	$.ajax({
		url: Url,
		headers: {
			"X-CSRF-Token": csrfToken,
		   
		},
		type: "POST",
		data: "id=" + id,
	}).done(function (response) {
		
		if (response.code == 200) {
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