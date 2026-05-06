<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $fillable = ['patient_id', 'doctor_id', 'appointment_date_time', 'reason', 'status', 'notes'];

    protected $casts = [
        'appointment_date_time' => 'datetime',
    ];

    /**
     * Get the patient for this appointment.
     */
    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    /**
     * Get the doctor for this appointment.
     */
    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    /**
     * Get the consultation for this appointment.
     */
    public function consultation()
    {
        return $this->hasOne(Consultation::class);
    }

    /**
     * Scope to get upcoming appointments.
     */
    public function scopeUpcoming($query)
    {
        return $query->where('appointment_date_time', '>=', now())
                     ->where('status', '!=', 'cancelled')
                     ->orderBy('appointment_date_time');
    }

    /**
     * Scope to get today's appointments.
     */
    public function scopeToday($query)
    {
        return $query->whereDate('appointment_date_time', today());
    }
}
