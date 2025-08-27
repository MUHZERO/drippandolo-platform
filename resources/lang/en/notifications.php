<?php

return [
    'order_created' => [
        'subject' => 'New order received',
        'body' => 'You have received a new order #:id.',
        'action' => 'View Order',
    ],
    'order_status_changed' => [
        'subject' => 'Order status updated',
        'body' => 'Order #:id has been updated from :old to :new.',
        'action' => 'View Order',
    ],
    'needs_confirmation' => [
        'subject' => 'Order #:order not confirmed',
        'line'    => 'You have an order #:order that has not been confirmed after 8 hours.',
        'action'  => 'Check Order',
    ],

    'no_orders_yesterday' => [
        'subject' => 'Reminder: No orders yesterday',
        'body'    => 'No orders were recorded yesterday (:date).',
        'action'  => 'View Orders',
    ],

    'order_delayed' => [
        'subject' => 'Order #:id delayed',
        'body'    => 'Order #:id has been marked as delayed (15 days without updates).',
        'action'  => 'View Order',
    ],

    'daily_summary' => [
        'subject'   => 'Orders summary for :date',
        'intro'     => 'Here is the orders summary for :date:',
        'total'     => 'Total orders: :count',
        'shipped'   => 'Shipped orders: :count',
        'delivered' => 'Delivered orders: :count',
        'canceled'  => 'Canceled orders: :count',
    ],
    'missing_revenue' => [
        'subject' => 'Missing revenue entry',
        'line'    => 'No revenue has been entered for :date.',
        'action'  => 'Enter revenue',
        'footer'  => 'Please update the data as soon as possible.',
        'db'      => 'Missing revenue for :date',
    ],

    'invoice_created' => [
        'subject' => 'New :type invoice (#:id)',
        'body'    => 'A new :type invoice has been generated (ID #:id).',
        'action'  => 'View Invoice',
    ],
    'invoice_paid' => [
        'subject' => 'Invoice #:id marked as paid',
        'body'    => 'Invoice #:id of type :type has been updated to paid.',
        'action'  => 'Open Invoice',
    ],
];
