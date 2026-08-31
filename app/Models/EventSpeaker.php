<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventSpeaker extends Model
{
    use HasFactory;

    protected $fillable = [
        'bimtek_event_id',
        'speaker_id',
        'topic',
        'material_path',
        'jp_hours',
        'rate_per_jp',
        'tax_percent',
        'certificate_path',
    ];

    public function event()
    {
        return $this->belongsTo(BimtekEvent::class, 'bimtek_event_id');
    }

    public function speaker()
    {
        return $this->belongsTo(Speaker::class, 'speaker_id');
    }

    // Accessors for Honorarium Calculations
    public function getTotalBrutoAttribute()
    {
        return $this->jp_hours * $this->rate_per_jp;
    }

    public function getTaxNominalAttribute()
    {
        return $this->total_bruto * ($this->tax_percent / 100);
    }

    public function getTotalNettoAttribute()
    {
        return $this->total_bruto - $this->tax_nominal;
    }
}
