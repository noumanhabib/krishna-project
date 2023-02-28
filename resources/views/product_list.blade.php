@extends('layouts.layout')
@section('title',$title)
@section('content')
<div id="page-wrapper" >
         <div class="header"> 
                        <h1 class="page-header">
                             <small>Product List</small>
                        </h1>
                        <ol class="breadcrumb">
                              <li><a href="{{url('/home')}}">Home</a></li>
                              <li class="active">Part</li>
                        </ol>     
         </div>
         <div id="page-inner">
            <div class="row">
                <div class="col-md-12">
                    <!-- Advanced Tables -->
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Spare Part List
                            <a href="{{url('add_product')}}"><button class="btn btn-primary" id="add_category_btn">+ Add Spare Part</button></a> 
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="category_tablelist">
                                    <thead>
                                        <tr>
                                            <th>Sr.No</th>
                                            <th>Part Name</th>
                                            <th>Part Model</th>
                                            <th>Part Brand</th>
                                            <th>Part SKU</th>
                                            <th>Part Price</th>
                                            <th>Part Received Quantity</th>
                                            <th>Created On</th>
                                            <th>Prodcut Status</th>
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

<!-- Edit Category Modal -->
<div id="EditCategoryModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Edit Product</h4>
      </div>
      <div class="modal-body">
        <form method="post">
                    @csrf
                    <input type="hidden"class="form-control" id="cat_id" name="categoryid">
                    <div>
     <label>Part Name<span class="text-danger">*</span></label>
     <br>
     <span id="editproductvalid" class="text-danger"></span>
     <input type="text" placeholder="Please Enter Product Name." class="form-control" id="editproduct" name="editproduct" value="">
                    </div>

    <div>
     <label>Product Brand<span class="text-danger">*</span></label>
     <br>
     <span id="editbrandvalid" class="text-danger"></span>
     <select required="" class="form-control" id="editbrand" onchange="brandvalue(this.value);">
                                                 <option value="">Select Brand</option>
                                                <?php  foreach ($elsBrd as $key => $value) {
                                               ?>
                                                <option value="<?=$value->id?>"><?=$value->bname?></option>
                                                <?php  }   ?>
                                              </select>
    </div>
      <div>
     <label>Product Model<span class="text-danger">*</span></label>
     <br>
     <span id="editmodelvalid" class="text-danger"></span>
    <select required="" class="form-control" id="editmodel">
                                                 <option value="">Select Model</option>
                                                </select>
    </div>
    <div>
     <label>Product Price<span class="text-danger">*</span></label>
     <br>
     <span id="editpricevalid" class="text-danger"></span>
     <input type="number" placeholder="Please Enter Product Price." class="form-control" id="editprice" name="editprice" value="">
                    </div>                  
    <div>
     <label>Product Type<span class="text-danger">*</span></label>
     <br>
     <span id="edittypevalid" class="text-danger"></span>
     <select required="" class="form-control" id="edittype">
     <option value="">Select Type</option>
     <option value="0">Custom Part</option>
     <option value="1">Assign Part</option>
     <option value="2">Spare Part</option>
     </select>
    </div>    
    <div>
     <label>Product Color<span class="text-danger">*</span></label>
     <br>
     <span id="editcolorvalid" class="text-danger"></span>
     <input type="text" placeholder="Please Enter Product Color." class="form-control" id="editcolor" name="editcolor" value="">
                    </div>

    <div>
     <label>Part Received Quantity<span class="text-danger">*</span></label>
     <br>
     <span id="editquantityvalid" class="text-danger"></span>
     <input type="number" placeholder="Please Enter Product Price." class="form-control" id="editquantity" name="editquantity" value="">
                    </div>                                                           
  <div>
     <label>Date Entry Person<span class="text-danger">*</span></label>
     <br>
     <span id="editpersonvalid" class="text-danger"></span>
     <select required="" class="form-control" id="editperson">
                                                 <option value="">Select Person</option>
                                                <?php  foreach ($elsUsr as $key => $value) {
                                               ?>
                                                <option value="<?=$value->id?>"><?=$value->name?></option>
                                                <?php  }   ?>
                                              </select>
                    </div> 

 <div>
     <label>Product Vendor<span class="text-danger">*</span></label>
     <br>
     <span id="editvendorvalid" class="text-danger"></span>
      <select required="" class="form-control" id="editvendor">
                                                 <option value="">Select Vendor</option>
                                                <?php  foreach ($elsVen as $key => $value) {
                                               ?>
                                                <option value="<?=$value->id?>"><?=$value->vname?></option>
                                                <?php  }   ?>
                                              </select>
                    </div> 


                    <div style="padding: 17px;">
                        <button type="button" id="editcategorybutton" class="btn btn-success mr-1">Submit <span class="btn-icon-right"><i class="fa fa-check"></i></span></button>
                        <button type="button" id="editmenu_test" class="btn btn-danger" data-dismiss="modal">Cancel <span class="btn-icon-right"><i class="fa fa-close"></i></span></button>
                    </div>
                </div>
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
$("#editcolor").keypress(function (e) 
   {
            var keyCode = e.keyCode || e.which;
            $("#editcolorvalid").html("");
            //Regex for Valid Characters i.e. Alphabets.
            var regex = /^[A-Za-z\-\s]+$/;
            //Validate TextBox value against the Regex.
            var isValid = regex.test(String.fromCharCode(keyCode));
            if (!isValid) {
                $("#editcolorvalid").html("Only Alphabets are allowed.");
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
        var Url = "{{url('fetch_product_list')}}";
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
                     var model=response["data"][i].mname;
                     var brand=response["data"][i].bname;
                     var price=response["data"][i].price;
                     var sku=response["data"][i].sku;
                     var quantity=response["data"][i].quantity;
                     var type=response["data"][i].type;
                     var entry=response["data"][i].entry;
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
                     var tr_str="<tr><td>"+num_increment+"</td><td style='text-transform:capitalize'>"+name+"</td><td style='text-transform:capitalize'>"+model+"</td><td style='text-transform:capitalize'>"+brand+"</td><td style='text-transform:capitalize'>"+sku+"<br> <div class='d-flex mb-2'>  <p class='m-0' style='height: 43px;overflow: hidden;''><img src='http://cilearningschool.com/sparepart/barcode/barcode.php?text="+sku+"&codetype=code39&orientation=horizontal&size=20&print=true'> </p>  </div></td><td style='text-transform:capitalize'>"+price+"</td><td style='text-transform:capitalize'>"+quantity+"</td><td>"+created_on1+"</td><td><label class='switch'><input type='checkbox' "+check+" id="+id+" value="+is_active+" onclick='getstatus("+id+")' class='statuscheckbox'><span class='slider round'></span></label></td><td><a href='#'  data-toggle='modal' data-target='#EditCategoryModal' onclick='edit_menucategory("+id+")'><i class='fa fa-edit text-primary mr-2'></i></a>/ <a href='#'  data-toggle='modal' data-target='#DeleteCategoryModal' onclick='delete_menucategory("+id+")'><i class='fa fa-trash text-danger mr-2'></i>Delete</a> </td></tr>";
                      $("#category_tablelist tbody").append(tr_str);
                }
               $("#category_tablelist").DataTable();  
            }
             else
            {
                var tr_str='<tr><td colspan="10">No Records are found</td></tr>';
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

        var Url = "{{url('update_product_status')}}";
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

    var Url = "{{url('update_product_status')}}";
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
        var Url = "{{url('edit_product')}}";
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
            var tr_str="<option value="+obj.model+">"+obj.mname+"</option>";
            if (response.code == 200) {
                $("#editmodel").html(''); 
                $("#cat_id").val(obj.id);
                $("#editproduct").val(obj.name);
                $("#editsku").val(obj.sku);
                $("#editcolor").val(obj.color);
                $("#editprice").val(obj.price);
                $("#editquantity").val(obj.quantity);
                $("#editperson").val(obj.entry);   
                $("#edittype option[value="+obj.type+"]").attr("selected","selected");      
                $("#editbrand option[value="+obj.brand+"]").attr("selected","selected");    
                $("#editmodel").append(tr_str);     
                $("#editmodel option[value="+obj.model+"]").attr("selected","selected"); 
                $("#editvendor option[value="+obj.vendor+"]").attr("selected","selected"); 
            }
        });
 }
/*--------------------Update category name-------------------------------*/
    $("#editcategorybutton").click(function () 
    {        
                var errorCount = 0;
                if ($("#editproduct").val().trim() == "") {
                    $("#editproduct").focus();
                    $("#editproductvalid").text("This field can't be empty.");
                    errorCount++;
                } 
                else if($("#editmodel").val().trim() == "")
                {
                    $("#editmodel").focus();
                    $("#editmodelvalid").text("This field can't be empty.");
                    errorCount++;

                }
                 else if($("#editprice").val().trim() == "")
                {
                    $("#editprice").focus();
                    $("#editpricevalid").text("This field can't be empty.");
                    errorCount++;

                }
                 else if($("#editbrand").val().trim() == "")
                {
                    $("#editbrand").focus();
                    $("#editbrandvalid").text("This field can't be empty.");
                    errorCount++;

                }
                 else if($("#editquantity").val().trim() == "")
                {
                    $("#editquantity").focus();
                    $("#editquantityvalid").text("This field can't be empty.");
                    errorCount++;

                }
                 else if($("#editcolor").val().trim() == "")
                {
                    $("#editcolor").focus();
                    $("#editcolorvalid").text("This field can't be empty.");
                    errorCount++;

                }
                 else if($("#editvendor").val().trim() == "")
                {
                    $("#editvendor").focus();
                    $("#editvendorvalid").text("This field can't be empty.");
                    errorCount++;
                }
                 else 
                 {
                    $("#editproductvalid").text("");
                      $("#editmodelvalid").text("");
                        $("#editpricevalid").text("");
                          $("#editbrandvalid").text("");
                          $("#editquantityvalid").text("");
                          $("#editcolorvalid").text("");
                            $("#editvendorvalid").text("");
                }
                if (errorCount > 0) {
                    return false;
                }
                var id = $("#cat_id").val();
                var editproduct = $("#editproduct").val();
                var editmodel = $("#editmodel").val();
                var editbrand = $("#editbrand").val();
                var editcolor = $("#editcolor").val();
                var editquantity = $("#editquantity").val();
                var edittype = $("#edittype").val();
                var editperson = $("#editperson").val();
                var editprice = $("#editprice").val();
                var editvendor = $("#editvendor").val();
                var csrfToken = "{{ csrf_token() }}";
                var Url = "{{url('update_product')}}";
                $.ajax({
                    url: Url,
                    headers: {
                        "X-CSRF-Token": csrfToken,
                        
                    },
                    type: "POST",
                    data: "id=" + id + "&editproduct=" + editproduct+ "&editmodel=" +editmodel+ "&editbrand=" +editbrand+ "&editprice="+editprice+ "&editcolor="+editcolor+ "&editquantity="+editquantity+ "&editperson="+editperson+ "&editcolor="+editcolor+ "&editvendor="+editvendor,
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

        var Url = "{{url('delete_product')}}";
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
 function brandvalue(id)
  {
  var id = id;
        var csrfToken = "{{ csrf_token() }}";
        var Url = "{{url('fetch-model')}}";
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
            var len = 0;
            if (response["data"] != null) {
            len = response["data"].length;
            }
            var num=1;
             $("#editmodel").html(''); 
             if(len > 0) 
            {  
           for(var i = 0; i < len; i++) 
            {
                var id = response["data"][i].id;
                var mname = response["data"][i].mname;
                tr_str="<option value="+id+">"+mname+"</option>";

                $("#editmodel").append(tr_str);       
               }  
             }
           }
        });
  }
</script>
@endsection