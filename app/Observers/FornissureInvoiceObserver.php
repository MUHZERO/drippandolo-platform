<?php

namespace App\Observers;

use App\Models\FornissureInvoice;
use App\Models\User;
use App\Notifications\InvoicePaidNotification;

class FornissureInvoiceObserver
{
    /**
     * Handle the FornissureInvoice "created" event.
     */
    public function created(FornissureInvoice $fornissureInvoice): void
    {
        //
    }

    /**
     * Handle the FornissureInvoice "updated" event.
     */
    public function updated(FornissureInvoice $invoice): void
    {
        // Check if status changed to "paid"
        if ($invoice->wasChanged('status') && $invoice->status === 'paid') {

            if ($invoice->type === 'payment') {
                // Payment invoices: notify the fornissure
                $fornissure = $invoice->fornissure;
                if ($fornissure) {
                    $fornissure->notify(new InvoicePaidNotification($invoice));
                }
            }

            if ($invoice->type === 'return') {
                // Return invoices: notify all admins
                $admins = User::whereHas('roles', function ($query) {
                    $query->where('name', 'admin');
                })->get();
                foreach ($admins as $admin) {
                    $admin->notify(new InvoicePaidNotification($invoice));
                }
            }
        }
    }

    /**
     * Handle the FornissureInvoice "deleted" event.
     */
    public function deleted(FornissureInvoice $fornissureInvoice): void
    {
        //
    }

    /**
     * Handle the FornissureInvoice "restored" event.
     */
    public function restored(FornissureInvoice $fornissureInvoice): void
    {
        //
    }

    /**
     * Handle the FornissureInvoice "force deleted" event.
     */
    public function forceDeleted(FornissureInvoice $fornissureInvoice): void
    {
        //
    }
}
