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
                                    <input type="text" class="form-control" id="title" placeholder="Informe o título"
                                        data-bind="value: title">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="phone">Telefone</label>
                                    <input type="text" class="form-control" id="phone" placeholder="Informe o telefone"
                                        data-bind="value: phone">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="zipcode">CEP: *</label>
                                    <input type="text" id="zipcode" class="form-control" name="zipcode"
                                        data-bind="masked: zipcode, mask: '99999-999'">
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="address">Endereço: *</label>
                                    <input type="text" id="address" class="form-control" name="address"
                                        data-bind="value: address" disabled>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="number">Número: *</label>
                                    <input type="text" id="number" class="form-control" name="number"
                                        data-bind="value: number">
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="form-group">
                                    <label for="district">Bairro: *</label>
                                    <input type="text" id="district" class="form-control" name="district"
                                        data-bind="value: district" disabled>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="uf">UF: *</label>
                                    <input type="text" id="uf" class="form-control" name="uf" data-bind="value: uf"
                                        disabled>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="city">Cidade: *</label>
                                    <input type="text" id="city" class="form-control" name="city"
                                        data-bind="value: city" disabled>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="complement">Complemento: </label>
                                    <input type="text" id="complement" class="form-control" name="complement"
                                        data-bind="value: complement">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="workHours">Horário de funcionamento</label>
                                    <input type="text" class="form-control" id="workHours"
                                        placeholder="Informe o horário de funcionamento" data-bind="value: workHours">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="mail">E-mail</label>
                                    <input type="text" class="form-control" id="mail" placeholder="Informe o e-mail"
                                        data-bind="value: mail">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="iframe">Iframe de Localização (width: 1110 | height: 420)</label>
                                    <input type="text" class="form-control" id="iframe"
                                        placeholder="Informe o iframe de localização" data-bind="value: iframe">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="description">Breve descrição da empresa</label>
                                    <textarea class="form-control" id="description" rows="8"
                                        placeholder="Informe a descrição" data-bind="value: description"></textarea>
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
    homeCompany.urlSearchZipcode = "{{ route('site.zipcode.index') }}";

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
        self.zipcode = ko.observable(obj.com_zipcode).extend({
            required: {
                params: true,
                message: 'O campo cep é obrigatório'
            },
            maxLength: {
                params: 9,
                message: 'O campo cep não pode ter mais que 9 caracteres.'
            }
        });
        self.address = ko.observable(obj.com_address).extend({
            required: {
                params: true,
                message: 'O campo endereço é obrigatório'
            },
            maxLength: {
                params: 255,
                message: 'O campo endereço não pode ter mais que 255 caracteres.'
            }
        });
        self.number = ko.observable(obj.com_number).extend({
            required: {
                params: true,
                message: 'O campo numero é obrigatório'
            },
            maxLength: {
                params: 11,
                message: 'O campo numero não pode ter mais que 11 caracteres.'
            }
        });
        self.district = ko.observable(obj.com_district).extend({
            required: {
                params: true,
                message: 'O campo bairro é obrigatório'
            },
            maxLength: {
                params: 255,
                message: 'O campo bairro não pode ter mais que 255 caracteres.'
            }
        });
        self.city = ko.observable(obj.com_city).extend({
            required: {
                params: true,
                message: 'O campo cidade é obrigatório'
            },
            maxLength: {
                params: 255,
                message: 'O campo cidade não pode ter mais que 255 caracteres.'
            }
        });
        self.complement = ko.observable(obj.com_complement).extend({
            maxLength: {
                params: 255,
                message: 'O campo complemento não pode ter mais que 255 caracteres.'
            }
        });
        self.uf = ko.observable(obj.com_uf).extend({
            maxLength: {
                params: 2,
                message: 'O campo UF não pode ter mais que 2 caracteres.'
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

        self.zipcode.subscribe(function(val) {
            if(val) {
                if (val.toString().replace(/[^0-9]/g, '') == '') {
                    self.zipcode(null);
                } else {
                    self.searchZipcode();
                }
            }
        });

        self.searchZipcode = function()
        {
            let
            zipcode = self.zipcode().toString().replace(/\D/g, ''),
            callback = function(data) {
                if(!data.status) {
                    Alert.error(data.message);
                    return;
                }
                self.address(data.response.street);
                self.district(data.response.district);
                self.city(data.response.city);
                self.uf(data.response.state);

            };
            base.post(homeCompany.urlSearchZipcode + '/' + zipcode, null, callback, 'GET');
        }

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
            formData.append('phone', self.phone());
            formData.append('mail', self.mail());
            formData.append('workHours', self.workHours());
            formData.append('iframe', self.iframe());
            formData.append('zipcode',  self.zipcode().toString().replace(/[^0-9]/g, ''));
            formData.append('address',  self.address());
            formData.append('number',  self.number());
            formData.append('district',  self.district());
            formData.append('city',  self.city());
            formData.append('complement',  self.complement());
            formData.append('uf',  self.uf());

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
