<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any users.
     */
    public function viewAny(User $user): bool
    {
        return $user->isSuperAdmin();
    }

    /**
     * Determine whether the user can view the user.
     */
    public function view(User $user, User $model): bool
    {
        return $user->isSuperAdmin() || $user->id === $model->id;
    }

    /**
     * Determine whether the user can create users.
     */
    public function create(User $user): bool
    {
        return $user->isSuperAdmin();
    }

    /**
     * Determine whether the user can update the user.
     */
    public function update(User $user, User $model): bool
    {
        // Super admin can update anyone
        if ($user->isSuperAdmin()) {
            return true;
        }

        // Users can update their own profile (but not role/status)
        return $user->id === $model->id;
    }

    /**
     * Determine whether the user can delete the user.
     */
    public function delete(User $user, User $model): bool
    {
        // Super admin can delete/deactivate anyone except themselves
        if ($user->isSuperAdmin()) {
            return $user->id !== $model->id;
        }

        return false;
    }

    /**
     * Determine whether the user can activate the user.
     */
    public function activate(User $user, User $model): bool
    {
        return $user->isSuperAdmin() && $user->id !== $model->id;
    }

    /**
     * Determine whether the user can deactivate the user.
     */
    public function deactivate(User $user, User $model): bool
    {
        // Super admin can deactivate anyone except themselves
        if ($user->isSuperAdmin()) {
            return $user->id !== $model->id;
        }

        return false;
    }

    /**
     * Determine whether the user can change role of the user.
     */
    public function changeRole(User $user, User $model): bool
    {
        return $user->isSuperAdmin() && $user->id !== $model->id;
    }
}