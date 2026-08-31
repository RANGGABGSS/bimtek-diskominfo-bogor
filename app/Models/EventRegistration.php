<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventRegistration extends Model
{
    use HasFactory;

    protected $fillable = [
        'bimtek_event_id',
        'user_id',
        'registration_code',
        'status',
        'certificate_path',
        'registered_at',
    ];

    protected $casts = [
        'registered_at' => 'datetime',
    ];

    public function event()
    {
        return $this->belongsTo(BimtekEvent::class, 'bimtek_event_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function answers()
    {
        return $this->hasMany(RegistrationAnswer::class, 'registration_id');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'registration_id');
    }
}
