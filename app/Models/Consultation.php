<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Consultation extends Model
{
    protected $fillable = ['appointment_id', 'diagnosis', 'prescription', 'notes'];

    /**
     * Get the appointment for this consultation.
     */
    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }
}
