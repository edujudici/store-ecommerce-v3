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
                        <form>
                            <div class="row">
                                <div class="col-lg-4" data-bind="visible: id">
                                    <div class="form-group">
                                        <label for="uuid">UUID</label>
                                        <input type="text" class="form-control" id="uuid"
                                            placeholder="Informe o uuid" data-bind="value: id" disabled>
                                    </div>
                                </div>
                                <div data-bind="class: id() ? 'col-lg-4' : 'col-lg-6'">
                                    <div class="form-group">
                                        <label for="name">Nome</label>
                                        <input type="text" class="form-control" id="name"
                                            placeholder="Informe o nome" data-bind="value: name">
                                    </div>
                                </div>
                                <div data-bind="class: id() ? 'col-lg-4' : 'col-lg-6'">
                                    <div class="form-group">
                                        <label for="surname">Sobrenome</label>
                                        <input type="text" class="form-control" id="surname"
                                            placeholder="Informe o sobrenome" data-bind="value: surname">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="email">E-mail</label>
                                        <input type="text" class="form-control" id="email"
                                            placeholder="Informe o e-mail" data-bind="value: email">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="role">Tipo</label>
                                        <select class="form-control"
                                            data-bind="
                                            options: $root.roles,
                                            optionsText: 'title',
                                            optionsValue: 'id',
                                            value: role">
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row" data-bind="visible: !id()">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="password">Senha</label>
                                        <input id="password" type="password" class="input-lg form-control"
                                            placeholder="Informe a senha" name="password" required
                                            autocomplete="new-password" data-bind="value: password">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="password-confirm">Confirmação de Senha</label>
                                        <input id="password-confirm" type="password" class="input-lg form-control"
                                            placeholder="Informe a confirmaão de senha" name="password_confirmation"
                                            required autocomplete="new-password" data-bind="value: passwordConfirmation">
                                    </div>
                                </div>
                            </div>

                            <div class="form-footer pt-4 pt-5 mt-4 border-top">
                                <button type="submit" class="btn btn-secondary btn-default"
                                    data-bind="click: cancel">Cancelar</button>
                                <button type="submit" class="btn btn-primary btn-default"
                                    data-bind="click: save">Enviar</button>
                            </div>
                        </form>

                        <!-- ko if: id() && addresses && addresses.length > 0 -->
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
                    </div>
                </div>
            </div>
        </div>
        <!-- /ko -->
        <div class="row" data-bind="visible: !user()" style="display: none">
            <div class="col-lg-4 mb-2">
                <button type="button" class="btn btn-primary btn-default" data-bind="click: addUser">Novo Usuário</button>
            </div>
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
                                    <td class="d-none d-md-table-cell" scope="row" data-bind="text: id"></td>
                                    <td><span data-bind="text: completeName"></span></td>
                                    <td class="d-none d-md-table-cell"><span data-bind="text: email"></span></td>
                                    <td><span data-bind="text: role"></span></td>
                                    <td class="center">
                                        <i class="mdi mdi-pencil" aria-hidden="true" data-bind="click: edit"></i>
                                        <i class="mdi mdi-delete" aria-hidden="true" data-bind="click: remove"></i>
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
        function homeUser() {
            [native / code]
        }
        homeUser.urlData = "{{ route('api.users.index') }}";
        homeUser.urlSave = "{{ route('api.users.store') }}";
        homeUser.urlDelete = "{{ route('api.users.destroy') }}";

        homeUser.User = function(obj) {
            let self = this;
            self.origin = obj;

            self.id = ko.observable(obj.uuid);
            self.name = ko.observable(obj.name).extend({
                required: {
                    params: true,
                    message: 'O campo nome é obrigatório'
                },
                maxLength: {
                    params: 255,
                    message: 'O campo nome não pode ter mais que 255 caracteres.'
                }
            });
            self.surname = ko.observable(obj.surname).extend({
                required: {
                    params: true,
                    message: 'O campo sobrenome é obrigatório'
                },
                maxLength: {
                    params: 255,
                    message: 'O campo sobrenome não pode ter mais que 255 caracteres.'
                }
            });
            self.email = ko.observable(obj.email).extend({
                required: {
                    params: true,
                    message: 'O campo e-mail é obrigatório'
                },
                maxLength: {
                    params: 255,
                    message: 'O campo e-mail não pode ter mais que 255 caracteres.'
                }
            });
            self.role = ko.observable(obj.role).extend({
                required: {
                    params: true,
                    message: 'O campo role é obrigatório'
                }
            });
            self.password = ko.observable().extend({
                validation: {
                    validator: function(val, otherVal) {
                        if (!self.id()) {
                            return val && val.length <= 255;
                        }
                        return true;
                    },
                    message: 'O campo senha é obrigatório e não pode ter mais que 255 caracteres.'
                }
            });
            self.passwordConfirmation = ko.observable().extend({
                validation: {
                    validator: function(val, otherVal) {
                        if (!self.id()) {
                            return val && val.length <= 255;
                        }
                        return true;
                    },
                    message: 'O campo confirmação de senha é obrigatório e não pode ter mais que 255 caracteres.'
                }
            });

            self.addresses = obj.addresses;

            self.errors = ko.validation.group(self);

            self.edit = function() {
                homeUser.viewModel.user(self);
            }

            self.remove = function(item) {
                Alert.confirm(
                    'Você realmente quer deletar este item?',
                    'Exclusão',
                    function(resp) {

                        if (!resp) return;

                        let data = {
                                id: item.id,
                            },
                            callback = function(data) {
                                if (!data.status) {
                                    Alert.error(data.message);
                                    return;
                                } else {
                                    homeUser.viewModel.users.remove(item);
                                    Alert.success(data.message);
                                }
                            };
                        base.post(homeUser.urlDelete, data, callback, 'DELETE');
                    }
                );
            }

            self.save = function() {
                if (self.errors().length > 0) {
                    Alert.error(self.errors().join(', '));
                    return;
                }

                let params = {
                        'id': self.id(),
                        'name': self.name(),
                        'surname': self.surname(),
                        'email': self.email(),
                        'role': self.role(),
                        'password': self.password(),
                        'password_confirmation': self.passwordConfirmation()
                    },
                    callback = function(data) {
                        if (!data.status) {
                            Alert.error(data.message);
                            return;
                        }

                        self.id(data.response.uuid);
                        self.origin = data.response;
                        homeUser.viewModel.user(null);
                        Alert.success('Item salvo com sucesso!');
                    };
                base.post(homeUser.urlSave, params, callback);
            }

            self.goBackData = function(item) {
                let dataOld = new homeUser.User(self.origin),
                    position = homeUser.viewModel.users.indexOf(item);
                homeUser.viewModel.users.splice(position, 1, dataOld);
            }

            self.cancel = function(item) {
                if (!item.id()) {
                    homeUser.viewModel.users.remove(item);
                } else {
                    self.goBackData(item);
                }
                homeUser.viewModel.user(null);
            }

            self.completeName = ko.computed(function() {
                return self.name() + ' ' + self.surname();
            });
        }

        homeUser.ViewModel = function() {
            let self = this;

            self.user = ko.observable();
            self.users = ko.observableArray();
            self.roles = ko.observableArray();

            self.getRoles = function() {
                return [{
                        'id': 'admin',
                        'title': 'Admin'
                    },
                    {
                        'id': 'shopper',
                        'title': 'Shopper'
                    },
                    {
                        'id': 'api',
                        'title': 'Api'
                    },
                ];
            }

            self.init = function() {
                self.roles(self.getRoles())

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

            self.addUser = function() {
                let user = new homeUser.User({});
                self.users.push(user);
                user.edit();
            }
        }

        homeUser.viewModel = new homeUser.ViewModel();
        ko.applyBindings(homeUser.viewModel, document.getElementById('koUser'));
    </script>
@endsection
