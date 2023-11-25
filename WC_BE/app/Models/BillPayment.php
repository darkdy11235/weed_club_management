<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillPayment extends Model
{
    use HasFactory;
    public $timestamps = false;
    
    protected $fillable = [
        'bill_id',
        'payment_id',
        'amount'
    ];

    // Add relationships if necessary
    public function bill()
    {
        return $this->belongsTo(Bill::class);
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }
}

