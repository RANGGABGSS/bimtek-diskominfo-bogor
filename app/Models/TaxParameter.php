<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaxParameter extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_name',
        'has_npwp',
        'tax_rate_percent',
        'description',
    ];

    protected $casts = [
        'has_npwp' => 'boolean',
        'tax_rate_percent' => 'float',
    ];
}
