@extends('layouts.layout')
@section('title','Add Els Product')
@section('content')
<style type="text/css">
    * { box-sizing: border-box; }
    body {
      font: 16px;
  }
  .autocomplete {
      /*the container must be positioned relative:*/
      position: relative;
      display: inline-block;
  }
  input {
      border: 1px solid transparent;
      background-color: #f1f1f1;
      padding: 10px;
      font-size: 16px;
  }
  input[type=text] {
      background-color: #f1f1f1;
      width: 100%;
  }
  input[type=submit] {
      background-color: DodgerBlue;
      color: #fff;
  }
  .autocomplete-items {
      position: absolute;
      border: 1px solid #d4d4d4;
      border-bottom: none;
      border-top: none;
      z-index: 99;
      /*position the autocomplete items to be the same width as the container:*/
      top: 100%;
      left: 0;
      right: 0;
  }
  .autocomplete-items div {
      padding: 10px;
      cursor: pointer;
      background-color: #fff;
      border-bottom: 1px solid #d4d4d4;
  }
  .autocomplete-items div:hover {
      /*when hovering an item:*/
      background-color: #e9e9e9;
  }
  .autocomplete-active {
      /*when navigating through the items using the arrow keys:*/
      background-color: DodgerBlue !important;
      color: #ffffff;
  }
</style>
<div id="page-wrapper" >
 <div class="header"> 
    <h1 class="page-header">
     <small>Add Els Product</small>
 </h1>
 <ol class="breadcrumb">
  <li><a href="{{url('/home')}}">Home</a></li>
  <li><a href="{{url('system-list')}}">ELS Product</a></li>
  <li class="active">Add ELS Product</li>
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
                                    <div class="title">Add ELS Product <?php // echo '<pre>';print_r($elsTitle);  die(); ?> </div>
                                </div>
                            </div>
                            <div class="panel-body">
                                <form class="form-horizontal" autocomplete="off" action="save_elsproduct" method="post">
                                    @csrf
                                    <?php 
                                    for($r=0;$r<count($elsCat);$r++){  ?>
                                       <p><?php print_r($elsCat[$r]->name);?></p>
                               <?php    
                               $cat_id=$elsCat[$r]->id;                        
                              if(array_key_exists($cat_id,$elsTitle))
                              {
                                     $cat=$elsTitle[$cat_id];
                                     for($v=0;$v<count($cat);$v++){           
                                ?>
                            <div>
                                       <div class="form-group">
                                            <div>
                                        <div class="col-sm-5"> 
                                           <div class="autocomplete" style="width:300px;">
                                             <label for="username" class="control-label"><?=$cat[$v]?></label>
                                            <input class="form-control" id="title<?=$elsCat[$r]->id?>0" value="<?=$cat[$v]?>" readonly type="hidden" name="title[<?=$elsCat[$r]->id?>][]" placeholder="Enter Your Title">
                                        </div>
                                    </div>
                                    <div class="col-sm-5">
                                     <div class="autocomplete" style="width:300px;">
                                      <?php if($cat[$v]=='ELS Brand'){   ?>
                                          <select required="" class="form-control" id="input" type="text" name="input[<?=$elsCat[$r]->id?>][]" onchange="brandvalue(this.value);">
                                                 <option value="">Select Brand</option>
                                                <?php  foreach ($elsBrd as $key => $value) {
                                               ?>
                                                <option value="<?=$value->id?>"><?=$value->bname?></option>
                                                <?php  }   ?>
                                              </select>
                                      <?php  } elseif($cat[$v]=='ELS Model'){  ?> 
                                          <select required="" class="form-control"  name="input[<?=$elsCat[$r]->id?>][]" id="pmodel" onchange="generate_sku_value(this.value);">
                                                 <option value="">Select Model</option>
                                                </select>
                                               
                                          <input type="hidden" name="psku" id="psku" value="">
                                     <?php
                                      }  elseif($cat[$v]=='Vendor'){  ?>  
                                        <select required="" class="form-control" name="input[<?=$elsCat[$r]->id?>][]"  id="pvendor">
                                                 <option value="">Select Vendor</option>
                                                <?php  foreach ($elsVen as $key => $value) {
                                               ?>
                                                <option value="<?=$value->id?>"><?=$value->vname?></option>
                                                <?php  }   ?>
                                              </select>
                                     <?php  }  elseif($cat[$v]=='Color'){  ?>  
                                        <select required="" class="form-control" name="input[<?=$elsCat[$r]->id?>][]"  id="pcolor">
                                                 <option value="">Select Color</option>
                                                <?php  foreach ($elsCol as $key => $value) {
                                               ?>
                                                <option value="<?=$value->id?>"><?=$value->name?></option>
                                                <?php  }   ?>
                                              </select>
                                    <?php  }  elseif($cat[$v]=='Ram'){  ?>  
                                        <select required="" class="form-control" name="input[<?=$elsCat[$r]->id?>][]"  id="pram">
                                                 <option value="">Select Ram</option>
                                                <?php  foreach ($elsRam as $key => $value) {
                                               ?>
                                                <option value="<?=$value->id?>"><?=$value->name?></option>
                                                <?php  }   ?>
                                              </select>
                                  <?php  }  elseif($cat[$v]=='Rom'){  ?>  
                                        <select required="" class="form-control" name="input[<?=$elsCat[$r]->id?>][]"  id="prom">
                                                 <option value="">Select Rom</option>
                                                <?php  foreach ($elsRom as $key => $value) {
                                               ?>
                                                <option value="<?=$value->id?>"><?=$value->name?></option>
                                                <?php  }   ?>
                                              </select>
                                   <?php  }  elseif($cat[$v]=='Grade'){  ?>  
                                        <select required="" class="form-control" name="input[<?=$elsCat[$r]->id?>][]"  id="pgrade">
                                                 <option value="">Select Grade</option>
                                                <?php  foreach ($elsGra as $key => $value) {
                                               ?>
                                                <option value="<?=$value->id?>"><?=$value->name?></option>
                                                <?php  }   ?>
                                              </select>

                                   <?php  } else {  ?>
                                    <input class="form-control" id="input" type="text" name="input[<?=$elsCat[$r]->id?>][]"  placeholder="Enter Your Input">
                                        <?php  }   ?>           
                                    </div>
                                    <span id="addpnamevalid" class="text-danger"></span>
                                </div>
                                   </div>
                            </div>
                          </div>             
                                <?php
                                 }    } ?>
                                      <div  class="field_wrapper<?=$elsCat[$r]->id?>">
                                       <div class="form-group">
                                            <div>
                                        <div class="col-sm-5"> 
                                           <div class="autocomplete" style="width:300px;">
                                            <input class="form-control" id="title<?=$elsCat[$r]->id?>0" type="text" name="title[<?=$elsCat[$r]->id?>][]" placeholder="Enter Your Title">
                                        </div>
                                    </div>
                                    <div class="col-sm-5">
                                     <div class="autocomplete" style="width:300px;">
                                        <input class="form-control" id="input" type="text" name="input[<?=$elsCat[$r]->id?>][]" placeholder="Enter Your Input">
                                    </div>
                                    <span id="addpnamevalid" class="text-danger"></span>
                                </div>
                                 <div class="col-sm-2">
                                    <input type="hidden" id="count_item<?=$elsCat[$r]->id?>" value="0">
                                      <button type="button"  onclick="add_more(<?=$elsCat[$r]->id?>);" class="add_button<?=$elsCat[$r]->id?> btn-success" title="Add field"> +Add More</button>
                                  </div>
                                   </div>
                            </div>
                          </div>             
                             <?php  }   ?>
                   <div class="form-group">
                    <div class="col-sm-offset-3 col-sm-10">
                        <button type="submit" name="save" class="btn btn-default" id="save_category_btn">Submit</button>
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
    var countries = []; 
    function autocomplete(inp, arr) {
      var currentFocus;
      inp.addEventListener("input", function(e) {
          var a, b, i, val = this.value;
          closeAllLists();
          if (!val) { return false;}
          currentFocus = -1;
          a = document.createElement("DIV");
          a.setAttribute("id", this.id + "autocomplete-list");
          a.setAttribute("class", "autocomplete-items");
          /*append the DIV element as a child of the autocomplete container:*/
          this.parentNode.appendChild(a);
          /*for each item in the array...*/
          for (i = 0; i < arr.length; i++) {
            /*check if the item starts with the same letters as the text field value:*/
            if (arr[i].substr(0, val.length).toUpperCase() == val.toUpperCase()) {
              /*create a DIV element for each matching element:*/
              b = document.createElement("DIV");
              /*make the matching letters bold:*/
              b.innerHTML = "<strong>" + arr[i].substr(0, val.length) + "</strong>";
              b.innerHTML += arr[i].substr(val.length);
              /*insert a input field that will hold the current array item's value:*/
              b.innerHTML += "<input type='hidden' value='" + arr[i] + "'>";
              /*execute a function when someone clicks on the item value (DIV element):*/
              b.addEventListener("click", function(e) {
                  /*insert the value for the autocomplete text field:*/
                  inp.value = this.getElementsByTagName("input")[0].value;
              /*close the list of autocompleted values,
              (or any other open lists of autocompleted values:*/
              closeAllLists();
          });
              a.appendChild(b);
          }
      }
  });
      /*execute a function presses a key on the keyboard:*/
      inp.addEventListener("keydown", function(e) {
          var x = document.getElementById(this.id + "autocomplete-list");
          if (x) x = x.getElementsByTagName("div");
          if (e.keyCode == 40) {
        /*If the arrow DOWN key is pressed,
        increase the currentFocus variable:*/
        currentFocus++;
        /*and and make the current item more visible:*/
        addActive(x);
      } else if (e.keyCode == 38) { //up
        /*If the arrow UP key is pressed,
        decrease the currentFocus variable:*/
        currentFocus--;
        /*and and make the current item more visible:*/
        addActive(x);
    } else if (e.keyCode == 13) {
        /*If the ENTER key is pressed, prevent the form from being submitted,*/
        e.preventDefault();
        if (currentFocus > -1) {
          /*and simulate a click on the "active" item:*/
          if (x) x[currentFocus].click();
      }
  }
});
      function addActive(x) {
        /*a function to classify an item as "active":*/
        if (!x) return false;
        /*start by removing the "active" class on all items:*/
        removeActive(x);
        if (currentFocus >= x.length) currentFocus = 0;
        if (currentFocus < 0) currentFocus = (x.length - 1);
        /*add class "autocomplete-active":*/
        x[currentFocus].classList.add("autocomplete-active");
    }
    function removeActive(x) {
        /*a function to remove the "active" class from all autocomplete items:*/
        for (var i = 0; i < x.length; i++) {
          x[i].classList.remove("autocomplete-active");
      }
  }
  function closeAllLists(elmnt) {
    /*close all autocomplete lists in the document,
    except the one passed as an argument:*/
    var x = document.getElementsByClassName("autocomplete-items");
    for (var i = 0; i < x.length; i++) {
      if (elmnt != x[i] && elmnt != inp) {
          x[i].parentNode.removeChild(x[i]);
      }
  }
}
/*execute a function when someone clicks in the document:*/
document.addEventListener("click", function (e) {
    closeAllLists(e.target);
});
}
var countries=<?php echo json_encode($elsTitle);?>;
<?php 
for($r=0;$r<count($elsCat);$r++){  ?>
    autocomplete(document.getElementById("title<?=$elsCat[$r]->id?>0"), countries);
<?php  }   ?>
</script>
<script type="text/javascript">
    var x = 1; 
   function add_more(id){
    var count_item=document.getElementById("count_item"+id).value;
    var count_item_new=parseInt(count_item)+1;
    document.getElementById("count_item"+id).value=count_item_new;
    var maxField = 20; //Input fields increment limitation
    var addButton = $('.add_button'+id); //Add button selector
    var wrapper = $('.field_wrapper'+id); //Input field wrapper
    var fieldHTML = '<div class="form-group"><div class="col-sm-5"><div class="autocomplete" style="width:300px;"><input class="form-control" id="title'+id+count_item_new+'" type="text" name="title['+id+'][]" placeholder="Enter Your Title">  </div> </div>  <div class="col-sm-5"><div class="autocomplete" style="width:300px;">  <input class="form-control" id="input" type="text" name="input['+id+'][]" placeholder="Enter Your Input"> </div> <span id="addpnamevalid" class="text-danger"></span> </div>   <div class="col-sm-2"> </div>  <button type="button" class="remove_button btn-danger"> -Remove</button></div>';
        //Check maximum number of input fields
        if(x < maxField){ 
            x++; //Increment field counter
            $(wrapper).append(fieldHTML); //Add field html
        }
     autocomplete(document.getElementById("title"+id+count_item_new), countries);
    }
    //Once remove button is clicked
    $(wrapper).on('click', '.remove_button', function(e){
        e.preventDefault();
        $(this).parent('div').remove(); //Remove field html
        x--; //Decrement field counter
    });
function brandvalue(id)
{
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
             $("#pmodel").html('<option value="">Select Model </option>'); 
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
      var model=$("#pmodel option:selected").text();
       var brand=$("#input option:selected").text();
       var sku=brand.substring(0, 3)+"-"+model;
       //alert(sku);
      $("#psku").val(sku);
      
  }
</script>
@endsection