<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlanningSuggestion extends Model
{
    protected $fillable = [
        'patient_id',
        'suggested_time',
        'priority',
        'validated',
    ];

    protected $casts = [
        'suggested_time' => 'datetime',
        'validated' => 'boolean',
    ];

    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }
}
