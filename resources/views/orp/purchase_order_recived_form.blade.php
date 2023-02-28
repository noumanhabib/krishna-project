@extends('layouts.layout')
@section('title', $title)
@section('content')
    @php $tabindex = 1; @endphp
    <div id="page-wrapper">
        <div class="header">
            <h1 class="page-header">
                <small>Add Purchase Product</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="{{ url('/home') }}">Home</a></li>
                <li><a href="{{ url('received-purchase-order') }}">Purchase Order Recived</a></li>
                <li class="active">Add Purchase Order Recived</li>
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
                                                <div class="title">Add Purchase Order</div>
                                            </div>
                                        </div>
                                        <div class="panel-body">
                                            {!! Form::open([
                                                'url' => route('save-recived-purchase-order'),
                                                'class' => 'form-horizontal',
                                                'role' => 'form',
                                                'id' => 'save-form',
                                                'onsubmit' => 'return false',
                                            ]) !!}

                                            <div class="form-group">
                                                <div class="col-sm-2">
                                                    {!! Form::label('title', 'Purchase Order No', ['class' => 'control-label']) !!}
                                                </div>
                                                <div class="col-sm-3">
                                                    {!! Form::text('purchase_order_id', '', [
                                                        'class' => 'form-control',
                                                        'id' => 'purchase_order_id',
                                                        'placeholder' => 'Purchase Order No',
                                                        'tabindex' => $tabindex++,
                                                        'onkeypress' => 'enterKeyPress(event)',
                                                        'required',
                                                    ]) !!}
                                                    <span class="help-block text-danger error"></span>
                                                </div>
                                                <div class="col-sm-2" style="display:none;">
                                                    {!! Form::label('title', 'Model', ['class' => 'control-label']) !!}
                                                </div>
                                                <div class="col-sm-3" style="display:none;">
                                                    {!! Form::select('model_id', $model, '', [
                                                        'class' => 'form-control',
                                                        'id' => 'model_id',
                                                        'placeholder' => '-Select-',
                                                        'tabindex' => $tabindex++,
                                                    ]) !!}
                                                    <span class="help-block text-danger error"></span>
                                                </div>
                                                <div class="col-sm-2">
                                                    {{ Form::submit('Submit', ['onclick' => 'getRequestList()', 'class' => 'btn btn-info']) }}
                                                </div>
                                            </div>

                                            <div id="parts-list" style="overflow-x:auto;"></div>

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
            getRequestList = function() {
                var purchase_order_id = $('#purchase_order_id').val();
                var model_id = $('#model_id').val();
                if (purchase_order_id) {
                    $.ajax({
                        type: "POST",
                        url: "{{ url(route('purchase-order-parts-list-form')) }}",
                        data: {
                            purchase_order_id: purchase_order_id,
                            model_id: model_id
                        },
                        dataType: 'JSON',
                        success: function(d) {
                            if (d.status) {
                                $('#parts-list').html(d.html);
                            } else {
                                alert(d.message)
                            }
                        }
                    });
                }
            }

            enterKeyPress = function(e) {
                if (e.keyCode === 13) {
                    getRequestList();
                }
            }

            getColour = function(e) {
                var series_id = $(e).val();
                var colourID = $(e).attr('next-id');
                if (series_id && colourID) {
                    $.ajax({
                        type: "POST",
                        url: "{{ url(route('get-series-colour')) }}",
                        data: {
                            series_id: series_id
                        },
                        dataType: 'JSON',
                        success: function(d) {
                            if (d.status) {
                                var option = '<option value="">-Select-</option>';
                                $.each(d.data, function(i, v) {
                                    option += '<option value="' + v.id + '">' + v.name + '</option>';
                                });
                                $('#' + colourID).html(option);
                            }
                        }
                    });
                }
            }
            getPrice = function(e) {
                var colour_id = $(e).val();
                var backID = $(e).attr('back-id');
                var priceID = $(e).attr('next-id');
                var series_id = $('#' + backID).val();
                if (colour_id && priceID && series_id) {
                    $.ajax({
                        type: "POST",
                        url: "{{ url(route('get-series-unit-price')) }}",
                        data: {
                            colour_id: colour_id,
                            series_id: series_id
                        },
                        dataType: 'JSON',
                        success: function(d) {
                            if (d.status) {
                                $('#' + priceID).val(d.data.price);
                                $("#" + priceID).trigger('change');
                            }
                        }
                    });
                }
            }

            getAmountDetails = function(key) {
                var unit_price = $('#price_' + key).val();
                var gst = $('#gst_' + key).val();
                var quantity = $('#quantity_' + key).val();
                if (unit_price && gst && quantity) {
                    var amount = unit_price * quantity;
                    var gst_amount = amount * (gst / 100);
                    var total_amount = amount + gst_amount;
                    $('#amount_' + key).val(amount);
                    $('#gst_amount_' + key).val(gst_amount);
                    $('#total_amount_' + key).val(total_amount);
                }
            }

            deleteRow = function(e) {
                $(e).parent().parent().remove()
            }

            saveForm = function() {
                var form = $('#save-form');
                var url = form.attr('action');
                var formData = new FormData(form[0]);
                $.ajax({
                    type: "POST",
                    url: url,
                    contentType: false,
                    processData: false,
                    data: formData,
                    dataType: 'JSON',
                    success: function(d) {
                        if (d.status) {
                            location.href = '{{ url('received-purchase-order') }}';
                        } else {}
                    }
                });
            }
        </script>
    @endsection
