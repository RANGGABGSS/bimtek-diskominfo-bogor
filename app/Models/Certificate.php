<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'user_id',
        'role_type',
        'certificate_number',
        'file_path',
        'issue_date',
    ];

    protected $casts = [
        'issue_date' => 'date',
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
