<!DOCTYPE HTML>
<html lang="{{ App::getLocale() }}" class="no-js">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <meta name="robots" content="index, follow">
    <meta name="revisit-after" content="1 day">
    <meta name="language" content="Portuguese">
    <meta name="generator" content="N/A">
    <meta name="format-detection" content="telephone=no">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    @yield('custom_tag')

    @include('shared.head')

    @yield('custom_head')

    <script type="text/javascript">
        let
            company = {!! isset($company) ? $company : 'null' !!},
            tokenApi = "{{ $tokenApi ?? '' }}",
            urlKeepAlive = "{{ route('api.keep.alive') }}";
        // setInterval(function() {
        //     keepAlive();
        // }, 240000); // mantém a sessão ativa a cada 4 minutos

        function keepAlive() {
            let callback = function(data) {
                if (data.status) {
                    console.log('Keep Alive');
                    tokenApi = data.token;
                }
            };
            base.post(urlKeepAlive, null, callback, 'GET', true);
        }
    </script>
</head>
@include('components.container')
@yield('maincontainer')
@include('shared.footer')
@include('shared.loading')

</html>
