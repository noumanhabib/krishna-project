@extends('layouts.layout')
@section('title',$title)
@section('content')
<div id="page-wrapper" >
         <div class="header"> 
                        <h1 class="page-header">
                             <small>Add Brand</small>
                        </h1>
                        <ol class="breadcrumb">
                              <li><a href="{{url('/home')}}">Home</a></li>
                              <li><a href="{{url('brand_list')}}">Brand</a></li>
                              <li class="active">Add Brand</li>
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
                                        <div class="title">Add Brand</div>
                                    </div>
                                </div>
                                <div class="panel-body">
                                    <form class="form-horizontal" method="post">
                                        @csrf
                                        <div class="form-group">
                                            <label for="brand_name" class="col-sm-2 control-label">Brand <span class="required_label">*</span></label>
                                            <div class="col-sm-4">
                                                <input type="text" class="form-control" id="brand_name" placeholder="Enter Brand Name " onkeypress="return onlyAlphaKey(event)" autocomplete="off">
                                                <span id="addbrandnamevalid" class="text-danger"></span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-sm-offset-2 col-sm-10">
                                                <button type="button" class="btn btn-default save_btn" id="save_brand_btn">Submit</button>
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
//Only Alphabets Validation...............................
   $("#brand_name").keypress(function (e) 
   {
            var keyCode = e.keyCode || e.which;
 
            $("#addbrandnamevalid").html("");
 
            //Regex for Valid Characters i.e. Alphabets.
            var regex = /^[A-Za-z\-\s]+$/;
 
            //Validate TextBox value against the Regex.
            var isValid = regex.test(String.fromCharCode(keyCode));
            if (!isValid) {
                $("#addbrandnamevalid").html("Only Alphabets are allowed.");
            }
 
            return isValid;
  });

//Add Brand ...........................................

  $("#save_brand_btn").on("click", function () {
    var errorCount = 0;
    if ($("#brand_name").val().trim() == "") {
        $("#brand_name").focus();
        $("#addbrandnamevalid").text("This field can't be empty.");
        errorCount++;
    } 
    else 
    {
        $("#addbrandnamevalid").text("");
    }
    if (errorCount > 0)
   {
      return false;
   }
     var  brand_name=$("#brand_name").val();
     var csrfToken = "{{ csrf_token() }}";
     var Url="{{url('save_brand')}}";
     $.ajax({
        url: Url,
            headers: {
               'X-CSRF-Token': csrfToken,
            },
            type: "POST",
            dataType: "json",
            data:'brand_name='+brand_name,

     }).done(function (response) {

        console.log(response);
         if (response.code == 200) {
                 $('#save_brand_btn').prop('disabled',true);
                toastr.success(response.message);

                setTimeout(function () {
                    window.location.href="{{url('brand_list')}}";
                }, 1000);
            }
            if (response.code == 502) {
                toastr.error(response.message);
                
            }
     });

  });
</script>
@endsection