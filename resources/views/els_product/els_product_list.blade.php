@extends('layouts.layout')
@section('title',$title)
@section('content')
<div id="page-wrapper" >
         <div class="header"> 
			<h1 class="page-header">
				 <small>ELS Product List</small>
			</h1>
			<ol class="breadcrumb">
				  <li><a href="{{url('/home')}}">Home</a></li>
				  <li class="active">ELS Product</li>
			</ol>     
         </div>
   
   
    <style>
        .sticky {
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 999;
        }
        .tooltip {
  position: relative;
  display: inline-block;
  border-bottom: 1px dotted black;
}

.tooltip .tooltiptext {
  visibility: hidden;
  width: 120px;
  background-color: #555;
  color: #fff;
  text-align: center;
  border-radius: 6px;
  padding: 5px 0;
  position: absolute;
  z-index: 1;
  bottom: 125%;
  left: 50%;
  margin-left: -60px;
  opacity: 0;
  transition: opacity 0.3s;
}

.tooltip .tooltiptext::after {
  content: "";
  position: absolute;
  top: 100%;
  left: 50%;
  margin-left: -5px;
  border-width: 5px;
  border-style: solid;
  border-color: #555 transparent transparent transparent;
}

.tooltip:hover .tooltiptext {
  visibility: visible;
  opacity: 1;
}


    #overlay{   
  position: fixed;
  top: 0;
  z-index: 100;
  width: 100%;
  height:100%;
  display: none;
  background: rgba(0,0,0,0.6);
}
.cv-spinner {
  height: 100%;
  display: flex;
  justify-content: center;
  align-items: center;  
}
.spinner {
  width: 40px;
  height: 40px;
  border: 4px #ddd solid;
  border-top: 4px #2e93e6 solid;
  border-radius: 50%;
  animation: sp-anime 0.8s infinite linear;
}
@keyframes sp-anime {
  100% { 
    transform: rotate(360deg); 
  }
}
.is-hide{
  display:none;
}


    </style>
   
         
<div id="overlay">
  <div class="cv-spinner">
    <span class="spinner"></span>
  </div>
</div>
         
         <div id="page-inner">
            <div class="row">
                <div class="col-md-12">
                    <!-- Advanced Tables -->
                    <div class="panel panel-default">
                        <div class="panel-heading d-flex">
                           <div>ELS Product List</div>
                           <div class="d-flex">
							<a href="javascript:;"><button class="btn btn-info" onclick="importData()">Import</button></a> &nbsp;&nbsp;
							{!! Form::open(['url' =>'export-els-product-report', 'class' => 'form-horizontal', 'role' => 'form']) !!}
							<a href="javascript:;"><button class="btn btn-info" type="submit">Export</button></a> &nbsp;&nbsp;
							{!! Form::close() !!}
								<?php  if(in_array('17',Helper::addedPermission())){  ?>
                            <a href="{{url('elsproduct_form')}}"><button class="btn btn-primary" id="add_category_btn">+ Add ELS Product</button></a> 
                            <?php }  ?>
                          </div>							
                        </div>
						
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table id="dataTableID" class="table table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>Sr.No</th>
                                            <th>Date</th>
                                            <th>ELS Brand</th>
                                            <th>ELS Model</th>
                                            <th>Color</th>
                                            <th>UIN</th>
                                            <th>GRN No.</th>
                                            <th>IMEI 1</th>
                                            <th>IMEI 2</th>
                                            <th>RAM</th>
                                            <th>ROM</th>
                                            <th>Incoming Grade</th>
                                            <th>Purchased Price</th>
                                            <!--<th>Test</th>-->
                                            <th>Remark</th>    
                                            <th>In Vendor Name</th>     
                                            <th>Status</th>           
                                            <th>Sub Status</th>  
                                            <th>Out Vendor Name</th>      
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
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

<!-- Import Data Modal -->
<div id="importModel" class="modal fade" role="dialog">
	<div class="modal-dialog">
    <!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Import ELS Product</h4>
			</div>
		<div class="modal-body">
			{!! Form::open(['url' =>'upload-bulk-report', 'class' => 'form-horizontal', 'id' => 'save-form', 'role' => 'form','files'=>'true']) !!}
				<div class="form-group">
					<div class="col-md-12">
						{!! Form::label('title', 'Choose Product File', ['class' => 'control-label']) !!} 
						{!! Form::file('file_csv',['class' => 	'form-control','required', 'id'=>'file_csv','placeholder'=>'-Select-']) !!}  
					</div>					
                    <div class="col-md-12" style="padding: 17px;">
                        <button type="submit" class="btn btn-success mr-1">Submit <span class="btn-icon-right"><i class="fa fa-check"></i></span></button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel <span class="btn-icon-right"><i class="fa fa-close"></i></span></button>
						<a href="{{url('assets\files\els_product.csv')}}" class="btn btn-info light" download>Download Sample File</span></a>
                    </div>
				</div>
                </div>
            {!! Form::close() !!}
		</div>
      
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script type="text/javascript">
importData=function(){
	$('#importModel').modal('show');
}

saveData=function(){
	var form = $('#save-form');
	var url = form.attr('action');
	var csrfToken = "{{ csrf_token() }}";
	var formData = new FormData(form[0]);
	$.ajax({
		type: "POST",
		url: url,
		headers: {
			"X-CSRF-Token": csrfToken,
		},
		contentType: false,
		processData: false,
		data: formData,
		dataType:'JSON',
		success: function(d)
		{
			if(d.status){
				alert(d.message);
				location.reload();
			}else{
				alert(d.message);
			}
		}
	});
}

function delete_data(id)
{
    //alert(id);
    $("#delete_id").val(id);
}

$("#delete_btn").on("click", function (){
    toastr.options = {
        progressBar: "true",
        positionClass: "toast-top-right",
    };

	var id = $("#delete_id").val();
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
		
		if (response.code == 200) 
		{
			$("#delete_btn").prop("disabled",true);
			toastr.success(response.message);
			setTimeout(function () {
				window.location.reload();
			}, 2000);
		}
	});
});

$(document).ready(function () { 
      $("#overlay").fadeIn(800);　
	var oTable = $('#dataTableID').DataTable({
		"bDestroy": true,
		'processing': true,
		'serverSide': true,		
		"ajax":{
			"url": "{{url('fatch_els_product_list')}}",
		}, 	
		"columns": [
			{ data: "id" },
			{ data: "resived_date" },
			{ data: "bname" },
			{ data: "mname" },
			{ data: "colour_name" },
			{ data: "barcode" },
			{ data: "grn_no" },
			{ data: "imei_1" },
			{ data: "imei_2" },
			{ data: "ram" },
			{ data: "rom" },
			{ data: "grade" },
			{ data: "mrp" },
// 			{ data: "new_price" },
			{ data: "remark" },
			{ data: "vname" },
			{ data: "sub_status" },
			{ data: "status" },
			{ data: "out_vendor" },
			{ data: "id" },
		],
		"rowCallback": function (nRow, aData, iDisplayIndex) {
		  //  alert(nRow);
			var oSettings = this.fnSettings ();
			$("td:first", nRow).html(oSettings._iDisplayStart+iDisplayIndex +1);
			var actionBtn = '';
					<?php  if(in_array('17',Helper::editPermission())){  ?>
			actionBtn += '<a href="{{url('edit_elsproduct')}}/'+aData.id+'"><i class="fa fa-edit text-danger mr-2"></i></a> /';
			    <?php  }   ?>
			    		<?php  if(in_array('17',Helper::deletedPermission())){  ?>
			actionBtn += '<a href="javascript:;"  data-toggle="modal" data-target="#DeleteModal" onclick="delete_data('+aData.id+')"><i class="fa fa-trash text-danger mr-2"></i>Delete</a></td> &nbsp;&nbsp;';
			<?php  }  ?>
			$("td:eq(18)", nRow).html(actionBtn);
				$("#overlay").fadeOut(800);
			return nRow;
		
		},
		"order": [[0, 'asc']],
		"columnDefs": [{ orderable: false, "targets": 0 }]
	});
	@if(!in_array(\App\Models\RoleModel::find(\Auth::user()->role)->role_name,['admin','finance']))
		oTable.column(11).visible(false);
	@endif
	@if(!Helper::actionPermission())
		oTable.column(17).visible(false);
	@endif
	
});
</script>
@endsection