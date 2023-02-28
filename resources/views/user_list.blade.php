@extends('layouts.layout')
@section('title',$title)
@section('content')
<div id="page-wrapper">
     <div class="header"> 
        <h1 class="page-header">
             <small>User List</small>
        </h1>
        <ol class="breadcrumb">
              <li><a href="{{url('/home')}}">Home</a></li>
              <li class="active">User</li>
        </ol>     
     </div>
         <div id="page-inner">
            <div class="row">
                <div class="col-md-12">
                    <!-- Advanced Tables -->
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            User List
							@if(Helper::actionPermission())
                            <a href="{{url('add_user')}}"><button class="btn btn-primary" id="add_category_btn">+ Add User</button></a> 
							@endif
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="category_tablelist">
                                    <thead>
                                        <tr>
                                            <th>Sr.No</th>
                                            <th>User Name</th>
                                            <th>Email</th>
                                            <th>Mobile</th>
                                            <th>Role</th>                                         
                                            <th>Status</th>
											@if(Helper::actionPermission())
                                            <th>Action</th>
											@endif
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
<div class="modal fade" id="DeleteCategoryModal" tabindex="-1" role="dialog" aria-labelledby="DeleteMenuCategory">
    <div class="modal-dialog modal-dialog-centered modal-min" role="document">
        <div class="modal-content">
            <div class="modal-body text-center">
                <i class="fa fa-ban" style="font-size: 130px; color: #ff8800;"></i>
                <h3>Are you sure want to delete it?</h3>
                <form method="post" style="padding: 24px;">
                    @csrf
                    <input type="hidden" name="delete_category_id" id="delete_category_id" value="">
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
        
            fetchRecords();
            
        });
/*alpha validation..........*/
$("#editcategory").keypress(function (e) 
   {
            var keyCode = e.keyCode || e.which;
 
            $("#editcategoryvalid").html("");
 
            //Regex for Valid Characters i.e. Alphabets.
            var regex = /^[A-Za-z\-\s]+$/;
 
            //Validate TextBox value against the Regex.
            var isValid = regex.test(String.fromCharCode(keyCode));
            if (!isValid) {
                $("#editcategoryvalid").html("Only Alphabets are allowed.");
            }
 
            return isValid;
  });
/*get categories list...................................*/


function convert(str) {
  var date = new Date(str),
    mnth = ("0" + (date.getMonth() + 1)).slice(-2),
    day = ("0" + date.getDate()).slice(-2);
  return [date.getFullYear(), mnth, day].join("-");
}
    function fetchRecords() 
    {
       
        var csrfToken = "{{ csrf_token() }}";
        var Url = "{{url('fetch_user_list')}}";
        $.ajax({
            url: Url,
            headers: {
               'X-CSRF-Token': csrfToken,
            },
            type: "GET",
            
        }).done(function (response) {

            var len = 0;
            if (response["data"] != null) {
                len = response["data"].length;
            }
            var num=1;
            if(len > 0) 
            {
                

                for(var i = 0; i < len; i++) 
                {
                     
					var num_increment=num++;  

					var id = response["data"][i].id;
					var name=response["data"][i].name;
					var email=response["data"][i].email;
					var mobile_number=response["data"][i].mobile_number;
					var role=response["data"][i].role; 
                     
					var is_active=response["data"][i].is_active;
					var created_on = new Date(response["data"][i].created_on);


                    if(is_active==1)
                    {
                        var check="checked";
                    }
                    else
                    {
                        var check="";
                    }  
					var edit_url="{{url('edit_user')}}/"+id;
					var tr_str="<tr><td>"+num_increment+"</td><td style='text-transform:capitalize'>"+name+"</td><td>"+email+"</td><td style='text-transform:capitalize'>"+mobile_number+"</td> <td style='text-transform:capitalize'>"+role+"</td><td><label class='switch'><input type='checkbox' "+check+" id="+id+" value="+is_active+" onclick='getstatus("+id+")' class='statuscheckbox'><span class='slider round'></span></label></td>";
					@if(Helper::actionPermission())
					tr_str +="<td><a href="+edit_url+"><i class='fa fa-edit text-primary mr-2'></i></a>/ <a href='#'  data-toggle='modal' data-target='#DeleteCategoryModal' onclick='delete_menucategory("+id+")'><i class='fa fa-trash text-danger mr-2'></i>Delete</a> </td>";
					@endif
					tr_str += "</tr>";
					$("#category_tablelist tbody").append(tr_str);
                }

               $("#category_tablelist").DataTable();
              
            }
             else
            {
                var tr_str='<tr><td colspan="5">No Records are found</td></tr>';
                 $("#category_tablelist tbody").append(tr_str); 
                 $("#category_tablelist").DataTable();
            }
            
        });
    }

/*.............................Active and Inactive status Of Category...............................*/
function getstatus(id) {
   
    var id = id;
    var status = $("#" + id).val();
  
    
    if (status == 0) {
        var update_status = 1;
        var csrfToken = "{{ csrf_token() }}";

        var Url = "{{url('update_user_status')}}";
        $.ajax({
            url: Url,
            headers: {
                "X-CSRF-Token": csrfToken,
            },
            type: "POST",
            data: "category_id=" +id + "&status=" + update_status,
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

    var Url = "{{url('update_user_status')}}";
    $.ajax({
        url: Url,
        headers: {
            "X-CSRF-Token": csrfToken,
            
        },
        type: "POST",
        data: "category_id=" + id + "&status=" + status,
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

        var Url = "{{url('edit_user')}}";
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

                var Url = "{{url('update_user')}}";
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
 function delete_menucategory(id)
    {
        $("#delete_category_id").val(id);
    }
$("#delete_btn").on("click", function () {
        toastr.options = {
            progressBar: "true",
            positionClass: "toast-top-right",
        };
       
        var id = $("#delete_category_id").val();
        var csrfToken = "{{ csrf_token() }}";

        var Url = "{{url('delete_user')}}";
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