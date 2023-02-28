<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RenewPartStatusUpdate extends Model
{
    use HasFactory;
    protected $table="els_order_request_parts";

    protected $fillable = [
        'status',
        'barcode',
    ];
}
