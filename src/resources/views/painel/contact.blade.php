@extends('painel.baseTemplate')

@section('content')
<div class="content" id="koContact">
    <!-- ko with: contact -->
    <div class="row" data-bind="visible: $root.contact" style="display: none">
        <div class="col-lg-12">
            <div class="card card-default">
                <div class="card-header card-header-border-bottom">
                    <h2 class="col-lg-12 pl-0">Enviar e-mail de resposta</h2>
                </div>
                <div class="card-body">
                    <form>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="email">E-mail</label>
                                    <input type="text" class="form-control" id="email" placeholder="Informe o email" data-bind="value: email" disabled>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="subject">Assunto</label>
                                    <input type="text" class="form-control" id="subject" placeholder="Informe o assunto" data-bind="value: subject" disabled>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="message">Mensagem</label>
                                    <textarea class="form-control" id="message" rows="3" data-bind="value: message" disabled></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="answer">Responder</label>
                                    <textarea class="form-control" id="answer" rows="3" placeholder="Informe a resposta" data-bind="value: answer"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="form-footer pt-4 pt-5 mt-4 border-top">
                            <button type="submit" class="btn btn-secondary btn-default" data-bind="click: cancel">Cancelar</button>
                            <button type="submit" class="btn btn-primary btn-default" data-bind="click: save">Enviar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- /ko -->
    <div class="row" data-bind="visible: !contact()" style="display: none">
        <div class="col-lg-12">
            <div class="card card-default">
                <div class="card-header card-header-border-bottom">
                    <h2>Listagem de Mensagens</h2>
                </div>
                <div class="card-body">
                    <table class="table table-hover ">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Nome</th>
                                <th scope="col">E-mail</th>
                                <th scope="col">Assunto</th>
                                <th scope="col">Mensagem</th>
                                <th scope="col">Data</th>
                                <th scope="col">Ações</th>
                            </tr>
                        </thead>
                        <tbody data-bind="foreach: contacts">
                            <tr data-bind="style: { 'background-color': answer() ? '#b2ffb2' : '' }">
                                <td scope="row" data-bind="text: id"></td>
                                <td><span data-bind="text: name"></span></td>
                                <td><span data-bind="text: email"></span></td>
                                <td><span data-bind="text: subject"></span></td>
                                <td><span data-bind="text: message"></span></td>
                                <td><span data-bind="text: base.monthStringEn(date)"></span></td>
                                <td class="center">
                                    <i class="mdi mdi-eye" aria-hidden="true" data-bind="click: show"></i>
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

    function contact(){[native/code]}
    contact.urlData = "{{ route('api.contacts.index') }}";
    contact.urlAnswer = "{{ route('api.contacts.answer') }}";
    contact.urlDelete = "{{ route('api.contacts.destroy') }}";

    contact.Contact = function(obj)
    {
        let self = this;
        self.origin = obj;

        self.id = ko.observable(obj.con_id);
        self.name = obj.con_name;
        self.email = obj.con_email;
        self.subject = obj.con_subject;
        self.message = obj.con_message;
        self.date = obj.created_at;
        self.answer = ko.observable(obj.con_answer).extend({
            required: {
                params: true,
                message: 'O campo resposta é obrigatório'
            }
        });
        self.errors = ko.validation.group(self);

        self.show = function()
        {
            contact.viewModel.contact(self);
        }

        self.remove = function(item)
        {
            Alert.confirm(
                'Você realmente quer deletar este item?',
                'Exclusão',
                function(resp) {

                    if (!resp) return;

                    let data = {
                        id : item.id,
                    },
                    callback = function(data)
                    {
                        if(!data.status) {
                            Alert.error(data.message);
                            return;
                        } else {
                            contact.viewModel.contacts.remove(item);
                            Alert.success(data.message);
                        }
                    };
                    base.post(contact.urlDelete, data, callback, 'DELETE');
                }
            );
        }

        self.save = function()
        {
            if (self.errors().length > 0) {
                Alert.error(self.errors().join(', '));
                return;
            }

            let params = {
                id: self.id(),
                answer: self.answer()
            },
            callback = function(data)
            {
                if(!data.status) {
                    Alert.error(data.message);
                    return;
                }

                contact.viewModel.contact(null);
                Alert.success('Resposta enviada com sucesso!');
            };
            base.post(contact.urlAnswer, params, callback);
        }

        self.goBackData = function(item)
        {
            let dataOld = new contact.Contact(self.origin),
                position = contact.viewModel.contacts.indexOf(item);
                contact.viewModel.contacts.splice(position,1,dataOld);
        }

        self.cancel = function(item)
        {
            self.goBackData(item);
            contact.viewModel.contact(null);
        }
    }

    contact.ViewModel = function()
    {
        let self = this;

        self.contact = ko.observable();
        self.contacts = ko.observableArray();
        self.init = function()
        {
            let callback = function(data) {
                if (data.status) {
                    self.contacts(ko.utils.arrayMap(data.response, function(obj) {
                        return new contact.Contact(obj);
                    }));
                }
            };
            base.post(contact.urlData, null, callback, 'GET');
        };
        self.init();
    }

	contact.viewModel = new contact.ViewModel();
    ko.applyBindings(contact.viewModel, document.getElementById('koContact'));

</script>
@endsection
