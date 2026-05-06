<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MedicalDocument extends Model
{
    protected $fillable = ['patient_id', 'doctor_id', 'type', 'file_path', 'description'];

    /**
     * Get the patient for this document.
     */
    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    /**
     * Get the doctor who created this document.
     */
    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }
}
