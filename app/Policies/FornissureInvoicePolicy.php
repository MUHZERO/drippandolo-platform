<?php

namespace App\Policies;

use App\Models\User;
use App\Models\FornissureInvoice;

class FornissureInvoicePolicy
{
    /**
     * Determine whether the user can view any invoices.
     */
    public function viewAny(User $user): bool
    {
        // Admins + fornissures can list invoices
        return $user->hasRole('admin') || $user->hasRole('fornissure');
    }

    /**
     * Determine whether the user can view the invoice.
     */
    public function view(User $user, FornissureInvoice $invoice): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        if ($user->hasRole('fornissure')) {
            // Fornissure can only view their own invoices
            return $invoice->fornissure_id === $user->id;
        }

        // Operators cannot view
        return false;
    }

    /**
     * Determine whether the user can create invoices.
     */


    /**
     * Determine whether the user can update the invoice.
     */
    public function update(User $user, FornissureInvoice $invoice): bool
    {
        if ($user->hasRole('admin') && $invoice->type !== 'return') {
            return true;
        }

        if ($user->hasRole('fornissure') && $invoice->type === 'return') {
            // Fornissure can edit return invoices
            return $invoice->fornissure_id === $user->id;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the invoice.
     */
    public function delete(User $user, FornissureInvoice $invoice): bool
    {
        return $user->hasRole('admin'); // only admins
    }
}

