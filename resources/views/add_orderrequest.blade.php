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
  .vodiapicker{
    display: none; 
  }

  #a{
    padding-left: 0px;
  }

  #a img, .btn-select img{
    width: 100px;

  }

  #a li{
    list-style: none;
    padding-top: 5px;
    padding-bottom: 5px;
  }

  #a li:hover{
   background-color: #F4F3F3;
 }

 #a li img{
  margin: 5px;
}

#a li span, .btn-select li span{
  margin-left: 30px;
}

/* item list */

.b{
  display: none;
  width: 100%;
  max-width: 350px;
  box-shadow: 0 6px 12px rgba(0,0,0,.175);
  border: 1px solid rgba(0,0,0,.15);
  border-radius: 5px;
  
}

.open{
  display: show !important;
}

.btn-select{
  margin-top: 10px;
  width: 100%;
  max-width: 350px;
  height: 34px;
  border-radius: 5px;
  background-color: #fff;
  border: 1px solid #ccc;

}
.btn-select li{
  list-style: none;
  float: left;
  padding-bottom: 0px;
}

.btn-select:hover li{
  margin-left: 0px;
}

.btn-select:hover{
  background-color: #F4F3F3;
  border: 1px solid transparent;
  box-shadow: inset 0 0px 0px 1px #ccc;
  
  
}

.btn-select:focus{
 outline:none;
}

.lang-select{
  margin-left: 50px;
}
.hidden_quantity {
    display: none;
}
</style>
<div id="page-wrapper" >
 <div class="header"> 
  <h1 class="page-header">
   <small>Add  Request Order</small>
 </h1>
 <ol class="breadcrumb">
  <li><a href="{{url('/home')}}">Home</a></li>
  <li><a href="{{url('order-request-list')}}"> Request Order</a></li>
  <li class="active">Add Request Order</li>
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
                  <div class="title">Add Request Order</div>
                </div>
              </div>
              <div class="panel-body">
                <form class="form-horizontal" autocomplete="off" action="save_orderrequest" method="post">
                  @csrf
                  <div class="form-group">
                    <div>
                      <div class="col-sm-10">
                     
                       <div  style="width:300px;">
                         <label>Barcode</label>
                         <input type="text" name="barcode" id="els" class="form-control p-3" onblur="GetModelAndColor(this.value);">
                      
                      </div>
                    
                    </div>
                  </div>
                </div>
                <div class="form-group">
                   <div class="col-md-4">
                   <label> GRN NO.</label><input type="text" name="grn_no" value="" id="grn_no" class="form-control" required="">
                   </div>
                </div>
                <div class="row">
                  <!-- <div class="col-md-6">
                    <div class="form-group">
                      <div class="col-md-12">
                         <label>Date</label>
                           <input type="date" name="date_val"  id="date_val" class="form-control" required="">
                       </div>
                    </div>
                  </div> -->
                  <div class="col-md-6">
                    <div class="form-group">
                      <div class="col-md-12">
                         <label>Model</label>
                         <input type="text" name="model" id="model" class="form-control p-3">
                        
                       </div>
                    </div>
                  </div>
                </div>
                 <div class="row">
                    <!-- <div class="col-md-6">
                      <div class="form-group">
                        <div class="col-md-12">
                           <label>Description</label>
                             <textarea name="description"  id="description" class="form-control" required=""></textarea>
                         </div>
                      </div>
                    </div> -->
                    <div class="col-md-6">
                      <div class="form-group">
                        <div class="col-md-12">
                           <label>colour</label>
                           <input type="text" name="colour" id="colour" class="form-control p-3">
                         
                         </div>
                      </div>
                 </div>
                 <div class="row">
                 </div>
                 <!-- <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <div class="col-md-12">
                           <label>Available</label>
                             <select class="form-control" name="availability" required="">
                               <option value="">Select Availability</option>
                               <option value="yes">Yes</option>
                               <option value="no">No</option>
                             </select>
                         </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <div class="col-md-12">
                           <label>Required Quantity</label>
                           <input type="text" name="req_quantity" class="form-control" id="req_quantity" required="">
                         </div>
                      </div>
                 </div> 
               </div> -->
              <!--  <div class="row">
                 <div class="col-md-6">
                      <div class="form-group">
                        <div class="col-md-12">
                           <label>NOS</label>
                           <input type="text" name="nos" class="form-control" id="nos" required="">
                         </div>
                      </div>
                 </div>
                  <div class="col-md-6">
                      <div class="form-group">
                        <div class="col-md-12">
                           <label>Recieved Quantity</label>
                           <input type="text" name="recieved_quantity" class="form-control" id="recieved_quantity" required="">
                         </div>
                      </div>
                 </div>
               </div> -->
             <!--   <div class="row">
                 <div class="col-md-6">
                      <div class="form-group">
                        <div class="col-md-12">
                           <label>Received Date</label>
                           <input type="date" name="recieved_date" class="form-control" id="recieved_date" required>
                         </div>
                      </div>
                 </div>
                  <div class="col-md-6">
                      <div class="form-group">
                        <div class="col-md-12">
                           <label>Remarks</label>
                           <textarea name="remark" class="form-control" id="remark" required></textarea>
                         </div>
                      </div>
                 </div>
               </div> -->
                <?php 

                for($r=0;$r<count($elsCat);$r++){  ?>
                 <p><?php print_r($elsCat[$r]->name);?></p>

                 <div  class="field_wrapper<?=$elsCat[$r]->id?>">
                  
                      @for($i=0;$i<=5;$i++)
                      <div class="form-group" id="row_{{$i}}">
                          <div>
                            <div class="col-sm-5"> 
                                 <div class="autocomplete" style="width:300px;">
                                  <select class="form-control sparepart_list"  id="required<?=$elsCat[$r]->id?>0" required   name="required[<?=$elsCat[$r]->id?>][]">
                                    <option value="">Select Spare Part Required</option>
                                    <?php 
                                       for($rr=0;$rr<count($elsOrder);$rr++){  ?>
                                      <option value="<?=$elsOrder[$rr]->id?>"><?=$elsOrder[$rr]->name?></option>
                                    <?php }  ?>
                                  </select>
                                </div>
                          </div>
                          <div class="col-sm-3">
                             <div class="autocomplete" style="width:100px;">
                              <input class="form-control quantity" id="input" type="number" required="" name="input[<?=$elsCat[$r]->id?>][]" placeholder="Enter Your Required Quantity" value="1">
                            </div>
                            <span id="addpnamevalid" class="text-danger"></span>
                         </div>
                        <div class="col-sm-4">
                            <input type="hidden" id="count_item<?=$elsCat[$r]->id?>" value="0">
                            <button type="button"  onclick="remove_row('{{$i}}');"   class="add_button<?=$elsCat[$r]->id?> btn-danger" title="Add field"> -Remove</button>
                        </div>
                      </div>
                        </div>                    
                     @endfor
                   <div class="form-group">
                      <div>
                        <div class="col-sm-5"> 

                             <div class="autocomplete" style="width:300px;">
                              <select class="form-control sparepart_list"  id="required<?=$elsCat[$r]->id?>0" required   name="required[<?=$elsCat[$r]->id?>][]">
                                <option value="">Select Spare Part Required</option>
                                <?php 
                                for($rr=0;$rr<count($elsOrder);$rr++){  ?>
                                  <option value="<?=$elsOrder[$rr]->id?>"><?=$elsOrder[$rr]->name?></option>
                                <?php }  ?>
                              </select>

                            </div>
                      </div>
                      <div class="col-sm-3">
                       <div class="autocomplete" style="width:100px;">
                        <input class="form-control quantity" id="input" type="number" required="" name="input[<?=$elsCat[$r]->id?>][]" placeholder="Enter Your Required Quantity" value="1">
                      </div>
                      <span id="addpnamevalid" class="text-danger"></span>
                    </div>
                    <div class="col-sm-4">
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
 <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script> 
<!--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" />
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script> -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.1/bootstrap3-typeahead.min.js"></script>
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
  autocomplete(document.getElementById("required<?=$elsCat[$r]->id?>0"), countries);
<?php  }   ?>
</script>

<script type="text/javascript">
 
  var x = 1; 
  function add_more(id)
  {
    var count_item=document.getElementById("count_item"+id).value;
    var count_item_new=parseInt(count_item)+1;
    document.getElementById("count_item"+id).value=count_item_new;
    var maxField = 20; //Input fields increment limitation
    var addButton = $('.add_button'+id); //Add button selector
    var wrapper = $('.field_wrapper'+id); //Input field wrapper
    var fieldHTML = '<div class="form-group"><div class="col-sm-5"><div class="autocomplete" style="width:300px;"><select required class="form-control sparepart_list" id="required'+id+count_item_new+'" type="text" name="required['+id+'][]" ><option>Select Spare Part Required</option>';
    <?php for($rr=0;$rr<count($elsOrder);$rr++){  ?>
      fieldHTML +=  '<option value="<?=$elsOrder[$rr]->id?>"><?=$elsOrder[$rr]->name?></option>';
    <?php }  ?>
    fieldHTML += '</select></div> </div>  <div class="col-sm-3"><div class="autocomplete" style="width:100px;">  <input class="form-control quantity" id="input" type="number" name="input['+id+'][]" required placeholder="Enter Your Required Quantity" value="1"> </div> <span id="addpnamevalid" class="text-danger"></span> </div>   <div class="col-sm-4"> </div> <button type="button" class="remove_button btn-danger"> -Remove</button></div>';
        //Check maximum number of input fields
         if(x < maxField)
         { 
            x++; //Increment field counter
            $(wrapper).prepend(fieldHTML); //Add field html
          }
          autocomplete(document.getElementById("required"+id+count_item_new), countries);
    }
    //Once remove button is clicked
    $(wrapper).on('click', '.remove_button', function(e){
      e.preventDefault();
        $(this).parent('div').remove(); //Remove field html
        x--; //Decrement field counter
      });
    //test for getting url value from attr
// var img1 = $('.test').attr("data-thumbnail");
// console.log(img1);

//test for iterating over child elements
var langArray = [];
$('.vodiapicker option').each(function(){
  var img = $(this).attr("data-thumbnail");
  var text = this.innerText;
  var value = $(this).val();
  var item = '<li><img src="'+ img +'" alt="" value="'+value+'"/><span>'+ text +'</span></li>';
  langArray.push(item);
})

$('#a').html(langArray);

//Set the button value to the first el of the array
$('.btn-select').html(langArray[0]);
$('.btn-select').attr('value', '');

//change button stuff on click
$('#a li').click(function(){
 var img = $(this).find('img').attr("src");
 var value = $(this).find('img').attr('value');
 var text = this.innerText;
 var item = '<li><img src="'+ img +'" alt="" /><span>'+ text +'</span></li>';
 $('.btn-select').html(item);
 $('.btn-select').attr('value', value);
 $(".b").toggle();
  //console.log(value);
});

$(".btn-select").click(function(){
  $(".b").toggle();
});

//check local storage for the lang
var sessionLang = localStorage.getItem('lang');
if (sessionLang){
  //find an item with value of sessionLang
  var langIndex = langArray.indexOf(sessionLang);
  $('.btn-select').html(langArray[langIndex]);
  $('.btn-select').attr('value', sessionLang);
} else {
 var langIndex = langArray.indexOf('ch');
 console.log(langIndex);
 $('.btn-select').html(langArray[langIndex]);
  //$('.btn-select').attr('value', 'en');
}


</script>
<script type="text/javascript">
  /*$('.livesearch').select2({
        placeholder: 'Select Barcode',
        
    }).on('change', function (e) {
       //alert("test");
       $('.quantity').addClass('hidden_quantity');
});*/

   function remove_row(num)
   {
       //alert(num);
        $("#row_"+num).remove();
   }

   function GetModelAndColor(val)
   {
      //alert(val);
     $('.quantity').addClass('hidden_quantity');
     var barcode = val;
     var csrfToken = "{{ csrf_token() }}";
     var Url="{{url('get_model_colour_by_barcode')}}";
     $.ajax({
        url: Url,
            headers: {
               'X-CSRF-Token': csrfToken,
            },
            type: "POST",
            dataType: "json",
            data:'barcode='+barcode,
     }).done(function (response) 
     {
       // console.log(response);
        var obj=response.data[0];
        $("#model").val(obj.mname);
        $("#colour").val(obj.name);
        GetSparePartProduct(obj.sparepart_product_sku);
        $(".sparepart_list").empty();
     });

   }
   function GetSparePartProduct(sku)
   {
          var Url     = "{{url('get_sparepart_product_list')}}";
          var csrfToken = "{{ csrf_token() }}";
        $.ajax({
                  url: Url,
                 headers: 
                 {
                    'X-CSRF-Token': csrfToken,
                 },
                  type: "POST",
                  data:"sku="+sku,
            })
         .done(function(response)
         {
              console.log(response);
               var  dropdown = $(".sparepart_list");

                dropdown.empty();
                dropdown.append('<option selected="true" disabled>Choose SparePart</option>');
                dropdown.prop("selectedIndex", 0);

                // Populate dropdown with list of provinces
                $.each(response.data, function (key, entry) {
                    dropdown.append($("<option></option>").attr("value", entry.id).text(entry.name));
                });
                
                
         });
   }
</script>
@endsection