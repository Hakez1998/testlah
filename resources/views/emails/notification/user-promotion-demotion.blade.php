@component('mail::message')
# Hi {{ $user }}, 

{{ $message }}

{{ config('mail.from.name')  }},<br>
{{ config('app.mail') }}
@endcomponent
