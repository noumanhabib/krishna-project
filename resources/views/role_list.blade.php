@extends('layouts.layout')
@section('title',$title)
@section('content')
@php $i=1;@endphp
<div id="page-wrapper" >
         <div class="header"> 
			<h1 class="page-header">
				 <small>Role List</small>
			</h1>
			<ol class="breadcrumb">
				  <li><a href="{{url('/home')}}">Home</a></li>
				  <li class="active">Role</li>
			</ol>     
         </div>
         <div id="page-inner">
            <div class="row">
                <div class="col-md-12">
                    <!-- Advanced Tables -->
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Role List
							@if(Helper::actionPermission())
                            <a href="{{url('add_role')}}"><button class="btn btn-primary" id="add_category_btn">+ Add Role</button></a> 
							@endif
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover data_tablelist" id="category_tablelist">
                                    <thead>
                                        <tr>
                                            <th>Sr.No</th>
                                            <th>Role Name</th>
											<th>Status</th>
											@if(Helper::actionPermission())
                                            <th>Action</th>
											@endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                         @foreach($data as $row)
                                          <tr>
											<td>{{$i++}}</td>
											<td>{{$row['name']}}</td>
                                         
											<td><label class='switch'><input type='checkbox' {{($row['IsActive']==1)?'checked':''}} id="{{$row['id']}}" value="{{$row['IsActive']}}" onclick='getstatus("{{$row['id']}}")' class='statuscheckbox'><span class='slider round'></span></label></td>
											@if(Helper::actionPermission())
											<td><a href='{{url("edit_role")}}/{{$row['id']}}'><i class='fa fa-edit text-primary mr-2'></i></a>/ <a href='#'  data-toggle='modal' data-target='#DeleteModal' onclick='delete_data("{{$row['id']}}")'><i class='fa fa-trash text-danger mr-2'></i>Delete</a> </td>
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
                    <button type="button" class="btn btn-danger light" data-dismiss="modal">Cancel <span class="btn-icon-right"><i class="fa fa-close"></i></span></button>
                </form>
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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script type="text/javascript">


/*.............................Active and Inactive status Of Category...............................*/
function getstatus(id) {
   
    var id = id;
    var status = $("#" + id).val();
    
    if (status == 0) {
        var update_status = 1;
        var csrfToken = "{{ csrf_token() }}";

        var Url = "{{url('update_role_status')}}";
        $.ajax({
            url: Url,
            headers: {
                "X-CSRF-Token": csrfToken,
            },
            type: "POST",
            data: "id=" +id + "&status=" + update_status,
        }).done(function (response) {
       
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

    var Url = "{{url('update_role_status')}}";
    $.ajax({
        url: Url,
        headers: {
            "X-CSRF-Token": csrfToken,
            
        },
        type: "POST",
        data: "id=" + id + "&status=" + status,
    }).done(function (response) {
    
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

//Edit category...........................................
 function edit_menucategory(id) 
 {  
        var id = id;
        var csrfToken = "{{ csrf_token() }}";

        var Url = "{{url('edit_role')}}";
        $.ajax({
            url: Url,
            headers: {
                "X-CSRF-Token": csrfToken,
               
            },
            type: "POST",
            data: "id=" + id,
        }).done(function (response)
        {
            
            var obj = response.data;
            if (response.code == 200) {
                $("#cat_id").val(obj.id);
                $("#editcategory").val(obj.name);
                
            }
        });
 }
/*--------------------Update category name-------------------------------*/
    $("#editcategorybutton").click(function () 
    {        
                var errorCount = 0;
                if ($("#editcategory").val().trim() == "") {
                    $("#editcategory").focus();
                    $("#editcategoryvalid").text("This field can't be empty.");
                    errorCount++;
                } else {
                    $("#editmenucategoryvalid").text("");
                }

                if (errorCount > 0) {
                    return false;
                }
                var id = $("#cat_id").val();
                var cat_name = $("#editcategory").val();
                var csrfToken = "{{ csrf_token() }}";

                var Url = "{{url('update_role')}}";
                $.ajax({
                    url: Url,
                    headers: {
                        "X-CSRF-Token": csrfToken,
                        
                    },
                    type: "POST",
                    data: "id=" + id + "&category_name=" + cat_name,
                }).done(function (response) {
                    //console.log(response);
                    if (response.code == 200) {
                        $("#editcategorybutton").prop("disabled",true);
                        toastr.success(response.message);
                        setTimeout(function () {
                            window.location.reload();
                        }, 2000);
                    }
                    if (response.code == 502) {
                        toastr.warning(response.message);
                        
                    }
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

        var Url = "{{url('delete_role')}}";
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