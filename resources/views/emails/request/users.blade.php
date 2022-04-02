@component('mail::message')
# Request to become administrator,

Hi, {{ $user }} want to become administrator.

@component('mail::button', ['url' => $url])
Approve Now!
@endcomponent

{{ config('mail.from.name')  }},<br>
{{ config('app.mail') }}
@endcomponent
