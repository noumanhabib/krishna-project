@extends('layouts.layout')
@section('title',$title)
@section('content')
<div id="page-wrapper" >
	 <div class="header"> 
		<h1 class="page-header">
			 <small>Collection</small>
		</h1>
		<ol class="breadcrumb">
			  <li><a href="{{url('/home')}}">Home</a></li>
			  <li class="active">Collection</li>
		</ol>     
	 </div>
	 <div id="page-inner">
		<div class="row">
			<div class="col-md-12">
				<!-- Advanced Tables -->
				<div class="panel panel-default">
					<div class="panel-heading d-flex">
					   <div>Collection List</div>
					   <div class="d-flex">
						<form method="POST" action="https://renewhub.controlz.world/exportcollectback/" accept-charset="UTF-8" class="form-horizontal" role="form"><input name="_token" type="hidden" value="p2oJkaKaV0RbCCis1mG9PnkAlXqjIqk2q854aT1q">
							<a href="javascript:;"><button class="btn btn-info" type="submit">Export</button></a> &nbsp;&nbsp;
							</form>
						<a href="{{url('collect_product_part')}}"><button class="btn btn-primary button_right" id="add_category_btn">+ Collection List </button></a> 
					  </div>							
					</div>
					<div class="panel-body">
						<div class="table-responsive" id="dvData">
							<table id="dataTableID" class="table table-striped table-bordered table-hover data_tablelist">
								<thead>
									<tr>
										<th>Sr.No</th>
										<th>UIN</th>                                          
										<th>IMEI 1</th>           
										<th>IMEI 2</th>           
										<th>Brand</th>
										<th>Model</th>
										<th>Colour</th>
										<th>Assignd Engineer</th>										
										<th>Consumed Parts</th>
										<th>Sub Status</th>
										<th>Date</th>
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
</div>


<!-- Edit Brand Modal -->
<div class="modal fade" id="editModel" tabindex="-1" role="dialog" aria-labelledby="ViewDetailModal">
    <div class="modal-dialog modal-dialog-centered modal-min" role="document">
        <div class="modal-content">
             <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <center><h3>Els Engineer status Log Details</h3></center>
      </div>
            <div class="modal-body text-center">

                 <table class="table table-striped table-bordered table-hover" id="product_list_tablelist">
                    <thead>
                        <tr>
                            <th>Sr.No</th>
                            <th>Engineer</th> 
                            <th>Status</th>
                            <th>Allocated on</th>
                            <th>Remark</th>
                            <th>Current Phase</th>
                            <th>Allocated Type</th>
                            
               
                            
                            
                            
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
				<h4 class="modal-title">Import Engineer Allocation</h4>
			</div>
		<div class="modal-body">
			{!! Form::open(['url' =>'upload-collect-allocation-enginner', 'class' => 'form-horizontal', 'id' => 'save-form', 'role' => 'form','files'=>'true']) !!}
				<div class="form-group">
					<div class="col-md-12">
						{!! Form::label('title', 'Choose Devices Price File', ['class' => 'control-label']) !!} 
						{!! Form::file('file_csv',['class' => 	'form-control','required', 'id'=>'file_csv','placeholder'=>'-Select-']) !!}  
					</div>					
                    <div class="col-md-12" style="padding: 17px;">
                        <button type="submit" class="btn btn-success mr-1">Submit <span class="btn-icon-right"><i class="fa fa-check"></i></span></button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel <span class="btn-icon-right"><i class="fa fa-close"></i></span></button>
						<a href="{{url('assets\files\els_product_enginner_allocation.csv')}}" class="btn btn-info light" download>Download Sample File</span></a>
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
	var oTable = $('#dataTableID').DataTable({
		"bDestroy": true,
		'processing': true,
		'serverSide': true,		
		"ajax":{
			"url": "{{url('part-collect-product-list')}}"
		}, 	
		"columns": [
			{ data: "id" },
			{ data: "barcode" },
			{ data: "imei_1" },
			{ data: "imei_2" },
			{ data: "bname" },
			{ data: "mname" },
			{ data: "colour_name" },
			{ data: "name" },
			{ data: "consumed_part" },
			{ data: "status" },
			{ data: "created_at" },
			{ data: "id" },
		],
		"rowCallback": function (nRow, aData, iDisplayIndex) {
			var oSettings = this.fnSettings ();
			$("td:first", nRow).html(oSettings._iDisplayStart+iDisplayIndex +1);			
			var actionBtn = '<a href="javascript:;"  onclick="view_detail('+aData.id+')""><i class="fa fa-eye text-primary mr-2"></i></a> &nbsp;&nbsp;';
			$("td:eq(11)", nRow).html(actionBtn);
			return nRow;
		},
		"order": [[1, 'asc']],
		"columnDefs": [{ orderable: false, "targets": 0 }]
	}); 
	@if(!Helper::actionPermission())
	//oTable.column(10).visible(false);
	@endif
	changeStatus=function(id,status){
		$('#id').val(id);
		$('#status').val(status);
		$('#editModel').modal('show');
	}
	
	saveStatus=function(){
		var form = $('#save-form');
		var url = form.attr('action');
		$.ajax({
			type: "POST",
			url: url,
			data: form.serialize(),
			dataType:'JSON',
			success: function(d)
			{
				if(d.status){
					oTable.ajax.reload();
					$('#editModel').modal('hide');					
				}else{
					alert(d.message);
				}
			}
		});
	}
});
function view_detail($id)
{
      	$('#editModel').modal('show');
      	 //$("#product_list_tablelist").html('');
      	  $("#product_list_tablelist tbody").html('');
     //alert($id);
      var id = $id;
        var csrfToken = "{{ csrf_token() }}";
        var Url = "{{url('get_engg_activity_log')}}";
        $.ajax({
            url: Url,
            headers: {
                "X-CSRF-Token": csrfToken,
            },
            type: "POST",
            data: "id=" + id,
        }).done(function (response) {
          
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
                    if(i==0){
                        var ss="style=background:#b6e7b6;";
                    }
                    else
                    {
                        var ss='';
                    }
                     var num_increment=num++;  
                     var id          = response["data"][i].id;
                     var ename  = response["data"][i].ename;
                     var sname  = response["data"][i].sname;
                     var created_at        = response["data"][i].created_at;
                     var remark        = response["data"][i].remark;
                     var acre        = response["data"][i].acre;
                     var sss  = response["data"][i].sss;
                    
                    
                     if(response["data"][i].type==1)
                     {
                     var type='Distributor';     
                     } else
                     {
                         var type='Collect Back'; 
                     }
                    
        
                     var tr_str="<tr "+ss+"><td>"+num_increment+"</td><td style='text-transform:capitalize'>"+ename+"</td><td style='text-transform:capitalize'>"+sname+"</td><td style='text-transform:capitalize'>"+created_at+"</td><td style='text-transform:capitalize'>"+remark+"</td><td style='text-transform:capitalize'>"+acre+"</td><td style='text-transform:capitalize'>"+sss+"</td><td class='qnty'>"+type+"</td></tr>";
                      $("#product_list_tablelist tbody").append(tr_str);
                }
                
            //   $("#product_list_tablelist").DataTable();
            }
             else
            {
                var tr_str='<tr><td colspan="8">No Records are found</td></tr>';
                 $("#product_list_tablelist tbody").append(tr_str); 
                //  $("#product_list_tablelist").DataTable();
            }

        });
}

</script>
@endsection