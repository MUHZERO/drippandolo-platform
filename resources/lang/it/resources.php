<?php

return [

    'navigation' => [
        'group' => 'Gestione',
        'label' => 'Risorsa',
    ],

    'fields' => [
        'id'         => 'ID',
        'name'       => 'Nome',
        'label'      => 'Etichetta',
        'price'      => 'Prezzo',
        'amount'     => 'Importo',
        'type'       => 'Tipo',
        'date'       => 'Data',
        'created_at' => 'Creato il',
        'updated_at' => 'Aggiornato il',
        'description' => 'Descrizione',
        'email'      => 'Email',
        'phone'      => 'Telefono',
        'location'   => 'Posizione',
        'roles'      => 'Ruoli',
        'about'      => 'Informazioni',
        'from'      => 'Da',
        'until'     => 'Fino a',
        'open' => 'Apri',
        'title'      => 'Titolo',
        'read_at' => 'Letto il',
        'message'   => 'Messaggio',
        'read'      => 'Letto',
        'unread'    => 'Non letto',
        'all'        => 'Tutti',
        'notes'      => 'Note',
        'status'     => 'Stato',
        'customer_name' => 'Nome Cliente',
        'customer_phone' => 'Telefono Cliente',
        'customer_address' => 'Indirizzo Cliente',
        'customer' => 'Cliente',
        'product_name' => 'Nome Prodotto',
        'product_image' => 'Immagine Prodotto',
        'size' => 'Taglia',
        'operator'   => 'Operatore',
        'fornissure' => 'Fornitore',
        'confirmation_price' => 'Prezzo di Conferma',
        'tracking_number' => 'Numero di Tracking',
        'shopify_order_id' => 'ID Ordine Shopify',
        'user'       => 'Utente',
        'action'     => 'Azione',
        'changes'    => 'Modifiche',
        'reference'       => 'Riferimento',
        'type_invoice'            => 'Tipo di Fattura',
        'period_start'    => 'Inizio Periodo',
        'period_end'      => 'Fine Periodo',
        'transaction_image' => 'Prova di Transazione',
    ],

    'actions' => [
        'create'       => 'Crea',
        'edit'         => 'Modifica',
        'delete'       => 'Elimina',
        'bulk_delete'  => 'Elimina selezionati',
        'view'         => 'Vedi',
        'export'       => 'Esporta',
        'import'       => 'Importa',
    ],

    'messages' => [
        'created_successfully' => 'Creato con successo',
        'updated_successfully' => 'Aggiornato con successo',
        'deleted_successfully' => 'Eliminato con successo',
        'weekend_not_allowed' => 'I fine settimana non sono consentiti',
        'missing_previous'    => 'Devi prima inserire le entrate per :date',
    ],

    'tooltips' => [
        'missing_tracking_over_3_days' => 'Numero di tracking mancante da oltre 3 giorni',
    ],
    'statuses' => [
        'shipped'   => 'Spedito',
        'delivered' => 'Consegnato',
        'delayed'   => 'Ritardato',
        'canceled'  => 'Annullato',
        'not_paid' => 'Non Pagato',
        'paid'     => 'Pagato',
    ],

    'invoice_types' => [
        'payment' => 'Pagamento',
        'return'  => 'Ritorno',
    ],

    'spends_types' => [
        'fornitore' => 'Fornitore',
        'ads' => 'Pubblicità',
        'hosting' => 'Hosting',
        'team' => 'Team',
        'influencer' => 'Influencer',
        'altro' => 'Altro',
    ],
    'pages' => [
        'dashboards' => [
            'plural' => 'Dashboard',
            'singular' => 'Dashboard',
        ],
        'orders' => [
            'plural' => 'Ordini',
            'singular' => 'Ordine',
        ],
        'spends' => [
            'plural' => 'Spese',
            'singular' => 'Spesa',
        ],
        'confirmation_prices' => [
            'plural' => 'Prezzi di Conferma',
            'singular' => 'Prezzo di Conferma',
        ],
        'revenus' => [
            'plural' => 'Entrate',
            'singular' => 'Entrata',
        ],
        'users' => [
            'plural' => 'Utenti',
            'singular' => 'Utente',
        ],
        'roles' => [
            'plural' => 'Ruoli',
            'singular' => 'Ruolo',
        ],
        'fornissure_invoices' => [
            'singular' => 'Fattura Fornitore',
            'plural'   => 'Fatture Fornitore',
        ],
        'notifications' => [
            'plural' => 'Notifiche',
            'singular' => 'Notifica',
        ],
    ],

    'navigation' => [
        'sales' => 'Vendite',
        'user_management' => 'Gestione Utenti',
        'system' => 'Sistema',
    ],


    'logs' => [
        'fields' => [
            'product_name'        => 'Nome Prodotto',
            'product_image'       => 'Immagine Prodotto',
            'customer_name'       => 'Nome Cliente',
            'customer_phone'      => 'Telefono Cliente',
            'customer_address'    => 'Indirizzo Cliente',
            'price'               => 'Prezzo',
            'status'              => 'Stato',
            'tracking_number'     => 'Numero di Tracking',
            'shopify_order_id'    => 'ID Ordine Shopify',
            'size'                => 'Taglia',
            'notes'               => 'Note',
            'confirmation_price_id' => 'Prezzo di Conferma',
            'confirmed_price'     => 'Prezzo Confermato',
            'operator_id'         => 'Operatore',
            'fornissure_id'       => 'Fornitore',
            'notified_at'         => 'Notificato il',
        ],
        'actions' => [
            'created' => 'Creato',
            'updated' => 'Aggiornato',
            'deleted' => 'Eliminato',
        ],
    ],

    'webhooks' => [
        'docs' => [
            'title' => 'Webhook Chatbot',
            'intro' => 'Cerca ordini per nome, telefono o ID Shopify tramite un webhook sicuro.',
            'endpoint' => 'Endpoint',
            'method' => 'Metodo',
            'auth' => 'Autenticazione',
            'auth_desc' => 'Invia l’header X-Webhook-Token con il valore di CHATBOT_WEBHOOK_TOKEN dal tuo ambiente.',
            'token_placeholder' => 'CHATBOT_WEBHOOK_TOKEN',
            'fields' => 'Campi della richiesta',
            'field_query' => 'Opzionale. Cerca nome, telefono normalizzato o ID Shopify (parziale).',
            'field_name' => 'Opzionale. Corrispondenza parziale sul nome cliente.',
            'field_phone' => 'Opzionale. Le cifre vengono normalizzate, supportata corrispondenza parziale.',
            'field_shopify_id' => 'Opzionale. Corrispondenza esatta se fornito esplicitamente.',
            'field_limit' => 'Opzionale. Predefinito 10, massimo 50.',
            'examples' => 'Esempi',
            'example_query_title' => 'Esempio di query singola',
            'example_fields_title' => 'Esempio con campi espliciti',
            'example_response_title' => 'Esempio di risposta',
        ],
    ],

];
