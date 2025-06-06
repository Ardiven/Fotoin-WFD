<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PhotographerProfile extends Model
{
    protected $fillable = [
    'user_id',
    'phone',
    'whatsapp',
    'bio',
    'experience_years',
];

    public function user()
{
    return $this->belongsTo(User::class);
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


}
