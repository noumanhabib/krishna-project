@extends('layouts.layout')
@section('title', $title)
@section('content')
    @php
        // $bar = DB::table('els_system_info_details')
        // 		->select('els_system_info_details.*')->get();
        // 		// dd($bar->model_id);
        
        // 		// dd($bar[0]);
        // $empty_barcodes = DB::table('els_order_request_parts')
        // 		// ->select('barcodes')
        // 		->where('model_id', $bar[0]->model_id)
        // 		->where('brand_id', $bar[0]->brand_id)
        // 		// ->where('barcodes', "!=", "")
        // 		->where('barcodes', "")
        // 		->get();
        // dd($empty_barcodes[0]->barcodes);
        
        // $part_name = DB::table('part')
        // ->select('name')
        // ->where('id', $empty_barcodes[0]->part_id)->first();
        
        // dd($part_name->name);
        
        // $variable = DB::table('received_parts_barcode_list')
        // ->select('barcode')
        // ->where('barcode',"")
        // ->get();
        // dd($variable[0]->barcode);
        
        // for($i=0;$i<230000;$i++)
        // {
        // 	if($variable[$i]->barcode == "")
        // 	{
        // 		dd('okay');
        // 	}
        // }
        
        // $variable = \App\Models\ReceivedPurchaseOrderPartsBarcode::join('received_purchase_order_parts_list', function ($join) {
        // 		$join->on('received_purchase_order_parts_list.id', 'received_parts_barcode_list.received_part_id');
        // 		// ->where('barcode', "");
        // 		// $join->whereNull('received_purchase_order_parts_list.deleted_at');
        // 	});
        
        // dd($variable);
        
        // $variable = \App\Models\ReceivedPurchaseOrderPartsBarcode::join('received_purchase_order_parts_list', function ($join) {
        // 		$join->on('received_purchase_order_parts_list.id', 'received_parts_barcode_list.received_part_id')
        // 		;
        // 		$join->whereNull('received_purchase_order_parts_list.deleted_at');
        // 	})->join('brand', function ($join) {
        // 		$join->on('received_purchase_order_parts_list.brand_id', 'brand.id');
        // 	})->join('model', function ($join) {
        // 		$join->on('received_purchase_order_parts_list.model_id', 'model.id');
        // 	})->join('colour', function ($join) {
        // 		$join->on('received_purchase_order_parts_list.colour_id', 'colour.id');
        // 	})->join('product_type', function ($join) {
        // 		$join->on('received_purchase_order_parts_list.product_type_id', 'product_type.id');
        // 	})->join('spare_part_price_list', function ($join) {
        // 		$join->on('received_purchase_order_parts_list.sku_id', 'spare_part_price_list.id');
        // 	})->join('part', function ($join) {
        // 		$join->on('received_purchase_order_parts_list.part_id', 'part.id');
        // 	})->leftjoin('users', function ($join) {
        // 		$join->on('users.id', 'received_parts_barcode_list.tester_id');
        // 	})->whereNotNull('received_parts_barcode_list.iqc_status')->orderBy('received_parts_barcode_list.id', 'DESC')->first();
        
        // dd($variable->barcode);
        // if($empty_barcodes[0]->barcodes == $variable->barcode)
        // {
        // 	$indicators = 0;
        // }
        // else {
        // 	$indicators = 1;
        // }
        
        // if($variable->barcode == "")
        // {
        // 	dd('helooo');
        // }
        // dd($variable->barcode);
        
        // dd($variable->model_id,$variable->part_id,$variable->brand_id,$empty_barcodes[0]->model_id,$empty_barcodes[0]->part_id,$empty_barcodes[0]->brand_id);
        
        // if(($empty_barcodes[0]->model_id == $variable->model_id))
        // {
        // 	dd("heloo");
        // }
    @endphp

    <div id="page-wrapper">
        <div class="header">
            <h1 class="page-header">
                <small>Device Inventory</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="{{ url('/home') }}">Home</a></li>
                <li class="active">Device Inventory</li>
            </ol>
        </div>
        <div id="page-inner">
            <div class="row">
                <div class="col-md-12">
                    <!-- Advanced Tables -->
                    <div class="panel panel-default">
                        <div class="panel-heading d-flex">
                            <div>Device Inventory List</div>
                            <div class="d-flex">
                                <a href="javascript:;"><button class="btn btn-info"
                                        onclick="importData()">Import</button></a> &nbsp;&nbsp;
                                {!! Form::open(['url' => 'export-els-product-report-in', 'class' => 'form-horizontal', 'role' => 'form']) !!}
                                <a href="javascript:;"><button class="btn btn-info" type="submit">Export</button></a>
                                &nbsp;&nbsp;
                                {!! Form::close() !!}
                                <!--<a href="{{ url('elsproduct_form') }}"><button class="btn btn-primary" id="add_category_btn">+ Add ELS Product</button></a> -->
                            </div>
                        </div>

                        <div class="panel-body">
                            <div class="table-responsive">
                                <table id="dataTableID" class="table table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>Sr.No</th>
                                            <th>Date</th>
                                            <th>ELS Brand</th>
                                            <th>ELS Model</th>
                                            <th>Color</th>
                                            <th>UIN</th>
                                            <th>GRN No.</th>
                                            <th>IMEI 1</th>
                                            <th>IMEI 2</th>
                                            <th>RAM</th>
                                            <th>ROM</th>
                                            <th>Incoming Grade</th>
                                            <!--<th>Purchased Price</th>-->
                                            <th style="display:none;">Return Price</th>
                                            <th>Remark</th>
                                            <!--<th>In Vendor Name</th>     -->
                                            <th>Sub Status</th>
                                            <th>Status</th>

                                            <th>Action</th>
                                            {{-- <th>Indicator</th> --}}
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- Delete Category Model -->
        <div class="modal fade" id="DeleteModal" tabindex="-1" role="dialog" aria-labelledby="DeleteModal">
            <div class="modal-dialog modal-dialog-centered modal-min" role="document">
                <div class="modal-content">
                    <div class="modal-body text-center">
                        <i class="fa fa-ban" style="font-size: 130px; color: #ff8800;"></i>
                        <h3>Are you sure want to delete it?</h3>
                        <form method="post" style="padding: 24px;">
                            @csrf
                            <input type="hidden" name="delete_id" id="delete_id" value="">
                            <button type="button" class="btn btn-success light" id="delete_btn" data-id="">Yes, delete
                                it!
                                <span class="btn-icon-right"><i class="fa fa-check"></i></span></button>
                            <button type="button" class="btn btn-danger light" data-dismiss="modal">Cancel <span
                                    class="btn-icon-right"><i class="fa fa-close"></i></span></button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Import Data Modal -->
        <div id="importModel" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Import ELS Status</h4>
                    </div>
                    <div class="modal-body">
                        {!! Form::open([
                            'url' => 'upload-change-status',
                            'class' => 'form-horizontal',
                            'id' => 'save-form',
                            'role' => 'form',
                            'files' => 'true',
                        ]) !!}
                        <div class="form-group">
                            <div class="col-md-12">
                                {!! Form::label('title', 'Choose Product File', ['class' => 'control-label']) !!}
                                {!! Form::file('file_csv', [
                                    'class' => 'form-control',
                                    'required',
                                    'id' => 'file_csv',
                                    'placeholder' => '-Select-',
                                ]) !!}
                            </div>
                            <div class="col-md-12" style="padding: 17px;">
                                <button type="submit" class="btn btn-success mr-1">Submit <span class="btn-icon-right"><i
                                            class="fa fa-check"></i></span></button>
                                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel <span
                                        class="btn-icon-right"><i class="fa fa-close"></i></span></button>
                                <a href="{{ url('assets\files\els_status.csv') }}" class="btn btn-info light"
                                    download>Download Sample File</span></a>
                            </div>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>

            </div>
        </div>

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script type="text/javascript">
            importData = function() {
                $('#importModel').modal('show');
            }

            saveData = function() {
                var form = $('#save-form');
                var url = form.attr('action');
                var csrfToken = "{{ csrf_token() }}";
                var formData = new FormData(form[0]);
                $.ajax({
                    type: "POST",
                    url: url,
                    headers: {
                        "X-CSRF-Token": csrfToken,
                    },
                    contentType: false,
                    processData: false,
                    data: formData,
                    dataType: 'JSON',
                    success: function(d) {
                        if (d.status) {
                            alert(d.message);
                            location.reload();
                        } else {
                            alert(d.message);
                        }
                    }
                });
            }

            function delete_data(id) {
                //alert(id);
                $("#delete_id").val(id);
            }

            $("#delete_btn").on("click", function() {
                toastr.options = {
                    progressBar: "true",
                    positionClass: "toast-top-right",
                };

                var id = $("#delete_id").val();
                var csrfToken = "{{ csrf_token() }}";

                var Url = "{{ url('delete_elsproduct') }}";
                $.ajax({
                    url: Url,
                    headers: {
                        "X-CSRF-Token": csrfToken,
                    },
                    type: "POST",
                    data: "id=" + id,
                }).done(function(response) {

                    if (response.code == 200) {
                        $("#delete_btn").prop("disabled", true);
                        toastr.success(response.message);
                        setTimeout(function() {
                            window.location.reload();
                        }, 2000);
                    }
                });
            });

            $(document).ready(function() {
                var oTable = $('#dataTableID').DataTable({
                    "bDestroy": true,
                    'processing': true,
                    'serverSide': true,
                    "ajax": {
                        "url": "{{ url('fatch_els_product_list_in') }}",
                    },
                    "columns": [{
                            data: "id"
                        },
                        {
                            data: "resived_date"
                        },
                        {
                            data: "bname"
                        },
                        {
                            data: "mname"
                        },
                        {
                            data: "colour_name"
                        },
                        {
                            data: "barcode"
                        },
                        {
                            data: "grn_no"
                        },
                        {
                            data: "imei_1"
                        },
                        {
                            data: "imei_2"
                        },
                        {
                            data: "ram"
                        },
                        {
                            data: "rom"
                        },
                        {
                            data: "grade"
                        },
                        // 			{ data: "mrp" },
                        //  { data: "new_price" },
                        {
                            data: "remark"
                        },
                        // 			{ data: "vname" },
                        {
                            data: "sub_status"
                        },
                        {
                            data: "status"
                        },
                        // 			{ data: "out_vendor" },
                        {
                            data: "id"
                        },
                        // { bar: "barcodes" },
                        // {
                        //     data: "indicator"
                        // },
                    ],
                    "rowCallback": function(nRow, aData, iDisplayIndex) {
                        console.log(nRow);
                        console.log(aData);
                        var oSettings = this.fnSettings();
                        $("td:first", nRow).html(oSettings._iDisplayStart + iDisplayIndex + 1);
                        var actionBtn = '';
                        actionBtn += '<a href="{{ url('edit_elsproduct') }}/' + aData.id +
                            '"><i class="fa fa-edit text-danger mr-2"></i></a> /';
                        actionBtn +=
                            '<a href="javascript:;"  data-toggle="modal" data-target="#DeleteModal" onclick="delete_data(' +
                            aData.id +
                            ')"><i class="fa fa-trash text-danger mr-2"></i>Delete</a></td> &nbsp;&nbsp;';
                        // var indicatorBtn = '';
                        // if($indicators == 0)
                        // {
                        // indicatorBtn +=
                        //     '<label class="switch"><input value="0" type="checkbox" class="statuscheckbox"><span class="slider round"></span></label>';
                        // }
                        // else
                        // {
                        // indicatorBtn +=
                        //     '<label class="switch"><input value="1" type="checkbox" class="statuscheckbox"><span class="slider round"></span></label>';
                        // }
                        $("td:eq(15)", nRow).html(actionBtn);
                        // $("td:eq(16)", nRow).html(indicatorBtn);
                        return nRow;
                    },
                    "order": [
                        [0, 'asc']
                    ],
                    "columnDefs": [{
                        orderable: false,
                        "targets": 0
                    }]
                });
                @if (!in_array(\App\Models\RoleModel::find(\Auth::user()->role)->role_name, ['admin', 'finance']))
                    oTable.column(10).visible(false);
                @endif
                @if (!Helper::actionPermission())
                    oTable.column(16).visible(false);
                @endif

            });
        </script>
    @endsection
