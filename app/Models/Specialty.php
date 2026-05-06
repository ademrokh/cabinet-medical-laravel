<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Specialty extends Model
{
    protected $fillable = ['name', 'description'];

    /**
     * Get the doctors with this specialty.
     */
    public function doctors()
    {
        return $this->hasMany(Doctor::class, 'specialty_id');
    }
}
