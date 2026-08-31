<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Speaker extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'nip_nik',
        'instansi',
        'jabatan',
        'golongan',
        'email',
        'no_hp',
        'bank_name',
        'account_number',
        'account_name',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function eventAssignments()
    {
        return $this->hasMany(EventSpeaker::class);
    }
}
