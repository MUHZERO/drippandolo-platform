@php($rec = $getRecord())
<div class="flex flex-col">
    <span class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $rec?->customer_name }}</span>
    <span class="text-xs text-gray-600 dark:text-gray-400">{{ $rec?->customer_phone }}</span>
</div>
