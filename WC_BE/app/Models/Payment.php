<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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

    protected $casts = [
        'amount_money' => 'decimal:2',
    ];

    public function bill()
    {
        return $this->hasOne(Bill::class);
    }

    public function refund()
    {
        return $this->hasOne(Refund::class);
    }

    public function billPayment()
    {
        return $this->hasOne(BillPayment::class);
    }

    public static function getAllPayments(){
        return Payment::all();
    }

    public static function getPaymentsByUserId($userId){
        $payments = Payment::where('user_id', $userId)->get();
        return $payments;

    }

    public static function getPaymentDetail($paymentId)
    {
        $paymentDetail = DB::table("payments")
        ->select(
            'payments.id',
            'payments.description',
            'payments.account_name',
            'bill_payments.amount',
            'bills.*',
        )
        ->join('bill_payments', 'payments.id', '=', 'bill_payments.payment_id') 
        ->join('bills', 'bill_payments.bill_id', '=', 'bills.id')  
        ->where('payments.id', '=', $paymentId)
        ->get();

        if ($paymentDetail->isEmpty()) {
            return response()->json(['message' => "No payment found with id: $paymentId"], 404);
        }

        $paymentInfo = [
            'id' => $paymentDetail[0]->id,
            'description' => $paymentDetail[0]->description,
            'account_name' => $paymentDetail[0]->account_name,
        ];

        $billDetails = $paymentDetail->map(function ($bill) {
            return [
                'bill_id' => $bill->id,
                'fee_type' => $bill->fee_type,
                'fee' => $bill->fee,
                'created_at' => $bill->created_at,
                'due_at' => $bill->bill_at,
                'description' => $bill->description,
                'create_by' => $bill->created_by,
                'payer' => $bill->payer,
                'year' => $bill->year,
                'month' => $bill->month,
                'paid' => $bill->amount,
            ];
        });

        $result = [
            'payment_info' => $paymentInfo,
            'bill_details' => $billDetails,
        ];

        return $result;
    }
}