@extends('layouts.layout')
@section('title',$title)
@section('content')
<div id="page-wrapper" >
         <div class="header"> 
			<h1 class="page-header">
				 <small>Grade List</small>
			</h1>
			<ol class="breadcrumb">
				  <li><a href="{{url('/home')}}">Home</a></li>
				  <li class="active">Grade</li>
			</ol>     
         </div>
         <div id="page-inner">
            <div class="row">
                <div class="col-md-12">
                    <!-- Advanced Tables -->
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Grade List
							@if(Helper::actionPermission())
                            <a href="{{url('add_grade')}}"><button class="btn btn-primary" id="add_category_btn">+ Add Grade</button></a> 
							@endif
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="grade_tablelist">
                                    <thead>
                                        <tr>
                                            <th>Sr.No</th>
                                            <th>Grade Name</th>
                                            <th>Created At</th>
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
<!-- Inactive Grade Model -->
<div class="modal fade" id="StatusGrade" tabindex="-1" role="dialog" aria-labelledby="StatusGrade">
    <div class="modal-dialog modal-dialog-centered modal-min" role="document">
        <div class="modal-content">
            <div class="modal-body text-center">
                <i class="fa fa-ban" style="font-size: 130px; grade: #ff8800;"></i>
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

<!-- Edit Grade Modal -->
<div id="EditGradeModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Edit Grade</h4>
      </div>
      <div class="modal-body">
        <form method="post">
                    @csrf
                    <input type="hidden"class="form-control" id="edit_id" name="edit_id">
                    <div>
                        <label>grade Name <span class="text-danger">*</span></label>
                        <br>
                        <span id="editgradevalid" class="text-danger"></span>
                        <input type="text" placeholder="Please enter grade name." class="form-control" id="editgrade" name="editgrade" value="">
                       
                       

                    </div>
                    <div style="padding: 17px;">
                        <button type="button" id="editgradebutton" class="btn btn-success mr-1">Submit <span class="btn-icon-right"><i class="fa fa-check"></i></span></button>
                        <button type="button" id="editmenu_test" class="btn btn-danger" data-dismiss="modal">Cancel <span class="btn-icon-right"><i class="fa fa-close"></i></span></button>
                    </div>
                </div>
            </form>
      </div>
      
    </div>

  </div>
</div>
<!-- Delete Grade Model -->
<div class="modal fade" id="DeleteGradeModal" tabindex="-1" role="dialog" aria-labelledby="DeleteMenuGrade">
    <div class="modal-dialog modal-dialog-centered modal-min" role="document">
        <div class="modal-content">
            <div class="modal-body text-center">
                <i class="fa fa-ban" style="font-size: 130px; grade: #ff8800;"></i>
                <h3>Are you sure want to delete it?</h3>
                <form method="post" style="padding: 24px;">
                    @csrf
                    <input type="hidden" name="delete_grade_id" id="delete_grade_id" value="">
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
    /*cancel inavtivate model...............................*/
$("#cancel").on('click',function(){
  window.location.reload();
});
/*alpha validation..........*/
$("#editgrade").keypress(function (e) 
   {
            var keyCode = e.keyCode || e.which;
 
            $("#editgradevalid").html("");
 
            //Regex for Valid Characters i.e. Alphabets.
            var regex = /^[A-Za-z\-\s]+$/;
 
            //Validate TextBox value against the Regex.
            var isValid = regex.test(String.fgradeCharCode(keyCode));
            if (!isValid) {
                $("#editgradevalid").html("Only Alphabets are allowed.");
            }
 
            return isValid;
  });
/*get categories list...................................*/
    function fetchRecords() 
    {
        
        var csrfToken = "{{ csrf_token() }}";
        var Url = "{{url('fetch_grade_list')}}";
        $.ajax({
            url: Url,
            headers: {
               'X-CSRF-Token': csrfToken,
            },
            type: "GET",
            
        }).done(function (response) {
            //console.log(response)
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
                     var grade_name=response["data"][i].name;
                     var status=response["data"][i].status;
                     var date     = new Date(response["data"][i].created_at);
                    
                    const options = { year: "numeric", month: "long", day: "numeric" };
                    var newdate = date.toLocaleDateString(undefined, options);
                    if(status==1)
                    {
                        var check="checked";
                    }
                    else
                    {
                        var check="";
                    }

                     var tr_str="<tr><td>"+num_increment+"</td><td style='text-transform:capitalize'>"+grade_name+"</td><td>"+newdate+"</td><td><label class='switch'><input type='checkbox' "+check+" id="+id+" value="+status+" onclick='getstatus("+id+")' class='statuscheckbox'><span class='slider round'></span></label></td>";
					 @if(Helper::actionPermission())
					 tr_str+="<td><a href='#'  data-toggle='modal' data-target='#EditGradeModal' onclick='edit_grade("+id+")'><i class='fa fa-edit text-primary mr-2'></i></a> / <a href='#'  data-toggle='modal' data-target='#DeleteGradeModal' onclick='delete_menugrade("+id+")'><span class='glyphicon glyphicon-trash'></span></a> </td>";
					 @endif
					 tr_str+="</tr>";
                      $("#grade_tablelist tbody").append(tr_str);
                }

               $("#grade_tablelist").DataTable();
              
            }
             else
            {
                var tr_str='<tr><td>No Records are found</td><td></td><td></td><td></td><td></td></tr>';
                 $("#grade_tablelist tbody").append(tr_str); 
                 $("#grade_tablelist").DataTable();
            }
            
        });
    }

/*.............................Active and Inactive status Of Grade...............................*/
function getstatus(id) {
   
    var id = id;
    var status = $("#" + id).val();
   // alert(status);
    
    if (status == 0) {
        var update_status = 1;
        var csrfToken = "{{ csrf_token() }}";

        var Url = "{{url('update_grade_status')}}";
        $.ajax({
            url: Url,
            headers: {
                "X-CSRF-Token": csrfToken,
            },
            type: "POST",
            data: "grade_id=" +id + "&status=" + update_status,
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
        $("#StatusGrade").modal("show");
        $("#inactive_btn").attr("data-id", id);
    }
}
   
$("#inactive_btn").on("click", function () {
    
    var id = $(this).data("id");
    var status = 0;
    var csrfToken = "{{ csrf_token() }}";

    var Url = "{{url('update_grade_status')}}";
    $.ajax({
        url: Url,
        headers: {
            "X-CSRF-Token": csrfToken,
            
        },
        type: "POST",
        data: "grade_id=" + id + "&status=" + status,
    }).done(function (response) {
        //console.log(response);
        if (response.code == 200) {
            $("#StatusGrade").modal("hide");
            toastr.success(response.message);
            $("#inactive_btn").prop("disabled", true);
            setTimeout(function () {
                window.location.reload();
            }, 1000);
        }
    });
});

//Edit grade...........................................
 function edit_grade(id) 
 {  
        var id = id;
        var csrfToken = "{{ csrf_token() }}";

        var Url = "{{url('edit_grade')}}";
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
                $("#edit_id").val(obj.id);
                $("#editgrade").val(obj.name);
                
            }
        });
 }
/*--------------------Update grade name-------------------------------*/
    $("#editgradebutton").click(function () 
    {        
                var errorCount = 0;
                if ($("#editgrade").val().trim() == "") {
                    $("#editgrade").focus();
                    $("#editgradevalid").text("This field can't be empty.");
                    errorCount++;
                } else {
                    $("#editmenugradevalid").text("");
                }
                

                if (errorCount > 0) {
                    return false;
                }
                var id = $("#edit_id").val();
                var name = $("#editgrade").val();
                
                var csrfToken = "{{ csrf_token() }}";

                var Url = "{{url('update_grade')}}";
                $.ajax({
                    url: Url,
                    headers: {
                        "X-CSRF-Token": csrfToken,
                        
                    },
                    type: "POST",
                    data: "id=" + id + "&grade_name=" + name,
                }).done(function (response) {
                    //console.log(response);
                    if (response.code == 200) {
                        $("#editgradebutton").prop("disabled",true);
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

/*..............Delete Grade.........................................*/
 function delete_menugrade(id)
    {
        $("#delete_grade_id").val(id);
    }
$("#delete_btn").on("click", function () {
        toastr.options = {
            progressBar: "true",
            positionClass: "toast-top-right",
        };
       
        var id = $("#delete_grade_id").val();
        var csrfToken = "{{ csrf_token() }}";

        var Url = "{{url('delete_grade')}}";
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