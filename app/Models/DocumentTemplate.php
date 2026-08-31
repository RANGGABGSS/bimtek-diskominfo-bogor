<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'template_code',
        'template_name',
        'header_config',
        'body_html',
        'signee_nama',
        'signee_nip',
        'signee_jabatan',
    ];

    protected $casts = [
        'header_config' => 'array',
    ];
}
