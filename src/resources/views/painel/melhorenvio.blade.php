@extends('painel.baseTemplate')

@section('content')
<div class="content" id="koAccountME">
    <!-- ko with: accountME -->
    <div class="row" data-bind="visible: $root.accountME" style="display: none">
        <div class="col-lg-12">
            <div class="card card-default">
                <div class="card-header card-header-border-bottom">
                    <h2>Gerenciar minha conta do Mercado Livre</h2>
                </div>
                <div class="card-body">
                    <form>
                        {{-- <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="title">Título</label>
                                    <input type="text" class="form-control" id="title" placeholder="Informe o título"
                                        data-bind="value: title">
                                </div>
                            </div>
                        </div>
                        <div class="form-footer pt-4 pt-5 mt-4 border-top">
                            <button type="submit" class="btn btn-secondary btn-default"
                                data-bind="click: cancel">Cancelar</button>
                            <button type="submit" class="btn btn-primary btn-default"
                                data-bind="click: save">Enviar</button>
                        </div> --}}
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- /ko -->
    <div class="row" data-bind="visible: !accountME()" style="display: none">
        @if(session('success'))
        <div class="col-md-12">
            <div class="alert alert-success" style="opacity: inherit; right: 0;">
                {{ session('success') }}
            </div>
        </div>
        @endif
        <div class="col-lg-4 mb-2">
            <a href="{{$authorization}}" class="btn btn-primary btn-default">Sincronizar Nova Conta do Melhor Envio</a>
        </div>
    </div>
</div>
@endsection

@section('custom_script')
<script type="text/javascript">
    function homeAccount(){[native/code]}

    homeAccount.ML = function(obj)
    {
        let self = this;
    }

    homeAccount.ViewModel = function()
    {
        let self = this;
        self.accountME = ko.observable();
    }

	homeAccount.viewModel = new homeAccount.ViewModel();
    ko.applyBindings(homeAccount.viewModel, document.getElementById('koAccountME'));

</script>
@endsection