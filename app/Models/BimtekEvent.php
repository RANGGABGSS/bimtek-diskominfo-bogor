<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BimtekEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'start_date',
        'end_date',
        'location',
        'quota',
        'status',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    public function formFields()
    {
        return $table = $this->hasMany(FormField::class)->orderBy('order_index', 'asc');
    }

    public function registrations()
    {
        return $this->hasMany(EventRegistration::class);
    }

    public function eventSpeakers()
    {
        return $this->hasMany(EventSpeaker::class);
    }

    public function paymentComponents()
    {
        return $this->hasMany(PaymentComponent::class, 'event_id');
    }

    public function payments()
    {
        return $this->hasMany(PaymentComponent::class, 'event_id');
    }
}

