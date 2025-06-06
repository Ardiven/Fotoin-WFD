<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'package_id',
        'payment_code',
        'amount',
        'payment_method',
        'status',
        'notes',
        'payment_details',
        'paid_at',
        'expired_at',
    ];

    protected $casts = [
        'payment_details' => 'array',
        'paid_at' => 'datetime',
        'expired_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }

    public function installments(): HasMany
    {
        return $this->hasMany(PaymentInstallment::class);
    }

    public function proofs(): HasMany
    {
        return $this->hasMany(PaymentProof::class);
    }

    public function generatePaymentCode(): string
    {
        $prefix = 'PAY';
        $timestamp = now()->format('ymdHis');
        $random = str_pad(rand(0, 999), 3, '0', STR_PAD_LEFT);
        
        return $prefix . $timestamp . $random;
    }

    public function isExpired(): bool
    {
        return $this->expired_at && now() > $this->expired_at;
    }

    public function getTotalPaidAmount(): float
    {
        return $this->installments()
            ->where('status', 'paid')
            ->sum('amount');
    }

    public function getRemainingAmount(): float
    {
        return $this->amount - $this->getTotalPaidAmount();
    }

    public function getPaymentProgress(): float
    {
        if ($this->amount <= 0) return 0;
        
        return ($this->getTotalPaidAmount() / $this->amount) * 100;
    }
}

class PaymentInstallment extends Model
{
    use HasFactory;

    protected $fillable = [
        'payment_id',
        'installment_number',
        'amount',
        'status',
        'due_date',
        'paid_at',
    ];

    protected $casts = [
        'due_date' => 'date',
        'paid_at' => 'datetime',
    ];

    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }

    public function isOverdue(): bool
    {
        return $this->status === 'pending' && now() > $this->due_date;
    }
}

class PaymentProof extends Model
{
    use HasFactory;

    protected $fillable = [
        'payment_id',
        'file_path',
        'original_name',
        'status',
        'admin_notes',
    ];

    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }
}