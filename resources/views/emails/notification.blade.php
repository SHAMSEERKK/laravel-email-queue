@component('mail::message')
# {{ $emailDetails['subject']}}

{{ $emailDetails['body']}}


Thanks,
{{ config('app.name') }}
@endcomponent
