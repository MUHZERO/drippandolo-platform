<ul class="text-xs space-y-1">
    @foreach($getState() ?? [] as $field => $change)
        <li>
            <strong>{{ __("resources.fields.$field") }}</strong>:

            @php
                $old = $change['old'] ?? '—';
                $new = $change['new'] ?? '—';

                // Translate status if this is the status field
                if ($field === 'status') {
                    $old = __("resources.statuses.$old");
                    $new = __("resources.statuses.$new");
                }

                // Format dates (like updated_at, notified_at)
                if (in_array($field, ['updated_at','created_at','notified_at']) && $old && $new) {
                    $old = \Carbon\Carbon::parse($old)->translatedFormat('d M Y H:i');
                    $new = \Carbon\Carbon::parse($new)->translatedFormat('d M Y H:i');
                }
            @endphp

            <span class="text-red-600 line-through">{{ $old }}</span>
            →
            <span class="text-green-600">{{ $new }}</span>
        </li>
    @endforeach
</ul>

