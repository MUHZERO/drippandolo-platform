<x-filament::page>
    <div class="prose max-w-none dark:prose-invert">
        <h1 class="mb-2">{{ __('resources.webhooks.docs.title') }}</h1>
        <p class="mt-0">{{ __('resources.webhooks.docs.intro') }}</p>

        <x-filament::section class="mt-6">
            <x-slot name="heading">{{ __('resources.webhooks.docs.endpoint') }}</x-slot>
            <div class="text-sm">
                <div><strong>{{ __('resources.webhooks.docs.method') }}:</strong> <code>POST</code></div>
                <div><strong>URL:</strong> <code>/api/webhooks/chatbot/order-status</code></div>
                <div class="mt-2">
                    <strong>{{ __('resources.webhooks.docs.auth') }}:</strong>
                    <div>{{ __('resources.webhooks.docs.auth_desc') }}</div>
                    <pre class="mt-2"><code>X-Webhook-Token: &lt;{{ __('resources.webhooks.docs.token_placeholder') }}&gt;</code></pre>
                </div>
            </div>
        </x-filament::section>

        <x-filament::section class="mt-6">
            <x-slot name="heading">{{ __('resources.webhooks.docs.fields') }}</x-slot>
            <ul class="text-sm">
                <li><code>query</code> — {{ __('resources.webhooks.docs.field_query') }}</li>
                <li><code>name</code> — {{ __('resources.webhooks.docs.field_name') }}</li>
                <li><code>phone</code> — {{ __('resources.webhooks.docs.field_phone') }}</li>
                <li><code>shopify_id</code> — {{ __('resources.webhooks.docs.field_shopify_id') }}</li>
                <li><code>limit</code> — {{ __('resources.webhooks.docs.field_limit') }}</li>
            </ul>
        </x-filament::section>

        <x-filament::section class="mt-6">
            <x-slot name="heading">{{ __('resources.webhooks.docs.examples') }}</x-slot>
            <div class="text-sm space-y-4">
<div>
<div class="font-semibold">{{ __('resources.webhooks.docs.example_query_title') }}</div>
<pre><code class="language-bash">curl -X POST \
  -H "Content-Type: application/json" \
  -H "X-Webhook-Token: &lt;{{ __('resources.webhooks.docs.token_placeholder') }}&gt;" \
  -d '{"query":"John","limit":5}' \
  https://your-domain.tld/api/webhooks/chatbot/order-status</code></pre>
</div>
<div>
<div class="font-semibold">{{ __('resources.webhooks.docs.example_fields_title') }}</div>
<pre><code class="language-bash">curl -X POST \
  -H "Content-Type: application/json" \
  -H "X-Webhook-Token: &lt;{{ __('resources.webhooks.docs.token_placeholder') }}&gt;" \
  -d '{"name":"John Doe","phone":"+39 320 123 4567","shopify_id":"SHP123456"}' \
  https://your-domain.tld/api/webhooks/chatbot/order-status</code></pre>
</div>
<div>
<div class="font-semibold">{{ __('resources.webhooks.docs.example_response_title') }}</div>
<pre><code class="language-json">{
  "ok": true,
  "count": 1,
  "results": [
    {
      "id": 123,
      "shopify_order_id": "SHP123456",
      "customer_name": "John Doe",
      "customer_phone": "+39 320 123 4567",
      "status": "shipped",
      "status_label": "{{ __('resources.statuses.shipped') }}",
      "tracking_number": "TRK123",
      "created_at": "2025-09-10T10:15:20Z",
      "updated_at": "2025-09-12T08:05:10Z",
      "summary": "#SHP123456 | John Doe (+39 320 123 4567) • {{ __('resources.statuses.shipped') }} • TRK: TRK123"
    }
  ]
}</code></pre>
</div>
            </div>
        </x-filament::section>
    </div>
</x-filament::page>

