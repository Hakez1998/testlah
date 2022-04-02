@component('mail::message')
# Hi {{ $user }}, 

{{ $sender}} has invited you to join project: {{ $title }} on Testlah.<br>
Click on the button below to get started.

@component('mail::button', ['url' => $url])
Login
@endcomponent

{{ config('mail.from.name') }},<br>
{{ config('app.mail') }}
@endcomponent
