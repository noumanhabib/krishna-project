<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

<html>
<head>
	
	<meta http-equiv="content-type" content="text/html; charset=iso-8859-1"/>
	<title></title>	
	<style type="text/css">
		body,div,table,thead,tbody,tfoot,tr,th,td,p { font-family:"Calibri"; font-size:x-small }
		a.comment-indicator:hover + comment { background:#ffd; position:absolute; display:block; border:1px solid black; padding:0.5em;  } 
		a.comment-indicator { background:red; display:inline-block; border:1px solid black; width:0.5em; height:0.5em;  } 
		comment { display:none;  } 
	</style>
	
</head>

<body>
<table cellspacing="0" border="0">
	<colgroup width="44"></colgroup>
	<colgroup width="279"></colgroup>
	<colgroup width="72"></colgroup>
	<colgroup width="61"></colgroup>
	<colgroup width="32"></colgroup>
	<colgroup width="146"></colgroup>
	<colgroup width="246"></colgroup>
	<colgroup width="258"></colgroup>
	<colgroup width="65"></colgroup>
	<tr>
		<td style="border-top: 2px solid #000000; border-bottom: 1px solid #000000; border-left: 2px solid #000000; border-right: 2px solid #000000" colspan=8 height="28" align="center" valign=top bgcolor="#FFFF00"><b><font size=4 color="#FF0000">CHALLAN</font></b></td>
		<td align="left" valign=bottom><font color="#000000"><br></font></td>
	</tr>
	<tr>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 2px solid #000000; border-right: 2px solid #000000" colspan=8 height="25" align="center" valign=middle bgcolor="#FFFF00"><b><font size=4 color="#FF0000">S.G CORPORATE MOBILITY PVT LTD</font></b></td>
		<td align="left" valign=bottom><font color="#000000"><br></font></td>
	</tr>
	<tr>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 2px solid #000000; border-right: 1px solid #000000" colspan=2 height="20" align="left" valign=middle><b><font color="#333333">Challan No :</font></b></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" colspan=3 align="center" valign=middle><b><font color="#333333">{{$data->els_system_id}}<br></font></b></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-right: 1px solid #000000" align="left" valign=middle><b><font color="#333333">PAYMENT TERMS</font></b></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-right: 1px solid #000000" align="left" valign=middle><b><font color="#333333">{{$data->payment_terms}}</font></b></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 2px solid #000000" rowspan=3 align="left" valign=top sdnum="1033;0;MM/DD/YYYY;@"><b><font color="#333333">Total Amount</font></b></td>
		<td align="left" valign=bottom><font color="#000000"><br></font></td>
	</tr>
	<tr>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 2px solid #000000; border-right: 1px solid #000000" colspan=2 height="20" align="left" valign=middle><b><font color="#333333">GST NO: </font></b></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" colspan=3 align="center" valign=middle><b><font color="#333333"></font></b>{{$data->gst_no}}</td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-right: 1px solid #000000" align="left" valign=middle><b><font color="#333333"><br></font></b></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-right: 1px solid #000000" align="left" valign=middle><b><font color="#333333"><br></font></b></td>
		<td align="left" valign=bottom><font color="#000000"><br></font></td>
	</tr>
	<tr>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 2px solid #000000" colspan=2 height="20" align="left" valign=middle><b><font color="#333333">Order Date:                     </font></b></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" colspan=3 align="center" valign=middle sdnum="1033;1033;M/D/YYYY"><b><font color="#333333"></font></b>{{date('d-m-Y',strtotime($data->created_at))}}</td>
		<td align="left" valign=bottom><font color="#000000"><br></font></td>
		<td align="left" valign=bottom><font color="#000000"><br></font></td>
		<td align="left" valign=bottom><font color="#000000"><br></font></td>
	</tr>
	<tr>
		<td style="border-top: 1px solid #000000; border-bottom: 2px solid #000000; border-left: 2px solid #000000; border-right: 1px solid #000000" colspan=2 height="21" align="left" valign=middle><b><font color="#333333">PAN NO : </font></b></td>
		<td style="border-top: 1px solid #000000; border-bottom: 2px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" colspan=3 align="center" valign=middle><b><font color="#333333"> {{$data->pan_no}}</font></b></td>
		<td style="border-top: 1px solid #000000; border-bottom: 2px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="left" valign=middle><b><font color="#333333">ORDER VALUE</font></b></td>
		<td style="border-top: 1px solid #000000; border-bottom: 2px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="left" valign=middle><b><font color="#333333"></font></b></td>
		<td style="border-top: 1px solid #000000; border-bottom: 2px solid #000000; border-left: 1px solid #000000; border-right: 2px solid #000000" align="left" valign=middle><b><font color="#333333"></font></b></td>
		<td align="left" valign=bottom><font color="#000000"><br></font></td>
	</tr>
	<tr>
		<td style="border-bottom: 1px solid #000000; border-left: 2px solid #000000; border-right: 1px solid #000000" colspan=5 height="20" align="center" valign=top bgcolor="#FFFF00"><b><font color="#FF0000">BILL FROM ADDRESS </font></b></td>
		<td style="border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 2px solid #000000" colspan=3 align="center" valign=top bgcolor="#FFFF00"><b><font color="#FF0000">PAYMENT data </font></b></td>
		<td align="left" valign=bottom><font color="#000000"><br></font></td>
	</tr>
	<tr>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 2px solid #000000; border-right: 1px solid #000000" colspan=5 height="21" align="center" valign=top><b><font size=3 color="#000000">{{$data->vname}}</font></b></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="left" valign=top><b><font size=3 color="#000000">A/C NO </font></b></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" colspan=2 align="center" valign=top><b><font size=3 color="#000000">{{$data->account_number}}</font></b></td>
		<td align="left" valign=bottom><font color="#000000"><br></font></td>
	</tr>
	<tr>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 2px solid #000000; border-right: 1px solid #000000" colspan=5 height="21" align="center" valign=top><b><font size=3 color="#000000">{{$data->address}}</font></b></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="left" valign=top><b><font size=3 color="#000000">IFS CODE</font></b></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" colspan=2 align="center" valign=top><b><font size=3 color="#000000">{{$data->ifs_code}}</font></b></td>
		<td align="left" valign=bottom><font color="#000000"><br></font></td>
	</tr>
	<tr>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 2px solid #000000; border-right: 1px solid #000000" colspan=5 height="21" align="center" valign=top><b><font size=3 color="#000000">{{$data->city}} , {{$data->state}} , {{$data->pincode}}</font></b></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="left" valign=top><b><font size=3 color="#000000">BANK NAME</font></b></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" colspan=2 align="center" valign=top><b><font size=3 color="#000000">State Bank of India</font></b></td>
		<td align="left" valign=bottom><font color="#000000"><br></font></td>
	</tr>
	<tr>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 2px solid #000000; border-right: 1px solid #000000" colspan=5 height="21" align="center" valign=top><b><font size=3 color="#000000">{{$data->country}} </font></b></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="left" valign=top><b><font size=3 color="#000000">MODE OF PAYMENT </font></b></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" colspan=2 align="center" valign=top><b><font size=3 color="#000000">{{$data->payment_mode}}</font></b></td>
		<td align="left" valign=bottom><font color="#000000"><br></font></td>
	</tr>
	
	<tr>
		<td style="border-top: 1px solid #000000; border-left: 2px solid #000000; border-right: 1px solid #000000" height="21" align="center" valign=middle bgcolor="#FFFF00"><b><font size=3 color="#FF0000">UIN No.</font></b></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=bottom bgcolor="#FFFF00"><b><font size=3 color="#FF0000">Brand</font></b></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=bottom bgcolor="#FFFF00"><b><font size=3 color="#FF0000">Model</font></b></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle bgcolor="#FFFF00"><b><font size=3 color="#FF0000" colspan="2">IMEI 1</font></b></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle bgcolor="#FFFF00"><b><font size=3 color="#FF0000">IMEI 2</font></b></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle bgcolor="#FFFF00"><b><font size=3 color="#FF0000">RAM</font></b></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=bottom bgcolor="#FFFF00"><b><font size=3 color="#FF0000">ROM</font></b></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=bottom bgcolor="#FFFF00"><b><font size=3 color="#FF0000">Status</font></b></td>
		
	</tr>
	<tr>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 2px solid #000000; border-right: 1px solid #000000"  height="21" align="center" valign=top><b><font size=3 color="#000000">{{$data->barcode}}</font></b></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 2px solid #000000; border-right: 1px solid #000000"  height="21" align="center" valign=top><b><font size=3 color="#000000">{{$data->bname}}</font></b></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 2px solid #000000; border-right: 1px solid #000000"  height="21" align="center" valign=top><b><font size=3 color="#000000">{{$data->mname}}</font></b></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 2px solid #000000; border-right: 1px solid #000000"  height="21" align="center" valign=top><b><font size=3 color="#000000"><b><font size=3 color="#000000">{{$data->imei_1?$data->imei_1:'NA'}}</font></b></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 2px solid #000000; border-right: 1px solid #000000"  height="21" align="center" valign=top><b><font size=3 color="#000000">{{$data->imei_2?$data->imei_2:'NA'}}</font></b></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 2px solid #000000; border-right: 1px solid #000000"  height="21" align="center" valign=top><b><font size=3 color="#000000">{{$data->ram?$data->ram:'NA'}}</font></b></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 2px solid #000000; border-right: 1px solid #000000"  height="21" align="center" valign=top><b><font size=3 color="#000000">{{$data->rom?$data->rom:'NA'}}</font></b></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 2px solid #000000; border-right: 1px solid #000000"  height="21" align="center" valign=top><b><font size=3 color="#000000">{{$data->status}}</font></b></td>
	</tr>
	<tr>
		<td style="border-top: 1px solid #000000; border-left: 2px solid #000000; border-right: 1px solid #000000" height="21" align="center" valign=middle bgcolor="#FFFF00"><b><font size=3 color="#FF0000">S. No.</font></b></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle bgcolor="#FFFF00"><b><font size=3 color="#FF0000" colspan="2">Product Discription</font></b></td>
	<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle bgcolor="#FFFF00"><b><font size=3 color="#FF0000">HSN Code</font></b></td>
	<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle bgcolor="#FFFF00"><b><font size=3 color="#FF0000">Tax rate</font></b></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=bottom bgcolor="#FFFF00"><b><font size=3 color="#FF0000">Qty</font></b></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle bgcolor="#FFFF00"><b><font size=3 color="#FF0000">Price</font></b></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle bgcolor="#FFFF00"><b><font size=3 color="#FF0000">Total Amount</font></b></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="left" valign=bottom bgcolor="#FFFF00"><b><font color="#FF0000">Remarks</font></b></td>
		<td align="left" valign=bottom><font color="#000000"><br></font></td>
	</tr>
	@foreach($parts as $key=>$part_data)
	@php $i=1;@endphp
	<tr>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 2px solid #000000; border-right: 1px solid #000000">{{$key+1}}</td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 2px solid #000000; border-right: 1px solid #000000"  height="21" align="center" valign=top><b><font size=3 color="#000000" colspan="2">{{$part_data['bname']}} , {{$part_data['mname']}} , {{$part_data['name']}}</font></b></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 2px solid #000000; border-right: 1px solid #000000"  height="21" align="center" valign=top><b><font size=3 color="#000000">--</td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 2px solid #000000; border-right: 1px solid #000000"  height="21" align="center" valign=top><b><font size=3 color="#000000">--</td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 2px solid #000000; border-right: 1px solid #000000"  height="21" align="center" valign=top><b><font size=3 color="#000000">{{$part_data['quantity']}}</font></b></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 2px solid #000000; border-right: 1px solid #000000"  height="21" align="center" valign=top><b><font size=3 color="#000000">{{$part_data['price']}}</font></b></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 2px solid #000000; border-right: 1px solid #000000"  height="21" align="center" valign=top><b><font size=3 color="#000000">--</td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 2px solid #000000; border-right: 1px solid #000000"  height="21" align="center" valign=top><b><font size=3 color="#000000">{{$part_data['remark']?$part_data['remark']:'-NA-'}}</font></b></td>
	</tr>
	@endforeach
	
	
</table>
</body>

</html>
