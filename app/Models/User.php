<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
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
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function photographerProfile()
{
    return $this->hasOne(PhotographerProfile::class);
}

public function customerProfile()
{
    return $this->hasOne(CustomerProfile::class);
}

public function specialties()
{
    return $this->belongsToMany(Specialty::class);
}
public function cities()
{
    return $this->belongsToMany(City::class);
}
public function packages()
{
    return $this->hasMany(Package::class);
}
public function portfolios()
{
    return $this->hasMany(Portfolio::class);

}
public function payments()
{
    return $this->hasMany(Payment::class); 
}
}
