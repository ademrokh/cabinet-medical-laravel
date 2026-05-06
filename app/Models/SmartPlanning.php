<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SmartPlanning extends Model
{
    protected $fillable = [
        'doctor_id',
        'date',
        'generated_plan',
        'status',
        'generated_at',
    ];

    protected $casts = [
        'date' => 'date',
        'generated_plan' => 'array',
        'generated_at' => 'datetime',
    ];

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }
}
