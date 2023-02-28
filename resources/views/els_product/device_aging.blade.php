@extends('layouts.layout')
@section('title',$title)
@section('content')
<div id="page-wrapper" >
	 <div class="header"> 
		<h1 class="page-header">
			<small>Device Aging Report List</small>
		</h1>
		<ol class="breadcrumb">
			<li><a href="{{url('/home')}}">Home</a></li>
			<li class="active">Device Aging Statistics Report</li>
		</ol>     
	 </div>
	 <div id="page-inner">
		<div class="row">
			<div class="col-md-12">
				<!-- Advanced Tables -->
				<div class="panel panel-default">
					<div class="panel-heading d-flex">
						<div>Device Aging Report </div>
						<div class="d-flex">	
							<!--<div id="reportrange" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">-->
							<!--	<i class="fa fa-calendar"></i>&nbsp;-->
							<!--	<span></span> <i class="fa fa-caret-down"></i>-->
							<!--</div>-->
							
							&nbsp;&nbsp;
							<a href="{{url('download-device_aging')}}"><button class="btn btn-primary button_right" id="add_category_btn">Export </button></a> 
						</div>
					</div>

				
					
					<div class="panel-heading">
						Device Aging  Report List				
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
                                            <!--<th>GRN No.</th>-->
                                            <!--<th>IMEI 1</th>-->
                                            <!--<th>IMEI 2</th>-->
                                            <th>RAM</th>
                                            <th>ROM</th>
                                            <!--<th>Incoming Grade</th>-->
                                            <!--<th>Purchased Price</th>-->
                                            <th style="display:none;">Return Price</th>
                                            <!--<th>Remark</th>    -->
                                            <!--<th>In Vendor Name</th>     -->
                                            <th>Sub Status</th>           
                                            <th>Status</th>  
                                           
                                            <th>Aging (Days)</th>
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
				<h4 class="modal-title">Import ELS Status</h4>
			</div>
		<div class="modal-body">
			{!! Form::open(['url' =>'upload-change-status', 'class' => 'form-horizontal', 'id' => 'save-form', 'role' => 'form','files'=>'true']) !!}
				<div class="form-group">
					<div class="col-md-12">
						{!! Form::label('title', 'Choose Product File', ['class' => 'control-label']) !!} 
						{!! Form::file('file_csv',['class' => 	'form-control','required', 'id'=>'file_csv','placeholder'=>'-Select-']) !!}  
					</div>					
                    <div class="col-md-12" style="padding: 17px;">
                        <button type="submit" class="btn btn-success mr-1">Submit <span class="btn-icon-right"><i class="fa fa-check"></i></span></button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel <span class="btn-icon-right"><i class="fa fa-close"></i></span></button>
						<a href="{{url('assets\files\els_status.csv')}}" class="btn btn-info light" download>Download Sample File</span></a>
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
	var oTable = $('#dataTableID').DataTable({
		"bDestroy": true,
		'processing': true,
		'serverSide': true,		
		"ajax":{
			"url": "{{url('fatch_els_product_list_ind')}}",
		}, 	
		"columns": [
			{ data: "id" },
			{ data: "resived_date" },
			{ data: "bname" },
			{ data: "mname" },
			{ data: "colour_name" },
			{ data: "barcode" },
// 			{ data: "grn_no" },
// 			{ data: "imei_1" },
// 			{ data: "imei_2" },
			{ data: "ram" },
			{ data: "rom" },
// 			{ data: "grade" },
// 			{ data: "mrp" },
		  //  { data: "new_price" },
// 			{ data: "remark" },
// 			{ data: "vname" },
			{ data: "sub_status" },
			{ data: "status" },
// 			{ data: "out_vendor" },
			{ data: "age" },
		],
		"rowCallback": function (nRow, aData, iDisplayIndex) {
			var oSettings = this.fnSettings ();
			$("td:first", nRow).html(oSettings._iDisplayStart+iDisplayIndex +1);
			var actionBtn = '';
			actionBtn += '<a href="{{url('edit_elsproduct')}}/'+aData.id+'"><i class="fa fa-edit text-danger mr-2"></i></a> /';
			actionBtn += '<a href="javascript:;"  data-toggle="modal" data-target="#DeleteModal" onclick="delete_data('+aData.id+')"><i class="fa fa-trash text-danger mr-2"></i>Delete</a></td> &nbsp;&nbsp;';
// 			$("td:eq(10)", nRow).html(actionBtn);
			return nRow;
		},
		"order": [[0, 'asc']],
		"columnDefs": [{ orderable: false, "targets": 0 }]
	});
	@if(!in_array(\App\Models\RoleModel::find(\Auth::user()->role)->role_name,['admin','finance']))
		oTable.column(5).visible(false);
	@endif
	@if(!Helper::actionPermission())
		oTable.column(11).visible(false);
	@endif
	
});
</script>



<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> 
<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<script type="text/javascript">
var start = moment().subtract(29, 'days');
var end = moment();
@if(session()->get('start_date') && session()->get('end_date'))
end = moment("{{session()->get('end_date')}}");
start = moment("{{session()->get('start_date')}}");
@endif 
function cb(start, end) {
	var csrfToken = "{{ csrf_token() }}";
	$.ajax({
		type: "POST",
		url: "{{url('set_date_range_filterd')}}",
		headers: {
			"X-CSRF-Token": csrfToken,
		},
		data: {start_date:start.format('YYYY-MM-DD'),end_date:end.format('YYYY-MM-DD')},
		dataType:'JSON',
		success: function(d)
		{
		}
	});
    $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
}

function cba(start, end) {
	var csrfToken = "{{ csrf_token() }}";
	$.ajax({
		type: "POST",
		url: "{{url('set_date_range_filterd')}}",
		headers: {
			"X-CSRF-Token": csrfToken,
		},
		data: {start_date:start.format('YYYY-MM-DD'),end_date:end.format('YYYY-MM-DD')},
		dataType:'JSON',
		success: function(d)
		{
			location.reload();
		}
	});
    $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
}

$('#reportrange').daterangepicker({
    startDate: start,
    endDate: end,
    ranges: {
       'Today': [moment(), moment()],
       'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
       'Last 7 Days': [moment().subtract(6, 'days'), moment()],
       'Last 30 Days': [moment().subtract(29, 'days'), moment()],
       'This Month': [moment().startOf('month'), moment().endOf('month')],
       'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
    }
}, cba);

cb(start, end);

$(document).ready(function () {
	$('#dataTableID').DataTable();
	
	
	
	// var oTable = $('#dataTableID').DataTable({
		// "bDestroy": true,
		// 'processing': true,
		// 'serverSide': true,		
		// "ajax":{
			// "url": "{{url('get-enginner-work-report-list')}}"
		// }, 	
		// "columns": [
			// { data: "id" },
			// { data: "name" },
			// { data: "repair" },
			// { data: "l3" },
			// { data: "l4" },
			// { data: "fqc" },
			// { data: "fqc_fails" },
			// { data: "shrink_pack" },
			// { data: "total_system" },
		// ],
		// "rowCallback": function (nRow, aData, iDisplayIndex) {
			// var oSettings = this.fnSettings ();
			// $("td:first", nRow).html(oSettings._iDisplayStart+iDisplayIndex +1);
			// return nRow;
		// },
		// "order": [[1, 'asc']],
		// "columnDefs": [{ orderable: false, "targets": 0 }]
	// });
});
</script>



@endsection