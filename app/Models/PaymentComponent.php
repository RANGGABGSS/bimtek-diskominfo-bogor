<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentComponent extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'user_id',
        'recipient_type',
        'component_type',
        'volume',
        'unit',
        'unit_price',
        'gross_amount',
        'tax_rate_percent',
        'tax_amount',
        'net_amount',
        'bank_name',
        'account_number',
        'account_name',
        'payment_status',
        'payment_date',
        'notes',
    ];

    protected $casts = [
        'payment_date' => 'datetime',
        'gross_amount' => 'float',
        'tax_amount' => 'float',
        'net_amount' => 'float',
        'unit_price' => 'float',
        'volume' => 'float',
    ];

    public function event()
    {
        return $this->belongsTo(BimtekEvent::class, 'event_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
