<?php
namespace App\Models;
// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'address',
        'active',
    ];
        // Permissions via role
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_permission', 'role', 'permission_id', 'role', 'id');
    }

    public function hasPermission($permission)
    {
        // Super admin always has all permissions
        if ($this->role === 'super_admin') return true;
        return $this->permissions()->where('name', $permission)->exists();
    }
    public function orders()
    {
        return $this->hasMany(\App\Models\Order::class);
    }
    public function activityLogs()
    {
        return $this->hasMany(\App\Models\UserActivityLog::class);
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
}
