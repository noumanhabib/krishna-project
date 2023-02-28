@php $tabindex = 1; @endphp
                        {!! Form::open(['url' =>'save_elsproductajax', 'class' => 'form-horizontal', 'role' => 'form']) !!}
							<div class="form-group">
							   <div class="col-md-3">
								  {!! Form::label('title', 'Brand', ['class' => 'control-label']) !!} 
								  {!! Form::select('brand_id',$brand_list,(isset($data) && !empty($data)) ? $data->brand_id : '',['class' => 'form-control','required', 'id'=>'brand_id','tabindex'=>$tabindex++,'placeholder'=>'-Select-']) !!}  
							   </div>
							   <div class="col-sm-3">
								  {!! Form::label('title', 'Model', ['class' => 'control-label']) !!}         
								  {!! Form::select('model_id',$model,(isset($data) && !empty($data)) ? $data->model_id : '', ['class' => 'form-control','required', 'id'=>'model_id','tabindex'=>$tabindex++,'placeholder'=>'-Select-']) !!} 
								  <span class="help-block text-danger error"></span>
							   </div>
							   <div class="col-sm-3">
								  {!! Form::label('title', 'Colour', ['class' => 'control-label']) !!}          
								  {!! Form::select('color_id',$color_list,(isset($data) && !empty($data)) ? $data->colour_id : '',['class'=>'form-control','required', 'id'=>'color_id','tabindex'=>$tabindex++,'placeholder'=>'-Select-']) !!} 
								  <span class="help-block text-danger error"></span>
							  </div>
							 <div class="col-sm-3">
									{!! Form::label('title','GRN No',['class' => 'control-label']) !!}         
									{!! Form::text('grn_no',(isset($data) && !empty($data)) ? $data->grn_no : '',['class'=>'form-control','required', 'id'=>'grn_no','placeholder'=>'GRN No','tabindex'=>$tabindex++]) !!} 
									<span class="help-block text-danger error"></span>
							 </div>
							 <div class="col-sm-3">
									{!! Form::label('title','IMEI 1',['class' => 'control-label']) !!}         
									{!! Form::text('imei_1',(isset($data) && !empty($data)) ? $data->imei_1 : '',['class'=>'form-control', 'id'=>'imei_1','onchange'=>'imeifun();','placeholder'=>'IMEI 1','tabindex'=>$tabindex++]) !!} 
									<span class="help-block text-danger error"></span>
							 </div>
							 <div class="col-sm-3">
									{!! Form::label('title','IMEI 2',['class' => 'control-label']) !!}         
									{!! Form::text('imei_2',(isset($data) && !empty($data)) ? $data->imei_2 : '',['class'=>'form-control', 'id'=>'imei_2','placeholder'=>'IMEI 2','tabindex'=>$tabindex++]) !!} 
									<span class="help-block text-danger error"></span>
							 </div>
							
							 <div class="col-sm-3">
									{!! Form::label('title','RAM',['class' => 'control-label']) !!}         
									{!! Form::select('ram', $ram, (isset($data) && !empty($data)) ? $data->ram : '',['class'=>'form-control', 'id'=>'ram','placeholder'=>'-Select-','tabindex'=>$tabindex++]) !!} 
									<span class="help-block text-danger error"></span>
							 </div>
							 <div class="col-sm-3">
									{!! Form::label('title','ROM',['class' => 'control-label']) !!}         
									{!! Form::select('rom', $rom, (isset($data) && !empty($data)) ? $data->rom : '',['class'=>'form-control', 'id'=>'rom','placeholder'=>'-Select-','tabindex'=>$tabindex++]) !!} 
									<span class="help-block text-danger error"></span>
							 </div>
							 <div class="col-sm-3">
									{!! Form::label('title','Incoming Grade',['class' => 'control-label']) !!}         
									{!! Form::select('grade', $grade, (isset($data) && !empty($data)) ? $data->grade : '',['class'=>'form-control','id'=>'grade','placeholder'=>'-Select-','tabindex'=>$tabindex++]) !!} 
									<span class="help-block text-danger error"></span>
							 </div>
							 <div class="col-sm-3" @if(!in_array(\App\Models\RoleModel::find(\Auth::user()->role)->role_name,['admin','finance'])) style="display:none" @endif>
									{!! Form::label('title','Cost',['class' => 'control-label']) !!}         
									{!! Form::text('mrp',(isset($data) && !empty($data)) ? $data->mrp : '',['class'=>'form-control', 'id'=>'mrp','placeholder'=>'Cost','tabindex'=>$tabindex++,'onkeypress'=>'return isNumberKey(event)']) !!} 
									<span class="help-block text-danger error"></span>
							 </div>
							  <div class="col-sm-3">
									{!! Form::label('title','Remark',['class' => 'control-label']) !!}         
									{!! Form::text('remark',(isset($data) && !empty($data)) ? $data->remark : '',['class'=>'form-control','id'=>'remark','placeholder'=>'REMARK','tabindex'=>$tabindex++]) !!} 
									<span class="help-block text-danger error"></span>
							 </div>
							 @if((isset($data) && !empty($data)))
							  <div class="col-sm-3">
									{!! Form::label('title','Date',['class' => 'control-label']) !!}         
									{!! Form::date('resived_date',(isset($data) && !empty($data)) ? $data->resived_date : '',['class'=>'form-control','id'=>'resived_date','placeholder'=>'Date','tabindex'=>$tabindex++]) !!} 
									<span class="help-block text-danger error"></span>
							 </div>
							 @endif
							  <div class="col-sm-3">
									{!! Form::label('title','Vendor',['class' => 'control-label']) !!}         
									{!! Form::select('vendor_id',$vendor,(isset($data) && !empty($data)) ? $data->vendor_id : '',['class'=>'form-control','id'=>'vendor_id','placeholder'=>'-Select-','tabindex'=>$tabindex++]) !!} 
									<span class="help-block text-danger error"></span>
							 </div>
							 {!!Form::hidden('id',(isset($data) && !empty($data)) ? @$data->id : '')!!}
							 
							</div>
							<div class="form-group">
								<div class="col-sm-2">
									<button type="submit" class="btn btn-default save_btn" id="save_btn">Submit</button>
								</div>
							</div>
					    {!! Form::close() !!}
              