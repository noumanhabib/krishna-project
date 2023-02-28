@php $tabindex = 1; @endphp
@if (!$data->isEmpty())
    <table class="table-bordered table">
        <thead>
            <tr>
                <th scope="col">Brand</th>
                <th scope="col">Model</th>
                <th scope="col">Product Type</th>
                <th scope="col">Part Name</th>
                <th scope="col">Part Color</th>
                <th scope="col" style="display:none">Price</th>
                <th scope="col" style="display:none">%GST</th>
                <th scope="col">Order Quantity</th>
                <th scope="col">Remaining Quantity</th>
                <th scope="col">Received Quantity</th>
                <th scope="col" style="display:none">Amount</th>
                <th scope="col" style="display:none">GST Amount</th>
                <th scope="col"style="display:none">Total Amount</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $key => $d)
                <tr>
                    <td scope="row">
                        {!! Form::hidden('purchase_order_parts_id[]', $d->id) !!}
                        {!! Form::hidden('purchase_order_id[]', $d->purchase_order_id) !!}
                        {!! Form::select('brand_id[]', $brand, $d->brand_id, [
                            'class' => 'form-control required pointer-events-none',
                            'id' => 'brand_id',
                            'tabindex' => $tabindex++,
                            'placeholder' => '-Select-',
                            'readonly' => 'readonly',
                        ]) !!}
                        <span class="help-block text-danger error"></span>
                    </td>
                    <td>
                        {!! Form::select('model_id[]', $model, $d->model_id, [
                            'class' => 'form-control required pointer-events-none',
                            'id' => 'model_id',
                            'tabindex' => $tabindex++,
                            'placeholder' => '-Select-',
                            'readonly' => 'readonly',
                        ]) !!}
                        <span class="help-block text-danger error"></span>
                    </td>
                    <td>
                        {!! Form::select('product_type_id[]', $product_type, $d->product_type_id, [
                            'class' => 'form-control required pointer-events-none',
                            'id' => 'product_type_id',
                            'tabindex' => $tabindex++,
                            'placeholder' => '-Select-',
                            'readonly' => 'readonly',
                        ]) !!}
                        <span class="help-block text-danger error"></span>
                    </td>
                    <td>
                        {!! Form::select('part_id[]', $parts, $d->part_id, [
                            'class' => 'form-control required pointer-events-none',
                            'id' => 'part_id',
                            'tabindex' => $tabindex++,
                            'placeholder' => '-Select-',
                            'readonly' => 'readonly',
                        ]) !!}
                        <span class="help-block text-danger error"></span>
                    </td>
                    @php
                        $colour = Helper::getPartColourList($d->model_id, $d->product_type_id, $d->part_id);
                        $gst = 18;
                        $amount = $d->price * $d->quantity;
                        $gst_amount = $amount * ($gst / 100);
                        $total_amount = $amount + $gst_amount;
                    @endphp
                    <td>
                        {!! Form::select('colour_id[]', $colour, $d->colour_id, [
                            'class' => 'form-control required',
                            'id' => 'colour_id_' . $key,
                            'tabindex' => $tabindex++,
                            'placeholder' => '-Select-',
                            'onchange' => 'getPrice(this)',
                            'back-id' => 'series_id_' . $key,
                            'next-id' => 'price_' . $key,
                        ]) !!}
                        <span class="help-block text-danger error"></span>
                    </td>
                    <td style="display:none">
                        {!! Form::text('price[]', $d->price, [
                            'class' => 'form-control required',
                            'id' => 'price_' . $key,
                            'tabindex' => $tabindex++,
                            'placeholder' => 'Price',
                            'onchange' => 'getAmountDetails(' . $key . ')',
                        ]) !!}
                        <span class="help-block text-danger error"></span>
                    </td>
                    <td style="display:none">
                        {!! Form::select('gst[]', ['18' => '18% GST', '12' => '12% GST', '5' => '5% GST', '0' => '0% GST'], $d->gst, [
                            'class' => 'form-control required',
                            'id' => 'gst_' . $key,
                            'tabindex' => $tabindex++,
                        ]) !!}
                        <span class="help-block text-danger error"></span>
                    </td>
                    <td>
                        {!! Form::text('total_request_quantity[]', $d->quantity, [
                            'class' => 'form-control required pointer-events-none',
                            'id' => 'request_quantity',
                            'tabindex' => $tabindex++,
                            'placeholder' => 'Request Quantity',
                            'readonly' => 'readonly',
                        ]) !!}
                        <span class="help-block text-danger error"></span>
                    </td>
                    <td>
                        {!! Form::text('request_quantity[]', $d->remaining_quantity, [
                            'class' => 'form-control required pointer-events-none',
                            'id' => 'request_quantity_remaining',
                            'tabindex' => $tabindex++,
                            'placeholder' => 'Request Remaining Quantity',
                            'readonly' => 'readonly',
                        ]) !!}
                        <span class="help-block text-danger error"></span>
                    </td>
                    <td>
                        {!! Form::text('quantity[]', $d->display_quantity, [
                            'class' => 'form-control required',
                            'id' => 'quantity_' . $key,
                            'tabindex' => $tabindex++,
                            'placeholder' => 'Received Quantity',
                            'onchange' => 'getAmountDetails(' . $key . ')',
                        ]) !!}
                        <span class="help-block text-danger error"></span>
                    </td>
                    <td style="display:none">{!! Form::text('amount[]', $amount, [
                        'class' => 'form-control required pointer-events-none',
                        'id' => 'amount_' . $key,
                        'tabindex' => $tabindex++,
                        'placeholder' => 'Amount',
                        'readonly' => 'readonly',
                    ]) !!} </td>
                    <td style="display:none">{!! Form::text('gst_amount[]', $gst_amount, [
                        'class' => 'form-control required pointer-events-none',
                        'id' => 'gst_amount_' . $key,
                        'tabindex' => $tabindex++,
                        'placeholder' => 'GST Amount',
                        'readonly' => 'readonly',
                    ]) !!} </td>
                    <td style="display:none">{!! Form::text('total_amount[]', $total_amount, [
                        'class' => 'form-control required pointer-events-none',
                        'id' => 'total_amount_' . $key,
                        'tabindex' => $tabindex++,
                        'placeholder' => 'Total Amount',
                        'readonly' => 'readonly',
                    ]) !!} </td>
                    <td><i class="fa fa-trash-o fa-3x" aria-hidden="true" onclick="deleteRow(this)"></i></td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endif
<div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
        <button type="button" class="btn btn-default save_btn" onclick="saveForm()">Submit</button>
    </div>
</div>
