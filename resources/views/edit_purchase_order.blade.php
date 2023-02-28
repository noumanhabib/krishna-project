@extends('layouts.layout')
@section('title',$title)
@section('content')
<div id="page-wrapper" >
         <div class="header"> 
                        <h1 class="page-header">
                             <small>Edit Purchase Order</small>
                        </h1>
                        <ol class="breadcrumb">
                              <li><a href="{{url('/home')}}">Home</a></li>
                              <li><a href="{{url('purchase_order')}}">Purchase Order List</a></li>
                              <li class="active">Edit Purchase Order</li>
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
                                        <div class="title">Edit Purchase Order</div>
                                    </div>
                                </div>
                                <div class="panel-body">
                                    <form class="form-horizontal" method="post">
                                        @csrf
                                        <div class="form-group" id="english">
                                            <label for="category_name" class="col-sm-2 control-label">Title <span class="required_label">*</span></label>
                                            <div class="col-sm-6">
                                                <input type="text" class="form-control" id="category_name_english" placeholder=""  autocomplete="off" value="">
                                                <span id="addcategorynamevalid" class="text-danger"></span>
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
//Only Alphabets Validation...............................
   $("#category_name").keypress(function (e) 
   {
            var keyCode = e.keyCode || e.which;
 
            $("#addcategorynamevalid").html("");
 
            //Regex for Valid Characters i.e. Alphabets.
            var regex = /^[A-Za-z\-\s]+$/;
 
            //Validate TextBox value against the Regex.
            var isValid = regex.test(String.fromCharCode(keyCode));
            if (!isValid) {
                $("#addcategorynamevalid").html("Only Alphabets are allowed.");
            }
 
            return isValid;
  });

//Add Category ...........................................

  $("#save_btn").on("click", function () {
    var errorCount = 0;
    if ($("#category_name_english").val().trim() == "") {
        $("#category_name_english").focus();
        $("#addcategorynamevalid").text("This field can't be empty.");
        errorCount++;
    } 
    else 
    {
        $("#addcategorynamevalid").text("");
    }
    if ($("#category_name_hindi").val().trim() == "") {
        $("#category_name_hindi").focus();
        $("#addcategorynamehindivalid").text("This field can't be empty.");
        errorCount++;
    } 
    else 
    {
        $("#addcategorynamehindivalid").text("");
    }
   if (errorCount > 0)
   {
      return false;
   }
     var  category_name_english=$("#category_name_english").val();
     var  category_name_hindi = $("#category_name_hindi").val();
     /*alert(category_name_hindi);
     alert(category_name);*/
     var csrfToken = "{{ csrf_token() }}";
     var Url="{{url('save_category')}}";
     $.ajax({
        url: Url,
            headers: {
               'X-CSRF-Token': csrfToken,
            },
            type: "POST",
            dataType: "json",
            data:'category_name_english='+category_name_english+'&category_name_hindi='+category_name_hindi,

     }).done(function (response) {

        //console.log(response);
         if (response.code == 200) {
                
                toastr.success(response.message);

                setTimeout(function () {
                    window.location.href="{{url('category_list')}}";
                }, 1000);
            }
            if (response.code == 502) {
                toastr.error(response.message);
                
            }
     });

  });
</script>
@endsection