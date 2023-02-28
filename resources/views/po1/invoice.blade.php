@php $grand_amount = 0;
$date = $order_no = '';
if(!$data->isEmpty())
{
	foreach($data as $key => $val){
		$date = date('d-m-Y',strtotime($val->created_at));
		$gst = $val->gst;
		$order_no = $val->purchase_order_id;
		$amount = $val->price * $val->quantity;
		$gst_amount = $amount*($gst/100);
		$total_amount = $amount + $gst_amount;
		$grand_amount += $total_amount;
	} 
		
}

@endphp
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
		<td style="border-top: 2px solid #000000; border-bottom: 1px solid #000000; border-left: 2px solid #000000; border-right: 2px solid #000000" colspan=8 height="28" align="center" valign=top bgcolor="#FFFF00"><b><font size=4 color="#FF0000">PURCHASE ORDER</font></b></td>
		<td align="left" valign=bottom><font color="#000000"><br></font></td>
	</tr>
	<tr>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 2px solid #000000; border-right: 2px solid #000000" colspan=8 height="25" align="center" valign=middle bgcolor="#FFFF00"><b><font size=4 color="#FF0000">S.G CORPORATE MOBILITY PVT LTD</font></b></td>
		<td align="left" valign=bottom><font color="#000000"><br></font></td>
	</tr>
	<tr>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 2px solid #000000; border-right: 1px solid #000000" colspan=2 height="20" align="left" valign=middle><b><font color="#333333">Order No :</font></b></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" colspan=3 align="center" valign=middle><b><font color="#333333">{{$order_no}}<br></font></b></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-right: 1px solid #000000" align="left" valign=middle><b><font color="#333333">PAYMENT TERMS</font></b></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-right: 1px solid #000000" align="left" valign=middle><b><font color="#333333">{{$po_details->payment_terms}}</font></b></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 2px solid #000000" rowspan=3 align="left" valign=top sdnum="1033;0;MM/DD/YYYY;@"><b><font color="#333333">Total Amount</font></b></td>
		<td align="left" valign=bottom><font color="#000000"><br></font></td>
	</tr>
	<tr>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 2px solid #000000; border-right: 1px solid #000000" colspan=2 height="20" align="left" valign=middle><b><font color="#333333">GST NO: </font></b></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" colspan=3 align="center" valign=middle><b><font color="#333333">{{$po_details->gst_no}}</font></b></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-right: 1px solid #000000" align="left" valign=middle><b><font color="#333333"><br></font></b></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-right: 1px solid #000000" align="left" valign=middle><b><font color="#333333"><br></font></b></td>
		<td align="left" valign=bottom><font color="#000000"><br></font></td>
	</tr>
	<tr>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 2px solid #000000" colspan=2 height="20" align="left" valign=middle><b><font color="#333333">Order Date:                     </font></b></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" colspan=3 align="center" valign=middle sdnum="1033;1033;M/D/YYYY"><b><font color="#333333">{{$date}}</font></b></td>
		<td align="left" valign=bottom><font color="#000000"><br></font></td>
		<td align="left" valign=bottom><font color="#000000"><br></font></td>
		<td align="left" valign=bottom><font color="#000000"><br></font></td>
	</tr>
	<tr>
		<td style="border-top: 1px solid #000000; border-bottom: 2px solid #000000; border-left: 2px solid #000000; border-right: 1px solid #000000" colspan=2 height="21" align="left" valign=middle><b><font color="#333333">PAN NO : </font></b></td>
		<td style="border-top: 1px solid #000000; border-bottom: 2px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" colspan=3 align="center" valign=middle><b><font color="#333333"> {{$po_details->pan_no}}</font></b></td>
		<td style="border-top: 1px solid #000000; border-bottom: 2px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="left" valign=middle><b><font color="#333333">ORDER VALUE</font></b></td>
		<td style="border-top: 1px solid #000000; border-bottom: 2px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="left" valign=middle><b><font color="#333333">{{number_format($grand_amount,2)}}</font></b></td>
		<td style="border-top: 1px solid #000000; border-bottom: 2px solid #000000; border-left: 1px solid #000000; border-right: 2px solid #000000" align="left" valign=middle><b><font color="#333333">{{number_format($grand_amount,2)}}</font></b></td>
		<td align="left" valign=bottom><font color="#000000"><br></font></td>
	</tr>
	<tr>
		<td style="border-bottom: 1px solid #000000; border-left: 2px solid #000000; border-right: 1px solid #000000" colspan=5 height="20" align="center" valign=top bgcolor="#FFFF00"><b><font color="#FF0000">BILL FROM ADDRESS </font></b></td>
		<td style="border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 2px solid #000000" colspan=3 align="center" valign=top bgcolor="#FFFF00"><b><font color="#FF0000">PAYMENT DETAILS </font></b></td>
		<td align="left" valign=bottom><font color="#000000"><br></font></td>
	</tr>
	<tr>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 2px solid #000000; border-right: 1px solid #000000" colspan=5 height="21" align="center" valign=top><b><font size=3 color="#000000">{{$po_details->vname}}</font></b></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="left" valign=top><b><font size=3 color="#000000">A/C NO </font></b></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" colspan=2 align="center" valign=top><b><font size=3 color="#000000">{{$po_details->account_number}}</font></b></td>
		<td align="left" valign=bottom><font color="#000000"><br></font></td>
	</tr>
	<tr>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 2px solid #000000; border-right: 1px solid #000000" colspan=5 height="21" align="center" valign=top><b><font size=3 color="#000000">{{$po_details->address}}</font></b></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="left" valign=top><b><font size=3 color="#000000">IFS CODE</font></b></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" colspan=2 align="center" valign=top><b><font size=3 color="#000000">{{$po_details->ifs_code}}</font></b></td>
		<td align="left" valign=bottom><font color="#000000"><br></font></td>
	</tr>
	<tr>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 2px solid #000000; border-right: 1px solid #000000" colspan=5 height="21" align="center" valign=top><b><font size=3 color="#000000">{{$po_details->city}} , {{$po_details->state}} , {{$po_details->pincode}}</font></b></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="left" valign=top><b><font size=3 color="#000000">BANK NAME</font></b></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" colspan=2 align="center" valign=top><b><font size=3 color="#000000">State Bank of India</font></b></td>
		<td align="left" valign=bottom><font color="#000000"><br></font></td>
	</tr>
	<tr>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 2px solid #000000; border-right: 1px solid #000000" colspan=5 height="21" align="center" valign=top><b><font size=3 color="#000000">{{$po_details->country}} </font></b></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="left" valign=top><b><font size=3 color="#000000">MODE OF PAYMENT </font></b></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" colspan=2 align="center" valign=top><b><font size=3 color="#000000">{{$po_details->payment_mode}}</font></b></td>
		<td align="left" valign=bottom><font color="#000000"><br></font></td>
	</tr>
	<tr>
		<td style="border-top: 1px solid #000000; border-left: 2px solid #000000; border-right: 1px solid #000000" height="21" align="center" valign=middle bgcolor="#FFFF00"><b><font size=3 color="#FF0000">S. No.</font></b></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle bgcolor="#FFFF00"><b><font size=3 color="#FF0000">Product Discription</font></b></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle bgcolor="#FFFF00"><b><font size=3 color="#FF0000">HSN Code</font></b></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle bgcolor="#FFFF00"><b><font size=3 color="#FF0000">Tax rate</font></b></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=bottom bgcolor="#FFFF00"><b><font size=3 color="#FF0000">Qty</font></b></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle bgcolor="#FFFF00"><b><font size=3 color="#FF0000">Price</font></b></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle bgcolor="#FFFF00"><b><font size=3 color="#FF0000">Total Amount</font></b></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="left" valign=bottom bgcolor="#FFFF00"><b><font color="#FF0000">Remarks</font></b></td>
		<td align="left" valign=bottom><font color="#000000"><br></font></td>
	</tr>
	@php $grand_total = $total_quantity = 0; @endphp;
	  @if(!$data->isEmpty())
	  @foreach($data as $key => $val)
		@php 
		$gst = $val->gst;
		$amount = $val->price * $val->quantity;
		$gst_amount = $amount*($gst/100);
		$total_amount = $amount + $gst_amount;
		$grand_total += $total_amount;
		$total_quantity += $val->quantity;
		@endphp
	<tr>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" height="20" align="center" valign=bottom><font color="#000000">{{$key+1}}</font></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=bottom><font color="#000000">{{$val->part_name}} ,{{$val->colour_name}}</font></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="right" valign=bottom><font color="#000000">{{$val->hsn_code}}</font></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="right" valign=bottom><font color="#000000">{{$val->gst}}%</font></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=bottom><font color="#000000">{{$val->quantity}}</font></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=bottom><font color="#000000">{{number_format($val->price,2)}}</font></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=bottom><font color="#000000">{{number_format($total_amount,2)}}</font></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000"  align="center" valign=middle><font color="#000000"><br></font></td>
		<td style="border-left: 1px solid #000000" align="center" valign=bottom><font color="#000000"><br></font></td>
	</tr>
	@endforeach
	@endif
	
	<tr>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" colspan=4 height="21" align="center" valign=middle bgcolor="#FFFF00"><b><font size=3 color="#FF0000">Total</font></b></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle bgcolor="#FFFF00"><b><font size=3 color="#FF0000">{{$total_quantity}}</font></b></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle bgcolor="#FFFF00"><b><font size=3 color="#FF0000"><br></font></b></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle bgcolor="#FFFF00"><b><font size=3 color="#FF0000">{{number_format($grand_total,2)}}</font></b></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle bgcolor="#FFFF00"><b><font size=3 color="#FF0000"><br></font></b></td>
		<td align="left" valign=bottom><font color="#000000"><br></font></td>
	</tr>
</table>
</body>

</html>
