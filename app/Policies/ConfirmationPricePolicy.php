<?php

namespace App\Policies;

use App\Models\User;
use App\Models\ConfirmationPrice;

class ConfirmationPricePolicy
{

    /**
     * Determine whether the user can view any confirmation prices.
     *
     * @param  User  $user
     * @return bool
     */

    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can view the confirmation price.
     *
     * @param  User  $user
     * @param  ConfirmationPrice  $model
     * @return bool
     */

    public function view(User $user, ConfirmationPrice $model): bool
    {
        return $user->hasRole('admin');
    }
    /**
     * Determine whether the user can create confirmation prices.
     *
     * @param  User  $user
     * @return bool
     */

    public function create(User $user): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can update the confirmation price.
     *
     * @param  User  $user
     * @param  ConfirmationPrice  $model
     * @return bool
     */

    public function update(User $user, ConfirmationPrice $model): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can delete the confirmation price.
     *
     * @param  User  $user
     * @param  ConfirmationPrice  $model
     * @return bool
     */

    public function delete(User $user, ConfirmationPrice $model): bool
    {
        return $user->hasRole('admin');
    }
}
