<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerProfile extends Model
{
    protected $fillable = [
        'user_id',
        'phone_number', 
        'address', 
        'city', 
        'preferred_photography_type', 
        'budget_range'
    ];
    public function user()
{
    return $this->belongsTo(User::class);
}

}
