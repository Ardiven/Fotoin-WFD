<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'package_id',
        'date',
        'time',
        'location_type',
        'location',
        'client_name',
        'client_phone',
        'notes',
        'total_price',
        'status',
        'booking_number',
        'payment_status',
        'payment_method',
        'paid_at',
    ];

    protected $casts = [
        'date' => 'date',
        'total_price' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    /**
     * Status options
     */
    const STATUS_PENDING = 'pending';
    const STATUS_CONFIRMED = 'confirmed';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';

    /**
     * Payment status options
     */
    const PAYMENT_PENDING = 'pending';
    const PAYMENT_PAID = 'paid';
    const PAYMENT_FAILED = 'failed';
    const PAYMENT_REFUNDED = 'refunded';

    /**
     * Location type options
     */
    const LOCATION_STUDIO = 'studio';
    const LOCATION_OUTDOOR = 'outdoor';
    const LOCATION_CLIENT_HOME = 'client_home';
    const LOCATION_VENUE = 'venue';

    /**
     * Relationship to photographer
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relationship to package
     */
    public function package()
    {
        return $this->belongsTo(Package::class);
    }


    public function payment()
    {
        return $this->hasOne(\App\Models\Payment::class, 'booking_id', 'id');
    }


    /**
     * Get formatted date
     */
    public function getFormattedDateAttribute()
    {
        return $this->date->format('d M Y');
    }

    /**
     * Get formatted time
     */
    public function getFormattedTimeAttribute()
    {
        return date('H:i', strtotime($this->time));
    }

    /**
     * Get formatted price
     */
    public function getFormattedPriceAttribute()
    {
        return 'Rp ' . number_format($this->total_price, 0, ',', '.');
    }

    /**
     * Get location type label
     */
    public function getLocationTypeLabelAttribute()
    {
        $labels = [
            'studio' => 'Studio',
            'outdoor' => 'Outdoor',
            'client_home' => 'Client\'s Home',
            'venue' => 'Event Venue',
        ];

        return $labels[$this->location_type] ?? $this->location_type;
    }

    /**
     * Get status label
     */
    public function getStatusLabelAttribute()
    {
        $labels = [
            'pending' => 'Menunggu Konfirmasi',
            'confirmed' => 'Terkonfirmasi',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
        ];

        return $labels[$this->status] ?? $this->status;
    }

    /**
     * Get payment status label
     */
    public function getPaymentStatusLabelAttribute()
    {
        $labels = [
            'pending' => 'Belum Dibayar',
            'paid' => 'Sudah Dibayar',
            'failed' => 'Gagal',
            'refunded' => 'Dikembalikan',
        ];

        return $labels[$this->payment_status] ?? $this->payment_status;
    }

    /**
     * Scope for pending bookings
     */
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    /**
     * Scope for confirmed bookings
     */
    public function scopeConfirmed($query)
    {
        return $query->where('status', self::STATUS_CONFIRMED);
    }

    /**
     * Scope for today's bookings
     */
    public function scopeToday($query)
    {
        return $query->whereDate('date', today());
    }

    /**
     * Scope for upcoming bookings
     */
    public function scopeUpcoming($query)
    {
        return $query->where('date', '>=', today());
    }
}