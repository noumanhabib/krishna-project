@extends('layouts.layout')
@section('title',$title)
@section('content')
@php $tabindex = 1; @endphp
<div id="page-wrapper" >
         <div class="header"> 
                        <h1 class="page-header">
                             <small>Add Role</small>
                        </h1>
                        <ol class="breadcrumb">
                              <li><a href="{{url('/home')}}">Home</a></li>
                              <li><a href="{{url('assign_role_list')}}">Role</a></li>
                              <li class="active">Add Role</li>
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
                                        <div class="title">Add Role</div>
                                    </div>
                                </div>
                                <div class="panel-body">
                                    {!! Form::open(['url' =>'save_role', 'class' => 'form-horizontal', 'role' => 'form']) !!}
                                 
                                   <div class="form-group">
                                     <div class="col-sm-2">
                                      {!! Form::label('title', 'Role', ['class' => 'control-label']) !!}    
                                     </div>
                                      <div class="col-sm-4">     
                                            {!! Form::text('role',(isset($data) && !empty($data)) ? $data->name : '', ['class'=>'form-control','required', 'id'=>'role','placeholder'=>'--Role--','tabindex'=>$tabindex++]) !!} 
                                            <span class="help-block text-danger error">{!! Session::has('msg') ? Session::get("msg") : '' !!}</span>
                                       </div>
                                    </div>
                                   {!!Form::hidden('id',(isset($data) && !empty($data)) ? $data->id : '')!!}
                                  </div>
                                        <div class="form-group">
                                            <div class="col-sm-10">
                                                <button type="submit" class="btn btn-default save_btn" id="save_btn">Submit</button>
                                            </div>
                                        </div>
                                    {!! Form::close() !!}
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
   $("#Rolename").keypress(function (e) 
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

  $("#save_category_btn").on("click", function () {
    var errorCount = 0;
    if ($("#Rolename").val().trim() == "") {
        $("#Rolename").focus();
        $("#addcategorynamevalid").text("This field can't be empty.");
        errorCount++;
    } 
    else 
    {
        $("#addcategorynamevalid").text("");
    }
     var Rolename=$("#Rolename").val();
  

     var csrfToken = "{{ csrf_token() }}";
     var Url="{{url('save_role')}}";
     $.ajax({
        url: Url,
            headers: {
               'X-CSRF-Token': csrfToken,
            },
            type: "POST",
            dataType: "json",
            data:'Rolename='+Rolename,

     }).done(function (response) {

        console.log(response);
         if (response.code == 200) {
                 $('#addrestaurantbutton').prop('disabled',true);
                toastr.success(response.message);

                setTimeout(function () {
                    window.location.href="{{url('assign_role_list')}}";
                }, 1000);
            }
            if (response.code == 502) {
                toastr.error(response.message);
                
            }
     });

  });
</script>
@endsection