@extends('layouts.layout')
@section('content')
    <style>
        .sticky {
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 999;
        }

        .tooltip {
            position: relative;
            display: inline-block;
            border-bottom: 1px dotted black;
        }

        .tooltip .tooltiptext {
            visibility: hidden;
            width: 120px;
            background-color: #555;
            color: #fff;
            text-align: center;
            border-radius: 6px;
            padding: 5px 0;
            position: absolute;
            z-index: 1;
            bottom: 125%;
            left: 50%;
            margin-left: -60px;
            opacity: 0;
            transition: opacity 0.3s;
        }

        .tooltip .tooltiptext::after {
            content: "";
            position: absolute;
            top: 100%;
            left: 50%;
            margin-left: -5px;
            border-width: 5px;
            border-style: solid;
            border-color: #555 transparent transparent transparent;
        }

        .tooltip:hover .tooltiptext {
            visibility: visible;
            opacity: 1;
        }


        #overlay {
            position: fixed;
            top: 0;
            z-index: 100;
            width: 100%;
            height: 100%;
            display: none;
            background: rgba(0, 0, 0, 0.6);
        }

        .cv-spinner {
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .spinner {
            width: 40px;
            height: 40px;
            border: 4px #ddd solid;
            border-top: 4px #2e93e6 solid;
            border-radius: 50%;
            animation: sp-anime 0.8s infinite linear;
        }

        @keyframes sp-anime {
            100% {
                transform: rotate(360deg);
            }
        }

        .is-hide {
            display: none;
        }
    </style>
    <div id="page-wrapper">
        @if (session()->has('error'))
            echo "Status not found";
        @endif
        <div class="header">
            <h1 class="page-header">
                <small>New-Pin List</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="{{ url('/home') }}">Home</a></li>
                <li class="active">Stock In Parts New-PIN List</li>
                {{-- <li> --}}

                {{--
			</li> --}}
                <li>
                    <form action="{{ url('export-renewhub-status') }}" method="post">

                        @csrf
                        @foreach ($data66 as $item)
                            <input type="hidden" name="created_at[]" value="{{ $item->created_at }}">
                            <input type="hidden" name="new_pin[]" value="{{ $item->new_pin }}">
                            <input type="hidden" name="barcode[]" value="{{ $item->old_pin }}">
                            <input type="hidden" name="bname[]" value="{{ $item->bname }}">
                            {{-- <input type="hidden" name="bstatus[]" value="{{ $item->bstatus }}"> --}}
                            <input type="hidden" name="mname[]" value="{{ $item->mname }}">
                            {{-- <input type="hidden" name="mstatus[]" value="{{ $item->mstatus }}"> --}}
                            <input type="hidden" name="name[]" value="{{ $item->name }}">
                            <input type="hidden" name="sku_no[]" value="{{ $item->sku_no }}">
                            <input type="hidden" name="status[]" value="{{ $item->status }}">
                        @endforeach
                        <button class="btn btn-info" type="submit">Export</button>
                    </form>
                    {{-- {!! Form::open(['url' =>'export-new-pin', 'class' => 'form-horizontal',
				'role' => 'form']) !!}
				<a href="javascript:;"><button class="btn btn-info" type="submit">Export</button></a>
				&nbsp;&nbsp;

				{!! Form::close() !!} --}}
                </li>
                <li>
                    <a href="javascript:;"><button class="btn btn-info" onclick="importData()">Import Status</button></a>
                    &nbsp;&nbsp;
                </li>
                <li>
                    <a href="javascript:;"><button class="btn btn-info" onclick="importPrice()">Consumed Pin</button></a>
                    &nbsp;&nbsp;
                </li>
            </ol>
        </div>


        <div id="overlay">
            <div class="cv-spinner">
                <span class="spinner"></span>
            </div>
        </div>

        <div id="page-inner">

            <div class="row">
                <div class="col-md-12">
                    <!-- Advanced Tables -->
                    <div class="panel panel-default">
                        <div id="reportrange"
                            style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                            <i class="fa fa-calendar"></i>&nbsp;
                            <span></span> <i class="fa fa-caret-down"></i>
                        </div>&nbsp;&nbsp;
                        {{-- {!! Form::open(['url' =>'export-new-pin', 'class' => 'form-horizontal',
					'role' => 'form']) !!}

					<a href="javascript:;"><button class="btn btn-info" type="submit">Export</button></a>


					&nbsp;&nbsp; --}}
                        {{-- <form action="{{ url('export-new-pin') }}" method="post">
						@csrf
						{{-- <button class="btn btn-info" type="submit">Export</button>

						--}}
                        {{-- <input type="submit" value="Export" class="btn btn-primary"> --}}


                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="dataTableID">
                                    <thead>
                                        <tr>
                                            <th>Sr.No</th>
                                            {{-- <th>PO No</th> --}}
                                            <th>Brand</th>
                                            <th>Model</th>
                                            {{-- <th>Colour</th> --}}
                                            <th>SKU No</th>
                                            <th>Parts Name</th>
                                            <th>PIN</th>
                                            {{-- <th>Price</th> --}}
                                            {{-- <th>IQC Engineer Name</th> --}}
                                            {{-- <th>IQC Pass/Failed</th> --}}
                                            <th>Status</th>
                                            {{-- <th>Received Date</th> --}}
                                            {{-- <th>After IQC Pass/Failed</th> --}}
                                            {{-- <th>Remark</th> --}}
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {{-- {{ dd($data66) }} --}}
                                        @foreach ($data6 as $item)
                                            <tr>


                                                <td>
                                                    1
                                                </td>

                                                {{-- <td></td> --}}

                                                <td>
                                                    {{-- <input type="text" style="border: none" name="bname[]" id="bname[]"
													value="{{ $item->bname }}"> --}}
                                                    {{ $item->bname }}
                                                </td>
                                                {{-- <td>
												{{
												$item->bname
												}}
											</td> --}}
                                                {{-- <td>
												@php
												echo session()->get('start_date');

												@endphp
											</td> --}}
                                                {{-- <td>
												@php
												echo session()->get('end_date');
												@endphp
											</td> --}}
                                                <td>
                                                    {{-- <input type="text" style="border: none" name="mname[]" id="mname[]"
													value="{{ $item->mname }}"> --}}
                                                    {{ $item->mname }}
                                                </td>
                                                {{-- <td>
												<input type="text" style="border: none" name="bname" id="bname"
													value="{{ $item->name }}">
												{{-- {{$item->name}} --}}
                                                </td>
                                                {{-- <td></td> --}}
                                                <td>
                                                    {{-- <input type="text" style="border: none" name="sku_no[]"
													id="sku_no[]" value="{{ $item->sku_no }}"> --}}
                                                    {{ $item->sku_no }}
                                                </td>
                                                <td>
                                                    {{-- <input type="text" style="border: none" name="name[]" id="name[]"
													value="{{ $item->name }}"> --}}
                                                    {{ $item->name }}
                                                </td>
                                                <td>
                                                    {{-- <input type="text" style="border: none" name="barcodes[]"
													id="barcodes[]" value="{{ $item->barcodes }}"> --}}
                                                    {{ $item->old_pin }}
                                                </td>
                                                {{-- <td>{{$item->price}}</td> --}}
                                                {{-- <td></td> --}}
                                                {{-- <td></td> --}}
                                                <td>
                                                    {{-- @php
                                                        dd($item->status);
                                                    @endphp --}}
                                                    {{-- <input type="text" style="border: none" name="status[]"
													id="status[]" value="{{ $item->status }}"> --}}
                                                    {{ $item->status }}

                                                </td>
                                                {{-- <td></td> --}}
                                                {{-- <td></td> --}}
                                                {{-- <td></td> --}}


                                            </tr>
                                        @endforeach


                                    </tbody>
                                </table>
                            </div>
                        </div>
                        {{-- {!! Form::close() !!} --}}

                        {{--
					</form> --}}

                    </div>
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
                    <h4 class="modal-title">Import Stock In Parts</h4>
                </div>
                <div class="modal-body">
                    <form action="{{ url('renew-hub-status-update') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <input type="file" name="file" class="form-control">
                        <button type="submit" class="btn btn-success mr-1">Submit
                            <span class="btn-icon-right">
                                <i class="fa fa-check"></i></span></button>
                    </form>
                    {{-- {!! Form::open(['url' =>'upload-stock-in-report', 'class' => 'form-horizontal', 'id' =>
				'upload-form',
				'role' => 'form', 'onsubmit'=>'return false','files'=>'true']) !!} --}}
                    <div class="form-group">
                        <div class="col-md-12">
                            {{-- {!! Form::label('title', 'Choose File', ['class' => 'control-label']) !!}
						{!! Form::file('file_csv',['class' => 'form-control','required', --}}
                            {{-- 'id'=>'file_csv','placeholder'=>'-Select-']) !!} --}}
                        </div>
                        <div class="col-md-12" style="padding: 17px;">
                            {{-- <button type="button" onclick="saveData(this)" data-id="upload-form"
							class="btn btn-success mr-1">Submit <span class="btn-icon-right"><i
									class="fa fa-check"></i></span></button> --}}
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel <span
                                    class="btn-icon-right"><i class="fa fa-close"></i></span></button>
                            <a href="{{ url('assets\files\renew_import.csv') }}" class="btn btn-info light"
                                download>Download
                                Sample File</span></a>
                        </div>
                    </div>
                </div>
                {{-- {!! Form::close() !!} --}}
            </div>

        </div>
    </div>

    <div id="importPrice" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Import Stock In Parts Price</h4>
                </div>
                <div class="modal-body">
                    <form action="{{ url('renew-hub-status-update-price') }}" method="post"
                        enctype="multipart/form-data">
                        @csrf
                        <input type="file" name="file" class="form-control">
                        <button type="submit" class="btn btn-success mr-1">Submit
                            <span class="btn-icon-right">
                                <i class="fa fa-check"></i></span></button>
                    </form>
                    {{-- {!! Form::open(['url' =>'upload-stock-in-report', 'class' => 'form-horizontal', 'id' =>
				'upload-form',
				'role' => 'form', 'onsubmit'=>'return false','files'=>'true']) !!} --}}
                    <div class="form-group">
                        <div class="col-md-12">
                            {{-- {!! Form::label('title', 'Choose File', ['class' => 'control-label']) !!}
						{!! Form::file('file_csv',['class' => 'form-control','required', --}}
                            {{-- 'id'=>'file_csv','placeholder'=>'-Select-']) !!} --}}
                        </div>
                        <div class="col-md-12" style="padding: 17px;">
                            {{-- <button type="button" onclick="saveData(this)" data-id="upload-form"
							class="btn btn-success mr-1">Submit <span class="btn-icon-right"><i
									class="fa fa-check"></i></span></button> --}}
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel <span
                                    class="btn-icon-right"><i class="fa fa-close"></i></span></button>
                            <a href="{{ url('assets\files\renew_price_import.csv') }}" class="btn btn-info light"
                                download>Download Sample File</span></a>
                        </div>
                    </div>
                </div>
                {{-- {!! Form::close() !!} --}}
            </div>

        </div>
    </div>


    <!-- Remark Data Modal -->
    <div id="remarkModel" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Remark</h4>
                </div>
                <div class="modal-body">
                    {!! Form::open([
                        'url' => 'remark-stock-in-barcode',
                        'class' => 'form-horizontal',
                        'id' => 'remark-form',
                        'role' => 'form',
                        'onsubmit' => 'return false',
                    ]) !!}
                    <div class="form-group">
                        <div class="col-md-12">
                            {!! Form::label('title', 'Remark', ['class' => 'control-label']) !!}
                            {!! Form::text('remark', '', [
                                'class' => 'form-control',
                                'required',
                                'id' => 'remark',
                                'placeholder' => 'Remark',
                            ]) !!}
                            {!! Form::hidden('id', '', ['id' => 'remark_id']) !!}
                        </div>
                        <div class="col-md-12" style="padding: 17px;">
                            <button type="button" onclick="saveData(this)" data-id="remark-form"
                                class="btn btn-success mr-1">Submit <span class="btn-icon-right"><i
                                        class="fa fa-check"></i></span></button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel <span
                                    class="btn-icon-right"><i class="fa fa-close"></i></span></button>
                        </div>
                    </div>
                </div>
                {!! Form::close() !!}
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
                    <h4 class="modal-title">Import Engineer Allocation</h4>
                </div>
                <div class="modal-body">
                    {!! Form::open([
                        'url' => 'upload-allocation-enginner',
                        'class' => 'form-horizontal',
                        'id' => 'save-form',
                        'role' => 'form',
                        'files' => 'true',
                    ]) !!}
                    <div class="form-group">
                        <div class="col-md-12">
                            {!! Form::label('title', 'Choose Devices Price File', ['class' => 'control-label']) !!}
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
                            <a href="{{ url('assets\files\els_product_enginner_allocation.csv') }}"
                                class="btn btn-info light" download>Download Sample File</span></a>
                        </div>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>

        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script type="text/javascript">
        function importData() {
            $('#importModel').modal('show');
        }

        function importPrice() {
            $('#importPrice').modal('show');
        }
        // $(document).ready(function () {
        // 	importData=function(){
        // 		$('#importModel').modal('show');
        // 	}

        // 	var oTable = $('#dataTableID').DataTable({
        // 		'processing': true,
        // 		'serverSide': true,
        // 		"ajax":{
        // 			"url": "{{ url('fetch_stock_in_parts_list_new') }}"
        // 		},
        // 		"columns": [
        // 			{ data: "id" },
        // 			{ data: "po_no" },
        // 			{ data: "bname" },
        // 			{ data: "mname" },
        // 			{ data: "colour_name" },
        // 			{ data: "sku_no" },
        // 			{ data: "part_name" },
        // 			{ data: "barcode" },
        // 			{ data: "price" },
        // 			{ data: "engineer_name" },
        // 			{ data: "iqc_status" },
        // 			{ data: "current_status" },
        // 			{ data: "received_date" },
        // 			{ data: "iqc_status_one" },
        // 			{ data: "remark" },
        // 		],

        // 		"rowCallback": function (nRow, aData, iDisplayIndex) {
        // 			var oSettings = this.fnSettings ();
        // 			$("td:first", nRow).html(oSettings._iDisplayStart+iDisplayIndex +1);
        // 			var actionBtn = '';
        // 				var actionBtn1 = '';
        // 			if(aData.status=='2'){
        // 				if(aData.iqc_status == 1){
        // 					actionBtn1 += 'Pass';
        // 				}else{
        // 					actionBtn1 += 'Failed';
        // 				}
        // 			}

        // 			$("td:eq(10)", nRow).html(actionBtn1);

        // 			if(aData.status=='2'){
        // 				if(aData.iqc_status_one == 1){
        // 					actionBtn += '<label class="switch"><input type="checkbox" 	 <?php  if(!in_array('45',Helper::editPermission())){  ?> disabled  <?php  }   ?>  value="0" onclick="setStatus('+aData.id+',0)" class="statuscheckbox" checked=""><span class="slider round"></span></label>';
        // 				}else{
        // 					actionBtn += '<label class="switch"><input type="checkbox"   <?php  if(!in_array('45',Helper::editPermission())){  ?> disabled  <?php  }   ?>   value="1" onclick="setStatus('+aData.id+',1)" class="statuscheckbox"><span class="slider round"></span></label>';
        // 				}
        // 			}

        // 			$("td:eq(13)", nRow).html(actionBtn);
        // 			return nRow;
        // 		},
        // 		"order": [[1, 'asc']],
        // 		"columnDefs": [{ orderable: false, "targets": 0 }]
        // 	});

        // 	saveData=function(e){
        // 	    $("#overlay").fadeIn(800);
        // 		var form = $('#'+$(e).attr('data-id'));
        // 		var url = form.attr('action');
        // 		var formData = new FormData(form[0]);
        // 		$.ajax({
        // 			type: "POST",
        // 			url: url,
        // 			contentType: false,
        // 			processData: false,
        // 			data: formData,
        // 			dataType:'JSON',
        // 			success: function(d)
        // 			{
        // 				if(d.status){
        // 					alert(d.message);
        // 					 $("#overlay").fadeOut(800);
        // 					$('.modal').modal('hide');
        // 					oTable.ajax.reload();
        // 				}else{
        // 					alert(d.message);
        // 				}
        // 			}
        // 		});
        // 	}

        // 	setStatus=function(id,status){
        // 		if(id){
        // 			$.ajax({
        // 				type: "POST",
        // 				url: "{{ url(route('set-iqc-status')) }}",
        // 				data:{id:id,status:status},
        // 				dataType:'JSON',
        // 				success: function(d){
        // 					if(d.status){
        // 						if(!status){
        // 							$('#remark_id').val(id);
        // 							$('#remarkModel').modal('show');
        // 						}
        // 						oTable.ajax.reload();
        // 					}else{
        // 						alert(d.message)
        // 					}
        // 				}
        // 			});
        // 		}
        // 	}

        // 	addBarcode=function(){
        // 		var barcode = $('#barcode').val();
        // 		if(barcode){
        // 			$.ajax({
        // 				type: "POST",
        // 				url: "{{ url(route('add-barcode-in-stock')) }}",
        // 				data:{barcode:barcode},
        // 				dataType:'JSON',
        // 				success: function(d){
        // 					if(d.status){
        // 						$('#barcode').val('');
        // 						oTable.ajax.reload();
        // 					}else{
        // 						alert(d.message)
        // 					}
        // 				}
        // 			});
        // 		}
        // 	}

        // 	enterKeyPress=function(e){
        // 		if(e.keyCode === 13){
        // 			addBarcode();
        // 		}
        // 	}
        // });
    </script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <script type="text/javascript">
        var start = moment().subtract(29, 'days');
        var end = moment();
        @if (session()->get('start_date') && session()->get('end_date'))
            end = moment("{{ session()->get('end_date') }}");
            start = moment("{{ session()->get('start_date') }}");
        @endif
        function cb(start, end) {
            var csrfToken = "{{ csrf_token() }}";
            $.ajax({
                type: "POST",
                url: "{{ url('set_date_range_filterr') }}",
                headers: {
                    "X-CSRF-Token": csrfToken,
                },
                data: {
                    start_date: start.format('YYYY-MM-DD'),
                    end_date: end.format('YYYY-MM-DD')
                },
                dataType: 'JSON',
                success: function(d) {}
            });
            $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
        }

        function cba(start, end) {
            var csrfToken = "{{ csrf_token() }}";
            $.ajax({
                type: "POST",
                url: "{{ url('set_date_range_filterr') }}",
                headers: {
                    "X-CSRF-Token": csrfToken,
                },
                data: {
                    start_date: start.format('YYYY-MM-DD'),
                    end_date: end.format('YYYY-MM-DD')
                },
                dataType: 'JSON',
                success: function(d) {
                    location.reload();
                }
            });
            $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
        }

        $('#reportrange').daterangepicker({
            startDate: start,
            endDate: end,
            ranges: {
                'Today': [moment(), moment().add(1, 'days')],
                'Yesterday': [moment().subtract(1, 'days'), moment()],
                'Last 7 Days': [moment().subtract(6, 'days'), moment().add(1, 'days')],
                'Last 30 Days': [moment().subtract(29, 'days'), moment().add(1, 'days')],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf(
                    'month')]
            }
        }, cba);

        cb(start, end);

        $(document).ready(function() {
            $('#dataTableID').DataTable();



            // var oTable = $('#dataTableID').DataTable({
            // "bDestroy": true,
            // 'processing': true,
            // 'serverSide': true,
            // "ajax":{
            // "url": "{{ url('get-enginner-work-report-list') }}"
            // },
            // "columns": [
            // { data: "id" },
            // { data: "name" },
            // { data: "repair" },
            // { data: "l3" },
            // { data: "l4" },
            // { data: "fqc" },
            // { data: "fqc_fails" },
            // { data: "shrink_pack" },
            // { data: "total_system" },
            // ],
            // "rowCallback": function (nRow, aData, iDisplayIndex) {
            // var oSettings = this.fnSettings ();
            // $("td:first", nRow).html(oSettings._iDisplayStart+iDisplayIndex +1);
            // return nRow;
            // },
            // "order": [[1, 'asc']],
            // "columnDefs": [{ orderable: false, "targets": 0 }]
            // });
        });
    </script>
@endsection
