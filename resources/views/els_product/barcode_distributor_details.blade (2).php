<table class="table table-responsive table-bordered">
	<thead>
		 <tr>
			<td colspan="2" style="text-align: center;"><b>Details</b></td>
		 </tr>
	</thead>
	<tbody>
		 <tr>
			<td><b>Brand</b></td>
			<td id="brand">{{$data->bname}}</td>
		 </tr>
		 <tr>
			<td><b>Model</b></td>
			<td id="model">{{$data->mname}}</td>
		 </tr>
		 <tr>
			<td><b>Colour</b></td>
			<td id="color">{{$data->colour_name}}</td>
		 </tr>
		 <tr>
			<td><b>IMEI 1</b></td>
			<td id="imei_1">{{$data->imei_1}}</td>
		 </tr>
		 <tr>
			<td><b>IMEI 2</b></td>
			<td id="imei_2">{{$data->imei_2}}</td>
		</tr>
	</tbody>
</table>
{!!Form::hidden('els_system_id',$data->id)!!}