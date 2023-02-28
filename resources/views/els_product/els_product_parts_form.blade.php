@php $tabindex = 10; @endphp

<table id="childTable" class="table-responsive table-bordered table">
    <thead>

        <tr>
            <td>Part Name</td>
            <td>Part Color</td>
            <td>PIN</td>
            <td><a href="javascript:;" id="add_more_parts" onclick="addMore({{ count($parts) }})"><span
                        class="glyphicon glyphicon-plus"></span></a></td>
            <td>Old PINs</td>
        </tr>
    </thead>
    <tbody>
        {{-- @php
            dd($parts);
        @endphp --}}

        @if (!$parts->isEmpty())
            @foreach ($parts as $key => $d)
                {{-- @if ($d->new_pin != 1) --}}

                @php
                    // dd($d);
                    $status = 0;
                    $barcode = null;
                    $colour = Helper::getPartColourListt($d->model_id, $d->part_type_id, $d->part_id);
                    $barcode = Helper::getSystemAllocatedBarcode($d->els_system_id, $d->model_id, $d->part_type_id, $d->part_id, $d->id);
                    // $code[] = $barcode;
                @endphp
                <tr id="row_{{ $key }}" <?php if($barcode!='' ){ ?> <?php } ?>>
                    <td>{!! Form::select('part_id[]', $part_list, $d->part_id, [
                        'class' => 'form-control
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                				pointer-events-none',
                        'required',
                        'id' => 'part_id_' . $key,
                        'tabindex' => $tabindex++,
                        'placeholder' => '-Select-',
                        'readonly' => 'readonly',
                    ]) !!}
                    </td>
                    <td> {!! Form::select('part_color[]', $colour, $d->colour_id, [
                        'class' => 'form-control
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                				pointer-events-none',
                        'required',
                        'id' => 'part_color_id_' . $key,
                        'tabindex' => $tabindex++,
                        'placeholder' => '-select-',
                        'readonly' => 'readonly',
                    ]) !!}</td>

                    <?php  if(in_array('23',Helper::editPermission())){  ?>
                    <td style="display: flex;align-items: center;gap: 1rem;">

                        <?php  if($d->id<0){  ?>
                        {!! Form::text('part_barcode[]', $barcode, [
                            'class' => 'form-control barcode',
                            'id' => 'part_barcode_' . $key,
                            'tabindex' => $tabindex++,
                            'autocomplete' => 'off',
                            'onPaste' => 'buttonDisabled()',
                            'onKeypress' => 'buttonDisabled()',
                        ]) !!}
                        @if ($barcode)
                            <a href="{{ url('/generate_bar_code?pin=' . $barcode . '&imei=' . $d->sku_no . '') }}"
                                target="_blank" rel="noopener noreferrer"><span
                                    class="glyphicon glyphicon-print"></span></a>
                        @endif
                    </td>
                    <?php  }  else {   ?>
                    {!! Form::text('part_barcode[]', $d->barcodes, [
                        'class' => 'form-control barcode',
                        'id' => 'part_barcode_' . $key,
                        'tabindex' => $tabindex++,
                        'autocomplete' => 'off',
                        'onPaste' => 'buttonDisabled()',
                        'onKeypress' => 'buttonDisabled()',
                        'onchange' => 'messages(this.value)',
                    ]) !!}
                    @if ($d->barcodes)
                        <a href="{{ url('/generate_bar_code?pin=' . $d->barcodes . '&imei=' . $d->sku_no . '') }}"
                            target="_blank" rel="noopener noreferrer"></a>
                    @endif
                    </td>

                    <?php  }   ?>

                    <?php  }  else {  ?>

                    <td style="display: flex;align-items: center;gap: 1rem;">{!! Form::text('part_barcode[]', $barcode, [
                        'class' => 'form-control barcode',
                        'id' => 'part_barcode_' . $key,
                        'tabindex' => $tabindex++,
                        'autocomplete' => 'off',
                        'readonly' => 'readonly',
                        'onPaste' => 'buttonDisabled()',
                        'onKeypress' => 'buttonDisabled()',
                        // 'onchange' => 'errors()',
                    ]) !!}
                        @if ($barcode)
                            <a href="{{ url('/generate_bar_code?pin=' . $barcode . '&imei=' . $d->sku_no . '') }}"
                                target="_blank" rel="noopener noreferrer"><span
                                    class="glyphicon glyphicon-print"></span></a>
                        @endif
                    </td>

                    <?php  }   ?>
                    <td class="action">
                        <?php  if(in_array('23',Helper::deletedPermission())){  ?>
                        <a href="javascript:;" onclick="deleteRemove(this)"><span
                                class="glyphicon glyphicon-trash"></span></a>
                        &nbsp;&nbsp;
                        {{-- <br> --}}
                        {{-- <form action="{{ url('update-pin') }}/'+id+'"><button class="btn btn-primary"
						value="Renew">Collect</button></form>
				<a onclick="cloneRow(event)" id="clone" class="btn btn-primary">Collect</a> --}}
                        <a onclick="cloneRowNew({{ $key }})" class="btn btn-primary">Collect</a>
                        <?php  }  ?>

                    </td>
                    <td>

                        @if ($d->old_pin)
                            <a href="{{ url('/generate_bar_code?pin=' . $d->old_pin . '&imei=' . $d->sku_no . '') }}"
                                target="_blank" rel="noopener noreferrer"><span
                                    class="glyphicon glyphicon-print"></span></a>
                            {{ $d->old_pin }}
                        @endif

                    </td>

                    <input type="hidden" name="extra[]" id="extra">
                    {!! Form::hidden('model_id[]', $d->model_id, ['id' => 'model_id_' . $key]) !!}
                    {!! Form::hidden('id[]', $d->id, ['id' => 'order_request_part_id_' . $key, 'class' => 'order_request_part_id']) !!}
                    {!! Form::hidden('order_request_part_imei[]', $d->sku_no, [
                        'id' => 'order_request_part_imei_' . $key,
                        'class' => 'order_request_part_imei',
                    ]) !!}

                </tr>
                {{-- @endif --}}
            @endforeach
        @endif
        <tr id="add-more"></tr>
    </tbody>
</table>

<div class="form-group" style="display:none;">
    <div class="col-sm-6">
        {!! Form::label('title', 'Assign Engineer', ['class' => 'control-label']) !!}
        {!! Form::select('engineer_id', $engineer, $data->engineer_id, [
            'class' => 'form-control',
            'required',
            'id' => 'engineer_id',
            'tabindex' => $tabindex++,
            'placeholder' => '-select-',
        ]) !!}
        <span class="help-block text-danger error"></span>
    </div>
</div>
<div class="form-group" id="button-html">
    <div class="col-sm-12">
        <button type="submit" class="btn btn-default save_btn" id="save_btn">Submit</button>
    </div>
</div>
