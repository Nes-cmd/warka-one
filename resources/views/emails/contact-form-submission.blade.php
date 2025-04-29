<x-mail::message>
# New Contact Form Submission

**From:** {{ $contact }}  
**Subject:** {{ $title }}

## Message
{{ $messageContent }}

<x-mail::button :url="url('/')">
View in Admin Panel
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message> 