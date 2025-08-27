<?php

return [

    'navigation' => [
        'group' => 'Management',
        'label' => 'Resource',
    ],

    'fields' => [
        'id'         => 'ID',
        'name'       => 'Name',
        'label'      => 'Label',
        'price'      => 'Price',
        'amount'     => 'Amount',
        'type'       => 'Type',
        'date'       => 'Date',
        'created_at' => 'Created At',
        'updated_at' => 'Updated At',
        'email'      => 'Email',
        'phone'      => 'Phone',
        'location'   => 'Location',
        'roles'      => 'Roles',
        'about'      => 'About',
        'from'      => 'From',
        'until'     => 'Until',
        'title'      => 'Title',
        'read_at' => 'Read At',
        'open' => 'Open',
        'message'   => 'Message',
        'read'      => 'Read',
        'unread'    => 'Unread',
        'all'        => 'All',
        'description' => 'Description',
        'notes'     => 'Notes',
        'status'     => 'Status',
        'customer_name' => 'Customer Name',
        'customer_phone' => 'Customer Phone',
        'customer_address' => 'Customer Address',
        'product_name' => 'Product Name',
        'product_image' => 'Product Image',
        'operator' => 'Operator',
        'fornissure' => 'Fornissure',
        'confirmation_price' => 'Confirmation Price',
        'user' => 'User',
        'action' => 'Action',
        'changes' => 'Changes',
        'reference'       => 'Reference',
        'type_invoice'            => 'Invoice Type',
        'period_start'    => 'Period Start',
        'period_end'      => 'Period End',
        'transaction_image' => 'Transaction Proof',
    ],

    'actions' => [
        'create'       => 'Create',
        'edit'         => 'Edit',
        'delete'       => 'Delete',
        'bulk_delete'  => 'Delete Selected',
        'view'         => 'View',
        'export'       => 'Export',
        'import'       => 'Import',
    ],

    'messages' => [
        'created_successfully' => 'Created successfully',
        'updated_successfully' => 'Updated successfully',
        'deleted_successfully' => 'Deleted successfully',
        'weekend_not_allowed' => 'Weekends are not allowed',
        'missing_previous'    => 'You must first enter revenue for :date',

    ],

    'statuses' => [
        'shipped'   => 'Shipped',
        'delivered' => 'Delivered',
        'delayed'   => 'Delayed',
        'canceled'  => 'Canceled',
        'not_paid' => 'Not Paid',
        'paid'     => 'Paid',
    ],

    'invoice_types' => [
        'payment' => 'Payment',
        'return'  => 'Return',
    ],

    'spends_types' => [
        'fornitore' => 'Supplier',
        'ads' => 'Ads',
        'hosting' => 'Hosting',
        'team' => 'Team',
        'influencer' => 'Influencer',
        'altro' => 'Other',
    ],

    'pages' => [
        'dashboards' => ['singular' => 'Dashboard', 'plural' => 'Dashboards'],
        'orders' => [
            'plural' => 'Orders',
            'singular' => 'Order',
        ],
        'spends' => [
            'plural' => 'Spends',
            'singular' => 'Spend',
        ],
        'confirmation_prices' => [
            'plural' => 'Confirmation Prices',
            'singular' => 'Confirmation Price',
        ],
        'revenus' => [
            'plural' => 'Revenues',
            'singular' => 'Revenue',
        ],
        'users' => [
            'plural' => 'Users',
            'singular' => 'User',
        ],
        'roles' => [
            'plural' => 'Roles',
            'singular' => 'Role',
        ],
        'fornissure_invoices' => [
            'singular' => 'Fornissure Invoice',
            'plural'   => 'Fornissure Invoices',
        ],
        'notifications' => ['singular' => 'Notification', 'plural' => 'Notifications'],
    ],

    'navigation' => [
        'sales' => 'Sales',
        'user_management' => 'User Management',
        'system' => 'System',
    ],
    'logs' => [
        'fields' => [
            'product_name'        => 'Product Name',
            'product_image'       => 'Product Image',
            'customer_name'       => 'Customer Name',
            'customer_phone'      => 'Customer Phone',
            'customer_address'    => 'Customer Address',
            'price'               => 'Price',
            'status'              => 'Status',
            'notes'               => 'Notes',
            'confirmation_price_id' => 'Confirmation Price',
            'confirmed_price'     => 'Confirmed Price',
            'operator_id'         => 'Operator',
            'fornissure_id'       => 'Fornissure',
            'notified_at'         => 'Notified At',
        ],
        'actions' => [
            'created' => 'Created',
            'updated' => 'Updated',
            'deleted' => 'Deleted',
        ],
    ],

];
