<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'role',
        'status',
        'profile_image',
        'provider',
        'id_provider',
        'password',
        'activated_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var list<string>
     */
    protected $appends = [
        'has_password',
    ];

    /**
     * Determine whether the user has a password set.
     *
     * @return Attribute<bool, string>
     */
    protected function hasPassword(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->password !== null && $this->password !== '',
        );
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'activated_at' => 'datetime',
        ];
    }

    public function transaksi()
    {
        return $this->hasMany(Transaksi::class, 'penginput_id');
    }

    public function scopeFilter($query, ?string $search = null, ?array $role = null, ?array $status = null)
    {
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                    ->orWhere('email', 'like', "%$search%");
            });
        }

        if ($role) {
            $query->whereIn('role', $role);
        }

        if ($status) {
            $query->whereIn('status', $status);
        }

        return $query;
    }
}
