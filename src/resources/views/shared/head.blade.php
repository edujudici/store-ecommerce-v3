<!--
    CSS
    ============================================= -->
    <link rel="stylesheet" href="{{asset('assets/Alertjs/css/Alert.css')}}">

<!--
    Javascript
    ============================================= -->
<!-- jQuery Plugins -->
<script type="text/javascript" src="{{asset('assets/js/jquery.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/js/main.js')}}"></script>

{{--  <script src="{{asset('assets/js/socket.io.js')}}"></script>  --}}
<script src="{{ asset('assets/knockoutjs-3.5.0/knockout-3.5.0.js') }}"></script>
<script src="{{ asset('assets/knockoutjs-3.5.0/knockout.validation.js') }}"></script>
<script src="{{ asset('assets/knockoutjs-3.5.0/knockout-custom-bindings.js') }}"></script>

<script src="{{ Route('dynamicjs.base.js') }}"></script>
<script src="{{asset('assets/Alertjs/js/Alert.js')}}"></script>

<script>
    let GLOBAL_PATH_LOADING_GIF_KNOCKOUT = '{{ asset('assets/admin/img/giphy.gif') }}';
</script>
