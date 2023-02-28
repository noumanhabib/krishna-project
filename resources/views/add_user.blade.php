@extends('layouts.layout')
@section('title',$title)
@section('content')
@php $tabindex = 1; @endphp
<style type="text/css">
   .custom_width_color
   {
   width: 205px;
   margin-left: 53%;
   }
   .custom_width_price
   {
   width: 206px;
   }
   .custom-label
   {
   text-align: left!important;
   }
</style>
<div id="page-wrapper" >
<div class="header">
   <h1 class="page-header">
      <small>Add User</small>
   </h1>
   <ol class="breadcrumb">
      <li><a href="{{url('/home')}}">Home</a></li>
      <li><a href="{{url('user_list')}}">User</a></li>
      <li class="active">Add User</li>
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
                              <div class="title">Add User</div>
                           </div>
                        </div>
                        <div class="panel-body">
                           {!! Form::open(['url' =>'save_user', 'class' => 'form-horizontal', 'role' => 'form']) !!}
                           <div class="row">
                             <div class="col-md-12">
                                 <div class="col-md-3">
                                  {!! Form::label('title','UserName', ['class' => 'control-label']) !!} 
                                </div>
                                <div class="col-md-4">
                              {!! Form::text('username',(isset($data) && !empty($data)) ? $data->name : '',['class'=>'form-control','id'=>'username','placeholder'=>'Enter Usename','tabindex'=>$tabindex++]) !!} 
                                 </div>
                              </div>
                            </div>
                            <br>
                            <div class="row">
                              <div class="col-md-12">
                                 <div class="col-md-3">
                                  {!! Form::label('title','Email', ['class' => 'control-label']) !!} 
                                </div>
                                <div class="col-md-4">
                                  {!! Form::text('email',(isset($data) && !empty($data)) ? $data->email :'',['class'=>'form-control','id'=>'email','placeholder'=>'Enter Email','tabindex'=>$tabindex++]) !!}
                                   <br>
                                    <span id="exist_email" style="color:red"></span> 
                                 </div>

                              </div>

                           </div>
                            <br>
                            <div class="row">
                            @if(isset($data->id))
							<div class="col-md-12">
                                <div class="col-md-3">
                                </div>
                                 <div class="col-md-4">
                                  <input type="button" class="btn btn-success" value="Change Password" id="change_password" onClick="ShowNewPassword(); return false;">
                                 </div>
                               </div>
                               <br><br>
                               <div class="col-md-12">
                                <div class="col-md-3">
                                </div>
                                <div class="col-md-4">
                                    {!! Form::text('new_password','',['class'=>'form-control','id'=>'new_password','placeholder'=>'Enter Password','tabindex'=>$tabindex++]) !!}
                                </div>
                            </div>
                            @else
                            <div class="col-md-12">
                                <div class="col-md-3">
                                  {!! Form::label('title','Password', ['class' => 'control-label']) !!} 
                                </div>
                                <div class="col-md-4">
                                  {!! Form::text('password','',['class'=>'form-control','id'=>'password','placeholder'=>'Enter Password','tabindex'=>$tabindex++]) !!} 
                                 </div>
                            </div>
                            @endif
                           </div>
                           <br>
                            <div class="row">
                              <div class="col-md-12">
                                 <div class="col-md-3">
                                  {!! Form::label('title','Mobile', ['class' => 'control-label']) !!} 
                                </div>
                                <div class="col-md-4">
                                  {!! Form::text('mobile',(isset($data) && !empty($data)) ? $data->mobile_number :'',['class'=>'form-control','id'=>'mobile','placeholder'=>'Enter Mobile','tabindex'=>$tabindex++]) !!} 
                                 </div>
                              </div>
                            </div>
                            <br>
                            <div class="row">
                              <div class="col-md-12">
                                 <div class="col-md-3">
                                  {!! Form::label('title','Role', ['class' => 'control-label']) !!} 
                                </div>
                                <div class="col-md-4">                                 
                                     {!! Form::select('role_id',$rolelist,(isset($data) && !empty($data)) ? $data->role : '',['class' => 'form-control', 'required', 'id'=>'role_id','tabindex'=>$tabindex++,'placeholder'=>'--Select--']) !!} 
								</div>
                              </div>
                            </div><br>
                            <div class="row">
                              <div class="col-md-12">
                                 <div class="col-md-3">
                                  {!! Form::label('title','Target A Day', ['class' => 'control-label']) !!} 
                                </div>
                                <div class="col-md-4">                                 
                                     {!! Form::text('target',(isset($data) && !empty($data)) ? $data->target : '',['class' => 'form-control', 'id'=>'target','tabindex'=>$tabindex++,'placeholder'=>'Target A Day','onkeypress'=>'return isNumberKey(event)']) !!} 
								</div>
                              </div>
                           </div>
                           <div class="form-group">
                              <div class="col-sm-10">
                                 <button type="submit" class="btn btn-default save_btn" id="save_btn">Submit</button>
                              </div>
                           </div>
                             {!!Form::hidden('id',(isset($data) && !empty($data)) ? $data->id : '')!!}
                           {!! Form::close() !!}
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
$("#new_password").hide();

function ShowNewPassword()
{
 $("#new_password").show();
 $("#password").val('');
 $("#password").hide();
}

isNumberKey=function(evt){
	var charCode = (evt.which) ? evt.which : event.keyCode
	if (charCode > 31 && (charCode < 48 || charCode > 57))
		return false;
	return true;
}
	
   
CheckEmail=function(e){
   var email=$(e).val();
   var csrfToken = "{{ csrf_token() }}";
   var Url = "{{url('check_email')}}";
       $.ajax({
           url: Url,
           headers: {
               "X-CSRF-Token": csrfToken,
           },
           type: "POST",
           data: "email=" + email,
       }).done(function (response)
       {
           console.log(response.message);
           //var obj=response.message;
           $("#exist_email").text(response.message);
       });
   }
</script>
@endsection
