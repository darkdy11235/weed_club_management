<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'role_name'
    ];

    public function permissions()
    {
        return $this->belongsToMany(Role::class, 'role_permissions', 'role_id', 'permission_id');
    }
}