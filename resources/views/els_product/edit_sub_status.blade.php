@extends('layouts.layout')
@section('title',$title)
@section('content')
<div id="page-wrapper" >
         <div class="header"> 
			<h1 class="page-header">
				<small>Edit Sub Status</small>
			</h1>
			<ol class="breadcrumb">
				<li><a href="{{url('/home')}}">Home</a></li>
				<li><a href="{{url('sub-status')}}">Sub Status List</a></li>
				<li class="active">Edit Sub Status</li>
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
                                        <div class="title">Edit Sub Status</div>
                                    </div>
                                </div>
                                <div class="panel-body">
                                    <form class="form-horizontal" method="post">
                                        @csrf
                                        <div class="form-group">
                                            <label for="ram_name" class="col-sm-2 control-label">Status <span class="required_label">*</span></label>
                                            <div class="col-sm-4">
                                                {!! Form::select('status_id',$status,(isset($data) && !empty($data)) ? $data->sub_status_id : '',['class' => 'form-control','required', 'id'=>'status_id','placeholder'=>'-Select-']) !!}  
                                                <span id="addramnamevalid" class="text-danger"></span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="ram_name" class="col-sm-2 control-label"> Sub Status <span class="required_label">*</span></label>
                                            <div class="col-sm-4">
                                                <input type="text" class="form-control" name="name"  id="name" placeholder="Enter Status Name" value="{{$data->name}}" autocomplete="off">
                                                <span id="addramnamevalid" class="text-danger"></span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-sm-offset-2 col-sm-10">
                                                <button type="button" class="btn btn-default save_btn" id="save_status_btn">Submit</button>
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

  $("#save_status_btn").on("click", function () {
    if ($("#status_id").val().trim() && $("#name").val().trim()) {
     var  name=$("#name").val();
     var  status_id=$("#status_id").val();
     var csrfToken = "{{ csrf_token() }}";
     var Url="{{url('save_els_product_status')}}";
     $.ajax({
        url: Url,
            headers: {
               'X-CSRF-Token': csrfToken,
            },
            type: "POST",
            dataType: "json",
            data:'id={{$data->id}}&name='+name+'&status_id='+status_id,

     }).done(function (response) {
         if (response.code == 200) {
                 $('#save_ram_btn').prop('disabled',true);
                toastr.success(response.message);

                setTimeout(function () {
                    window.location.href="{{url('sub-status')}}";
                }, 1000);
            }
            if (response.code == 502) {
                toastr.error(response.message);
                
            }
     });
	}
  });
</script>
@endsection