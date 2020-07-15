@component('mail::message')
# Introduction

The body of your message.

@component('mail::button', ['url' => route('verify', $user->verification_token)])
Button Text
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
