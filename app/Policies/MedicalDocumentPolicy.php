<?php

namespace App\Policies;

use App\Models\MedicalDocument;
use App\Models\User;

class MedicalDocumentPolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, MedicalDocument $document): bool
    {
        return $user->id === $document->patient_id ||
               ($user->isDoctor() && $user->doctor->id === $document->doctor_id) ||
               $user->isAdmin();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->isDoctor() || $user->isAdmin();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, MedicalDocument $document): bool
    {
        return $user->id === $document->doctor_id || $user->isAdmin();
    }
}
