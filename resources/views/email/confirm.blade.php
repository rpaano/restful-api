@component('mail::message')
# Introduction

The body of your message.

@component('mail::button', ['url' => route('verify', $user->verification_token)])
Button Text
@endcomponent
user => {{ route('verify', $user->verification_token) }}
Thanks,<br>
{{ config('app.name') }}
@endcomponent
