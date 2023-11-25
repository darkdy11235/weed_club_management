<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Refund extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'payment_id',
        'amount'
    ];

    /**
     * Get the payment associated with the refund.
     */
    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }
}
