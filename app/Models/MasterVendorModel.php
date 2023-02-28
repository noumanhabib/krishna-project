<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasterVendorModel extends Model
{
    use SoftDeletes;
    protected $table = 'vendor';
	protected $primaryKey = 'id';
	protected $fillable = [
		'vname','address','city','state','country','pincode','account_number','ifs_code','bank_name','payment_mode','gst_no','pan_no','payment_terms','status'
	];
}
