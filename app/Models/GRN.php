<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GRN extends Model
{
    use HasFactory;

    protected $table = 'good_receive_notes';

    protected $fillable = ['remarks', 'vendor_id', 'customer_id', 'corriour_by', 'po_no', 'stock', 'items'];

    protected $casts = ['items' => 'array'];
}
