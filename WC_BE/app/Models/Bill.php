<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'fee_type',
        'payer',
        'fee',
        'created_by',
        'bill_at',
        'month',
        'year',
        'description'
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
        $data = Bill::select('bill.*', 'users.name')
            ->join('users', 'bills.payer', '=', 'users.id_user')
            ->orderBy('bill_id', 'desc')
            ->get();

        return $data;
    }

    public static function getBillById($id){
        $data = Bill::where('bill_id', $id)->get();

        return $data;
    }

    public static function deleteBill($id) {
        $deletedBill = Bill::where('bill_id', $id)->delete();

        return $deletedBill;
    }
}
