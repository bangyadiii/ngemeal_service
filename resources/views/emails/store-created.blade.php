<x-mail::message>
# Store Created
 
Your store has been created successfully with the name {{ store->name  }}
 
<x-mail::button :url="$url">
Post your best menu right now
</x-mail::button>
 
Thanks,<br>
{{ config('app.name') }}
</x-mail::message>