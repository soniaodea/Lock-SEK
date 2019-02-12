@component('mail::layout')
    {{-- Header --}}
    @slot('header')
        @component('mail::header', ['url' => config('app.url')])
            <img src="https://i.imgur.com/jL1LdCd.png" width="200" alt="logo LockSEK">
        @endcomponent
    @endslot

    {{-- Body --}}
    {{$slot}}
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
            © {{ date('Y') }} LockSEK. @lang('Todos los derechos reservados.')
        @endcomponent
    @endslot
@endcomponent
