<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'pay_account',
        'pay_method',
        'account_name',
        'account_number',
        'amount_money',
        'description'
    ];

    /**
     * Get the user associated with the payment.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
