<?php

namespace App\Models;

use App\Models\trait\BootTrait;
use App\Models\trait\CreatedByRelationship;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes, CreatedByRelationship, BootTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'role_id',
        'email',
        'phone_number',
        'password',
        'gender',
        'address'
    ];

    const PROFILE_PICTURE_TYPE = 'profile_picture';

    protected $with = ['profilePicture', 'role'];

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'role_id', 'id');
    }

    /**
     * @param $permission
     * @return bool
     */
    public function hasPermission($permission): bool
    {
        return $this->role
            ->permissions()
            ->where('slug', $permission)
            ->count() ? true : false;
    }

    /**
     * @return mixed
     */
    public function rolePermissions(): mixed
    {
        return $this->role->permissions();
    }

    /**
     * @return MorphOne
     */
    public function profilePicture(): MorphOne
    {
        return $this->morphOne(File::class, 'fileable')
            ->where('type', self::PROFILE_PICTURE_TYPE);
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
