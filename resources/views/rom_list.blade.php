@extends('layouts.layout')
@section('title',$title)
@section('content')
<div id="page-wrapper" >
         <div class="header"> 
			<h1 class="page-header">
				 <small>ROM List</small>
			</h1>
			<ol class="breadcrumb">
				  <li><a href="{{url('/home')}}">Home</a></li>
				  <li class="active">ROM</li>
			</ol>     
         </div>
         <div id="page-inner">
            <div class="row">
                <div class="col-md-12">
                    <!-- Advanced Tables -->
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            ROM List
							@if(Helper::actionPermission())
                            <a href="{{url('add_rom')}}"><button class="btn btn-primary" id="add_category_btn">+ Add ROM</button></a> 
							@endif
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="rom_tablelist">
                                    <thead>
                                        <tr>
                                            <th>Sr.No</th>
                                            <th>ROM Name</th>
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
<!-- Inactive ROM Model -->
<div class="modal fade" id="StatusROM" tabindex="-1" role="dialog" aria-labelledby="StatusROM">
    <div class="modal-dialog modal-dialog-centered modal-min" role="document">
        <div class="modal-content">
            <div class="modal-body text-center">
                <i class="fa fa-ban" style="font-size: 130px; rom: #ff8800;"></i>
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

<!-- Edit ROM Modal -->
<div id="EditROMModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Edit ROM</h4>
      </div>
      <div class="modal-body">
        <form method="post">
                    @csrf
                    <input type="hidden"class="form-control" id="edit_id" name="edit_id">
                    <div>
                        <label>rom Name <span class="text-danger">*</span></label>
                        <br>
                        <span id="editromvalid" class="text-danger"></span>
                        <input type="text" placeholder="Please enter rom name." class="form-control" id="editrom" name="editrom" value="">
                       
                       

                    </div>
                    <div style="padding: 17px;">
                        <button type="button" id="editrombutton" class="btn btn-success mr-1">Submit <span class="btn-icon-right"><i class="fa fa-check"></i></span></button>
                        <button type="button" id="editmenu_test" class="btn btn-danger" data-dismiss="modal">Cancel <span class="btn-icon-right"><i class="fa fa-close"></i></span></button>
                    </div>
                </div>
            </form>
      </div>
      
    </div>

  </div>
</div>
<!-- Delete ROM Model -->
<div class="modal fade" id="DeleteROMModal" tabindex="-1" role="dialog" aria-labelledby="DeleteMenuROM">
    <div class="modal-dialog modal-dialog-centered modal-min" role="document">
        <div class="modal-content">
            <div class="modal-body text-center">
                <i class="fa fa-ban" style="font-size: 130px; rom: #ff8800;"></i>
                <h3>Are you sure want to delete it?</h3>
                <form method="post" style="padding: 24px;">
                    @csrf
                    <input type="hidden" name="delete_rom_id" id="delete_rom_id" value="">
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
$("#editrom").keypress(function (e) 
   {
            var keyCode = e.keyCode || e.which;
 
            $("#editromvalid").html("");
 
            //Regex for Valid Characters i.e. Alphabets.
            var regex = /^[A-Za-z\-\s]+$/;
 
            //Validate TextBox value against the Regex.
            var isValid = regex.test(String.fromCharCode(keyCode));
            if (!isValid) {
                $("#editromvalid").html("Only Alphabets are allowed.");
            }
 
            return isValid;
  });
/*get categories list...................................*/
    function fetchRecords() 
    {
        
        var csrfToken = "{{ csrf_token() }}";
        var Url = "{{url('fetch_rom_list')}}";
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
                     var rom_name=response["data"][i].name;
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

                     var tr_str="<tr><td>"+num_increment+"</td><td style='text-transform:capitalize'>"+rom_name+"</td><td>"+newdate+"</td><td><label class='switch'><input type='checkbox' "+check+" id="+id+" value="+status+" onclick='getstatus("+id+")' class='statuscheckbox'><span class='slider round'></span></label></td>";
					 @if(Helper::actionPermission())
					 tr_str +="<td><a href='#'  data-toggle='modal' data-target='#EditROMModal' onclick='edit_rom("+id+")'><i class='fa fa-edit text-primary mr-2'></i></a> / <a href='#'  data-toggle='modal' data-target='#DeleteROMModal' onclick='delete_menurom("+id+")'><span class='glyphicon glyphicon-trash'></span></a> </td>";
					 @endif
					 tr_str +="</tr>";
                      $("#rom_tablelist tbody").append(tr_str);
                }

               $("#rom_tablelist").DataTable();
              
            }
             else
            {
                var tr_str='<tr><td>No Records are found</td><td></td><td></td><td></td><td></td></tr>';
                 $("#rom_tablelist tbody").append(tr_str); 
                 $("#rom_tablelist").DataTable();
            }
            
        });
    }

/*.............................Active and Inactive status Of ROM...............................*/
function getstatus(id) {
   
    var id = id;
    var status = $("#" + id).val();
   // alert(status);
    
    if (status == 0) {
        var update_status = 1;
        var csrfToken = "{{ csrf_token() }}";

        var Url = "{{url('update_rom_status')}}";
        $.ajax({
            url: Url,
            headers: {
                "X-CSRF-Token": csrfToken,
            },
            type: "POST",
            data: "rom_id=" +id + "&status=" + update_status,
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
        $("#StatusROM").modal("show");
        $("#inactive_btn").attr("data-id", id);
    }
}
   
$("#inactive_btn").on("click", function () {
    
    var id = $(this).data("id");
    var status = 0;
    var csrfToken = "{{ csrf_token() }}";

    var Url = "{{url('update_rom_status')}}";
    $.ajax({
        url: Url,
        headers: {
            "X-CSRF-Token": csrfToken,
            
        },
        type: "POST",
        data: "rom_id=" + id + "&status=" + status,
    }).done(function (response) {
        //console.log(response);
        if (response.code == 200) {
            $("#StatusROM").modal("hide");
            toastr.success(response.message);
            $("#inactive_btn").prop("disabled", true);
            setTimeout(function () {
                window.location.reload();
            }, 1000);
        }
    });
});

//Edit rom...........................................
 function edit_rom(id) 
 {  
        var id = id;
        var csrfToken = "{{ csrf_token() }}";

        var Url = "{{url('edit_rom')}}";
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
                $("#editrom").val(obj.name);
                
            }
        });
 }
/*--------------------Update rom name-------------------------------*/
    $("#editrombutton").click(function () 
    {        
                var errorCount = 0;
                if ($("#editrom").val().trim() == "") {
                    $("#editrom").focus();
                    $("#editromvalid").text("This field can't be empty.");
                    errorCount++;
                } else {
                    $("#editmenuromvalid").text("");
                }
                

                if (errorCount > 0) {
                    return false;
                }
                var id = $("#edit_id").val();
                var name = $("#editrom").val();
                
                var csrfToken = "{{ csrf_token() }}";

                var Url = "{{url('update_rom')}}";
                $.ajax({
                    url: Url,
                    headers: {
                        "X-CSRF-Token": csrfToken,
                        
                    },
                    type: "POST",
                    data: "id=" + id + "&rom_name=" + name,
                }).done(function (response) {
                    //console.log(response);
                    if (response.code == 200) {
                        $("#editrombutton").prop("disabled",true);
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

/*..............Delete ROM.........................................*/
 function delete_menurom(id)
    {
        $("#delete_rom_id").val(id);
    }
$("#delete_btn").on("click", function () {
        toastr.options = {
            progressBar: "true",
            positionClass: "toast-top-right",
        };
       
        var id = $("#delete_rom_id").val();
        var csrfToken = "{{ csrf_token() }}";

        var Url = "{{url('delete_rom')}}";
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