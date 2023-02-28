@extends('layouts.layout')
@section('title',$title)
@section('content')
<div id="page-wrapper" >
         <div class="header"> 
                        <h1 class="page-header">
                             <small>Add Model</small>
                        </h1>
                        <ol class="breadcrumb">
                              <li><a href="{{url('/home')}}">Home</a></li>
                              <li><a href="{{url('media_list')}}">Model</a></li>
                              <li class="active">Add Model</li>
                        </ol>     
         </div>
         <div id="page-inner">
            <div class="row">
                <div class="col-md-12">
                    <!-- Advanced Tables -->
                    <div class="panel panel-default">
                        <div class="panel-body">
                           <div class="row">
                        <div class="col-xs-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <div class="card-title">
                                        <div class="title">Add Model</div>
                                    </div>
                                </div>
                                <div class="panel-body">
                                    <form class="form-horizontal" method="post" enctype="multipart/formdata">
                                        @csrf
                                        <div class="form-group">
                                            <label for="category_name" class="col-sm-2 control-label">Brand <span class="required_label">*</span></label>
                                            <div class="col-sm-6">
                                                <select class="form-control" name="brand_name" id="brand_id">
                                                  
                                                </select>
                                                <span id="addbrandnamevalid" class="text-danger"></span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="category_name" class="col-sm-2 control-label">Model Name <span class="required_label">*</span></label>
                                            <div class="col-sm-6">
                                                <input type="text" name="model_name" value="" class="form-control" id="model_name">
                                                <span id="addmodelnamevalid" class="text-danger"></span>
                                            </div>
                                        </div>
                                      
                                        <div class="form-group">
                                            <div class="col-sm-offset-2 col-sm-10">
                                                <button type="button" class="btn btn-default save_btn" id="save_btn">Submit</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script type="text/javascript">


/*Get Category in select dropdown-------------------------*/

var Url = "{{url('get_active_brand_list')}}";
var csrfToken = "{{ csrf_token() }}";
        
$.ajax({
        url: Url,
       headers: 
       {
           'X-CSRF-Token': csrfToken,
       },
        type: "POST",
        
})
.done(function( response )
{
      //console.log(response);
      var  dropdown = $("#brand_id");

                dropdown.empty();
                dropdown.append('<option selected="true" value="">Choose Brand</option>');
                dropdown.prop("selectedIndex", 0);

                // Populate dropdown with list of provinces
                $.each(response.data, function (key, entry) {
                    dropdown.append($("<option></option>").attr("value", entry.id).text(entry.bname));
                });
});



//Save Story ...........................................

  $("#save_btn").on("click", function () {
    //alert("tsets");
    var errorCount = 0;
    if ($("#brand_id").val()== "") {
        $("#brand_id").focus();
        $("#addbrandnamevalid").text("This field can't be empty.");
        errorCount++;
    } 
    else 
    {
        $("#addbrandnamevalid").text("");
    }
    if ($("#model_name").val() == "") {
        $("#model_name").focus();
        $("#addmodelnamevalid").text("This field can't be empty.");
        errorCount++;
    } 
    else 
    {
        $("#addmodelnamevalid").text("");
    }
    
    
    if (errorCount > 0)
   {
      return false;
   }

   
    var formData = {
            brand_id: $("#brand_id").val(),
            model_name: $("#model_name").val(),
            
        };


     var csrfToken = "{{ csrf_token() }}";
     var Url="{{url('save_model')}}";
     $.ajax({
        url: Url,
            headers: {
               'X-CSRF-Token': csrfToken,
            },
            type: "POST",
            dataType: "json",
            data:formData,

     }).done(function (response) {

       // console.log(response);
         if (response.code == 200) {
                 
                toastr.success(response.message);

                setTimeout(function () {
                    window.location.href="{{url('model_list')}}";
                }, 1000);
            }
            /*if (response.code == 502) {
                toastr.error(response.message);
                
            }*/
     });

  });
  
</script>
@endsection