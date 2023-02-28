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
				 <small>Add SparePart</small>
			</h1>
			<ol class="breadcrumb">
				  <li><a href="{{url('/home')}}">Home</a></li>
				  <li><a href="{{url('sparepart_list')}}">SparePart</a></li>
				  <li class="active">Add SparePart</li>
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
                                        <div class="title">Add SparePart</div>
                                    </div>
                                </div>
                                <div class="panel-body">
									{!! Form::open(['url' =>'save_sparepart', 'class' => 'form-horizontal', 'role' => 'form']) !!}
                                       
										<div class="form-group">
											<div class="col-sm-3">
											{!! Form::label('title','Brand', ['class' => 'control-label']) !!}				  
											{!! Form::select('brand_id',$brand_list,(isset($data) && !empty($data)) ? $data->brand_id : '',['class' => 'form-control', 'required', 'id'=>'brand_id','tabindex'=>$tabindex++,'placeholder'=>'-Select-']) !!}	
											<span class="help-block text-danger error"></span>
											</div>
											<div class="col-sm-3">
											{!! Form::label('title', 'Model', ['class' => 'control-label']) !!}				  
											{!! Form::select('model_id',$model,(isset($data) && !empty($data)) ? $data->model_id : '', ['class' => 'form-control','required', 'id'=>'model_id','tabindex'=>$tabindex++,'placeholder'=>'-Select-']) !!}	
											<span class="help-block text-danger error"></span>
											</div>
											<div class="col-sm-3">
											{!! Form::label('title', 'Product Type', ['class' => 'control-label']) !!}				  
											{!! Form::select('product_type_id',$type_list,(isset($data) && !empty($data)) ? $data->type_id : '',['class'=>'form-control','required', 'id'=>'product_type_id','tabindex'=>$tabindex++,'placeholder'=>'-Select-']) !!}	
											<span class="help-block text-danger error"></span>
											</div>
											<div class="col-sm-3">
											{!! Form::label('title', 'Part Name', ['class' => 'control-label']) !!}				  
											{!! Form::select('part_id',$part_name_list,(isset($data) && !empty($data)) ? $data->part_id : '',['class'=>'form-control','required', 'id'=>'part_id','tabindex'=>$tabindex++,'placeholder'=>'-Select-']) !!}	
											<span class="help-block text-danger error"></span>
											</div>
										
											<div class="col-sm-3">
												  {!! Form::label('title', 'Part Colour', ['class' => 'control-label']) !!}          
												  {!! Form::select('part_color_id[]',$color_list,(isset($multiple_list) && !empty($multiple_list[0])) ? $multiple_list[0]->colour_id : '',['class'=>'form-control','required', 'id'=>'part_color_id','tabindex'=>$tabindex++,'placeholder'=>'-Select-']) !!} 
												  <span class="help-block text-danger error"></span>
											</div>
										
											<div class="col-sm-3">
												  {!! Form::label('title', 'SKU Number', ['class' => 'control-label']) !!}          
												  {!! Form::text('sku_no[]',(isset($multiple_list) && !empty($multiple_list[0])) ? $multiple_list[0]->sku_no : '',['class'=>'form-control','required', 'id'=>'sku_no','tabindex'=>$tabindex++,'placeholder'=>'SKU Number','onBlur'=>'checkSkuNumber(this)','onKeypress'=>'submitDisable(this)','onPaste'=>'submitDisable(this)']) !!} 
												  <span class="help-block text-danger error"></span>
											</div>
											<div class="col-sm-3">
												<i class="fa fa-plus-circle fa-3x" aria-hidden="true" id="add_more" style="margin-top:10%"></i>
											</div>											
										{!!Form::hidden('id',(isset($data) && !empty($data)) ? $data->id : '')!!}
										</div>										   
										@if(count($multiple_list)>1)
											@foreach($multiple_list as $key => $val)
											@if($key)
											<div class="form-group">												  
												<div class="col-sm-3">
													{!! Form::label('title', 'Part Color', ['class' => 'control-label']) !!}          
													{!! Form::select('part_color_id[]',$color_list,(isset($val) && !empty($val)) ? $val->colour_id : '',['class'=>'form-control','required', 'id'=>'part_color_id','tabindex'=>$tabindex++,'placeholder'=>'-Select-']) !!} 
													<span class="help-block text-danger error"></span>
												</div>									
												<div class="col-sm-3">
													  {!! Form::label('title', 'SKU Number', ['class' => 'control-label']) !!}          
													  {!! Form::text('sku_no[]',(isset($val) && !empty($val)) ? $val->sku_no : '',['class'=>'form-control','required', 'id'=>'sku_no','tabindex'=>$tabindex++,'placeholder'=>'SKU Number','onBlur'=>'checkSkuNumber(this)','onKeypress'=>'submitDisable(this)','onPaste'=>'submitDisable(this)']) !!} 
													  <span class="help-block text-danger error"></span>
												</div>
											</div>
											@endif
											@endforeach
										@endif
										<div class="after-add-more"></div>
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
 $("#add_more").click(function(){ 
  var Url = "{{url('get_html_price_color')}}";
  var csrfToken = "{{ csrf_token() }}";
        $.ajax({
            url: Url,
            headers: {
                "X-CSRF-Token": csrfToken,
            },
            type: "GET",
           
        }).done(function (response)
        {
             $(".after-add-more").before(response.html);
        });
});
//onchange brand ................................
 $("#brand_id").change(function(){
      // var id = $("#brand_id").val();
       var id=$(this).val();
        var csrfToken = "{{ csrf_token() }}";
        var Url = "{{url('fetch-model')}}";
        $.ajax({
            url: Url,
            headers: {
                "X-CSRF-Token": csrfToken,
               
            },
            type: "POST",
            data: "id=" + id,
        }).done(function (response)
        {
            var obj = response.data;
             if (response.code == 200) {
            var len = 0;
            if (response["data"] != null) {
            len = response["data"].length;
            }
            var num=1;
             $("#model_id").html('<option value="">Select Model</option>'); 
             if(len > 0) 
            {  
           for(var i = 0; i < len; i++) 
            {
                var id = response["data"][i].id;
                var mname = response["data"][i].mname;
                tr_str="<option value="+id+">"+mname+"</option>";

                $("#model_id").append(tr_str);       
               }  
             }
           }
        });
});

submitDisable=function(e){
	$('#save_btn').prop("disabled", true);
}

checkSkuNumber=function(e){
	var sku_no = $(e).val();
	var part_id = $('#part_id').val();
	var colour_id = $(e).parent().parent().find('#part_color_id').val();
	var csrfToken = "{{ csrf_token() }}";
	var url = "{{url('check-sku-number')}}";
	$.ajax({
		type: "POST",
		url: url,
		headers: {
			"X-CSRF-Token": csrfToken,
		},
		data: {sku_no:sku_no,part_id:part_id,colour_id:colour_id},
		dataType:'JSON',
		success: function(d)
		{
			$('#save_btn').prop("disabled", false);
			if(d.status){
				$(e).val('');
				alert(d.message);
			}
		}
	});
}

  /*$(document).ready(function() {


      $("#add_more").click(function(){ 
          var html = $(".copy").html();
          $(".after-add-more").after(html);
      });


      $("body").on("click",".remove",function(){ 
          $(this).parents(".control-group").remove();
      });


    });*/
</script>
@endsection