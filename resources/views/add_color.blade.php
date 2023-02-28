@extends('layouts.layout')
@section('title',$title)
@section('content')
<div id="page-wrapper" >
         <div class="header"> 
                        <h1 class="page-header">
                             <small>Add Colour</small>
                        </h1>
                        <ol class="breadcrumb">
                              <li><a href="{{url('/home')}}">Home</a></li>
                              <li><a href="{{url('color_list')}}">Colour</a></li>
                              <li class="active">Add Colour</li>
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
                                        <div class="title">Add Colour</div>
                                    </div>
                                </div>
                                <div class="panel-body">
                                    <form class="form-horizontal" method="post">
                                        @csrf
                                        <div class="form-group">
                                            <label for="color_name" class="col-sm-2 control-label">Colour <span class="required_label">*</span></label>
                                            <div class="col-sm-4">
                                                <input type="text" class="form-control" id="color_name" placeholder="Enter Colour Name " onkeypress="return onlyAlphaKey(event)" autocomplete="off">
                                                <span id="addcolornamevalid" class="text-danger"></span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-sm-offset-2 col-sm-10">
                                                <button type="button" class="btn btn-default save_btn" id="save_color_btn">Submit</button>
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
   $("#color_name").keypress(function (e) 
   {
            var keyCode = e.keyCode || e.which;
 
            $("#addcolornamevalid").html("");
 
            //Regex for Valid Characters i.e. Alphabets.
            var regex = /^[A-Za-z\-\s]+$/;
 
            //Validate TextBox value against the Regex.
            var isValid = regex.test(String.fromCharCode(keyCode));
            if (!isValid) {
                $("#addcolornamevalid").html("Only Alphabets are allowed.");
            }
 
            return isValid;
  });

//Add Colour ...........................................

  $("#save_color_btn").on("click", function () {
    var errorCount = 0;
    if ($("#color_name").val().trim() == "") {
        $("#color_name").focus();
        $("#addcolornamevalid").text("This field can't be empty.");
        errorCount++;
    } 
    else 
    {
        $("#addcolornamevalid").text("");
    }
    if (errorCount > 0)
   {
      return false;
   }
     var  color_name=$("#color_name").val();
     var csrfToken = "{{ csrf_token() }}";
     var Url="{{url('save_color')}}";
     $.ajax({
        url: Url,
            headers: {
               'X-CSRF-Token': csrfToken,
            },
            type: "POST",
            dataType: "json",
            data:'color_name='+color_name,

     }).done(function (response) {

        console.log(response);
         if (response.code == 200) {
                 $('#save_color_btn').prop('disabled',true);
                toastr.success(response.message);

                setTimeout(function () {
                    window.location.href="{{url('color_list')}}";
                }, 1000);
            }
            if (response.code == 502) {
                toastr.error(response.message);
                
            }
     });

  });
</script>
@endsection