<div class="container">
<table class="table table-bordered">
    <tbody>
	@foreach($data as $d)
      <tr>
        <td>
			<div>{!!DNS1D::getBarcodeHTML($d->barcode, 'S25')!!}</div>
				{{$d->barcode}}<hr>
		</td>
      </tr>
	@endforeach
    </tbody>
</table>
</div>