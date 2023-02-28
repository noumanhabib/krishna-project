@extends('layouts.layout')
@section('title',$title)
@section('content')
<div id="page-wrapper" >
         <div class="header"> 
			<h1 class="page-header">
				<small>{{$title}}</small>
			</h1>
			<ol class="breadcrumb">
				<li><a href="{{url('/home')}}">Home</a></li>
				<li><a href="{{url('vendor_list')}}">Vendor</a></li>
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
										   {!! Form::open(['url' =>'save_vendor', 'class' => 'form-horizontal', 'role' => 'form']) !!}
												@php $tabindex = 1; @endphp
												<div class="col-xs-6">
													<div class="form-group">
														{!! Form::label('title','Vendor Name', ['class' => 'control-label col-sm-4']) !!}   
														<div class="col-sm-8">      
															{!! Form::text('vendor_name',(isset($data) && !empty($data)) ? $data->vname : '',['class' => 'form-control', 'required', 'id'=>'vendor_name','tabindex'=>$tabindex++,'placeholder'=>'Vendor Name']) !!}  
															<span class="help-block text-danger error"></span>
														</div>
													</div>
													<div class="form-group">
														{!! Form::label('title','Vendor Address', ['class' => 'control-label col-sm-4']) !!}   
														<div class="col-sm-8">      
															{!! Form::text('vendor_address',(isset($data) && !empty($data)) ? $data->address : '',['class' => 'form-control', 'required', 'id'=>'vendor_address','tabindex'=>$tabindex++,'placeholder'=>'Vendor Address']) !!}  
															<span class="help-block text-danger error"></span>
														</div>
													</div>	
													<div class="form-group">
														{!! Form::label('title','City', ['class' => 'control-label col-sm-4']) !!}   
														<div class="col-sm-8">      
															{!! Form::text('city',(isset($data) && !empty($data)) ? $data->city : '',['class' => 'form-control', 'required', 'id'=>'city','tabindex'=>$tabindex++,'placeholder'=>'City']) !!}  
															<span class="help-block text-danger error"></span>
														</div>
													</div>	
													<div class="form-group">
														{!! Form::label('title','State', ['class' => 'control-label col-sm-4']) !!}   
														<div class="col-sm-8">      
															{!! Form::text('state',(isset($data) && !empty($data)) ? $data->state : '',['class' => 'form-control', 'required', 'id'=>'state','tabindex'=>$tabindex++,'placeholder'=>'State']) !!}  
															<span class="help-block text-danger error"></span>
														</div>
													</div>	
													<div class="form-group">
														{!! Form::label('title','Country', ['class' => 'control-label col-sm-4']) !!}   
														<div class="col-sm-8">      
															{!! Form::text('country',(isset($data) && !empty($data)) ? $data->country : '',['class' => 'form-control', 'required', 'id'=>'country','tabindex'=>$tabindex++,'placeholder'=>'Country']) !!}  
															<span class="help-block text-danger error"></span>
														</div>
													</div>	
													<div class="form-group">
														{!! Form::label('title','Pincode', ['class' => 'control-label col-sm-4']) !!}   
														<div class="col-sm-8">      
															{!! Form::text('pincode',(isset($data) && !empty($data)) ? $data->pincode : '',['class' => 'form-control', 'required', 'id'=>'pincode','tabindex'=>$tabindex++,'placeholder'=>'Pincode','onkeypress'=>'return isNumberKey(event)']) !!}  
															<span class="help-block text-danger error"></span>
														</div>
													</div>							   
												</div>	
												<div class="col-xs-6">
													<div class="form-group">
														{!! Form::label('title','AC No', ['class' => 'control-label col-sm-4']) !!}   
														<div class="col-sm-8">      
															{!! Form::text('account_number',(isset($data) && !empty($data)) ? $data->account_number : '',['class' => 'form-control', 'required', 'id'=>'account_number','tabindex'=>$tabindex++,'placeholder'=>'AC No','onkeypress'=>'return isNumberKey(event)']) !!}  
															<span class="help-block text-danger error"></span>
														</div>
													</div>
													<div class="form-group">
														{!! Form::label('title','IFS Code', ['class' => 'control-label col-sm-4']) !!}   
														<div class="col-sm-8">      
															{!! Form::text('ifs_code',(isset($data) && !empty($data)) ? $data->ifs_code : '',['class' => 'form-control', 'required', 'id'=>'ifs_code','tabindex'=>$tabindex++,'placeholder'=>'IFC Code']) !!}  
															<span class="help-block text-danger error"></span>
														</div>
													</div>	
													<div class="form-group">
														{!! Form::label('title','Bank Name', ['class' => 'control-label col-sm-4']) !!}   
														<div class="col-sm-8">      
															{!! Form::text('bank_name',(isset($data) && !empty($data)) ? $data->bank_name : '',['class' => 'form-control', 'required', 'id'=>'bank_name','tabindex'=>$tabindex++,'placeholder'=>'Bank Name']) !!}  
															<span class="help-block text-danger error"></span>
														</div>
													</div>	
													<div class="form-group">
														{!! Form::label('title','Mode of Payment', ['class' => 'control-label col-sm-4']) !!}   
														<div class="col-sm-8">      
															{!! Form::text('payment_mode',(isset($data) && !empty($data)) ? $data->payment_mode : '',['class' => 'form-control', 'required', 'id'=>'payment_mode','tabindex'=>$tabindex++,'placeholder'=>'Mode of Payment']) !!}  
															<span class="help-block text-danger error"></span>
														</div>
													</div>		
													<div class="form-group">
														{!! Form::label('title','GST Number', ['class' => 'control-label col-sm-4']) !!}   
														<div class="col-sm-8">      
															{!! Form::text('gst_no',(isset($data) && !empty($data)) ? $data->gst_no : '',['class' => 'form-control', 'required', 'id'=>'gst_no','tabindex'=>$tabindex++,'placeholder'=>'GST Number']) !!}  
															<span class="help-block text-danger error"></span>
														</div>
													</div>		
													<div class="form-group">
														{!! Form::label('title','PAN Number', ['class' => 'control-label col-sm-4']) !!}   
														<div class="col-sm-8">      
															{!! Form::text('pan_no',(isset($data) && !empty($data)) ? $data->pan_no : '',['class' => 'form-control', 'required', 'id'=>'pan_no','tabindex'=>$tabindex++,'placeholder'=>'PAN Number']) !!}  
															<span class="help-block text-danger error"></span>
														</div>
													</div>			
													<div class="form-group">
														{!! Form::label('title','Payment Terms', ['class' => 'control-label col-sm-4']) !!}   
														<div class="col-sm-8">      
															{!! Form::text('payment_terms',(isset($data) && !empty($data)) ? $data->payment_terms : '',['class' => 'form-control', 'required', 'id'=>'payment_terms','tabindex'=>$tabindex++,'placeholder'=>'Payment Terms']) !!}  
															<span class="help-block text-danger error"></span>
														</div>
													</div>							   
												</div>							   
												<div class="form-group">
													<div class="col-sm-offset-2 col-sm-10">
														<button type="submit" class="btn btn-default save_btn" id="save_vendor_btn">Submit</button>
													</div>
												</div>
												{!! Form::hidden('id',(isset($data) && !empty($data)) ? $data->id : '') !!}
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
</script>
@endsection