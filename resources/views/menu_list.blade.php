@extends('layouts.layout')
@section('title',$title)
@section('content')
<div id="page-wrapper" >
         <div class="header"> 
			<h1 class="page-header">
				 <small>Menu List</small>
			</h1>
			<ol class="breadcrumb">
				  <li><a href="{{url('/home')}}">Home</a></li>
				  <li class="active">Menu</li>
			</ol>     
         </div>
         <div id="page-inner">
            <div class="row">
                <div class="col-md-12">
                    <!-- Advanced Tables -->
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Menu List
							@if(Helper::actionPermission())
                            <a href="{{url('add_menu')}}"><button class="btn btn-primary" id="add_category_btn">+ Add Menu</button></a> 
							@endif
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="category_tablelist">
                                    <thead>
                                        <tr>
                                            <th>Sr.No</th>
                                            <th>Menu Name</th>                                 
											<th>Menu Link</th> 
											<th>Menu Number</th> 
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

<!-- Edit Category Modal -->
<div id="EditCategoryModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Edit Menu</h4>
      </div>
      <div class="modal-body">
        <form method="post">
                    @csrf
                    <input type="hidden"class="form-control" id="cat_id" name="categoryid">
                    <div>
                        <label>Menu Name <span class="text-danger">*</span></label>
                        <br>
                        <span id="editcategoryvalid" class="text-danger"></span>
                        <input type="text" placeholder="Please enter MenuName." class="form-control" id="editname" name="editname" value="">
                    </div>
                       <div>
                        <label>Menu Link <span class="text-danger">*</span></label>
                        <br>
                        <span id="editlinkvalid" class="text-danger"></span>
                        <input type="text" placeholder="Please enter MenuLink." class="form-control" id="editlink" name="editname" value="">
                    </div>
                       <div>
                        <label>Menu Number <span class="text-danger">*</span></label>
                        <br>
                        <span id="editnumbervalid" class="text-danger"></span>
                        <input type="number" placeholder="Please enter MenuNumber." class="form-control" id="editnumber" name="editname" value="">
                    </div>



                    <div style="padding: 17px;">
                        <button type="button" id="editcategorybutton" class="btn btn-success mr-1">Submit <span class="btn-icon-right"><i class="fa fa-check"></i></span></button>
                        <button type="button" id="editmenu_test" class="btn btn-danger" data-dismiss="modal">Cancel <span class="btn-icon-right"><i class="fa fa-close"></i></span></button>
                    </div>
              </form>       
                </div>
           
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
        var Url = "{{url('fetch_menu_list')}}";
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
                     var name=response["data"][i].name;
                    var link=response["data"][i].link;
                    var number=response["data"][i].number;
                     var is_active=response["data"][i].is_active;
            


                    if(is_active==1)
                    {
                        var check="checked";
                    }
                    else
                    {
                        var check="";
                    }  

                 



                     var tr_str="<tr><td>"+num_increment+"</td><td style='text-transform:capitalize'>"+name+"</td> <td>"+link+"</td> <td>"+number+"</td><td><label class='switch'><input type='checkbox' "+check+" id="+id+" value="+is_active+" onclick='getstatus("+id+")' class='statuscheckbox'><span class='slider round'></span></label></td>";
					 @if(Helper::actionPermission())
					 tr_str += "<td><a href='#'  data-toggle='modal' data-target='#EditCategoryModal' onclick='edit_menucategory("+id+")'><i class='fa fa-edit text-primary mr-2'></i></a>/ <a href='#'  data-toggle='modal' data-target='#DeleteCategoryModal' onclick='delete_menucategory("+id+")'><i class='fa fa-trash text-danger mr-2'></i>Delete</a> </td>";
					 @endif
					 tr_str +="</tr>";


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

        var Url = "{{url('update_menu_status')}}";
        $.ajax({
            url: Url,
            headers: {
                "X-CSRF-Token": csrfToken,
            },
            type: "POST",
            data: "category_id=" +id + "&status=" + update_status,
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
        data: "category_id=" + id + "&status=" + status,
    }).done(function (response) {
    
     
            $("#StatusCategory").modal("hide");
            toastr.success(response.message);
            $("#inactive_btn").prop("disabled", true);
            setTimeout(function () {
                window.location.reload();
            }, 1000);
        
    });
});

//Edit category...........................................
 function edit_menucategory(id) 
 {  
        var id = id;
        var csrfToken = "{{ csrf_token() }}";

        var Url = "{{url('edit_menu')}}";
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
                $("#editname").val(obj.name);
                    $("#editlink").val(obj.link);
                        $("#editnumber").val(obj.number);
                
            }
        });
 }
/*--------------------Update category name-------------------------------*/
    $("#editcategorybutton").click(function () 
    {        
                var errorCount = 0;
                if ($("#editname").val().trim() == "") {
                    $("#editname").focus();
                    $("#editcategoryvalid").text("This field can't be empty.");
                    errorCount++;
                } else {
                    $("#editcategoryvalid").text("");
                }


  if ($("#editlink").val().trim() == "") {
                    $("#editlink").focus();
                    $("#editlinkvalid").text("This field can't be empty.");
                    errorCount++;
                } else {
                    $("#editlinkvalid").text("");
                }



         if ($("#editnumber").val().trim() == "") {
                    $("#editnumber").focus();
                    $("#editnumbervalid").text("This field can't be empty.");
                    errorCount++;
                } else {
                    $("#editnumbervalid").text("");
                }         




                if (errorCount > 0) {
                    return false;
                }
                var id = $("#cat_id").val();
                var cat_name = $("#editname").val();
                var cat_link = $("#editlink").val();
                var cat_number = $("#editnumber").val();
                var csrfToken = "{{ csrf_token() }}";

                var Url = "{{url('update_menu')}}";
                $.ajax({
                    url: Url,
                    headers: {
                        "X-CSRF-Token": csrfToken,
                        
                    },
                    type: "POST",
                    data: "id=" + id + "&name=" + cat_name + "&link=" + cat_link + "&number=" + cat_number,
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