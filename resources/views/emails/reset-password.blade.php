@component('mail::message')
{{-- Header --}}
# {{ __('auth.reset_password') }}

{{ __('auth.enter_email') }}

{{-- Action Button --}}
@component('mail::button', ['url' => $url, 'color' => 'primary'])
{{ __('auth.reset_password_button') }}
@endcomponent

{{-- Expiration Notice --}}
@lang('This password reset link will expire in :count minutes.', ['count' => $count])

{{-- Manual Link --}}
@lang('If you\'re having trouble clicking the button, copy and paste the URL below into your web browser:')

<span style="word-break: break-all;">{{ $url }}</span>

{{-- Footer --}}
@lang('If you did not request a password reset, no further action is required.')

{{ __('website.common.thanks') }},<br>
**{{ config('app.name') }}**
@endcomponent
