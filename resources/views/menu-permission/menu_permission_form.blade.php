@extends('layouts.layout')
@section('title',$title)
@section('content')
@php $tabindex = 1;$i=1; @endphp
<div id="page-wrapper" >
         <div class="header"> 
                        <h1 class="page-header">
                             <small>Add Menu</small>
                        </h1>
                        <ol class="breadcrumb">
                              <li><a href="{{url('/home')}}">Home</a></li>
                              <li><a href="{{url('menu_permission_list')}}">Menu</a></li>
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
                                   {!! Form::open(['url' =>'save_menu_permission', 'class' => 'form-horizontal', 'role' => 'form']) !!}

                                    <div class="form-group">
                                     <div class="col-sm-2">
                                      {!! Form::label('title', 'Role', ['class' => 'control-label']) !!}    
                                     </div>
                                      <div class="col-sm-4">     
                                            {!! Form::select('role_id',$role_list,(isset($role_id) && !empty($role_id)) ? $role_id : '', ['class'=>'form-control','required', 'id'=>'role_id','placeholder'=>'-Select -','tabindex'=>$tabindex++]) !!}
                                       </div>
                                    </div>
                                    <div class="table-responsive">
                                      <table class="table table-striped table-bordered table-hover">
                                          <thead>
                                              <tr>
                                                  <th>Sr.No</th>
                                                  <th>Menu</th>                                 
                                                  <th>Sub Menu</th>                                 
                                                  <th>Action</th>
                                                  <th>Add</th>
                                                  <th>Edit</th>
                                                  <th>View</th>
                                                  <th>Delete</th>
                                              </tr>
                                          </thead>
                                          <tbody>
                                              <?php //print_r($menu_data);  ?>
											@foreach($menu_data as $row)											
											@php $subMenu = Helper::subMenuList($row->id,$role_id); @endphp
											<tr>
												<td @if(count($subMenu)) rowspan="{{count($subMenu)+1}}" @endif>{{$i++}}</td>
												<td @if(count($subMenu)) rowspan="{{count($subMenu)+1}}" @endif>{{$row->menu}}</td>
												<td></td>
										<td>{{ Form::checkbox('permission[]',$row->id,($row->id == $row->menu_id_permission) ? 'checked' : '',['id'=>$row->id,'onclick'=>'checkedClick(this)']) }}</td>
										<?php  if(isset($row->id)){   ?>
										
										
								<td>{{ Form::checkbox('added[]',$row->id,($row->added == $row->id) ? 'checked' : '',['id'=>$row->id,'onclick'=>'checkedClick(this)']) }}</td>
							
				                <td>{{ Form::checkbox('edit[]',$row->id,($row->edit == $row->id) ? 'checked' : '',['id'=>$row->id,'onclick'=>'checkedClick(this)']) }}</td>
						
					           	<td>{{ Form::checkbox('view[]',$row->id,($row->view == $row->id) ? 'checked' : '',['id'=>$row->id,'onclick'=>'checkedClick(this)']) }}</td>
							
								<td>{{ Form::checkbox('deleted[]',$row->id,($row->deleted == $row->id) ? 'checked' : '',['id'=>$row->id,'onclick'=>'checkedClick(this)']) }}</td>
							   <?php  }  else  {   ?>
							       
							   <?php  }   ?>
 											</tr>
											@if(!$subMenu->isEmpty())
												@foreach($subMenu as $d)
												<tr>
													<td>{{$d->menu}}</td>
								<td>{{ Form::checkbox('permission[]',$d->id,($d->id == $d->menu_id_permission) ? 'checked' : '',['id'=>$d->id,'parent_id'=>$row->id,'onclick'=>'checkedClick(this)']) }}</td>
								<?php  if(isset($d->id)){   ?>
								<td>{{ Form::checkbox('added[]',$d->id,($d->id == $d->added) ? 'checked' : '',['id'=>$d->id,'parent_id'=>$row->id,'onclick'=>'checkedClick(this)']) }}</td>
							
				                <td>{{ Form::checkbox('edit[]',$d->id,($d->id == $d->edit) ? 'checked' : '',['id'=>$d->id,'parent_id'=>$row->id,'onclick'=>'checkedClick(this)']) }}</td>
						
					           	<td>{{ Form::checkbox('view[]',$d->id,($d->id == $d->view) ? 'checked' : '',['id'=>$d->id,'parent_id'=>$row->id,'onclick'=>'checkedClick(this)']) }}</td>
							
								<td>{{ Form::checkbox('deleted[]',$d->id,($d->id == $d->deleted) ? 'checked' : '',['id'=>$d->id,'parent_id'=>$row->id,'onclick'=>'checkedClick(this)']) }}</td>
							   <?php  }   ?>
												</tr>
												@endforeach
											@endif
											{!!Form::hidden('permission_id[]',(isset($row->permission_id) && !empty($row->permission_id)) ? $row->permission_id : '')!!}
											@endforeach
                                          </tbody>
                                      </table>
                                  </div>
                                  </div>
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
        </div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script type="text/javascript">
checkedClick=function(e){
	var ischecked= $(e).is(':checked');
	if(ischecked){
		var parent_id = $(e).attr('parent_id');
		if(parent_id){
			$('#'+parent_id).prop('checked', true);
		}
	}
}
</script>
@endsection