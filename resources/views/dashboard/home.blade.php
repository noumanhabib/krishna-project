@extends('layouts.layout')
@section('title', $title)
@section('content')

    <div id="page-wrapper">
        <div class="header">
            <h1 class="page-header">
                Dashboard
            </h1>
            <ol class="breadcrumb">
                <li><a href="javascript:;">Home</a></li>
                <li class="active">Dashboard</li>
            </ol>
        </div>
        <div id="page-inner">
            <!-- /. ROW  -->


            <div class="row">
                <div class="col-md-12">
                    <!-- Advanced Tables -->
                    <div class="panel panel-default">
                        <div class="panel-heading d-flex">
                            <div>Spare Part List SKU Level</div>
                            <div class="d-flex">


                                <a href="{{ url('export-spare-parts-sku') }}"><button class="btn btn-info" type="submit">Export</button></a> &nbsp;&nbsp;


                            </div>

                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table-striped table-bordered table-hover data_tablelist table">
                                <thead>
                                    <tr>
                                        <th>Sr.No</th>
                                        <th>Part Name</th>
                                        <th>Part Model</th>
                                        <th>Part Brand</th>
                                        <th>Type</th>
                                        <th>Colour</th>
                                        <th>SKU Number</th>
                                        <th>Quantity</th>
                                        <th>Failed Quantity</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @php $i=1;@endphp
                                    @if (!empty($data1))
                                        @foreach ($data1 as $row)
                                            <tr>
                                                <td>{{ $i++ }}</td>
                                                <td>{{ $row['name'] }}</td>
                                                <td>{{ $row['mname'] }}</td>
                                                <td>{{ $row['bname'] }}</td>
                                                <td>{{ $row['type'] }}</td>
                                                <td>{{ $row['colour_name'] }}</td>
                                                <td>{{ $row['sku_no'] }}</td>

                                                <?php $usersd = DB::table('received_purchase_order_parts_list')
                                                    ->select('quantity', 'id')
                                                    ->where('sku_id', $row['sku_id'])
                                                    ->get();

                                                $quantity = [];
                                                $idd = [];
                                                for ($iR = 0; $iR < count($usersd); $iR++) {
                                                    $idd[] = $usersd[$iR]->id;
                                                    $quantity[] = $usersd[$iR]->quantity;
                                                }

                                                // print_r($idd);
                                                // print_r($quantity);

                                                ?>



                                                <td>
                                                    <?php
                                                    if (!empty($usersd[0])) {
                                                        //   echo $usersd->id;

                                                        $usersdd = DB::table('received_parts_barcode_list')
                                                            ->selectRaw('count(id) as live')
                                                            ->whereIn('received_part_id', $idd)
                                                            ->where('iqc_status_one', '1')
                                                            ->where('status', '2')
                                                            // ->groupBy('received_part_id')
                                                            ->get();
                                                        // print_r($usersdd);
                                                        // die();
                                                        if (!empty($usersdd)) {
                                                            echo $usersdd[0]->live;
                                                        }
                                                    } else {
                                                        echo 0;
                                                    }

                                                    ?>
                                                </td>







                                                <td>
                                                    <?php
                                                    // if(!empty($usersd[0]))
                                                    // {
                                                    //   echo array_sum($quantity);
                                                    // }
                                                    // else
                                                    // {
                                                    //     echo 0;
                                                    // }
                                                    ?>

                                                    <?php if (!empty($usersd[0])) {
                  $usersddf = DB::table('received_parts_barcode_list')
                      ->selectRaw('count(id) as live')
                      ->whereIn('received_part_id', $idd)
                      ->where('iqc_status_one', '0')
                      ->where('status', '2')
                      // ->groupBy('received_part_id')
                      ->get();

                  if (!empty($usersddf)) {
                      echo $usersddf[0]->live;
                  }
              } else {
                  echo 0;
              } ?>
                                                </td>

                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>




        <div class="row">
            <div class="col-md-12">
                <!-- Advanced Tables -->
                <div class="panel panel-default">
                    <div class="panel-heading d-flex">
                        <div>ELS Inventory System</div>
                        <div class="d-flex">

                            {!! Form::open(['url' => 'export-els-product-report', 'class' => 'form-horizontal', 'role' => 'form']) !!}
                            <!--<a href="javascript:;"><button class="btn btn-info" type="submit">Export</button></a> &nbsp;&nbsp;-->

                            {!! Form::close() !!}
                            <a href="{{ url('export-els-product-inv') }}"><button class="btn btn-info" type="submit">Export</button></a> &nbsp;&nbsp;


                        </div>
                    </div>

                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table-striped table-bordered table-hover data_tablelist table">
                                <thead>
                                    <tr>
                                        <th>Sr.No</th>

                                        <th>ELS Brand</th>
                                        <th>ELS Model</th>

                                        <th>Stock IN </th>
                                        <!--<th>Stock Out</th>      -->

                                    </tr>
                                </thead>
                                <tbody>
                                    @php $i=1;@endphp
                                    @if (!empty($data2))
                                        @foreach ($data2 as $row)
                                            <tr>
                                                <?php
                                                $usersddfi = DB::table('els_system_info_details')
                                                    ->selectRaw('count(id) as live')
                                                    ->where('model_id', $row['model_id'])
                                                    ->where('stock_in', 1)
                                                    ->whereNull('deleted_at')
                                                    ->get();

                                                //  $usersddfo = DB::table('els_system_info_details')
                                                // ->selectRaw('count(id) as live')
                                                // ->where('model_id', $row['model_id'])
                                                //  ->where('stock_in', 0)
                                                // ->get();

                                                ?>

                                                <td>{{ $i++ }}</td>
                                                <td>{{ $row['bname'] }}</td>
                                                <td>{{ $row['mname'] }}</td>
                                                <td>{{ $usersddfi[0]->live }}</td>
                                                <!--<td>-->

                                                <!--    </td>-->
                                            </tr>
                                        @endforeach
                                    @endif

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>




        <div class="row">
            <div class="col-md-12">
                <!-- Advanced Tables -->
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Device Backward Tracking
                    </div>

                    <div class="panel-body">
                        {!! Form::open(['url' => 'download-device-backward-tracking', 'class' => 'form-horizontal', 'role' => 'form', 'id' => 'download-device', 'onsubmit' => 'return false']) !!}
                        <div class="form-group">
                            <div class="col-sm-2">
                                {!! Form::label('title', 'Barcode', ['class' => 'control-label']) !!}
                            </div>
                            <div class="col-sm-6">
                                {!! Form::text('barcode', '', ['class' => 'form-control required', 'id' => 'barcode', 'placeholder' => 'Barcode', 'onkeypress' => 'enterKeyPress(event)']) !!}
                                <span class="help-block text-danger error"></span>
                            </div>
                            <div class="col-sm-4">
                                {{ Form::button('View', ['onclick' => 'viewDeviceBarcode()', 'class' => 'btn btn-info']) }}
                                {{ Form::button('Download', [
                                    'onclick' => 'downloadDevicelog()',
                                    'class' => 'btn btn-info
                                							hide',
                                    'id' => 'download-btn',
                                ]) }}
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12" id="device_backward"></div>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <!-- Advanced Tables -->
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Spare Part Backward Tracking
                    </div>

                    <div class="panel-body">
                        {!! Form::open(['url' => 'download-spare-part-backward-tracking', 'class' => 'form-horizontal', 'role' => 'form', 'id' => 'download-spare-part', 'onsubmit' => 'return false']) !!}
                        <div class="form-group">
                            <div class="col-sm-2">
                                {!! Form::label('title', 'Barcode', ['class' => 'control-label']) !!}
                            </div>
                            <div class="col-sm-6">
                                {!! Form::text('spare_barcode', '', ['class' => 'form-control required', 'id' => 'spare_barcode', 'placeholder' => 'Barcode', 'onkeypress' => 'enterKeyPressS(event)']) !!}
                                <span class="help-block text-danger error"></span>
                            </div>
                            <div class="col-sm-4">
                                {{ Form::button('View', ['onclick' => 'viewSparePartBarcode()', 'class' => 'btn btn-info']) }}
                                {{ Form::button('Download', [
                                    'onclick' => 'downloadSparePartlog()',
                                    'class' => 'btn btn-info
                                							hide',
                                    'id' => 'download-spare-btn',
                                ]) }}
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12" id="spare_part_backward"></div>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#dataTableID_1').DataTable();
            var oTable = $('#dataTableID').DataTable({
                "bDestroy": true,
                'processing': true,
                'serverSide': true,
                "ajax": {
                    "url": "{{ url('get-device-inventory-list') }}"
                },
                "columns": [{
                        data: "id"
                    },
                    {
                        data: "resived_date"
                    },
                    {
                        data: "barcode"
                    },
                    {
                        data: "sku_no"
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
                        data: "current_status"
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
                ],
                "rowCallback": function(nRow, aData, iDisplayIndex) {
                    var oSettings = this.fnSettings();
                    $("td:first", nRow).html(oSettings._iDisplayStart + iDisplayIndex + 1);
                    // if(aData.video_id){
                    // var actionBtn = '<a href="javascript:;" onclick="downloadVideo('+aData.id+')"><i class="fa fa-download text-primary mr-2"></i></a> &nbsp;&nbsp;';
                    // actionBtn += '<a href="javascript:;" onclick="deleteVideo('+aData.id+')"><i class="fa fa-trash-o text-danger mr-2"></i></a>';
                    // }else{
                    // var actionBtn = '<a href="javascript:;" onclick="uploadVideo('+aData.id+')"><i class="fa fa-upload text-primary mr-2"></i></a>';
                    // }
                    // $("td:last", nRow).html(actionBtn);
                    return nRow;
                },
                "order": [
                    [1, 'asc']
                ],
                "columnDefs": [{
                    orderable: false,
                    "targets": 0
                }]
            });

            viewDeviceBarcode = function() {
                var barcode = $('#barcode').val();
                if (barcode) {
                    $.ajax({
                        type: "POST",
                        url: "{{ url(route('get-device-backward-tracking')) }}",
                        data: {
                            barcode: barcode
                        },
                        dataType: 'JSON',
                        success: function(d) {
                            if (d.status) {
                                $('#device_backward').html(d.html);
                                $('#download-btn').removeClass('hide');
                            } else {
                                alert(d.message)
                            }
                        }
                    });
                }
            }

            enterKeyPress = function(e) {
                if (e.keyCode === 13) {
                    viewDeviceBarcode();
                } else {
                    $('#device_backward').html('');
                    $('#download-btn').addClass('hide');
                }
            }


            downloadDevicelog = function() {
                $('#download-device').attr('onsubmit', 'return true');
                $('#download-device').submit();
                $('#download-device').attr('onsubmit', 'return false');
            }

            viewSparePartBarcode = function() {
                var barcode = $('#spare_barcode').val();
                if (barcode) {
                    $.ajax({
                        type: "POST",
                        url: "{{ url(route('get-spare-part-backward-tracking')) }}",
                        data: {
                            barcode: barcode
                        },
                        dataType: 'JSON',
                        success: function(d) {
                            if (d.status) {
                                $('#spare_part_backward').html(d.html);
                                $('#download-spare-btn').removeClass('hide');
                            } else {
                                alert(d.message)
                            }
                        }
                    });
                }
            }

            enterKeyPressS = function(e) {
                if (e.keyCode === 13) {
                    viewSparePartBarcode();
                } else {
                    $('#spare_part_backward').html('');
                    $('#download-spare-btn').addClass('hide');
                }
            }

            downloadSparePartlog = function() {
                $('#download-spare-part').attr('onsubmit', 'return true');
                $('#download-spare-part').submit();
                $('#download-spare-part').attr('onsubmit', 'return false');
            }
        });
    </script>
@endsection
