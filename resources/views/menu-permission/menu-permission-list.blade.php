@extends('layouts.layout')
@section('title',$title)
@section('content')
@php $i=1;@endphp
<div id="page-wrapper" >
	 <div class="header"> 
		<h1 class="page-header">
			 <small>Menu List</small>
		</h1>
		<ol class="breadcrumb">
			  <li><a href="{{url('/home')}}">Home</a></li>
			  <li class="active">Menu Permission</li>
		</ol>     
	 </div>
	 <div id="page-inner">
		<div class="row">
			<div class="col-md-12">
				<!-- Advanced Tables -->
				<div class="panel panel-default">
					<div class="panel-heading">
					   Menu Permission Menu List
					   @if(Helper::actionPermission())
						<a href="{{url('menu_permission_form')}}"><button class="btn btn-primary" id="add_category_btn">+ Add Permission</button></a> 
						@endif
					</div>
					<div class="panel-body">
						<div class="table-responsive">
							<table class="table table-striped table-bordered table-hover data_tablelist">
								<thead>
									<tr>
										<th>Sr.No</th>
										<th>Role</th>                                 
										@if(Helper::actionPermission())
										<th>Action</th>
										@endif
									</tr>
								</thead>
								<tbody>
								   @foreach($list as $row)
									  <tr>
										<td>{{$i++}}</td>
										  <td>{{$row['name']}}</td>
										 @if(Helper::actionPermission())  
										<td><a href='{{url("edit_menu_permission")}}/{{$row['role_id']}}'><i class='fa fa-edit text-primary mr-2'></i></a> </td>
										@endif
									  </tr>
								   @endforeach
								</tbody>
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


/*.............................Active and Inactive status Of Category...............................*/
function getstatus(id) {
   
    var id = id;
    var status = $("#"+id).val();
    //alert(status);
  
    if (status == 0) {
        var update_status = 1;
        var csrfToken = "{{ csrf_token() }}";

        var Url = "{{url('update_menu_status')}}";
        $.ajax({
            url: Url,
            headers: {
                "X-CSRF-Token": csrfToken,
            },
            type: "POST",
            data: "id=" +id + "&status=" + update_status,
        }).done(function (response) {
       
          
               
                toastr.success(response.message);
                $(".statuscheckbox").prop("disabled", true);
                setTimeout(function () {
                    window.location.reload();
                }, 1000);
            
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

    var Url = "{{url('update_menu_status')}}";
    $.ajax({
        url: Url,
        headers: {
            "X-CSRF-Token": csrfToken,
            
        },
        type: "POST",
        data: "id=" + id + "&status=" + status,
    }).done(function (response) {
    
     
            $("#StatusCategory").modal("hide");
            toastr.success(response.message);
            $("#inactive_btn").prop("disabled", true);
            setTimeout(function () {
                window.location.reload();
            }, 1000);
        
    });
});

/*..............Delete Category.........................................*/
 function delete_data(id)
    {
        $("#delete_id").val(id);
    }
$("#delete_btn").on("click", function () {
        toastr.options = {
            progressBar: "true",
            positionClass: "toast-top-right",
        };
       
        var id = $("#delete_id").val();
        var csrfToken = "{{ csrf_token() }}";

        var Url = "{{url('delete_menu')}}";
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