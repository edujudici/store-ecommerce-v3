@component('mail::layout')
{{-- Header --}}
@slot('header')
    @component('mail::header', ['url' => config('app.url')])
        {{ config('app.name') }}
        <img src="{{ asset('assets/site/img/user-logo.png') }}" style="margin: auto; display: block; width: 60px; margin-top: 10px"/>
    @endcomponent
@endslot

{{-- Body --}}
## Olá, {{ $details['name'] }}!
Agradecemos pelo contato.

{{ $details['body'] }}

<br>
<br>
<small>Não responder esse e-mail, em caso de dúvidas entre em contato pelo nosso site.</small>
@component('mail::button', ['url' => route('site.contact.index')])
    Registrar novo contato
@endcomponent

{{-- Subcopy --}}
@isset($subcopy)
    @slot('subcopy')
        @component('mail::subcopy')
            {{ $subcopy }}
        @endcomponent
    @endslot
@endisset

{{-- Footer --}}
@slot('footer')
    @component('mail::footer')
        &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
    @endcomponent
@endslot
@endcomponent
