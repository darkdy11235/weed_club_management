<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = [
        'fee_type',
        'payer',
        'fee',
        'bill_at',
        'month',
        'year',
        'description',
        'created_by',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'bill_at' => 'datetime',
    ];

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }

    public static function createBill($bill)
    {
        $created_at = now();
        $bill_at = now()->addDays(30);

        $newBills = [];

        foreach ($bill['payers'] as $payer) {
            $newBill = Bill::create([
                'fee_type' => $bill['fee_type'],
                'fee' => $bill['fee'],
                'created_at' => $created_at,
                'bill_at' => $bill_at,
                'description' => $bill['description'],
                'created_by' => $bill['create_by'], 
                'payer' => $payer,
                'year' => $bill['year'],
                'month' => $bill['month'],
            ]);

            $newBills[] = $newBill;
        }

        return $bill;
    }

    public static function updateBill($newBill)
    {
        $updatedBill = Bill::where('id', $newBill['id'])->update([
            'fee_type' => $newBill['fee_type'],
            'fee' => $newBill['fee'],
            'description' => $newBill['description'],
            'payer' => $newBill['payer'],
            'year' => $newBill['year'],
            'month' => $newBill['month'],
        ]);

        return $updatedBill;
    }

    public static function getAllBill()
    {
        $data = Bill::select('bills.*', 'users.name')
            ->join('users', 'bills.payer', '=', 'users.id')
            ->orderBy('id', 'desc')
            ->get();

        return $data;
    }

    public static function getBillById($id){
        $data = Bill::where('id', $id)->get();

        return $data;
    }

    public static function deleteBill($id) {
        $deletedBill = Bill::where('id', $id)->delete();

        return $deletedBill;
    }

}