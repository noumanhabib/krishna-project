@extends('layouts.layout')
@section('title',$title)
@section('content')
<div id="page-wrapper" >
         <div class="header"> 
			<h1 class="page-header">
				 <small>Order Purchase List</small>
			</h1>
			<ol class="breadcrumb">
				  <li><a href="{{url('/home')}}">Home</a></li>
				  <li class="active">Order Purchase</li>
			</ol>     
         </div>
         <div id="page-inner">
            <div class="row">
                <div class="col-md-12">
                    <!-- Advanced Tables -->
                    <div class="panel panel-default">
                        <div class="panel-heading d-flex">
                           <div>Order Purchase List</div>
                           <div class="d-flex">
							<a href="javascript:;"><button class="btn btn-info" onclick="importData()">Import</button></a> &nbsp;&nbsp;
							{!! Form::open(['url' =>'export-purchase-order', 'class' => 'form-horizontal', 'role' => 'form']) !!}
							<a href="javascript:;"><button class="btn btn-info" type="submit">Export</button></a> &nbsp;&nbsp;
							{!! Form::close() !!}
                            <a href="{{url(route('add-purchase-order'))}}"><button class="btn btn-primary" id="add_category_btn">+ Add Order Purchase</button></a> 
                          </div>							
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="category_tablelist">
                                    <thead>
                                        <tr>
											<th>Sr.No</th>
											<th>Date</th> 
											<th>PO No</th> 
											<th>Vendor</th>
											<th>Created By</th>
											<!--<th>View Request Order</th>
											<th>View Purchased Order</th> -->  
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


<!-- Import Data Modal -->
<div id="importModel" class="modal fade" role="dialog">
	<div class="modal-dialog">
    <!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Request Purchase Order</h4>
			</div>
		<div class="modal-body">
			{!! Form::open(['url' =>'bulk-request-purchase-order', 'class' => 'form-horizontal', 'id' => 'save-form', 'role' => 'form','files'=>'true']) !!}
				<div class="form-group">
					<div class="col-md-12">
						{!! Form::label('title', 'Choose Request PO File', ['class' => 'control-label']) !!} 
						{!! Form::file('file_csv',['class' => 	'form-control','required', 'id'=>'file_csv','placeholder'=>'-Select-']) !!}  
					</div>					
                    <div class="col-md-12" style="padding: 17px;">
                        <button type="submit" class="btn btn-success mr-1">Submit <span class="btn-icon-right"><i class="fa fa-check"></i></span></button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel <span class="btn-icon-right"><i class="fa fa-close"></i></span></button>
						<a href="{{url('assets\files\request_purchase_order.csv')}}" class="btn btn-info light" download>Download Sample File</span></a>
                    </div>
				</div>
                </div>
            {!! Form::close() !!}
		</div>
      
    </div>
</div>

{!! Form::open(['url' =>route('download-purchase-order'), 'class' => 'form-horizontal', 'role' => 'form', 'id' => 'download-po']) !!}									
	{!! Form::hidden('po_id', '', ['id'=>'po-id']) !!}	
{!! Form::close() !!}

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script type="text/javascript">
importData=function(){
	$('#importModel').modal('show');
}

    $(document).ready(function () {     
        var oTable = $('#category_tablelist').DataTable({
			'processing': true,
			'serverSide': true,		
			"ajax":{
				"url": "{{url('fetch_orderpurchase_list')}}"
			}, 	
			"columns": [
				{ data: "id" },
				{ data: "date" },
				{ data: "po_no" },
				{ data: "vname" },
				{ data: "name" },
				{ data: "id" }
			],
			"rowCallback": function (nRow, aData, iDisplayIndex) {
				var oSettings = this.fnSettings ();
				$("td:first", nRow).html(oSettings._iDisplayStart+iDisplayIndex +1);
				var actionBtn = '';
				@if(Helper::actionPermission())
				actionBtn += '<a href="{{url("edit-purchase-order")}}/'+aData.id+'"><i class="fa fa-edit text-primary mr-2"></i></a>&nbsp';
				@endif
				actionBtn += '&nbsp<a href="javascript:;" onclick="downloadPO('+aData.id+')"><i class="fa fa-download text-primary mr-2"></i></a>';
				// actionBtn = '<div class="btn-group"><button type="button" class="btn btn-tool" data-toggle="dropdown"><i class="fas fa-cog"></i></button><div class="dropdown-menu dropdown-menu-right" role="menu">';
				// actionBtn += '</div></div>';
				$("td:eq(5)", nRow).html(actionBtn);
				return nRow;
			},
			"order": [[1, 'asc']],
			"columnDefs": [{ orderable: false, "targets": 0 }]
		});      
	});
	

downloadPO=function(id){
	if(id){
		$('#po-id').val(id);
		$('#download-po').submit();
	}
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
        var Url = "{{url('update_purchase_status')}}";
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
    var Url = "{{url('update_purchase_status')}}";
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
        var Url = "{{url('edit_purchase')}}";
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
                var Url = "{{url('update_purchase')}}";
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
        var Url = "{{url('delete_purchase')}}";
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
                var tr_str='<tr><td colspan="4">No Records are found</td></tr>';
                 $("#product_list_tablelist tbody").append(tr_str); 
                 $("#product_list_tablelist").DataTable();
            }

        });
}
function view_product_order($id)
{
     //alert($id);
      var idd = $id;
        var csrfToken = "{{ csrf_token() }}";
        var Url = "{{url('get_product_list_by_order_id')}}";
        $.ajax({
            url: Url,
            headers: {
                "X-CSRF-Token": csrfToken,   
            },
            type: "POST",
            data: "id=" + idd,
        }).done(function (response) {       
           $("#product_list_tablelist_pp tbody").html('');
            $("#total_price_p").text('');
            $("#total_quantity_p").text('');
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
                      var vname=response["data"][i].vname;
                        var price=response["data"][i].price;

                        var t_price=price*quantity;
                    
                     //var created_on1=convert(created_on);
                     var tr_str="<tr><td>"+num_increment+"</td><td style='text-transform:capitalize'>"+name+"</td><td>"+vname+"</td><td class='prc_p"+idd+"'>"+price+"</td><td class='qnty_p"+idd+"''>"+quantity+"</td><td class='prce_p"+idd+"'>"+t_price+"</td></tr>";
                      $("#product_list_tablelist_pp tbody").append(tr_str);
                }
                var sum = 0;
                    $('.qnty_p'+idd).each(function(){
                        sum += parseFloat($(this).text());  // Or this.innerHTML, this.innerText
                    });
                var tot = 0;
                    $('.prce_p'+idd).each(function(){
                        tot += parseFloat($(this).text());  // Or this.innerHTML, this.innerText
                    });     
             $("#total_price_p").text(tot);
            $("#total_quantity_p").text(sum);

               $("#product_list_tablelist_pp").DataTable();
            }
             else
            {
                var tr_str='<tr><td colspan="6">No Records are found</td></tr>';
                 $("#product_list_tablelist_pp tbody").append(tr_str); 
                 $("#product_list_tablelist_pp").DataTable();
            }

        });
}
</script>
@endsection