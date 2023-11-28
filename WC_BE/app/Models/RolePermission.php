<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RolePermission extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'role_id',
        'permission_id',
    ];

    /**
     * Get the user associated with the user role.
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Get the role associated with the user role.
     */
    public function permission()
    {
        return $this->belongsTo(Permission::class);
    }
}