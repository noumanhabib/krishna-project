@extends('layouts.layout')
@section('title',$title)
@section('content')
<div id="page-wrapper" >
         <div class="header"> 
                        <h1 class="page-header">
                             <small>Els Product List</small>
                        </h1>
                        <ol class="breadcrumb">
                              <li><a href="{{url('/home')}}">Home</a></li>
                              <li class="active">Els Product</li>
                        </ol>     
         </div>
         <div id="page-inner">
            <div class="row">
                <div class="col-md-12">
                    <!-- Advanced Tables -->
                    <div class="panel panel-default">
                        <div class="panel-heading">
                           Els Product List
                            <a href="{{url('add_elsproduct')}}"><button class="btn btn-primary" id="add_category_btn">+ Add Els Product</button></a> 
							 <button class="btn btn-info"   data-toggle='modal' data-target='#bulkUpload' ><i class='fa fa-trash text-danger mr-2'></i>Import Data</button>
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="category_tablelist">
                                    <thead>
                                        <tr>
                                            <th>Sr.No</th>
                                            <th>Els Brand</th>
                                            <th>Els Model</th>
                                            <th>Barcode</th>
                                            <th>Color</th>
                                            <th>Imei 1</th>
                                            <th>Imei 2</th>
                                            <th>Ram</th>
                                            <th>Rom</th>
                                            <th>Grade</th>
                                            <th>Price</th> 
                                            <th>Vendor</th>
                                            <th>Grn</th>
                                            <th>Remark</th>    
                                            <th>Quantity</th>        
                                             <th>Created On</th>          
                                            <th>Action</th>
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

<!-- Bulk Upload Model -->
<div class="modal fade" id="bulkUpload" tabindex="-1" role="dialog" aria-labelledby="BulkUpload">
    <div class="modal-dialog modal-dialog-centered modal-min" role="document">
        <div class="modal-content">
            <div class="modal-body text-center">
                <form method="post" action="{{url(route('upload.report'))}}" style="padding: 24px;" enctype="multipart/form-data">
                    @csrf
                    <input type="file" name="file_csv" id="file_csv" value="">
                    <button type="submit" class="btn btn-success light">Upload<span class="btn-icon-right"><i class="fa fa-check"></i></span></button>
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
function fetchRecords() 
    {
        var csrfToken = "{{ csrf_token() }}";
        var Url = "{{url('fetch_elsproduct_list')}}";
        $.ajax({
            url: Url,
            headers: {
               'X-CSRF-Token': csrfToken,
            },
            type: "GET",   
        }).done(function (response) {
             console.log(response);
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
                     var els_brand=response["data"][i].bname;
                     var els_model=response["data"][i].mname;
                     var els_color=response["data"][i].name;
                     var barcode=response["data"][i].barcode;
                    // var color=response["data"][i].color;
                     var imei_1=response["data"][i].imei_1;
                     var imei_2=response["data"][i].imei_2;
                     var ram=response["data"][i].ram;
                     var rom=response["data"][i].rom;
                     var grade=response["data"][i].grade;
                     var mrp=response["data"][i].mrp;
                     var vendor=response["data"][i].vname;
                     var grn=response["data"][i].grn;
                     var remark=response["data"][i].remark;
                     var quantity=response["data"][i].quantity;
                     var is_active=response["data"][i].is_active;
                     var created_on     = new Date(response["data"][i].created_on);
                    if(is_active==1)
                    {
                        var check="checked";
                    }
                    else
                    {
                        var check="";
                    }  
                    var created_on1=convert(created_on);
                     var tr_str="<tr><td>"+num_increment+"</td><td style='text-transform:capitalize'>"+els_brand+"</td><td style='text-transform:capitalize'>"+els_model+"</td>><td style='text-transform:capitalize'>"+barcode+"<br> <div class='d-flex mb-2'>  <p class='m-0' style='height: 43px;overflow: hidden;''><img src='http://cilearningschool.com/sparepart/barcode/barcode.php?text="+barcode+"&codetype=code39&orientation=horizontal&size=20&print=true'> </p>  </div></td><td style='text-transform:capitalize'>"+els_color+"</td><td style='text-transform:capitalize'>"+imei_1+"</td><td style='text-transform:capitalize'>"+imei_2+"</td><td style='text-transform:capitalize'>"+ram+"</td><td style='text-transform:capitalize'>"+rom+"</td><td style='text-transform:capitalize'>"+grade+"</td><td style='text-transform:capitalize'>"+mrp+"</td><td style='text-transform:capitalize'>"+vendor+"</td><td style='text-transform:capitalize'>"+grn+"</td><td style='text-transform:capitalize'>"+remark+"</td><td style='text-transform:capitalize'>"+quantity+"</td><td>"+created_on1+"</td><td><a href='#'><i class='fa fa-edit text-primary mr-2'></i></a>/ <a href='#'  data-toggle='modal' data-target='#DeleteCategoryModal' onclick='delete_menucategory("+id+")'><i class='fa fa-trash text-danger mr-2'></i>Delete</a> </td></tr>";
                  
                      $("#category_tablelist tbody").append(tr_str);
                }
               $("#category_tablelist").DataTable();  
            }
             else
            {
                var tr_str='<tr><td colspan="17">No Records are found</td></tr>';
                 $("#category_tablelist tbody").append(tr_str); 
                 $("#category_tablelist").DataTable();
            }  
        });
    }

/*alpha validation..........*/
$("#editcategory").keypress(function (e){
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


/*.............................Active and Inactive status Of Category...............................*/
function getstatus(id) {
   
    var id = id;
    var status = $("#" + id).val();
  
    
    if (status == 0) {
        var update_status = 1;
        var csrfToken = "{{ csrf_token() }}";

        var Url = "{{url('update_elsproduct_status')}}";
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
    var Url = "{{url('update_elsproduct_status')}}";
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
        var Url = "{{url('edit_elsproduct')}}";
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
              $("#pname").val(obj.pname);
              $("#pimei").val(obj.pimei);
              $("#pram").val(obj.pram);
              $("#prom").val(obj.prom);
              $("#pmodel").val(obj.pmodel);
              $("#pbrand").val(obj.pbrand);
               
            }
        });
 }
/*--------------------Update category name-------------------------------*/
    $("#editcategorybutton").click(function () 
    {        
                var errorCount = 0;
                if ($("#pname").val().trim() == "") {
                    $("#pname").focus();
                    $("#editnamevalid").text("This field can't be empty.");
                    errorCount++;
                } 
                else if($("#pimei").val().trim() == "")
                {
                    $("#pimei").focus();
                    $("#editimeivalid").text("This field can't be empty.");
                    errorCount++;

                }
                 else if($("#pram").val().trim() == "")
                {
                    $("#pram").focus();
                    $("#editramvalid").text("This field can't be empty.");
                    errorCount++;

                }
                 else if($("#prom").val().trim() == "")
                {
                    $("#prom").focus();
                    $("#editromvalid").text("This field can't be empty.");
                    errorCount++;

                }
                 else if($("#pmodel").val().trim() == "")
                {
                    $("#pmodel").focus();
                    $("#editmodelvalid").text("This field can't be empty.");
                    errorCount++;

                }
                   else if($("#pbrand").val().trim() == "")
                {
                    $("#pbrand").focus();
                    $("#editbrandvalid").text("This field can't be empty.");
                    errorCount++;

                }
                 else 
                 {
                    $("#editnamevalid").text("");
                     $("#editimeivalid").text("");
                      $("#editramvalid").text("");
                       $("#editromvalid").text("");
                        $("#editmodelvalid").text("");
                         $("#editbrandvalid").text("");

                }

                if (errorCount > 0) {
                    return false;
                }
                var id = $("#cat_id").val();
                var pname = $("#pname").val();
                var pimei = $("#pimei").val();
                var pram = $("#pram").val();
                var prom = $("#prom").val();
                var pmodel = $("#pmodel").val();
                var pbrand = $("#pbrand").val();
                        
                var csrfToken = "{{ csrf_token() }}";

                var Url = "{{url('update_elsproduct')}}";
                $.ajax({
                    url: Url,
                    headers: {
                        "X-CSRF-Token": csrfToken,
                        
                    },
                    type: "POST",
                    data: "id=" + id + "&pname=" + pname+ "&pimei=" +pimei+ "&pram="+pram+ "&prom="+prom+ "&pmodel="+pmodel+ "&pbrand="+pbrand,
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

        var Url = "{{url('delete_elsproduct')}}";
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