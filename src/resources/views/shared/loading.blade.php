<style>
    .masterloading { position: fixed; top: 0; min-width: 100vw !important; min-height: 100vh !important; background-color: rgba(55, 55, 55, 0.5); z-index: 99999; }
    .masterloading-img { position: fixed; top: 50%; left: 50%; width: 70px; }
</style>
<script>$(function(){ko.applyBindings(base.loading, document.getElementById('masterloading'))})</script>
<div class="masterloading" id="masterloading" data-bind="visible:show" style="display:none;">
    <img class="masterloading-img" src="{{ asset('assets/admin/img/giphy.gif') }}" alt="masterloading" />
</div>
