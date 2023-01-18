@extends('painel.baseTemplate')

@section('content')
<div class="content-wrapper">
    @if(auth()->user()->is_admin)
        <div class="content" id="koHome">
            <!-- Top Statistics -->
            <div class="row">
                <div class="col-xl-3 col-sm-6">
                    <div class="card card-mini mb-4">
                        @include('painel.partials.template-painel-total-shoppers-area')
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6">
                    <div class="card card-mini mb-4">
                        @include('painel.partials.template-painel-total-revenue-area')
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-8 col-md-12">
                    <!-- Sales Graph -->
                    @include('painel.partials.template-painel-total-order-area')
                </div>
                <div class="col-xl-4 col-md-12">
                    <!-- Doughnut Chart -->
                    @include('painel.partials.template-painel-orders-overview-area')
                </div>
            </div>

            <div class="row">
                <div class="col-xl-4 col-12">
                    @include('painel.partials.template-painel-total-notifications-area')
                </div>
                <div class="col-xl-8 col-12">
                    @include('painel.partials.template-painel-notifications-area')
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <!-- Recent Order Table -->
                    @include('painel.partials.template-painel-recent-orders-area')
                </div>
            </div>

            <div class="row">
                <div class="col-xl-4 col-12">
                    <!-- New Customers -->
                    @include('painel.partials.template-painel-new-shoppers-area')
                </div>
                <div class="col-xl-8 col-12">
                    <!-- Top Products -->
                    @include('painel.partials.template-painel-top-products-area')
                </div>
            </div>
        </div>
    @endif
</div>
@endsection

@section('custom_script')

    <script type="text/javascript">

        function ViewModel()
        {
            var self = this;
            self.setData = function()
            {
            }
        }

        var viewModel = new ViewModel();
            viewModel.setData();
        ko.applyBindings(viewModel, document.getElementById('koHome'));
    </script>
@endsection
