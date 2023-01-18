@extends('painel.baseTemplate')

@section('content')
<div class="content" id="koCompany">
    <div class="row" data-bind="with: company">
        <div class="col-lg-12">
            <div class="card card-default">
                <div class="card-header card-header-border-bottom">
                    <h2>Cadastro da Empresa</h2>
                </div>
                <div class="card-body">
                    <form>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <upload-file params="file: file, image: image, size: [380, 50]"></upload-file>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="title">Título</label>
                                    <input type="text" class="form-control" id="title" placeholder="Informe o título" data-bind="value: title">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="phone">Telefone</label>
                                    <input type="text" class="form-control" id="phone" placeholder="Informe o telefone" data-bind="value: phone">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="address">Endereço</label>
                                    <input type="text" class="form-control" id="address" placeholder="Informe o endereço" data-bind="value: address">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="workHours">Horário de funcionamento</label>
                                    <input type="text" class="form-control" id="workHours" placeholder="Informe o horário de funcionamento" data-bind="value: workHours">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="mail">E-mail</label>
                                    <input type="text" class="form-control" id="mail" placeholder="Informe o e-mail" data-bind="value: mail">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="iframe">Iframe de Localização (width: 1110 | height: 420)</label>
                                    <input type="text" class="form-control" id="iframe" placeholder="Informe o iframe de localização" data-bind="value: iframe">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="description">Descrição</label>
                                    <textarea class="form-control" id="description" rows="8" placeholder="Informe a descrição" data-bind="value: description"></textarea>
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
</div>
@endsection

@section('custom_script')
<script type="text/javascript">

    function homeCompany(){[native/code]}
    homeCompany.urlData = "{{ route('api.companies.index') }}";
    homeCompany.urlSave = "{{ route('api.companies.store') }}";

    homeCompany.Company = function(obj)
    {
        let self = this;
        self.origin = obj;

        self.id = ko.observable(obj.com_id);
        self.title = ko.observable(obj.com_title).extend({
            required: {
                params: true,
                message: 'O campo título é obrigatório'
            }
        });
        self.description = ko.observable(obj.com_description).extend({
            required: {
                params: true,
                message: 'O campo descrição é obrigatório'
            }
        });
        self.address = ko.observable(obj.com_address).extend({
            maxLength: {
                params: 255,
                message: 'O campo endereço não pode ter mais que 255 caracteres.'
            }
        });
        self.address = ko.observable(obj.com_address).extend({
            maxLength: {
                params: 255,
                message: 'O campo endereço não pode ter mais que 255 caracteres.'
            }
        });
        self.phone = ko.observable(obj.com_phone).extend({
            maxLength: {
                params: 255,
                message: 'O campo telefone não pode ter mais que 255 caracteres.'
            }
        });
        self.mail = ko.observable(obj.com_mail).extend({
            maxLength: {
                params: 255,
                message: 'O campo e-mail não pode ter mais que 255 caracteres.'
            }
        });
        self.workHours = ko.observable(obj.com_work_hours).extend({
            maxLength: {
                params: 255,
                message: 'O campo horário de funcionamento não pode ter mais que 255 caracteres.'
            }
        });
        self.iframe = ko.observable(obj.com_iframe);
        self.image = ko.observable(obj.com_image
            ? base.displayImage(obj.com_image)
            : null);
        self.file  = ko.observableArray().extend({
            required: {
                message: "O campo imagem é obrigatório",
                onlyIf: function() {
                    return !self.id();
                }
            }
        });
        self.errors = ko.validation.group(self);

        self.save = function()
        {
            if (self.errors().length > 0) {
                Alert.error(self.errors().join(', '));
                return;
            }

            if (!self.id()) {
                let hasImageError = base.imagesAllCropped(self.file());
                if (hasImageError) {
                    Alert.error('Uma ou mais imagem não foram cortadas');
                    return;
                }
            }

            let formData = new FormData();
            if (self.id()) {
                formData.append('id', self.id());
            }
            if (self.file().length > 0) {
                formData.append('file', self.file()[0].fileCropped);
            }
            formData.append('title', self.title());
            formData.append('description', self.description());
            formData.append('address', self.address());
            formData.append('phone', self.phone());
            formData.append('mail', self.mail());
            formData.append('workHours', self.workHours());
            formData.append('iframe', self.iframe());

            let callback = function(data)
            {
                if(!data.status) {
                    Alert.error(data.message);
                    return;
                }

                self.id(data.response.com_id);
                self.origin = data.response;
                Alert.success('Item salvo com sucesso!');
            };
            base.postImage(homeCompany.urlSave, formData, callback);
        }

        self.goBackData = function(item)
        {
            let dataOld = new homeCompany.Company(self.origin);
            homeCompany.viewModel.company(dataOld);
        }

        self.cancel = function(item)
        {
            self.goBackData(item);
        }
    }

    homeCompany.ViewModel = function()
    {
        let self = this;

        self.company = ko.observable();
        self.init = function()
        {
            let callback = function(data) {
                if (data.status) {
                    setTimeout(function() {
                        self.company(new homeCompany.Company(data.response || {}));
                    }, 500)
                }
            };
            base.post(homeCompany.urlData, null, callback, 'GET');
        };
        self.init();
    }

	homeCompany.viewModel = new homeCompany.ViewModel();
    ko.applyBindings(homeCompany.viewModel, document.getElementById('koCompany'));

</script>
@endsection
