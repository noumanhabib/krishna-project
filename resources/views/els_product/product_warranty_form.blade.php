@extends('layouts.layout')
@section('title',$title)
@section('content')
@php $tabindex = 1; @endphp
<div id="page-wrapper" >
         <div class="header"> 
			<h1 class="page-header">
				 <small>{{$title}}</small>
			</h1>
			<ol class="breadcrumb">
				  <li><a href="{{url('/home')}}">Home</a></li>
				  <li><a href="{{url('manage-warranty')}}">ELS Product Warranty List</a></li>
				  <li class="active">{{$title}}</li>
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
												<div class="title">{{$title}}</div>
											</div>
										</div>
										<div class="panel-body">
											{!! Form::open(['url' =>'save_elsproduct_warranty', 'class' => 'form-horizontal', 'role' => 'form','id' => 'formid']) !!}												
												<div class="form-group">
													<div class="row">
														<div class="col-md-12">
															<div class="col-sm-6">
															   {!! Form::label('title','Barcode', ['class' => 'control-label']) !!} 
															   {!! Form::text('barcode',(isset($data) && !empty($data)) ? $data->barcode : '', ['class'=>'form-control','id'=>'barcode','placeholder'=>'Enter Barcode','required','tabindex'=>$tabindex++,'onblur'=>'getBarcodeDetailsList(this)']) !!} 
															</div>
															<div class="col-md-6" id="barcode_details_table"></div>
														</div>
													</div>
												</div>
													
												<div class="form-group">						   
													<div class="col-sm-3">
														{!! Form::label('title','Start Date',['class' => 'control-label']) !!}         
														{!! Form::date('start_date',(isset($data) && !empty($data)) ? $data->start_date : '',['class'=>'form-control','', 'id'=>'start_date','placeholder'=>'Start Date','tabindex'=>$tabindex++]) !!} 
														<span class="help-block text-danger error"></span>
													</div>
													<div class="col-sm-3">
														{!! Form::label('title','Warranty Duration',['class' => 'control-label']) !!}         
														{!! Form::text('duration',(isset($data) && !empty($data)) ? $data->duration : '',['class'=>'form-control','', 'id'=>'duration','placeholder'=>'Warranty Dutation','tabindex'=>$tabindex++,'onkeypress'=>'return isNumberKey(event)']) !!} 
														<span class="help-block text-danger error"></span>
													</div>
													<div class="col-sm-3">
														{!! Form::label('title','Duration Type',['class' => 'control-label']) !!}         
														{!! Form::select('type', ['year'=>'Year','month'=>'Month','day'=>'Days'], (isset($data) && !empty($data)) ? $data->type : '',['class'=>'form-control','', 'id'=>'grade','placeholder'=>'-Select-','tabindex'=>$tabindex++]) !!} 
														<span class="help-block text-danger error"></span>
													</div>
													
													<div class="col-sm-3">
														{!! Form::label('title','Remark',['class' => 'control-label']) !!}         
														{!! Form::textarea('remark',(isset($data) && !empty($data)) ? $data->remark : '',['class'=>'form-control','', 'id'=>'remark','rows'=>3,'placeholder'=>'REMARK','tabindex'=>$tabindex++]) !!} 
														<span class="help-block text-danger error"></span>
													</div>
												
													{!!Form::hidden('id',(isset($data) && !empty($data)) ? $data->id : '')!!}																	  
												</div>
												<div class="form-group">
													<div class="col-sm-2">
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
        </div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script type="text/javascript">
isNumberKey=function(evt){
	var charCode = (evt.which) ? evt.which : event.keyCode
	if (charCode > 31 && (charCode < 48 || charCode > 57))
		return false;
	return true;
}


$('#barcode').keypress(function (e) {
 var key = e.which;
 if(key == 13)  // the enter key code
  {
   	var barcode=$(e).val();
	var csrfToken = "{{ csrf_token() }}";
	if(barcode){
		$.ajax({
			type: "POST",
			url: "{{url('fetch_barcode_deatils')}}",
			headers: {
               'X-CSRF-Token': csrfToken,
            },
			data:{barcode:barcode},
			dataType:'JSON',
			success: function(d)
			{
				if(d.status){	
					$('#barcode_details_table').html(d.product_details);
				}
			}
		});
	}  
  }
});   


$('#formid').on('keyup keypress', function(e) {
  var keyCode = e.keyCode || e.which;
  if (keyCode === 13) { 
    e.preventDefault();
    return false;
  }
});
			
getBarcodeDetailsList=function(e){
	var barcode=$(e).val();
	var csrfToken = "{{ csrf_token() }}";
	if(barcode){
		$.ajax({
			type: "POST",
			url: "{{url('fetch_barcode_deatils')}}",
			headers: {
               'X-CSRF-Token': csrfToken,
            },
			data:{barcode:barcode},
			dataType:'JSON',
			success: function(d)
			{
				if(d.status){	
					$('#barcode_details_table').html(d.product_details);
				}
			}
		});
	}	
}

$('#barcode').trigger('blur');
</script>
@endsection