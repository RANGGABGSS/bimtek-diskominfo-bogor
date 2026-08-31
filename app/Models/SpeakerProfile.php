<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpeakerProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nip_nik',
        'foto_ktp_path',
        'npwp',
        'foto_npwp_path',
        'salinan_buku_rekening_path',
        'bahan_materi_path',
        'bank_name',
        'account_number',
        'account_name',
        'instansi',
        'jabatan',
        'golongan',
        'verification_status',
        'verification_notes',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
