@extends('layouts.layout')
@section('title',$title)
@section('content')


<div id="page-wrapper" >
	 <div class="header"> 
		<h1 class="page-header">
			 <small>Devices Price List</small>
		</h1>
		<ol class="breadcrumb">
			  <li><a href="{{url('/home')}}">Home</a></li>
			  <li class="active">Devices Cost</li>
		</ol>     
	 </div>
	 <div id="page-inner">
		<div class="row">
			<div class="col-md-12">
				<!-- Advanced Tables -->
				<div class="panel panel-default">
					<div class="panel-heading d-flex">
						<div>Devices Price List</div>
						<div class="d-flex">
						    <?php  if(in_array('29',Helper::addedPermission())){  ?>
							<a href="javascript:;"><button class="btn btn-info" onclick="importData()">Import Price</button></a> &nbsp;&nbsp;
							<?php  }  ?>
							{!! Form::open(['url' =>'export-product-price-report', 'class' => 'form-horizontal', 'role' => 'form']) !!}
							<a href="javascript:;"><button class="btn btn-info" type="submit">Export</button></a> &nbsp;&nbsp;
							{!! Form::close() !!}
						</div>
					</div>

					<div class="panel-body">
						<div class="table-responsive" id="dvData">
							<table id="dataTableID" class="table table-striped table-bordered table-hover data_tablelist">
								<thead>
									<tr>
										<th>Sr.No</th>
										<th>UIN</th>
										<th>PIN</th>
										<th>Brand</th>
										<th>Model</th>
										<th>Colour</th>
										<th>Assignd Engineer</th>
										<th>Part Consumed</th>
										<th>Part Cost</th>										
										<th>Extra Cost</th>										
										<th>Extra Amount</th>										
										<th>Device Cost</th>
										<th>Total Cost</th>
										<th>Status</th>
										<th>Action</th>
									</tr>
										
								</thead>
									<tbody>
				<?php  
				$cc=1;
				foreach($data as $value){ 
				$part_name=explode(',',$value->part_name);
				$part_price=explode(',',$value->part_price);
			    $pin_name=explode(',',$value->pin_name);
				
				
				
				// $els_system_id=$value->els_system_id;
				
				// $user = DB::table('els_system_allocated_parts_barcode')->where('els_system_id', $value->els_system_id)->get();
				
				// foreach($user as $values){
				//     $barcode_id[]=$values->barcode_id;
				//     $id[]=$values->id;
				    
				    
				// $usesr = DB::table('received_parts_barcode_list')->where('id', $values->barcode_id)->first();
				
				// $barcode[]=$usesr->barcode;
				// $received_part_id[]=$usesr->received_part_id;
				
				
				    
				    
				// }
				
				
				
				
				?>
				
				<?php   
				for($ff=0;$ff<count($part_name);$ff++){
				?>
									<tr>
									    
									    
									    <td><?=$cc++?></td>  
									    <td><?=$value->barcode?></td>
									        <td><?=$pin_name[$ff]?></td>
									     <td><?=$value->bname?></td>
									      <td><?=$value->mname?></td>
									       <td><?=$value->colour_name?></td>
									        <td><?=$value->name?></td>
									         <td><?=$part_name[$ff]?></td>
									          <td><?=$part_price[$ff]?></td>
									   <td><?=$value->extra_expence?></td>
									   <td><?=$value->extra_amount?></td>
									  <td><?=$value->old_price?></td>
									   <td><?=$value->new_price?></td>
									   <td><?=$value->status?></td>
									  <td>
									      <a href="javascript:;" onclick="add_more_expense( <?=$value->id?>)"><i class="fa fa-edit text-primary mr-2"></i></a>
									      
									     </td>
									    
									    
									    
									    
									    
									    </tr>
									<?php  }  }   ?>
									
									</tbody>
									
									
								
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Extra Expence Modal -->
<div id="expenceModel" class="modal fade" role="dialog">
	<div class="modal-dialog">
    <!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Extra Cost</h4>
			</div>
			<div id="modal_body" class="modal-body">
				
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
				<h4 class="modal-title">Import Devices Price</h4>
			</div>
		<div class="modal-body">
			{!! Form::open(['url' =>'upload-device-price', 'class' => 'form-horizontal', 'id' => 'save-form', 'role' => 'form','files'=>'true']) !!}
				<div class="form-group">
					<div class="col-md-12">
						{!! Form::label('title', 'Choose Devices Price File', ['class' => 'control-label']) !!} 
						{!! Form::file('file_csv',['class' => 	'form-control','required', 'id'=>'file_csv','placeholder'=>'-Select-']) !!}  
					</div>					
                    <div class="col-md-12" style="padding: 17px;">
                        <button type="submit" class="btn btn-success mr-1">Submit <span class="btn-icon-right"><i class="fa fa-check"></i></span></button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel <span class="btn-icon-right"><i class="fa fa-close"></i></span></button>
						<a href="{{url('assets\files\els_product_price.csv')}}" class="btn btn-info light" download>Download Sample File</span></a>
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

$(document).ready(function () {     
// 	var oTable = $('#dataTableID').DataTable({
// 		"bDestroy": true,
// 		'processing': true,
// 		'serverSide': true,		
// 		"ajax":{
// 			"url": "{{url('get-product-final-price-list')}}"
// 		}, 	
// 		"columns": [
// 			{ data: "id" },
// 			{ data: "barcode" },
// 			{ data: "bname" },
// 			{ data: "mname" },
// 			{ data: "colour_name" },
// 			{ data: "name" },
// 			{ data: "part_name" },
// 			{ data: "part_price" },
// 			{ data: "extra_expence" },
// 			{ data: "extra_amount" },
// 			{ data: "old_price" },
// 			{ data: "new_price" },
// 			{ data: "status" },
// 			{ data: "id" },
// 		],
// 		"rowCallback": function (nRow, aData, iDisplayIndex) {
// 			var oSettings = this.fnSettings ();
// 			$("td:first", nRow).html(oSettings._iDisplayStart+iDisplayIndex +1);
// 			 <?php  if(in_array('29',Helper::editPermission())){  ?>
// 			var actionBtn = '<a href="javascript:;" onclick="add_more_expense('+aData.id+')"><i class="fa fa-edit text-primary mr-2"></i></a> &nbsp;&nbsp;';
// 			<?php   }  else {  ?>
// 			var actionBtn ='';
// 			<?php  }  ?>
// 			$("td:eq(13)", nRow).html(actionBtn);
// 			return nRow;
// 		},
// 		"order": [[1, 'asc']],
// 		"columnDefs": [{ orderable: false, "targets": 0 }]
// 	}); 
	
// 	@if(!Helper::actionPermission())
// 	oTable.column(13).visible(false);
// 	@endif
	
	add_more_expense=function(id){
		$.ajax({
			type: "POST",
			url: "{{url(route('add-more-expence'))}}",
			data: {id:id},
			dataType:'JSON',
			success: function(d)
			{
				if(d.status){
					$('#modal_body').html(d.html);
					$('#expenceModel').modal('show');					
				}else{
					alert(d.message);
				}
			}
		});
	}
	
	addMore=function(){
		$.ajax({
			type: "POST",
			url: "{{url(route('add-more-expence-option'))}}",
			dataType:'JSON',
			success: function(d)
			{
				if(d.status){
					$('.add-more-expence').before(d.html);	
				}else{
					alert(d.message);
				}
			}
		});
	}
	
	remove=function(e){
		$(e).parent().parent().remove();
	}
	
	isNumberKey=function(evt){
		var charCode = (evt.which) ? evt.which : event.keyCode
		if (charCode > 31 && (charCode < 48 || charCode > 57))
			return false;
		return true;
	}
		
	saveDetails=function(){
		var form = $('#save-form');
		var url = form.attr('action');
		var formData = new FormData(form[0]);
		$.ajax({
			type: "POST",
			url: url,
			contentType: false,
			processData: false,
			data: formData,
			dataType:'JSON',
			success: function(d)
			{
				if(d.status){
					oTable.ajax.reload();
					$('.modal').modal('hide');					
				}else{
					alert(d.message);
				}
			}
		});
	}
});
</script>
@endsection