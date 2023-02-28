@extends('layouts.layout')
@section('title',$title)
@section('content')
<div id="page-wrapper" >
         <div class="header"> 
                        <h1 class="page-header">
                             <small>Model List</small>
                        </h1>
                        <ol class="breadcrumb">
                              <li><a href="{{url('/home')}}">Home</a></li>
                              <li class="active">Model</li>
                        </ol>     
         </div>
         <div id="page-inner">
            <div class="row">
                <div class="col-md-12">
                    <!-- Advanced Tables -->
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Model List
							@if(Helper::actionPermission())
                            <a href="{{url('add_model')}}"><button class="btn btn-primary" id="add_btn">+ Add Model</button></a>
							@endif
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="model_tablelist">
                                    <thead>
                                        <tr>
                                            <th>Sr.No</th>
                                            <th>Brand Name</th>
                                            <th>Model Name</th>
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
<!-- Inactive Category Model -->
<div class="modal fade" id="StatusModel" tabindex="-1" role="dialog" aria-labelledby="StatusModel">
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

<!-- Edit Category Modal -->
<div id="EditCategoryModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Edit Model</h4>
      </div>
      <div class="modal-body">
        <form method="post">
                    @csrf
                    <div class="row">
                    <input type="hidden"class="form-control" id="edit_id" name="modelid">
                    <div class="form-group">
                        <label for="category_name" class="col-sm-2 control-label">Brand <span class="required_label">*</span></label>
                        <div class="col-sm-6">
                            <select class="form-control" name="edit_brand_name" id="edit_brand_id">
                              
                            </select>
                            <span id="editbrandnamevalid" class="text-danger"></span>
                        </div>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="form-group">
                        <label class="col-sm-2 control-label">model Name <span class="required_label">*</span></label>
                       <div class="col-sm-6">
                        <span id="editmodelvalid" class="text-danger"></span>
                         
                        <input type="text" placeholder="Please enter model name." class="form-control" id="editmodel" name="editmodel" value="">
                       </div>
                    </div>
                  </div>
                    <div class="form-group">
                        <button type="button" id="editmodelbutton" class="btn btn-success mr-1">Submit <span class="btn-icon-right"><i class="fa fa-check"></i></span></button>
                        <button type="button" id="editmenu_test" class="btn btn-danger" data-dismiss="modal">Cancel <span class="btn-icon-right"><i class="fa fa-close"></i></span></button>
                    </div>
                </div>
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
                    <input type="hidden" name="delete_model_id" id="delete_model_id" value="">
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

/*get categories list...................................*/
    function fetchRecords() 
    {
        
        var csrfToken = "{{ csrf_token() }}";
        var Url = "{{url('fetch_model_list')}}";
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
                     var model_name=response["data"][i].mname;
                      var brand_name=response["data"][i].bname;
                     var status=response["data"][i].mstatus;
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

                    var tr_str="<tr><td>"+num_increment+"</td><td style='text-transform:capitalize'>"+brand_name+"</td><td style='text-transform:capitalize'>"+model_name+"</td><td>"+newdate+"</td><td><label class='switch'><input type='checkbox' "+check+" id="+id+" value="+status+" onclick='getstatus("+id+")' class='statuscheckbox'><span class='slider round'></span></label></td>";
					@if(Helper::actionPermission()) 
					tr_str += "<td><a href='#'  data-toggle='modal' data-target='#EditCategoryModal' onclick='edit_model("+id+")'><i class='fa fa-edit text-primary mr-2'></i></a> / <a href='#'  data-toggle='modal' data-target='#DeleteModal' onclick='delete_model("+id+")'><span class='glyphicon glyphicon-trash'></span></a> </td>";
					@endif
					tr_str += "</tr>";
                      $("#model_tablelist tbody").append(tr_str);
                }

               $("#model_tablelist").DataTable();
              
            }
             else
            {
                var tr_str='<tr><td>No Records are found</td><td></td><td></td><td></td><td></td></tr>';
                 $("#model_tablelist tbody").append(tr_str); 
                 $("#model_tablelist").DataTable();
            }
            
        });
    }

/*.............................Active and Inactive status Of Category...............................*/
function getstatus(id) {
   
    var id = id;
    var status = $("#" + id).val();
    //alert(status);
    
    if (status == 0) {
        var update_status = 1;
        var csrfToken = "{{ csrf_token() }}";

        var Url = "{{url('update_model_status')}}";
        $.ajax({
            url: Url,
            headers: {
                "X-CSRF-Token": csrfToken,
            },
            type: "POST",
            data: "model_id=" +id + "&status=" + update_status,
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
        $("#StatusModel").modal("show");
        $("#inactive_btn").attr("data-id", id);
    }
}
   
$("#inactive_btn").on("click", function () {
    
    var id = $(this).data("id");
    var status = 0;
    var csrfToken = "{{ csrf_token() }}";

    var Url = "{{url('update_model_status')}}";
    $.ajax({
        url: Url,
        headers: {
            "X-CSRF-Token": csrfToken,
            
        },
        type: "POST",
        data: "model_id=" + id + "&status=" + status,
    }).done(function (response) {
        //console.log(response);
        if (response.code == 200) {
            $("#StatusModel").modal("hide");
            toastr.success(response.message);
            $("#inactive_btn").prop("disabled", true);
            setTimeout(function () {
                window.location.reload();
            }, 1000);
        }
    });
});

//Edit model...........................................

function FetchActiveBrand(brand_id)
{
   var Url = "{{url('get_active_brand_list')}}";
        var csrfToken = "{{ csrf_token() }}";
                
        $.ajax({
                url: Url,
               headers: 
               {
                   'X-CSRF-Token': csrfToken,
               },
                type: "POST",
                
        })
        .done(function( response )
        {
              //console.log(response);
              var  dropdown = $("#edit_brand_id");

                        dropdown.empty();
                        dropdown.append('<option selected="true" value="">Choose Brand</option>');
                        dropdown.prop("selectedIndex", 0);

                        // Populate dropdown with list of provinces
                        $.each(response.data, function (key, entry) {
                               if(brand_id == entry.id)
                               {
                                  dropdown.append($('<option selected="true"></option>').attr("value", entry.id).text(entry.bname));
                               }
                               else
                               {
                                   dropdown.append($("<option></option>").attr("value", entry.id).text(entry.bname));
                               }
                            
                        });
        });
}
 function edit_model(id) 
 {  
        var id = id;
        var csrfToken = "{{ csrf_token() }}";

        var Url = "{{url('edit_model')}}";
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
                $("#editmodel").val(obj.mname);
                FetchActiveBrand(obj.brand_id);
                
            }
        });
 }
/*--------------------Update model name-------------------------------*/
    $("#editmodelbutton").click(function () 
    {        
                var errorCount = 0;
                if ($("#editmodel").val().trim() == "") {
                    $("#editmodel").focus();
                    $("#editmodelvalid").text("This field can't be empty.");
                    errorCount++;
                } else {
                    $("#editmenumodelvalid").text("");
                }
                

                if (errorCount > 0) {
                    return false;
                }
                var id = $("#edit_id").val();
                var m_name = $("#editmodel").val();
                var brand_id = $("#edit_brand_id").val();
                
                var csrfToken = "{{ csrf_token() }}";

                var Url = "{{url('update_model')}}";
                $.ajax({
                    url: Url,
                    headers: {
                        "X-CSRF-Token": csrfToken,
                        
                    },
                    type: "POST",
                    data: "id=" + id + "&model_name=" + m_name+"&brand_id="+brand_id,
                }).done(function (response) {
                    //console.log(response);
                    if (response.code == 200) {
                        $("#editmodelbutton").prop("disabled",true);
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
 function delete_model(id)
    {
        $("#delete_model_id").val(id);
    }
$("#delete_btn").on("click", function () {
        toastr.options = {
            progressBar: "true",
            positionClass: "toast-top-right",
        };
       
        var id = $("#delete_model_id").val();
        var csrfToken = "{{ csrf_token() }}";

        var Url = "{{url('delete_model')}}";
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