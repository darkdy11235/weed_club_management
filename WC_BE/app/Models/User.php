<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\RolePermission;
use App\Models\Role;
use App\Models\UserRole;

class User extends Authenticatable
{
    use HasFactory, HasApiTokens, Notifiable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'age',
        'gender',
        'phone',
        'address',
        'email',
        'email_verified_at', 
        'password',
        'avatar',
        'remember_token',
        'verified',
        'verification_code',
        'reset_password_code',
        'status',
        'created_by',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime'
    ];

     /**
     * Check if the user has a specific role.
     *
     * @param string $roleName
     * @return bool
     */
    public function hasAnyPermission($permissions)
    {
        $userRoles = $this->roles()->pluck('id')->toArray();
        $rolePermissions = RolePermission::whereIn('role_id', $userRoles)->pluck('permission_id')->toArray();
        $userPermissions = Permission::whereIn('id', $rolePermissions)->pluck('slug')->toArray();

        return count(array_intersect($permissions, $userPermissions)) > 0;
    }

    public function hasAnyRole(array $roles)
    {
        $userRoles = $this->roles()->pluck('role_name')->toArray();

        return count(array_intersect($roles, $userRoles)) > 0;
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_roles', 'user_id', 'role_id');
    }

    public function bills()
    {
        return $this->hasMany(Bill::class);
    }


    public function getAllRoles()
    {
        $userRoles = $this->roles()->pluck('role_name')->toArray();
        return  $userRoles;
    }

    public function paidBills()
    {
        return $this->belongsToMany(Bill::class, 'bill_payments', 'user_id', 'id')
            ->using(BillPayment::class)
            ->withPivot(['id']);
    }


    // Event listener for the 'created' event
    protected static function boot()
    {
        parent::boot();

        static::created(function ($user) {
            // Attach the default role (role_id = 2) to the user
            $user->roles()->attach(2); // Assuming 2 is the role_id for the default role
        });
    }
}