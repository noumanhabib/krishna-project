@extends('layouts.layout')
@section('title',$title)
@section('content')


{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
<link rel="stylesheet" href="style.css"> --}}

<style>
    .top-nav {
        display: block;
        display: inline;
        display: flex;
        justify-content: space-around;

    }

    .top-nav-links {
        position: relative;
        bottom: 10px;
    }

    .table-input {
        margin-top: -29px;
        background-color: transparent;
        color: black;
        border: none;
        border-bottom: 1px solid black;
        outline: none;
        width: 97%;


    }

    #cars {

        background-color: transparent;
        color: black;
        border: none;
        border-bottom: 1px solid black;
        outline: none;
        width: 97%;
    }

    @media only screen and (max-width: 767px) {
        #cars {
            border: none;
            border-bottom: 1px solid black;
            width: 75%;
        }
    }

    .table>:not(:first-child) {
        border-top: transparent;
    }

    .add-row {
        cursor: pointer;
        color: blue;
        border-bottom: none;
    }

    .table-heading {
        padding-left: 5px;
    }

    .table-of {
        margin-left: 25px;
    }

    .table-button {

        width: 100%;
    }

    .f-cal {
        display: flex;
        justify-content: space-between;
        border-bottom: 1px solid;

    }

    .calculation {
        float: right;
        width: 35%;
        line-height: 15px;
        position: relative;
        bottom: 5px;

    }

    @media only screen and (max-width: 767px) {
        .f-cal {
            display: flex;
            justify-content: space-between;
            margin-bottom: 1px solid;

        }

        .calculation {


            width: 100%;

        }
    }

    .table-head {
        background-color: #dadada;
    }

    .nav-btn {
        background-color: #928181;
    }
</style>
<script>
    var i = 1;
function childrenRow() {
  i++;
  $('#childTable').find('tbody').append('<tr><th scope="row">'+i+'</th><td class="col-sm-4"><input type="text" name="items" class="form-control" /></td><td><input type="text" name="Quantity" class="form-control" /></td><td class="col-sm-2"><input type="text" name="Price Per Unit" class="form-control" /></td><td class="col-sm-2"><input type="text" name="Total price" class="form-control" /></td></td><td class="col-sm-2"><input type="text" name="Tax" class="form-control" /></td></td><td class="col-sm-2"><input type="text" name="sales items" class="form-control" /></td></td><td class="col-sm-2"><input type="text" name="ingredents" class="form-control" /></td></td><td class="col-sm-2"><input type="text" name="production" class="form-control" /></td></tr>');
}

</script>









<div id="page-wrapper">
    <div class="header">
        <h1 class="page-header">
            <small>Good Receive Notes</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('home') }}">Home</a></li>
            {{-- <li><a href="{{url('brand_list')}}">/a></li> --}}
            <li class="active">Good Receive Notes</li>
        </ol>
    </div>

    <div class="container-fluid">

        <div class="row bg-light">
            <div>Sales order</div>
            <div class="top-nav">

                <div class="name me-auto mb-2">
                    <h5>SO-1[DEMO] jane Rooms [DEMO]</h5>
                </div>
                {{-- <div class="d-flex">	
                    <div id="reportrange" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                        <i class="fa fa-calendar"></i>&nbsp;
                        <span></span> <i class="fa fa-caret-down"></i>
                    </div>&nbsp;&nbsp;
                    <a href="{{url('download-fqc-report')}}"><button class="btn btn-primary button_right" id="add_category_btn">Export </button></a> 
                </div> --}}
                <div class="top-nav-links"><button class=" nav-btn btn btn-secondary dropdown-toggle me-5" type="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        Not shipped
                    </button>

                    <i class="bi bi-printer me-4"></i>
                    <i class="bi bi-three-dots-vertical me-4 "></i>
                    <i class="bi bi-x "></i>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-8">
                <p class="table-heading"><strong>Customer</strong></p>
                <input class="table-input" type="text">
            </div>
            <div class="col-md-2">
                <p class="table-heading"><strong>Delivery Deadline</strong></p>
                <input class="table-input" type="text">
            </div>
            <div class="col-md-2">
                <p class="table-heading"><strong>Created date</strong></p>
                <input class="table-input" type="text">
            </div>
            <div class="col-md-4">
                <p class="table-heading"><strong>Sales order #</strong></p>
                <input class="table-input" type="text">
            </div>
            <div class="col-md-4"></div>
            <div class="col-md-4">
                <p class="table-heading"><strong>Ship from</strong></p>
                <form action="/action_page.php">
                    <select name="cars" id="cars">
                        <option value="Location">Location</option>
                        <option value="saab">M</option>
                        <option value="opel">k</option>
                        <option value="audi">l</option>
                    </select>

                </form>
                <i class="bi bi-pencil"></i>
            </div>
            <div class="col-md-4">
                <p class="table-heading"><strong>Bill to</strong></p>
                <input class="table-input" type="text">
            </div>
            <div class="col-md-4">
                <p class="table-heading"><strong>Ship to</strong></p>
                <input class="table-input" type="text">
            </div>


        </div>

        <div class="table-responsive table table-hover my-4 ">


            <table class="table" id="childTable">
                <thead class="table-head">
                    <tr>
                        <th class="col-md-1">No.</th>
                        <th class="col-md-3">Items</th>
                        <th class="col-md-2">Quantity </th>
                        <th class="col-md-2">Price per unit</th>
                        <th class="col-md-2">Total-price</th>
                        <th class="col-md-2">Tax%</th>
                        <th class="col-md-2">Sales_item</th>
                        <th class="col-md-2">Ingrediants</th>
                        <th class="col-md-2">production</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th class="col-md-1">1</th>
                        <td class="col-md-3">
                            <input type="text" name="items" class="form-control" />
                        </td>
                        <td class="col-md-1">
                            <input type="text" name="Quantity" class="form-control" />
                        </td>
                        <td class="col-md-1">
                            <input type="text" name="Price per unit" class="form-control" />
                        </td>
                        <td class="col-md-1">
                            <input type="text" name="total price " class="form-control" />
                        </td>
                        <td class="col-md-1">
                            <input type="text" name="tax " class="form-control" />
                        </td>
                        <td class="col-md-2">
                            <div class=""> <button class="table-button btn btn-success" type="button"> Stock</button>
                            </div>
                        </td>
                        <td class="col-md-2">
                            <div class=""><button class="table-button btn btn-success" type="button"> Stock</button>
                            </div>
                        </td>
                        <td class="col-md-2">
                            <div class=""><button class="table-button btn btn-success" type="button"> Done</button>
                            </div>
                        </td>

                    </tr>
                </tbody>
            </table>
            <input type="text" class="add-row" id="addrow" onclick="childrenRow()" value="+ Add New Row" />
        </div>

        <div class="calculation">
            <div class="f-cal">
                <h5>Total</h5>
                <p>1pcs</p>
            </div>
            <div class="f-cal">
                <h5>Sub(total excluded)</h5>
                <p>1pcs</p>
            </div>
            <div class="f-cal">
                <h5>Plus Tax</h5>
                <p>1pcs</p>
            </div>
            <div class="f-cal">
                <h5>Total</h5>
                <p>1pcs</p>
            </div>
        </div>
    </div>
    {{-- <div id="page-inner">
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
                                            <div class="title">Add Brand</div>
                                        </div>
                                    </div>
                                    <div class="panel-body">
                                        <form class="form-horizontal" method="post">
                                            @csrf
                                            <div class="form-group">
                                                <label for="brand_name" class="col-sm-2 control-label">Brand <span
                                                        class="required_label">*</span></label>
                                                <div class="col-sm-4">
                                                    <input type="text" class="form-control" id="brand_name"
                                                        placeholder="Enter Brand Name "
                                                        onkeypress="return onlyAlphaKey(event)" autocomplete="off">
                                                    <span id="addbrandnamevalid" class="text-danger"></span>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-sm-offset-2 col-sm-10">
                                                    <button type="button" class="btn btn-default save_btn"
                                                        id="save_brand_btn">Submit</button>
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
    </div> --}}
</div>




























<!-- <div class="table-responsive table table-hover my-4 ">
           

<table class="table" id="childTable">
  <thead class="table-success">
    <tr>
      <th scope="col">No.</th>
      <th>Items</th>
      <th scope="col">Quantity </th>
      <th scope="col">Price per unit</th>
      <th scope="col">Total-price</th>
      <th scope="col">Tax%</th>
      <th scope="col">Sales item</th>
      <th scope="col">Ingrediants</th>
      <th scope="col">production</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <th scope="row">1</th>
      <td class="col-sm-4">
        <input type="text" name="items" class="form-control" />
      </td>
      <td>
        <input type="text" name="Quantity" class="form-control" />
      </td>
      <td class="col-sm-2">
        <input type="text" name="Price per unit" class="form-control" />
      </td>
      <td class="col-sm-2">
        <input type="text" name="total price " class="form-control" />
      </td>
      <td class="col-sm-2">
        <input type="text" name="tax " class="form-control" />
      </td>
      <td class="col-sm-2">
       <div class=""> <button  class="table-button btn btn-primary"type="button"> Stock</button></div>
      </td>
      <td class="col-sm-2">
        <div class=""><button  class="table-button btn btn-primary"type="button"> Stock</button></div>
      </td>
      <td class="col-sm-2">
        <div class=""><button  class="table-button btn btn-primary"type="button"> Done</button></div>
      </td>
    
    </tr>
  </tbody>
</table>  
</div>   -->


<!-- <input type="button" class="btn btn-block btn-default" id="addrow" onclick="childrenRow()" value="+add new row" />
    </div>
    </div>
    </div> -->
{{-- <script src="assets/bootstrap/js/bootstrap.min.js"></script>
<script src="assets/js/Table-With-Search.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"
    integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous">
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.min.js"
    integrity="sha384-IDwe1+LCz02ROU9k972gdyvl+AESN10+x7tBKgc9I5HFtuNz0wWnPclzo6p9vxnk" crossorigin="anonymous">
</script> --}}

@endsection