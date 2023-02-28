@extends('layouts.layout')
@section('title',$title)
@section('content')
<div id="page-wrapper" >
         <div class="header"> 
                        <h1 class="page-header">
                             <small>Request Order List</small>
                        </h1>
                        <ol class="breadcrumb">
                              <li><a href="{{url('/home')}}">Home</a></li>
                              <li class="active"> Request Order</li>
                        </ol>     
         </div>
         <div id="page-inner">
            <div class="row">
                <div class="col-md-12">
                    <!-- Advanced Tables -->
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Request Order List
                            <a href="{{url('add_orderrequest')}}"><button class="btn btn-primary button_right" id="add_category_btn">+ Add Order Request</button></a> 
						<!-- 	<input type="button"  class="button_right" style="float: right;margin-right: 10px;" onclick="tableToExcel('data_tablelist', 'W3C Example Table')" value="Export to Excel"> -->
                      <!--  <input type="button" id="btnExport" value="Export Table data into Excel " /> 
 -->
                        </div>

                        <div class="panel-body">
                            <div class="table-responsive" id="dvData">
                                <table class="table table-striped table-bordered table-hover" id="ro_data_tablelist">
                                    <thead>
                                        <tr >
                                            <th>Sr.No</th>
                                            <th>GRN No.</th> 
                                            <th>Barcode</th>
                                            <th>created By</th>
                                             <th>Status</th> 
                                             <th>View Products</th>           
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                     <tbody>
                                          @php
                                              $i = 1;
                                            @endphp
                                            @foreach($product_list as $row) 
                                            <?php 
                                                   $status=$row->status;
                                                    if($status==1)
                                                    {
                                                         $sts="Done";
                                                          $label="success";
                                                    }
                                                    else
                                                    {
                                                         $sts="panding";
                                                          $label="danger";
                                                    }
                                                   ?>
                                              <tr>
                                                 <td>{{$i++}}</td>
                                                 <td>{{$row->grn_no}}</td>
                                                  <td>{{$row->barcode_id?$row->barcode_id:'-NA-'}}</td>
                                                   <td>{{Auth::user()->name}}</td>
                                                   <td><span class='label label-{{$label}}'>{{$sts}}</span></td>

                                                    <td><a href='#'  onclick='view_product("{{$row->id}}")' data-toggle='modal' data-target='#ViewProductModal'><i class='fa fa-eye text-primary mr-2'></i></a></td>

                                                   <td><a href="{{url('edit_request_order_data')}}/{{$row->id}}"><i class='fa fa-edit text-primary mr-2'></i></a> / <a href='#'  data-toggle='modal' data-target='#DeleteCategoryModal'><i class='fa fa-trash text-danger mr-2'></i>Delete</a></td>
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

<!-- View Product Model -->
<div class="modal fade" id="ViewProductModal" tabindex="-1" role="dialog" aria-labelledby="ViewProductModal">
    <div class="modal-dialog modal-dialog-centered modal-min" role="document">
         
        <div class="modal-content">
             <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
                         <h3>Product Details</h3>
      </div>
            <div class="modal-body text-center">

                 <table class="table table-striped table-bordered table-hover" id="product_list_tablelist">
                    <thead>
                        <tr>
                            <th>Sr.No</th>
                            <th>Product</th> 
                            <th>Quantity</th>
                        </tr>
                    </thead>
                     <tbody>
                       
                    </tbody>
                    <tfoot>
                        <tr><td colspan="2">Total Quantity</td><td id="total_quantity"></td></tr>
                    </tfoot>
                </table>

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


function view_product($id)
{
     //alert($id);
      var id = $id;
        var csrfToken = "{{ csrf_token() }}";
        var Url = "{{url('get_product_list_by_grn_no')}}";
        $.ajax({
            url: Url,
            headers: {

                "X-CSRF-Token": csrfToken,
               
            },
            type: "POST",
            data: "id=" + id,
        }).done(function (response) {       
           $("#product_list_tablelist tbody").html('');
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
                     var name  =response["data"][i].name;
                     var quantity=response["data"][i].quantity;
                    
                     //var created_on1=convert(created_on);
                     var tr_str="<tr><td>"+num_increment+"</td><td style='text-transform:capitalize'>"+name+"</td><td class='qnty'>"+quantity+"</td></tr>";
                      $("#product_list_tablelist tbody").append(tr_str);
                }
                var sum = 0;
                    $('.qnty').each(function(){
                        sum += parseFloat($(this).text());  // Or this.innerHTML, this.innerText
                    });

            $("#total_quantity").text(sum);

               $("#product_list_tablelist").DataTable();
            }
             else
            {
                var tr_str='<tr><td colspan="3">No Records are found</td></tr>';
                 $("#product_list_tablelist tbody").append(tr_str); 
                 $("#product_list_tablelist").DataTable();
            }

        });
}
</script>
<!-- <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script> -->
<!-- <script src="src/jquery.table2excel.js"></script> -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
<script src="https://cdn.rawgit.com/rainabba/jquery-table2excel/1.1.0/dist/jquery.table2excel.min.js"></script>
<script type="text/javascript">//<![CDATA[
$.noConflict();

$("#btnExport").click(function () {
  //alert("test");
  $("#ro_data_tablelist").table2excel({
    // exclude CSS class
    
    name: "Worksheet Name",
    filename: "SomeFile", //do not include extension
    fileext: ".xls" // file extension
  }); 
});



  //]]></script>
@endsection