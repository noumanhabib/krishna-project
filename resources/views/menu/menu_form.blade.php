@extends('layouts.layout')
@section('title',$title)
@section('content')
@php $tabindex = 1; @endphp
<div id="page-wrapper" >
    <div class="header"> 
        <h1 class="page-header">
            <small>Add Menu</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{url('/home')}}">Home</a></li>
            <li><a href="{{url('menu_list')}}">Menu</a></li>
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
                                        {!! Form::open(['url' =>'save_menu', 'class' => 'form-horizontal', 'role' => 'form']) !!}
                                        <div class="form-group">
                                            <div class="col-sm-2">
                                                {!! Form::label('title', 'Menu Name', ['class' => 'control-label']) !!}    
                                            </div>
                                            <div class="col-sm-4">     
                                                {!! Form::text('menu',(isset($data) && !empty($data)) ? $data->menu : '', ['class'=>'form-control','required', 'id'=>'menu','placeholder'=>'Menu Name','tabindex'=>$tabindex++]) !!} 
                                                <span class="help-block text-danger error">{!! Session::has('msg') ? Session::get("msg") : '' !!}</span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-sm-2">
                                                {!! Form::label('title', 'Main Menu', ['class' => 'control-label']) !!}    
                                            </div>
                                            <div class="col-sm-4">     
                                                {!! Form::select('main_menu',$menu_list,(isset($data) && !empty($data)) ? $data->main_menu : '', ['class'=>'form-control', 'id'=>'main_menu','placeholder'=>'--select Menu Name--','tabindex'=>$tabindex++]) !!} 

                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-sm-2">
                                                {!! Form::label('title', 'Slug', ['class' => 'control-label']) !!}    
                                            </div>
                                            <div class="col-sm-4">     
                                                {!! Form::text('slug',(isset($data) && !empty($data)) ? $data->slug : '', ['class'=>'form-control','required', 'id'=>'slug','placeholder'=>'','tabindex'=>$tabindex++]) !!} 
                                                <span class="help-block text-danger error"></span>
                                            </div>
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
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script type="text/javascript">
//Only Alphabets Validation...............................
$("#menu").keyup(function () {
    var Text = $(this).val();
    Text = Text.toLowerCase();
    Text = Text.replace(/[^a-zA-Z0-9]+/g, '-');
    $("#slug").val(Text);

});
</script>
@endsection