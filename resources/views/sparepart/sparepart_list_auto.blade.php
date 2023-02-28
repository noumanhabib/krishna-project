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
				  <li class="active">SparePart</li>
			</ol>     
         </div>
         <div id="page-inner">
            <div class="row">
                <div class="col-md-12">
                    <!-- Advanced Tables -->
                    <div class="panel panel-default">
						<div class="panel-heading d-flex">
                           <div>Spare Part List</div>
                           <div class="d-flex">
						    @if(Helper::actionPermission())
							<!--<a href="javascript:;"><button class="btn btn-info" onclick="importData()">Import</button></a> &nbsp;&nbsp;-->
							@endif
							{!! Form::open(['url' =>'export-spare-parts', 'class' => 'form-horizontal', 'role' => 'form']) !!}
							<!--<a href="javascript:;"><button class="btn btn-info" type="submit">Export</button></a> &nbsp;&nbsp;-->
							{!! Form::close() !!}
							@if(Helper::actionPermission())
                            <!--<a href="{{url('add_sparepart')}}"><button class="btn btn-primary" id="add_category_btn">+ Add Spare Part</button></a> &nbsp;&nbsp;-->
                            	<?php  if(in_array('48',Helper::addedPermission())){  ?>
                                <a href="{{url('add_sparepart_auto')}}"><button class="btn btn-primary" id="add_category_btn">+ Add Spare Part AutoGenerate</button></a>
                                <?php  }   ?>
							@endif							
                          </div>							
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover data_tablelist">
                                    <thead>
                                        <tr>
                                            <th>Sr.No</th>
                                            <!--<th>Part Name</th>-->
                                            <th>Part Model</th>
                                            <th>Part Brand</th>
                                            <th>Type</th>
                                            <!--<th>Colour</th>-->
                                            <!--<th>SKU Number</th>-->
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
                                                <!--<td>{{$row['name']}}</td>-->
                                                <td>{{$row['mname']}}</td>
                                                <td>{{$row['bname']}}</td>
                                                <td>{{$row['type']}}</td>
                                                <!--<td>{{$row['colour_name']}}</td>-->
                                                <!--<td>{{$row['sku_no']}}</td>-->
												
                                                <!--<td><a href='#'  data-toggle='modal' data-target='#ViewDetailModal' onclick='view_detail("{{$row['id']}}")'><i class='fa fa-eye text-danger mr-2'></i>Detail</a></td>-->
												@if(Helper::actionPermission())
                                                <td>
                                                    <?php  if(in_array('48',Helper::editPermission())){  ?>
                                                    <a href='{{url("add_sparepart_auto")}}/{{$row['model_id']}}'><i class='fa fa-edit text-danger mr-2'></i></a> 
                                                    <?php  }   ?>
                                                <!--<a href='#'  data-toggle='modal' data-target='#DeleteModal' onclick='delete_data("{{$row['sku_id']}}")'><i class='fa fa-trash text-danger mr-2'></i>Delete</a></td>-->
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
<div class="modal fade" id="DeleteModal" tabindex="-1" role="dialog" aria-labelledby="DeleteMenuCategory">
    <div class="modal-dialog modal-dialog-centered modal-min" role="document">
        <div class="modal-content">
            <div class="modal-body text-center">
                <i class="fa fa-ban" style="font-size: 130px; color: #ff8800;"></i>
                <h3>Are you sure want to delete it?</h3>
                <form method="post" style="padding: 24px;">
                    @csrf
                    <input type="hidden" name="delete_category_id" id="delete_id" value="">
                    <button type="button" class="btn btn-success light" id="delete_btn" data-id="">Yes, delete it! <span class="btn-icon-right"><i class="fa fa-check"></i></span></button>
                    <button type="button" class="btn btn-danger light" data-dismiss="modal">Cancel <span class="btn-icon-right"><i class="fa fa-close"></i></span></button>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- View Detail Model -->
<div class="modal fade" id="ViewDetailModal" tabindex="-1" role="dialog" aria-labelledby="ViewDetailModal">
    <div class="modal-dialog modal-dialog-centered modal-min" role="document">
         
        <div class="modal-content">
             <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h3>Details</h3>
      </div>
            <div class="modal-body text-center">

                 <table class="table table-striped table-bordered table-hover" id="data_list_tablelist">
                    <thead>
                        <tr>
                            <th>Sr.No</th>
                            <th>color</th> 
                            <th>price</th>
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
				<h4 class="modal-title">Import Spare Part</h4>
			</div>
		<div class="modal-body">
			{!! Form::open(['url' =>'upload-bulk-spare-parts', 'class' => 'form-horizontal', 'id' => 'save-form', 'role' => 'form','files'=>'true']) !!}
				<div class="form-group">
					<div class="col-md-12">
						{!! Form::label('title', 'Choose Parts File', ['class' => 'control-label']) !!} 
						{!! Form::file('file_csv',['class' => 	'form-control','required', 'id'=>'file_csv','placeholder'=>'-Select-']) !!}  
					</div>					
                    <div class="col-md-12" style="padding: 17px;">
                        <button type="submit" class="btn btn-success mr-1">Submit <span class="btn-icon-right"><i class="fa fa-check"></i></span></button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel <span class="btn-icon-right"><i class="fa fa-close"></i></span></button>
						<a href="{{url('assets\files\spart_parts.csv')}}" class="btn btn-info light" download>Download Sample File</span></a>
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
        var csrfToken = "{{ csrf_token() }}";

        var Url = "{{url('delete_sparepartproduct')}}";
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
        var Url = "{{url('get_color_price_detail_by_seriesid')}}";
        $.ajax({
            url: Url,
            headers: {

                "X-CSRF-Token": csrfToken,
               
            },
            type: "POST",
            data: "id=" + id,
        }).done(function (response) {  
         console.log(response);     
           $("#data_list_tablelist tbody").html('');
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
                     var price=response["data"][i].price;
                    
                     //var created_on1=convert(created_on);
                     var tr_str="<tr><td>"+num_increment+"</td><td style='text-transform:capitalize'>"+name+"</td><td class='qnty'>"+price+"</td></tr>";
                      $("#data_list_tablelist tbody").append(tr_str);
                }

               $("#data_list_tablelist").DataTable();
            }
             else
            {
                var tr_str='<tr><td colspan="3">No Records are found</td></tr>';
                 $("#data_list_tablelist tbody").append(tr_str); 
                 $("#data_list_tablelist").DataTable();
            }

        });
}
</script>
@endsection
    