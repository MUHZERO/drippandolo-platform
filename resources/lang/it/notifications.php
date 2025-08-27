<?php

return [
    'order_created' => [
        'subject' => 'Nuovo ordine ricevuto',
        'body' => 'Hai ricevuto un nuovo ordine #:id.',
        'action' => 'Visualizza Ordine',
    ],
    'order_status_changed' => [
        'subject' => 'Stato ordine aggiornato',
        'body' => 'L\'ordine #:id è stato aggiornato da :old a :new.',
        'action' => 'Visualizza Ordine',
    ],
    'needs_confirmation' => [
        'subject' => 'Ordine #:order non confermato',
        'line'    => 'Hai un ordine #:order che non è stato confermato dopo 8 ore.',
        'action'  => 'Controlla Ordine',
    ],

    'no_orders_yesterday' => [
        'subject' => 'Promemoria: Nessun ordine ieri',
        'body'    => 'Non sono stati registrati ordini ieri (:date).',
        'action'  => 'Visualizza Ordini',
    ],

    'order_delayed' => [
        'subject' => 'Ordine #:id ritardato',
        'body'    => 'L\'ordine #:id è stato segnato come ritardato (15 giorni senza aggiornamenti).',
        'action'  => 'Visualizza Ordine',
    ],

    'daily_summary' => [
        'subject'   => 'Riepilogo ordini del :date',
        'intro'     => 'Ecco il riepilogo degli ordini del :date:',
        'total'     => 'Totale ordini: :count',
        'shipped'   => 'Ordini spediti: :count',
        'delivered' => 'Ordini consegnati: :count',
        'canceled'  => 'Ordini cancellati: :count',
    ],

    'missing_revenue' => [
        'subject' => 'Mancata registrazione entrate',
        'line'    => 'Non è stato inserito alcun ricavo per la data :date.',
        'action'  => 'Inserisci ricavi',
        'footer'  => 'Per favore aggiorna i dati quanto prima.',
        'db'      => 'Manca il ricavo per :date',
    ],

    'invoice_created' => [
        'subject' => 'Nuova fattura :type (#:id)',
        'body'    => 'È stata generata una nuova fattura di tipo :type (ID #:id).',
        'action'  => 'Visualizza Fattura',
    ],
    'invoice_paid' => [
        'subject' => 'Fattura #:id segnata come pagata',
        'body'    => 'La fattura #:id di tipo :type è stata aggiornata a pagata.',
        'action'  => 'Apri Fattura',
    ],
];
