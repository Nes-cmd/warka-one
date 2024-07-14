<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Support\Str;

class User extends Authenticatable implements MustVerifyEmail, FilamentUser
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'country_id',
        'phone_verified_at',
        'email_verified_at',
    ];

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
        'phone_verified_at' => 'datetime',
        'password'          => 'hashed',
    ];

    protected $primaryKey = 'id';

    public $incrementing = false;

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->email == 'nesrusadik0@gmail.com';
    }

    protected static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->id = Str::uuid();
        });
    }
    public function country()  {
        return $this->hasOne(Country::class);
    }

    public function userDetail() : HasOne {
        return $this->hasOne(UserDetail::class);
    }
}
