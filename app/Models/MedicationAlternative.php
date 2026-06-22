<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MedicationAlternative extends Model
{
    protected $fillable = ['medication_id', 'alternative_id', 'notes'];

    public function medication() { return $this->belongsTo(Medication::class); }
    public function alternative() { return $this->belongsTo(Medication::class, 'alternative_id'); }
}
