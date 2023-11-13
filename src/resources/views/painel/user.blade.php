@extends('painel.baseTemplate')

@section('content')
<div class="content" id="koUser">
    <!-- ko with: user -->
    <div class="row" data-bind="visible: $root.user" style="display: none">
        <div class="col-lg-12">
            <div class="card card-default">
                <div class="card-header card-header-border-bottom">
                    <h2>Detalhes do Usuário</h2>
                </div>
                <div class="card-body">

                    <p data-bind="text: uuid"></p>
                    <p data-bind="text: name"></p>
                    <p data-bind="text: email"></p>
                    <p data-bind="text: role"></p>

                    <!-- ko if: addresses.length > 0 -->
                    <hr>
                    <br>

                    <p><b>Dados de Endereço</b></p><br>
                    <!-- ko foreach: addresses -->
                    <p><b>Nome:</b> <span data-bind="text: adr_name + ' ' + adr_surname"></span></p>
                    <p><b>Telefone:</b> <span data-bind="text: base.mascaraTelefone(adr_phone)"></span></p>
                    <p><b>Endereço:</b> <span data-bind="text: adr_address + ', ' + adr_number"></span></p>
                    <p><b>Bairro:</b> <span data-bind="text: adr_district"></span></p>
                    <p><b>Complemento:</b> <span data-bind="text: adr_complement"></span></p>
                    <p><b>Cidade:</b> <span data-bind="text: adr_city + ' - ' + adr_uf"></span></p>
                    <p><b>CEP:</b> <span data-bind="text: base.mascaraCep(adr_zipcode)"></span></p>
                    <br>
                    <br>
                    <!-- /ko -->
                    <!-- /ko -->

                    <div class="form-footer pt-4 pt-5 mt-4 border-top">
                        <button type="submit" class="btn btn-secondary btn-default"
                            data-bind="click: cancel">Cancelar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /ko -->
    <div class="row" data-bind="visible: !user()" style="display: none">
        <div class="col-lg-12">
            <div class="card card-default">
                <div class="card-header card-header-border-bottom">
                    <h2>Listagem de Usuários</h2>
                </div>
                <div class="card-body">
                    <table class="table table-hover ">
                        <thead>
                            <tr>
                                <th class="d-none d-md-table-cell" scope="col">#</th>
                                <th scope="col">Nome</th>
                                <th class="d-none d-md-table-cell" scope="col">E-mail</th>
                                <th scope="col">Tipo</th>
                                <th scope="col">Ações</th>
                            </tr>
                        </thead>
                        <tbody data-bind="foreach: users">
                            <tr>
                                <td class="d-none d-md-table-cell" scope="row" data-bind="text: uuid"></td>
                                <td><span data-bind="text: name"></span></td>
                                <td class="d-none d-md-table-cell"><span data-bind="text: email"></span></td>
                                <td><span data-bind="text: role"></span></td>
                                <td class="center">
                                    <i class="mdi mdi-eye" aria-hidden="true" data-bind="click: show"></i>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('custom_script')
<script type="text/javascript">
    function homeUser(){[native/code]}
    homeUser.urlData = "{{ route('api.users.index') }}";

    homeUser.User = function(obj)
    {
        let self = this;

        self.uuid = obj.uuid;
        self.name = obj.name + ' ' + (obj.surname ? obj.surname : '');
        self.email = obj.email;
        self.role = obj.role;
        self.addresses = obj.addresses;

        self.show = function()
        {
            homeUser.viewModel.user(self);
        }

        self.cancel = function(item)
        {
            homeUser.viewModel.user(null);
        }
    }

    homeUser.ViewModel = function()
    {
        let self = this;

        self.user = ko.observable();
        self.users = ko.observableArray();
        self.init = function()
        {
            let callback = function(data) {
                if (data.status) {
                    self.users(ko.utils.arrayMap(data.response, function(obj) {
                        return new homeUser.User(obj);
                    }));
                }
            };
            base.post(homeUser.urlData, null, callback, 'GET');
        };
        self.init();
    }

	homeUser.viewModel = new homeUser.ViewModel();
    ko.applyBindings(homeUser.viewModel, document.getElementById('koUser'));

</script>
@endsection