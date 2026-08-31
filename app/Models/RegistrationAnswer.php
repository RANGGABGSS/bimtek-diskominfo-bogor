<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegistrationAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'registration_id',
        'form_field_id',
        'answer_value',
    ];

    public function registration()
    {
        return $this->belongsTo(EventRegistration::class, 'registration_id');
    }

    public function formField()
    {
        return $this->belongsTo(FormField::class, 'form_field_id');
    }
}
