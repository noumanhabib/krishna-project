@extends('layouts.layout')
@section('title',$title)
@section('content')
<div id="page-wrapper" >
         <div class="header"> 
                        <h1 class="page-header">
                             <small>Purchase Order List</small>
                        </h1>
                        <ol class="breadcrumb">
                              <li><a href="{{url('/home')}}">Home</a></li>
                              <li class="active">Purchase Order List</li>
                        </ol>     
         </div>
         <div id="page-inner">
            <div class="row">
                <div class="col-md-12">
                    <!-- Advanced Tables -->
                    <div class="panel panel-default">
                        <div class="panel-heading">
                           Purchase Order List
                            <a href="{{url('add_purchase_order')}}"><button class="btn btn-primary" id="add_category_btn">+ Add Purchase Order</button></a> 
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                 <table class="table table-striped table-bordered table-hover" id="po_tablelist">
                                    <thead>
                                        <tr>
                                            <th>Sr.No</th>
                                            <th>Dummy Title</th>
                                            <th>Dummy Title</th>
                                            <th>Dummy Title</th>
                                            <th>Action</th>
                                            
                                        </tr>
                                    </thead>
                                    <tbody>
                                       <tr>
                                         <td>1</td>
                                         <td>Dummy text</td>
                                         <td>Dummy text</td>
                                         <td>Dummy text</td>
                                         <td><a href="{{url('edit_purchase_order')}}"><i class='fa fa-edit text-primary mr-2'></i></a> / <a href="#"><span class='glyphicon glyphicon-trash'></span></a></td>
                                       </tr>
                                       <tr>
                                         <td>2</td>
                                         <td>Dummy text</td>
                                         <td>Dummy text</td>
                                         <td>Dummy text</td>
                                         <td><a href="{{url('edit_purchase_order')}}"><i class='fa fa-edit text-primary mr-2'></i></a> / <a href="#"><span class='glyphicon glyphicon-trash'></span></a></td>
                                       </tr>
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
                    <button type="button" class="btn btn-danger light" data-dismiss="modal" id="cancel">Cancel <span class="btn-icon-right"><i class="fa fa-close"></i></span></button>
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
                    <input type="hidden" name="delete_story_id" id="delete_story_id" value="">
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
/*cancel inavtivate model...............................*/
$("#cancel").on('click',function(){
  window.location.reload();
});

/*get categories list...................................*/
    function fetchRecords() 
    {
        
        var csrfToken = "{{ csrf_token() }}";
        var Url = "{{url('fetch_story_list')}}";
        $.ajax({
            url: Url,
            headers: {
               'X-CSRF-Token': csrfToken,
            },
            type: "GET",
            
        }).done(function (response) {
            /*console.log(response);
            return;*/
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
                     var category_name=response["data"][i].name_en;
                     var story_type=response["data"][i].story_type;
                     var year=response["data"][i].year;
                     var kid_name_en=response["data"][i].kid_name_en;
                     var kid_descp_en=response["data"][i].kid_descp_en;

                     var kid_name_hi=response["data"][i].kid_name_hi;
                     var kid_descp_hi=response["data"][i].kid_descp_hi;

                     var status=response["data"][i].status;
                     var date     = new Date(response["data"][i].created_at);
                    
                    const options = { year: "numeric", month: "long", day: "numeric" };
                    var newdate = date.toLocaleDateString(undefined, options);
                    if(status==1)
                    {
                        var check="checked";
                    }
                    else
                    {
                        var check="";
                    }
                    var kid_image=response["data"][i].kid_image;
                    
                     var edit_url="{{url('edit_story')}}/"+id;
                     var tr_str="<tr><td>"+num_increment+"</td><td style='text-transform:capitalize'>"+category_name+"</td><td style='text-transform:capitalize'>"+story_type+"</td><td style='text-transform:capitalize'>"+year+"</td><td style='text-transform:capitalize'>"+kid_name_en+"</td><td style='text-transform:capitalize'>"+kid_descp_en+"</td><td style='text-transform:capitalize'>"+kid_name_hi+"</td><td style='text-transform:capitalize'>"+kid_descp_hi+"</td><td><img src="+kid_image+" width='50px' height='50px'></td><td>"+newdate+"</td><td><label class='switch'><input type='checkbox' "+check+" id="+id+" value="+status+" onclick='getstatus("+id+")' class='statuscheckbox'><span class='slider round'></span></label></td><td><a href="+edit_url+"><i class='fa fa-edit text-primary mr-2'></i></a> / <a href='#'  data-toggle='modal' data-target='#DeleteCategoryModal' onclick='delete_menucategory("+id+")'><span class='glyphicon glyphicon-trash'></span></a> </td></tr>";
                      $("#story_tablelist tbody").append(tr_str);
                }

               $("#story_tablelist").DataTable();
              
            }
             else
            {
                var tr_str='<tr><td>No Records are found</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>';
                 $("#story_tablelist tbody").append(tr_str); 
                 $("#story_tablelist").DataTable();
            }
            
        });
    }

/*.............................Active and Inactive status Of Category...............................*/
function getstatus(id) {
   
    var id = id;
    var status = $("#" + id).val();
    //alert(status);
    
    if (status == 0) {
        var update_status = 1;
        var csrfToken = "{{ csrf_token() }}";

        var Url = "{{url('update_story_status')}}";
        $.ajax({
            url: Url,
            headers: {
                "X-CSRF-Token": csrfToken,
            },
            type: "POST",
            data: "id=" +id + "&status=" + update_status,
        }).done(function (response) {
            //console.log(response);
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

    var Url = "{{url('update_story_status')}}";
    $.ajax({
        url: Url,
        headers: {
            "X-CSRF-Token": csrfToken,
            
        },
        type: "POST",
        data: "id=" + id + "&status=" + status,
    }).done(function (response) {
        //console.log(response);
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


/*..............Delete Category.........................................*/
 function delete_menucategory(id)
    {
        $("#delete_story_id").val(id);
    }
$("#delete_btn").on("click", function () {
        toastr.options = {
            progressBar: "true",
            positionClass: "toast-top-right",
        };
       
        var id = $("#delete_story_id").val();
        var csrfToken = "{{ csrf_token() }}";

        var Url = "{{url('delete_story')}}";
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