@extends('layouts.layout')
@section('title',$title)
@section('content')

<style>

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
</style>
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
                           <div> Request Order List</div>
							<div class="d-flex">
								<a href="javascript:;"><button class="btn btn-info" onclick="importData()">Import</button></a> &nbsp;&nbsp;
								{!! Form::open(['url' =>'export-request-order-report', 'class' => 'form-horizontal', 'role' => 'form']) !!}
								<a href="javascript:;"><button class="btn btn-info" type="submit">Export</button></a> &nbsp;&nbsp;
								{!! Form::close() !!}
										<?php  if(in_array('18',Helper::addedPermission())){  ?>
								<a href="{{url('request_order_form')}}"><button class="btn btn-primary" id="add_category_btn">+ Add Request Order</button></a>
								<?php  }  ?>
							</div>
                        </div>
						
                        <div class="panel-body">
                            <div class="table-responsive" id="dvData">
                                <table class="table table-striped table-bordered table-hover data_tablelist">
                                    <thead>
                                        <tr>
                                            <th>Sr.No</th>
                                            <th>Date</th> 
                                            <th>RO No.</th> 
                                            <th>UIN</th>                                         
                                            <th>View Request Parts</th> 
											@if(Helper::actionPermission())	
                                            <th>Action</th>
											@endif
                                        </tr>
                                    </thead>
                                     <tbody>
                                    @php $i=1;@endphp
                                       @if(!empty($data))
                                           @foreach ($data as $row)
                                            <tr>
                                                <td>{{$i++}}</td>
                                                <td>{{date('d/m/Y',strtotime($row['created_at']))}}</td>
                                                <td>RO-{{$row['id']}}</td>
                                                <td>{{$row['barcode']}}</td>

                                                <td><a href='#'  data-toggle='modal' data-target='#ViewDetailModal' onclick='view_detail("{{$row['id']}}")'><i class='fa fa-eye text-danger mr-2'></i>Detail</a></td>
												@if(Helper::actionPermission())
														<?php  if(in_array('18',Helper::editPermission())){  ?>
                                                <td><a href='{{url("edit_request_order")}}/{{$row['id']}}'><i class='fa fa-edit text-danger mr-2'></i></a> </td>
                                                <?php  }  ?>
												@endif
                                            </tr>
                                          @endforeach
                                       @endif  
                                    </tbody>
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

<!-- View Product Model -->
<div class="modal fade" id="ViewDetailModal" tabindex="-1" role="dialog" aria-labelledby="ViewDetailModal">
    <div class="modal-dialog modal-dialog-centered modal-min" role="document">
        <div class="modal-content">
             <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h3>Part Details</h3>
      </div>
            <div class="modal-body text-center">

                 <table class="table table-striped table-bordered table-hover" id="product_list_tablelist">
                    <thead>
                        <tr>
                            <th>Sr.No</th>
                            <th>Brand</th> 
                            <th>Model</th>
                            <th>Part Type</th>
                            <th>Part</th>
                            <th>Colour</th>
                            <th>Quantity</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                     <tbody>
                       
                    </tbody>
                    
                </table>

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
				<h4 class="modal-title">Import Request Order</h4>
			</div>
		<div class="modal-body">
			{!! Form::open(['url' =>'upload_request_order', 'class' => 'form-horizontal', 'id' => 'save-form', 'role' => 'form', 'onsubmit'=>'return false','files'=>'true']) !!}
				<div class="form-group">
					<div class="col-md-12">
						{!! Form::label('title', 'Choose RO File', ['class' => 'control-label']) !!} 
						{!! Form::file('ro_file',['class' => 	'form-control','required', 'id'=>'ro_file','placeholder'=>'-Select-']) !!}  
					</div>					
                    <div class="col-md-12" style="padding: 17px;">
                        <button type="button" onclick="saveData()" class="btn btn-success mr-1">Submit <span class="btn-icon-right"><i class="fa fa-check"></i></span></button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel <span class="btn-icon-right"><i class="fa fa-close"></i></span></button>
						<a href="{{url('assets\files\request_order.csv')}}" class="btn btn-info light" download>Download Sample File</span></a>
                    </div>
				</div>
                </div>
            {!! Form::close() !!}
		</div>
      
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script type="text/javascript">
    $("#overlay").fadeIn(800);
       $("#overlay").fadeOut(800);
importData=function(){
	$('#importModel').modal('show');
}

saveData=function(){
         $("#overlay").fadeIn(800);
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
		    $("#overlay").fadeOut(800);
			if(d.status){
				alert(d.message);
				location.reload();
			}else{
				alert(d.message);
			}
		}
	});
}

/*..............Delete Category.........................................*/
 function delete_data(id)
{
    $("#delete_id").val(id);
}
$("#delete_btn").on("click", function () {
        toastr.options = {
            progressBar: "true",
            positionClass: "toast-top-right",
        };  
        var id = $("#delete_id").val();
        //alert(id);
        var csrfToken = "{{ csrf_token() }}";
        var Url = "{{url('delete_request_order')}}";
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


function view_detail($id)
{
       
     //alert($id);
      var id = $id;
        var csrfToken = "{{ csrf_token() }}";
        var Url = "{{url('get_part_list_by_id')}}";
        $.ajax({
            url: Url,
            headers: {
                "X-CSRF-Token": csrfToken,
            },
            type: "POST",
            data: "id=" + id,
        }).done(function (response) {
            //   $("#overlay").fadeIn(800);
        console.log(response);       
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
                    // console.log(response["data"][1].barcodes);
                     var num_increment=num++;  
                     var id          = response["data"][i].id;
                     var brand_name  = response["data"][i].bname;
                     var model_name  = response["data"][i].mname;
                     var type        = response["data"][i].type;
                     var part        = response["data"][i].name;
                     var color_name  = response["data"][i].color_name;
                     var quantity    = response["data"][i].quantity;


                     if(response["data"][i].barcodes == "")
                     {
                         var barcodes    = response["data"][i].barcodes;
                     }else{
                         var barcodes = "Consumed";  
                     }
                    
                     //var created_on1=convert(created_on);
                     var tr_str="<tr><td>"+num_increment+"</td><td style='text-transform:capitalize'>"+brand_name+"</td><td style='text-transform:capitalize'>"+model_name+"</td><td style='text-transform:capitalize'>"+type+"</td><td style='text-transform:capitalize'>"+part+"</td><td style='text-transform:capitalize'>"+color_name+"</td><td class='qnty'>"+quantity+"</td><td style='text-transform:capitalize'>"+barcodes+"</td></tr>";
                      $("#product_list_tablelist tbody").append(tr_str);
                }
                //   $("#overlay").fadeIn(800);
                
               $("#product_list_tablelist").DataTable();
            }
             else
            {
                var tr_str='<tr><td colspan="8">No Records are found</td></tr>';
                 $("#product_list_tablelist tbody").append(tr_str); 
                 $("#product_list_tablelist").DataTable();
            }

        });
}
</script>


@endsection