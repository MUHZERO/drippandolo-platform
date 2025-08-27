<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Order;

class OrderPolicy
{

    /**
     * Determine whether the user can view any orders.
     *
     * @param  User  $user
     * @return bool
     */

    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin') || $user->hasRole('operator') || $user->hasRole('fornissure');
    }

    /**
     * Determine whether the user can view the order.
     *
     * @param  User  $user
     * @param  Order  $order
     * @return bool
     */

    public function view(User $user, Order $order): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        if ($user->hasRole('operator')) {
            return $order->operator_id === $user->id;
        }

        if ($user->hasRole('fornissure')) {
            return $order->fornissure_id === $user->id;
        }

        return false;
    }

    /**
     * Determine whether the user can create orders.
     *
     * @param  User  $user
     * @return bool
     */

    public function create(User $user): bool
    {
        return $user->hasRole('admin') || $user->hasRole('operator');
    }

    /**
     * Determine whether the user can update the order.
     *
     * @param  User  $user
     * @param  Order  $order
     * @return bool
     */

    public function update(User $user, Order $order): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        if ($user->hasRole('operator')) {
            return $order->operator_id === $user->id;
        }
        if ($user->hasRole('fornissure')) {
            return $order->fornissure_id === $user->id;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the order.
     *
     * @param  User  $user
     * @param  Order  $order
     * @return bool
     */

    public function delete(User $user, Order $order): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        if ($user->hasRole('operator') && ! $order->invoices()->exists()) {
            return true;
        }

        return false;
    }

    public function restore(User $user, Order $order): bool
    {
        return $user->hasRole('admin') ?? false;
    }

    public function forceDelete(User $user, Order $order): bool
    {
        return $user->hasRole('admin') ?? false;
    }
}
