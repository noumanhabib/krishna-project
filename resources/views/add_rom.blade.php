@extends('layouts.layout')
@section('title',$title)
@section('content')
<div id="page-wrapper" >
         <div class="header"> 
                        <h1 class="page-header">
                             <small>Add ROM</small>
                        </h1>
                        <ol class="breadcrumb">
                              <li><a href="{{url('/home')}}">Home</a></li>
                              <li><a href="{{url('rom_list')}}">ROM</a></li>
                              <li class="active">Add ROM</li>
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
                                        <div class="title">Add ROM</div>
                                    </div>
                                </div>
                                <div class="panel-body">
                                    <form class="form-horizontal" method="post">
                                        @csrf
                                        <div class="form-group">
                                            <label for="rom_name" class="col-sm-2 control-label">ROM <span class="required_label">*</span></label>
                                            <div class="col-sm-4">
                                                <input type="text" class="form-control" id="rom_name" placeholder="Enter ROM Name "  autocomplete="off">
                                                <span id="addromnamevalid" class="text-danger"></span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-sm-offset-2 col-sm-10">
                                                <button type="button" class="btn btn-default save_btn" id="save_rom_btn">Submit</button>
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


//Add ROM ...........................................

  $("#save_rom_btn").on("click", function () {
    var errorCount = 0;
    if ($("#rom_name").val().trim() == "") {
        $("#rom_name").focus();
        $("#addromnamevalid").text("This field can't be empty.");
        errorCount++;
    } 
    else 
    {
        $("#addromnamevalid").text("");
    }
    if (errorCount > 0)
   {
      return false;
   }
     var  rom_name=$("#rom_name").val();
     var csrfToken = "{{ csrf_token() }}";
     var Url="{{url('save_rom')}}";
     $.ajax({
        url: Url,
            headers: {
               'X-CSRF-Token': csrfToken,
            },
            type: "POST",
            dataType: "json",
            data:'rom_name='+rom_name,

     }).done(function (response) {

        console.log(response);
         if (response.code == 200) {
                 $('#save_rom_btn').prop('disabled',true);
                toastr.success(response.message);

                setTimeout(function () {
                    window.location.href="{{url('rom_list')}}";
                }, 1000);
            }
            if (response.code == 502) {
                toastr.error(response.message);
                
            }
     });

  });
</script>
@endsection