<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    protected $fillable = ['user_id', 'specialty_id', 'biography', 'available'];

    protected $casts = [
        'available' => 'boolean',
    ];

    /**
     * Get the user associated with this doctor.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the specialty for this doctor.
     */
    public function specialty()
    {
        return $this->belongsTo(Specialty::class);
    }

    /**
     * Get the appointments for this doctor.
     */
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    /**
     * Get the availabilities for this doctor.
     */
    public function availabilities()
    {
        return $this->hasMany(Availability::class);
    }

    /**
     * Get the medical documents created by this doctor.
     */
    public function medicalDocuments()
    {
        return $this->hasMany(MedicalDocument::class);
    }
}
