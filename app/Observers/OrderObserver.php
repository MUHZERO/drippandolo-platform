<?php

namespace App\Observers;

use App\Models\Order;
use App\Models\OrderLog;
use App\Notifications\OrderCreatedNotification;
use App\Notifications\OrderStatusChangedNotification;

class OrderObserver
{
    public function created(Order $order): void
    {
        // Notify fornissure when a new order is created
        if ($order->fornissure) {
            $order->fornissure->notify(new OrderCreatedNotification($order));
        }
    }

    public function updated(Order $order): void
    {
        if ($order->isDirty('status')) {
            $oldStatus = $order->getOriginal('status');
            $oldUpdatedAt = $order->getOriginal('updated_at');
            $newStatus = $order->status;


            //block update status if cancelled or delivered or returned after 20 days from last update
            $newUpdatedAt = $order->updated_at;
            if (in_array($oldStatus, ['canceled', 'delivered', 'returned']) && $newUpdatedAt->diffInDays($oldUpdatedAt) > 20 && auth()->check() && !auth()->user()->hasRole('admin')) {
                $order->status = $oldStatus;
                $order->saveQuietly();
                return;
            }
            if ($order->wasChanged('status')) {
                // If fornissure updated status → notify operator
                if (auth()->check() && auth()->user()->hasRole('fornissure')) {
                    if ($order->operator) {
                        $order->operator->notify(
                            new OrderStatusChangedNotification($order, $oldStatus, $newStatus)
                        );
                    }
                }
                // Else operator/admin updated → notify fornissure
                else {
                    if ($order->fornissure) {
                        $order->fornissure->notify(
                            new OrderStatusChangedNotification($order, $oldStatus, $newStatus)
                        );
                    }
                }
            }
        }

        $changes = [];
        foreach ($order->getDirty() as $field => $new) {
            $old = $order->getOriginal($field);
            $changes[$field] = [
                'old' => $old,
                'new' => $new,
            ];
        }

        OrderLog::create([
            'order_id' => $order->id,
            'user_id'  => auth()->id(),
            'action'   => 'updated',
            'changes'  => $changes,
        ]);

    }
}
