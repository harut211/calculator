<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Operation extends Model
{
    protected $fillable = [
        'operation_date',
        'user_id',
        'user_type',
        'operation_type',
        'amount',
        'currency',
        'commission_fee',
    ];


}

