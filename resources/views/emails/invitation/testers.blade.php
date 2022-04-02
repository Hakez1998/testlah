@component('mail::message')
# Hi {{ $user }}, 

{{ $sender}} has invited you to execute Project:{{ $title }} on Testlah.<br>
Click on the button below to get started.

@component('mail::button', ['url' => $url])
Execute now
@endcomponent

{{ config('mail.from.name')  }},<br>
{{ config('app.mail') }}
@endcomponent