<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'price',
        'duration',
        'custom_duration',
        'description',
        'status',
        'is_popular',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_popular' => 'boolean',
    ];

    protected $attributes = [
        'status' => 'active',
        'is_popular' => false,
    ];

    public function getFormattedPriceAttribute()
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }

    public function getFormattedDurationAttribute()
    {
        if ($this->duration === 'custom' && $this->custom_duration) {
            return $this->custom_duration . ' Hours';
        }
        
        return $this->duration ? $this->duration . ' Hours' : 'Not specified';
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
    public function scopeCheaper($query)
    {
        return $query->orderBy('price', 'asc');
    }

    public function scopePopular($query)
    {
        return $query->where('is_popular', true);
    }


    public function scopeSearch($query, $term)
    {
        return $query->where('name', 'like', '%' . $term . '%')
                    ->orWhere('description', 'like', '%' . $term . '%');
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function features()
    {
        return $this->hasMany(Feature::class);
    }
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}