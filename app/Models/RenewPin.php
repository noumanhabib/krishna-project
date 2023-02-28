<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RenewPin extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'received_parts_barcode_list';
    protected $primaryKey = 'id';
    protected $fillable = [
        'received_part_id', 'barcode', 'iqc_status', 'price', 'status', 'tester_id', 'received_date', 'remark'
    ];
}