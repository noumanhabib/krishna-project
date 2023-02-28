@extends('layouts.layout')
@section('title',$title)
@section('content')
<div id="page-wrapper" >
         <div class="header"> 
                        <h1 class="page-header">
                             <small>Add RAM</small>
                        </h1>
                        <ol class="breadcrumb">
                              <li><a href="{{url('/home')}}">Home</a></li>
                              <li><a href="{{url('ram_list')}}">RAM</a></li>
                              <li class="active">Add RAM</li>
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
                                        <div class="title">Add RAM</div>
                                    </div>
                                </div>
                                <div class="panel-body">
                                    <form class="form-horizontal" method="post">
                                        @csrf
                                        <div class="form-group">
                                            <label for="ram_name" class="col-sm-2 control-label">RAM <span class="required_label">*</span></label>
                                            <div class="col-sm-4">
                                                <input type="text" class="form-control" id="ram_name" placeholder="Enter RAM Name " onkeypress="return onlyAlphaKey(event)" autocomplete="off">
                                                <span id="addramnamevalid" class="text-danger"></span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-sm-offset-2 col-sm-10">
                                                <button type="button" class="btn btn-default save_btn" id="save_ram_btn">Submit</button>
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


//Add RAM ...........................................

  $("#save_ram_btn").on("click", function () {
    var errorCount = 0;
    if ($("#ram_name").val().trim() == "") {
        $("#ram_name").focus();
        $("#addramnamevalid").text("This field can't be empty.");
        errorCount++;
    } 
    else 
    {
        $("#addramnamevalid").text("");
    }
    if (errorCount > 0)
   {
      return false;
   }
     var  ram_name=$("#ram_name").val();
     var csrfToken = "{{ csrf_token() }}";
     var Url="{{url('save_ram')}}";
     $.ajax({
        url: Url,
            headers: {
               'X-CSRF-Token': csrfToken,
            },
            type: "POST",
            dataType: "json",
            data:'ram_name='+ram_name,

     }).done(function (response) {

        console.log(response);
         if (response.code == 200) {
                 $('#save_ram_btn').prop('disabled',true);
                toastr.success(response.message);

                setTimeout(function () {
                    window.location.href="{{url('ram_list')}}";
                }, 1000);
            }
            if (response.code == 502) {
                toastr.error(response.message);
                
            }
     });

  });
</script>
@endsection