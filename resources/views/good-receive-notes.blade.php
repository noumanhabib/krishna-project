@extends('layouts.layout')
@section('title', $title)
@section('content')


    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
<link rel="stylesheet" href="style.css"> --}}
    <!--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">-->
    <!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>-->
    <!--<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>-->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <style>
        .popup {
            background-color: white;
            align-self: center;
            height: 40vh;
            box-shadow: rgba(0, 0, 0, 0.02) 0px 1px 3px 0px, rgba(27, 31, 35, 0.15) 0px 0px 0px 1px;
            z-index: -1;
            display: none;
        }

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
        function childrenRoww(i, p_id, p_name, m_id, m_name, c_id, c_name, q) {
            $('#childTable').find('tbody').append(
                `
                <tr id="remove" class="remove-all-from-tow-row">
                    <th scope="row">` + i + `</th>
                    <td class="col-sm-2">
                        <select name="item[` + i + `][part_id]" id="item_` + i + `" class="form-control">
                                <option class="dispalyN" value="${p_id}">${p_name}</option>
                        </select>
                    </td>
                    <td class"col-sm-2>
                        <select name="item[` + i + `][model_id]" id="model_` + i + `" class="form-control">

                                <option class="dispalyN" value="${m_id}">${m_name}</option>
                        </select>
                    </td>
                    <td class="col-sm-2">
                        <select onchange = "return generateSku(` + i + `)" class="form-control" name="item[` + i +
                `][color_id]" id="color_` + i + `">
                                <option class="dispalyN" value="${c_id}">${c_name}</option>
                        </select>
                    </td>
                    <td class="col-sm-1"><input type="text" value="${q}" name="item[` + i + `][old_quantity]" class="form-control" readOnly/></td>
                    <td class="col-sm-1"><input type="text" name="item[` + i + `][quantity]" class="form-control" /></td>
                    <td class="col-sm-4"><input type="text" id="sku_` + i + `" name="item[` + i + `][sku]" class="form-control" /></td>
                    <td><li class="btn btn-danger btn-remove">remove</li></td>
                </tr>
            `);
        }


        function childrenRow() {

            i++;

            $('#childTable').find('tbody').append(
                `
        <tr id="remove" class="remove-all-from-tow-row">
            <th scope="row">` + i + `</th>
            <td class="col-sm-2">
                <select name="item[` + i + `][part_id]" id="item_` + i + `" class="form-control">
                    @foreach ($items as $item)
                        <option value="{{ $item->id }}">{{ $item->name }}${j}</option>
                    @endforeach
                </select>
            </td>
            <td class"col-sm-2>
                <select name="item[` + i + `][model_id]" id="model_` + i + `" class="form-control">

                    @foreach ($models as $model)
                        <option value="{{ $model->id }}">{{ $model->mname }}</option>
                    @endforeach
                </select>
            </td>
            <td class="col-sm-2">
                <select onchange = "return generateSku(` + i + `)" class="form-control" name="item[` + i +
                `][color_id]" id="color_` + i + `">
                    @foreach ($colours as $colour)
                        <option value="{{ $colour->id }}">{{ $colour->name }}</option>
                    @endforeach
                </select>
            </td>
            <td class="col-sm-1"><input type="text" name="item[` + i + `][quantity]" class="form-control" /></td>
            <td class="col-sm-1"><input type="text" name="item[` + i + `][old_quantity]" class="form-control" /></td>
            <td class="col-sm-4"><input type="text" id="sku_` + i + `" name="item[` + i + `][sku]" class="form-control" /></td>
            <td><li class="btn btn-danger btn-remove">remove</li></td>
        </tr>
    `);
        }
        // <td><button class="btn btn-danger btn-remove" onclick="remove();"></button></td>
        // function remove()
        // {
        $(document).on('click', '.btn-remove', function() {
            var button_id = $(this).attr("id");
            $('#remove').remove();
            //    i--;
        });
        //     var button_id = $(this).attr("id");
        //     $('$remove').remove();
        // }
    </script>

    <div id="page-wrapper">
        <div class="header">
            <h1 class="page-header">
                <small>Good Receive Notes</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="{{ route('home') }}">Home</a></li>
                <li class="active">Good Receive Notes</li>
            </ol>
        </div>
        <div class="container-fluid">
            @if (Session::has('message'))
                <div class="alert {{ Session::get('alert-class') }} validation">

                    <button class="close" type="button" data-dismiss="alert"><span>×</span></button>

                    <p class="text-left">{!! Session::get('message') !!}</p>

                </div>
            @endif

            <form action="{{ url('store-grn') }}" method="post">
                @csrf
                <div class="row">
                    <div class="col-md-3">

                        <p class="table-heading"><strong>Vendor</strong></p>
                        {{-- @foreach ($vendors as $vendor) --}}
                        {{-- <input type="text" id="vendor_id"> --}}
                        {{-- @endforeach --}}
                        <select id="vendor_id" class="form-control" name="vendor_id">
                            @foreach ($vendors as $vendor)
                                <option class="dispalyN" value="{{ $vendor->id }}">{{ $vendor->vname }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <p class="table-heading"><strong>Customer</strong></p>
                        <select class="form-control" name="customer_id">
                            {{-- @php
                        $user = Auth::user();
                        echo "<option value='{{ $user->id }}'>$user->name</option>";
                        @endphp --}}
                            @foreach ($customers as $user)
                                <option class="dispalyN" value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <p class="table-heading"><strong>Corriour by</strong></p>
                        <input class="table-input" required name="courror_by" class="form-control" value=""
                            type="text">
                    </div>
                    <div class="col-md-2">
                        <p class="table-heading"><strong>PO Number</strong></p>
                        <input class="table-input" required id="po_no" onchange="return get_data()" name="po_no"
                            class="form-control" value="" type="text">

                    </div>
                    <div class="col-md-2">
                        <p class="table-heading"><strong>Stock</strong></p>
                        <select class="form-control" name="stock">
                            <option value="New Stock">New Stock</option>
                            <option value="return">Return</option>
                            <option value="service">Service</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">&nbsp;</div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive table table-hover my-4 ">
                            <table class="table" id="childTable">
                                <thead class="table-head">
                                    <tr>
                                        <th class="col-md-1">No.</th>
                                        <th class="col-md-2">Items</th>
                                        <th class="col-md-2">Model</th>
                                        <th class="col-md-2">Color</th>
                                        <th class="col-md-1">PO Quantity </th>
                                        <th class="col-md-1">Received Quantity </th>
                                        <th class="col-md-3">SKU</th>
                                        <!--<th class="col-md-2">Total price</th>-->
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th class="col-md-1">1</th>
                                        <td class="col-md-2">
                                            <select name="item[1][part_id]" id="item_1" class="form-control">
                                                @foreach ($items as $item)
                                                    <option class="dispalyN" value="{{ $item->id }}">
                                                        {{ $item->name }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td class="col-md-2">
                                            <select name="item[1][model_id]" id="model_1" class="form-control">
                                                @foreach ($models as $model)
                                                    <option class="dispalyN" value="{{ $model->id }}">
                                                        {{ $model->mname }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td class="col-md-2">
                                            <select name="item[1][color_id]" id="color_1" onchange="return generateSku(1)"
                                                class="form-control">
                                                @foreach ($colours as $colour)
                                                    <option class="dispalyN" value="{{ $colour->id }}">
                                                        {{ $colour->name }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td class="col-md-1">
                                            <input type="text" id="old_quantity" name="item[1][old_quantity]"
                                                class="form-control" />
                                        </td>
                                        <td class="col-md-1">
                                            <input type="text" name="item[1][quantity]" id="quantity_1"
                                                class="
                                            form-control" />
                                        </td>

                                        <td class="col-md-3">
                                            <input type="text" name="item[1][sku]" id="sku_1"
                                                class="form-control" />
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <a type="text" class="add-row" id="addrow" onclick="childrenRow()"
                                value="+ Add New Row">+ Add
                                New Row</a>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
            <div class="row">
                <div class="col-md-12">&nbsp;</div>
            </div>
            <div class="row">

                <div class="col-md-12">
                    <h1>GRN's</h1>
                </div>
            </div>
            <form action="{{ url('GRNexport') }}" method="post">
                <div class="d-flex flex-row">
                    @csrf
                    <input required type="date" name="start" class="form-control  "
                        style="width:200px !important ; margin-left:20px !important;">
                    @error('start')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                    <input required type="date" name="last" class="form-control "
                        style="width:200px !important ;margin-left:20px !important;">
                    @error('last')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                    <input type="submit" value="Export" class='btn  btn-success' style="margin-left:20px !important;">
                </div>
            </form>
            <div class="row">
                <div class="col-md-12">&nbsp;</div>
            </div>
            <div class="popup">

            </div>
            <div class="row showeddata">
                <div class="col-md-12">
                    <div class="table-responsive table table-hover my-4 ">
                        <table class="table" id="childTable">
                            <thead class="table-head">
                                <tr>
                                    <th>No.</th>
                                    <th>Date</th>
                                    <th>Vendor</th>
                                    <th>Customer</th>
                                    <th>Corriour by</th>
                                    <th>PO Nmber</th>
                                    <th>Stock</th>
                                    <th>&nbsp;</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($mygrn as $grn)
                                    <tr>
                                        <td>{{ 'GRN' . $grn->id }}</td>
                                        <td>{{ date('Y-m-d', strtotime($grn->created_at)) }}</td>
                                        <td>{{ \App\Models\MasterVendorModel::find($grn->vendor_id)->vname }}</td>
                                        <td>{{ \App\Models\User::find($grn->customer_id)->name }}</td>
                                        <td>{{ $grn->corriour_by }}</td>
                                        <td>{{ $grn->po_no }}</td>
                                        <td>{{ $grn->stock }}</td>
                                        <td>

                                            {{-- @include('grn-include') --}}
                                            <a href="javascript:;"><button class="btn btn-info"
                                                    onclick="	$('#importModel{{ $grn->id }}').modal('show');">view</button></a>
                                            &nbsp;&nbsp;
                                            @include('my_modal')

                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>

                            {{--
                </div> --}}





                            {{-- </td>
                </tr> --}}
                            {{-- @endforeach --}}
                            {{-- </tbody> --}}
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    <script>
        let varr = document.getElementById("po_no").value;
        console.log(varr);

        function createPopup() {
            let div = document.querySelector('.popup');
            div.style.display = "block";
            let data = document.querySelector('.showeddata').style.index = "2";



        }

        function get_data() {
            var po_no = document.getElementById('po_no').value;
            var csrfToken = "{{ csrf_token() }}";
            var Url = "{{ url('get_data_po_no') }}";
            $.ajax({
                url: Url,
                type: "GET",
                data: "po_no=" + po_no,
            }).done(function(response) {
                console.log(response);
                let ele = document.querySelectorAll(".dispalyN")
                ele.forEach(element => {
                    element.style.display = 'none'
                });
                $(".remove-all-from-tow-row").remove();
                document.getElementById('old_quantity').readOnly = true;

                let part = response.array['part'];
                let model = response.array['model'];
                let color = response.array['color'];
                let quantity = response.array['quantity'];
                if (is_not_null_array(part) && is_not_null_array(model) && is_not_null_array(color) &&
                    is_not_null_array(quantity)) {
                    document.getElementById('vendor_id').value = response.vname;
                    document.getElementById('item_1').value = part[0]['id'];
                    document.getElementById('model_1').value = model[0]['id'];
                    document.getElementById('color_1').value = color[0]['id'];
                    document.getElementById('old_quantity').value = quantity[0];
                    generateSku(1);
                    let count = 1;
                    for (let index = 1; index < part.length; index++) {
                        count++;
                        childrenRoww(count, part[index]['id'], part[index]['name'], model[index]['id'], model[index]
                            ['mname'], color[index]['id'], color[index]['name'], quantity[index]);
                        generateSku(count);
                    }
                }

            });
        }

        function is_not_null_array(object) {
            return object && typeof object == "object" && object.length > 0;
        }

        function generateSku(val) {
            console.log("value: ", val);
            var part_color_id = document.getElementById('color_' + val).value;
            var part_id = document.getElementById('item_' + val).value;
            var brand_id = 1;
            var model_id = document.getElementById('model_' + val).value;
            var product_type_id = 1;


            var csrfToken = "{{ csrf_token() }}";
            var Url = "{{ url('get_html_sku_auto') }}";
            $.ajax({
                url: Url,
                headers: {
                    "X-CSRF-Token": csrfToken,

                },
                type: "POST",
                data: "id=" + val + "&part_color_id=" + part_color_id + "&part_id=" + part_id + "&brand_id=" +
                    brand_id + "&model_id=" + model_id + "&product_type_id=" + product_type_id,
            }).done(function(response) {
                document.getElementById('sku_' + val).value = response.html;
            });
        }
    </script>




























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
