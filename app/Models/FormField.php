<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormField extends Model
{
    use HasFactory;

    protected $fillable = [
        'bimtek_event_id',
        'field_label',
        'field_type',
        'field_options',
        'is_required',
        'order_index',
    ];

    protected $casts = [
        'field_options' => 'array',
        'is_required' => 'boolean',
    ];

    public function event()
    {
        return $this->belongsTo(BimtekEvent::class, 'bimtek_event_id');
    }

    public function answers()
    {
        return $this->hasMany(RegistrationAnswer::class);
    }
}
