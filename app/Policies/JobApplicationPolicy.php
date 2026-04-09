<?php

namespace App\Policies;

use App\Models\User;
use App\Models\JobApplication;

class JobApplicationPolicy
{
    /**
     * Determine if the user can view the application.
     */
    public function view(User $user, JobApplication $application): bool
    {
        return $user->id === $application->user_id;
    }

    /**
     * Determine if the user can update the application.
     */
    public function update(User $user, JobApplication $application): bool
    {
        return $user->id === $application->user_id;
    }

    /**
     * Determine if the user can delete the application.
     */
    public function delete(User $user, JobApplication $application): bool
    {
        return $user->id === $application->user_id;
    }
}
