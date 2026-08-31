<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // 'admin', 'user' (peserta), 'pembicara'
        'nip_nik',
        'instansi',
        'jabatan',
        'no_hp',
        'avatar',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function participantProfile()
    {
        return $this->hasOne(ParticipantProfile::class, 'user_id');
    }

    public function speakerProfileDetail()
    {
        return $this->hasOne(SpeakerProfile::class, 'user_id');
    }

    public function speakerProfile()
    {
        return $this->hasOne(Speaker::class, 'user_id');
    }

    public function registrations()
    {
        return $this->hasMany(EventRegistration::class, 'user_id');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'user_id');
    }

    public function certificates()
    {
        return $this->hasMany(Certificate::class, 'user_id');
    }

    public function paymentComponents()
    {
        return $this->hasMany(PaymentComponent::class, 'user_id');
    }
}
