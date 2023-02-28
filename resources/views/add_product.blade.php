@extends('layouts.layout')
@section('title','Add Product List')
@section('content')
<div id="page-wrapper" >
         <div class="header"> 
                        <h1 class="page-header">
                             <small>Add Product</small>
                        </h1>
                        <ol class="breadcrumb">
                              <li><a href="{{url('/home')}}">Home</a></li>
                              <li><a href="{{url('product_list')}}">Product</a></li>
                              <li class="active">Add Product</li>
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
                                        <div class="title">Add Spare Product</div>
                                    </div>
                                </div>
                                <div class="panel-body">
                                    <form class="form-horizontal" method="post" action="{{url('save_product')}}">
                                        @csrf
                                          <div class="form-group">
                                            <label for="username" class="col-sm-2 control-label">Product Brand <span class="required_label">*</span></label>
                                            <div class="col-sm-6">
                                              <select required="" class="form-control" id="pbrand" name="pbrand" onchange="brandvalue(this.value);">
                                                 <option value="">Select Brand</option>
                                                <?php  foreach ($elsBrd as $key => $value) {
                                               ?>
                                                <option value="<?=$value->id?>"><?=$value->bname?></option>
                                                <?php  }   ?>
                                              </select>
                                                <span id="addcategorynamevalid" class="text-danger"></span>
                                            </div>
                                        </div>
                                         <div class="form-group">
                                            <label for="username" class="col-sm-2 control-label">Product Model<span class="required_label">*</span></label>
                                            <div class="col-sm-6">
                                                 <select required="" class="form-control" id="pmodel" name="pmodel" onchange="generate_sku_value(this.value);">
                                                 <option value="">Select Model</option>
                                                </select>
                                                <span id="addcategorynamevalid" class="text-danger"></span>
                                            </div>
                                        </div>
                                          <div class="form-group">
                                            <label for="username" class="col-sm-2 control-label">Product Type <span class="required_label">*</span></label>
                                            <div class="col-sm-6">
                                              <select required="" class="form-control" id="ptype" name="ptype">
                                                 <option value="">Select Type</option>
                                                <option value="0">Consumable Part</option>
                                                <option value="1">Assign Part</option>
                                                <option value="2">Spare Part</option>
                                              </select>
                                                <span id="addcategorynamevalid" class="text-danger"></span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="username" class="col-sm-2 control-label">Part Name <span class="required_label">*</span></label>
                                            <div class="col-sm-6">
                                                <input type="text" required="" class="form-control" id="pname"  name="pname" placeholder="Enter Your PartName " onkeypress="return onlyAlphaKey(event)" autocomplete="off">
                                                <span id="addnamevalid" class="text-danger"></span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="username" class="col-sm-2 control-label">Series Number <span class="required_label">*</span></label>
                                            <div class="col-sm-6">
                                                <input type="text" required="" class="form-control" id="series_num" placeholder="Enter Series Number"  autocomplete="off" name="series[]">
                                                <span id="addseries_numvalid" class="text-danger"></span>
                                            </div>
                                        </div>
                                         <div class="form-group">
                                          <label for="username" class="col-sm-2 control-label"> Add More Series<span class="required_label">*</span></label>
                                           <div class="col-sm-6">
                                                <table class="table table-bordered">
                                                  <thead><th colspan="2" class="text-right" style="color:#5cb85c;"><i class="fa fa-plus-circle fa-3x" aria-hidden="true" id="add_more"></i></th></thead>
                                                     <tbody class="input_fields_wrap">
                                                            <tr class="element" id="row_1">
                                                              <td>
                                                               <input type="text" name="series[]" class="form-control">
                                                              </td>
                                                              <td></td>
                                                           </tr>
                                                      </tbody>
                                                  </table>
                                              </div>
                                            </div>
                                         <div class="form-group">
                                            <label for="username" class="col-sm-2 control-label">Part Color <span class="required_label">*</span></label>
                                            <div class="col-sm-6">
                                              <!--   <input type="text" required="" class="form-control" id="pcolor" placeholder="Enter Your PartColor" onkeypress="return onlyAlphaKey(event)" autocomplete="off"> -->
                                              <select class="form-control" id="pcolor" name="pcolor" autocomplete="off">
                                                <option value=""> Select Color</option>
                                                  @if(!empty($elsClr))
                                                    @foreach($elsClr as $row)
                                                    <option value="{{$row->id}}">{{$row->name}}</option>
                                                    @endforeach
                                                  @endif
                                              </select>
                                                <span id="addcolorvalid" class="text-danger"></span>
                                            </div>
                                        </div>
                                         <!--  <div class="form-group">
                                            <label for="email" class="col-sm-2 control-label">Product Received Quantity <span class="required_label">*</span></label>
                                            <div class="col-sm-6">
                                                <input type="number" required="" class="form-control" id="pquantity" placeholder="Enter Your ReceivedQuantity " onkeypress="return onlyAlphaKey(event)" autocomplete="off">
                                                <span id="addquantityvalid" class="text-danger"></span>
                                            </div>
                                        </div> -->
                                          <div class="form-group">
                                            <label for="password" class="col-sm-2 control-label">Product Price <span class="required_label">*</span></label>
                                            <div class="col-sm-6">
                                                <input type="number" required="" class="form-control" id="pprice" name="pprice" placeholder="Enter Your ProductPrice " onkeypress="return onlyAlphaKey(event)" autocomplete="off">
                                                <span id="addpricevalid" class="text-danger"></span>
                                            </div>
                                        </div>
                                          <div class="form-group" style="display: none">
                                            <label for="mobile" class="col-sm-2 control-label">Product Sku <span class="required_label">*</span></label>
                                            <div class="col-sm-6">
                                                <input type="text" required="" class="form-control" id="psku" name="psku" placeholder="Enter Your ProductSku "  autocomplete="off">
                                                <span id="addskuvalid" class="text-danger"></span>
                                            </div>
                                        </div>
                                             <div class="form-group">
                                            <label for="mobile" class="col-sm-2 control-label">Data Entry Person <span class="required_label">*</span></label>
                                            <div class="col-sm-6">
                                                <select required="" class="form-control" id="pentry" name="pentry">
                                                 <option value="">Select Person</option>
                                                <?php  foreach ($elsUsr as $key => $value) {
                                               ?>
                                                <option value="<?=$value->id?>"><?=$value->name?></option>
                                                <?php  }   ?>
                                              </select>
                                                <span id="addpersonvalid" class="text-danger"></span>
                                            </div>
                                        </div>
                                             <div class="form-group">
                                            <label for="pvendor" class="col-sm-2 control-label">Product Vendor <span class="required_label">*</span></label>
                                            <div class="col-sm-6">
                                              <select required="" class="form-control" id="pvendor" name="pvendor">
                                                 <option value="">Select Vendor</option>
                                                <?php  foreach ($elsVen as $key => $value) {
                                               ?>
                                                <option value="<?=$value->id?>"><?=$value->vname?></option>
                                                <?php  }   ?>
                                              </select>
                                                <span id="addvendorvalid" class="text-danger"></span>
                                            </div>
                                        </div>
                                            <div class="form-group">
                                            <div class="col-sm-offset-2 col-sm-6">
                                                <button type="submit" class="btn btn-default" id="save_category_btn">Submit</button>
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
//Only Alphabets Validation...............................
   $("#pcolor").keypress(function (e) 
   {
            var keyCode = e.keyCode || e.which;
            $("#addcolorvalid").html("");
            //Regex for Valid Characters i.e. Alphabets.
            var regex = /^[A-Za-z\-\s]+$/;
            //Validate TextBox value against the Regex.
            var isValid = regex.test(String.fromCharCode(keyCode));
            if (!isValid) {
                $("#addcolorvalid").html("Only Alphabets are allowed.");
            }
            return isValid;
  });
//Add Category ...........................................

 
  function brandvalue(id)
  {
    //alert(id);
  var id = id;
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
             $("#pmodel").html('<option value="">Select Model</option>'); 
             if(len > 0) 
            {  
           for(var i = 0; i < len; i++) 
            {
                var id = response["data"][i].id;
                var mname = response["data"][i].mname;
                tr_str="<option value="+id+">"+mname+"</option>";

                $("#pmodel").append(tr_str);       
               }  
             }
           }
        });
  }
  function generate_sku_value(val)
  {
      //alert(val);
      var model=$( "#pmodel option:selected" ).text();
       var brand=$( "#pbrand option:selected" ).text();
       var sku=brand.substring(0, 3)+"-"+model;
       //alert(sku);
      $("#psku").val(sku);
      
  }

  /*--------------------- Add More series value---------------------*/
   var max_fields    = 11; 
                        var wrapper       = $(".input_fields_wrap"); //Fields wrapper
                        var add_button    = $("#add_more"); //Add button ID
                        
                        
                        var x = 1; //initlal text box count
                        $(add_button).click(function(e){ //on add input button click
                          e.preventDefault();
                          if(x < max_fields){ //max input box allowed
                            x++; //text box increment
                            
                            $(wrapper).append('<tr class="element" id="row_'+x+'"><td><input type="text" name="series[]" class="form-control"><td><button class="btn btn-danger remove_field" data-id="'+x+'" value="'+x+'">X</button></td></tr>'); 
                          }
                        });
                        
                        $(wrapper).on("click",".remove_field", function(e){ //user click on remove text
                          e.preventDefault(); 
                          var id = $(this).val();
                          $("#row_"+id).remove();
                          x--;
                        })

</script>
@endsection