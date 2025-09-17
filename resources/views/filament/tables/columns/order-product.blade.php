<div class="flex items-center gap-3">
    @php($rec = $getRecord())
    @if($rec && $rec->product_image)
        <img src="{{ asset($rec->product_image) }}" alt="" class="h-8 w-8 rounded object-cover"/>
    @else
        <div class="h-8 w-8 rounded bg-gray-200 dark:bg-gray-700"></div>
    @endif
    <div class="min-w-0">
        <div class="text-sm font-medium text-gray-900 dark:text-gray-100 truncate">
            {{ $rec?->product_name }}
        </div>
        <div class="text-xs text-gray-500 dark:text-gray-400 truncate">
            {{ $rec?->price }} €
        </div>
    </div>
</div>
