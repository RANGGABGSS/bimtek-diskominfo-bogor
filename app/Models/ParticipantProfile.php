<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParticipantProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nik',
        'foto_ktp_path',
        'npwp',
        'foto_npwp_path',
        'bank_name',
        'account_number',
        'account_name',
        'instansi',
        'no_hp',
        'verification_status',
        'verification_notes',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
