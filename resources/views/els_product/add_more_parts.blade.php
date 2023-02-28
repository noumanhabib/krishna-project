<tr>
    <td>{!! Form::select('part_id[]', $part_list, '', [
        'class' => 'form-control',
        'required',
        'id' => 'part_id_' . $key,
        'placeholder' => '-Select-',
        'onChange' => 'getPartColour(' . $key . ')',
    ]) !!} </td>
    <td> {!! Form::select('part_color[]', [], '', [
        'class' => 'form-control',
        'required',
        'id' => 'part_color_id_' . $key,
        'placeholder' => '-select-',
    ]) !!}</td>
    <td>{!! Form::text('part_barcode[]', '', [
        'class' => 'form-control barcode',
        'id' => 'part_barcode_' . $key,
        'autocomplete' => 'off',
        'onPaste' => 'buttonDisabled()',
        'onKeypress' => 'buttonDisabled()',
        'onBlur' => 'checkBarcode("' . $key . '")',
    ]) !!} </td>
    <input type="hidden" name="extra[]" id="extra" value="1">
    <td class="action">
        <a href="javascript:;" onclick="deleteRemove(this)"><span class="glyphicon glyphicon-trash"></span></a>

    </td>
    {!! Form::hidden('model_id[]', $data->model_id, ['id' => 'model_id_' . $key]) !!}
    {!! Form::hidden('id[]', '', ['id' => 'order_request_part_id']) !!}

</tr>
