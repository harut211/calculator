<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fee extends Model
{
    use HasFactory;


    protected $fillable = [
        'user_id',
        'fee_type',
        'fee_amount'
    ];

    protected $table = 'fees';


    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
