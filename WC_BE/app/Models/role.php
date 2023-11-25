<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'permission_id',
    ];

    /**
     * Get the permission associated with the role.
     */
    public function permission()
    {
        return $this->belongsTo(Permission::class);
    }
}
