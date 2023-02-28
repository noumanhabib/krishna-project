@extends('layouts.layout')
@section('title',$title)
@section('content')
<div id="page-wrapper" >
         <div class="header"> 
                        <h1 class="page-header">
                             <small>Add Menu</small>
                        </h1>
                        <ol class="breadcrumb">
                              <li><a href="{{url('/home')}}">Home</a></li>
                              <li><a href="{{url('menu_master_list')}}">Menu</a></li>
                              <li class="active">Add Menu</li>
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
                                        <div class="title">Add Menu</div>
                                    </div>
                                </div>
                                <div class="panel-body">
                                    <form class="form-horizontal" method="post">
                                        @csrf
                                        <div class="form-group">
                                            <label for="Menuname" class="col-sm-2 control-label">MenuName <span class="required_label">*</span></label>
                                            <div class="col-sm-10">
                                                <input type="text" required="" class="form-control" id="Menuname" placeholder="Enter Your MenuName " onkeypress="return onlyAlphaKey(event)" autocomplete="off">
                                                <span id="addmenunamevalid" class="text-danger"></span>
                                            </div>
                                        </div>
                                             <div class="form-group">
                                            <label for="Menulink" class="col-sm-2 control-label">MenuLink <span class="required_label">*</span></label>
                                            <div class="col-sm-10">
                                                <input type="text" required="" class="form-control" id="Menulink" placeholder="Enter Your MenuLink " onkeypress="return onlyAlphaKey(event)" autocomplete="off">
                                                <span id="addmenulinkvalid" class="text-danger"></span>
                                            </div>
                                        </div>
                                             <div class="form-group">
                                            <label for="Menunumber" class="col-sm-2 control-label">Menu Number <span class="required_label">*</span></label>
                                            <div class="col-sm-10">
                                                <input type="number" required="" class="form-control" id="Menunumber" placeholder="Enter Your MenuNumber " onkeypress="return onlyAlphaKey(event)" autocomplete="off">
                                                <span id="addmenunumbervalid" class="text-danger"></span>
                                            </div>
                                        </div>

                                        

                                     


                                        <div class="form-group">
                                            <div class="col-sm-offset-2 col-sm-10">
                                                <button type="button" class="btn btn-default" id="save_category_btn">Submit</button>
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
   $("#Menuname").keypress(function (e) 
   {
            var keyCode = e.keyCode || e.which;
 
            $("#addmenunamevalid").html("");
 
            //Regex for Valid Characters i.e. Alphabets.
            var regex = /^[A-Za-z\-\s]+$/;
 
            //Validate TextBox value against the Regex.
            var isValid = regex.test(String.fromCharCode(keyCode));
            if (!isValid) {
                $("#addmenunamevalid").html("Only Alphabets are allowed.");
            }
 
            return isValid;
  });



//Add Category ...........................................

  $("#save_category_btn").on("click", function () {
    var errorCount = 0;
    if ($("#Menuname").val().trim() == "") {
        $("#Menuname").focus();
        $("#addmenunamevalid").text("This field can't be empty.");
        errorCount++;
    } 

     else if($("#Menunumber").val().trim() == "")
    {
       $("#Menunumber").focus();
        $("#addmenunumbervalid").text("This field can't be empty.");
        errorCount++;
    }
    else 
    {
        $("#addmenunamevalid").text("");
    }
     var Menuname=$("#Menuname").val();
     var Menulink=$("#Menulink").val();
     var Menununber=$("#Menunumber").val();
  
  

     var csrfToken = "{{ csrf_token() }}";
     var Url="{{url('save_menu')}}";
     $.ajax({
        url: Url,
            headers: {
               'X-CSRF-Token': csrfToken,
            },
            type: "POST",
            dataType: "json",
            data:'name='+Menuname+"&link="+Menulink+"&number="+Menununber,

     }).done(function (response) {

        console.log(response);
         if (response.code == 200) {
                 $('#addrestaurantbutton').prop('disabled',true);
                toastr.success(response.message);

                setTimeout(function () {
                    window.location.href="{{url('menu_master_list')}}";
                }, 1000);
            }
            if (response.code == 502) {
                toastr.error(response.message);
                
            }
     });

  });
</script>
@endsection