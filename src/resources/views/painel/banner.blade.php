@extends('painel.baseTemplate')

@section('content')
<div class="content" id="koBanner">
    <!-- ko with: banner -->
    <div class="row" data-bind="visible: $root.banner" style="display: none">
        <div class="col-lg-12">
            <div class="card card-default">
                <div class="card-header card-header-border-bottom">
                    <h2>Cadastro de Banner</h2>
                </div>
                <div class="card-body">
                    <form>
                        <div class="row">
                            <div class="col-lg-12">
                                <upload-file params="file: file, image: image, size: [635, 380]"></upload-file>
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
                                    <label for="description">Descrição</label>
                                    <textarea class="form-control" id="description" rows="6" placeholder="Informe a descrição" data-bind="value: description"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="url">URL</label>
                                    <input type="text" class="form-control" id="url" placeholder="Informe a url" data-bind="value: url">
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
    <div class="row" data-bind="visible: !banner()" style="display: none">
        <div class="col-lg-4 mb-2">
            <button type="button" class="btn btn-primary btn-default" data-bind="click: addBanner">Novo Banner</button>
        </div>
        <div class="col-lg-12">
            <div class="card card-default">
                <div class="card-header card-header-border-bottom">
                    <h2>Listagem de Banners</h2>
                </div>
                <div class="card-body">
                    <table class="table table-hover ">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Título</th>
                                <th scope="col">Descrição</th>
                                <th scope="col">URL</th>
                                <th scope="col">Ações</th>
                            </tr>
                        </thead>
                        <tbody data-bind="foreach: banners">
                            <tr>
                                <td scope="row" data-bind="text: id"></td>
                                <td><span data-bind="text: title"></span></td>
                                <td><span data-bind="text: description"></span></td>
                                <td><span data-bind="text: url"></span></td>
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

    function homeBanner(){[native/code]}
    homeBanner.urlData = "{{ route('api.banners.index') }}";
    homeBanner.urlSave = "{{ route('api.banners.store') }}";
    homeBanner.urlDelete = "{{ route('api.banners.destroy') }}";

    homeBanner.Banner = function(obj)
    {
        let self = this;
        self.origin = obj;

        self.id = ko.observable(obj.ban_id);
        self.title = ko.observable(obj.ban_title).extend({
            required: {
                params: true,
                message: 'O campo título é obrigatório'
            },
            maxLength: {
                params: 255,
                message: 'O campo título não pode ter mais que 255 caracteres.'
            }
        });
        self.description = ko.observable(obj.ban_description).extend({
            required: {
                params: true,
                message: 'O campo descrição é obrigatório'
            }
        });
        self.url = ko.observable(obj.ban_url).extend({
            required: {
                params: true,
                message: 'O campo url é obrigatório'
            }
        });
        self.image = ko.observable(base.displayImage(obj.ban_image));
        self.file  = ko.observableArray().extend({
            required: {
                message: "O campo imagem é obrigatório",
                onlyIf: function() {
                    return !self.id();
                }
            }
        });

        self.errors = ko.validation.group(self);

        self.edit = function()
        {
            homeBanner.viewModel.banner(self);
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
                            homeBanner.viewModel.banners.remove(item);
                            Alert.success(data.message);
                        }
                    };
                    base.post(homeBanner.urlDelete, data, callback, 'DELETE');
                }
            );
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
            formData.append('url', self.url());

            let callback = function(data)
            {
                if(!data.status) {
                    Alert.error(data.message);
                    return;
                }

                self.id(data.response.ban_id);
                self.origin = data.response;
                homeBanner.viewModel.banner(null);
                Alert.success('Item salvo com sucesso!');
            };
            base.postImage(homeBanner.urlSave, formData, callback);
        }

        self.goBackData = function(item)
        {
            let dataOld = new homeBanner.Banner(self.origin),
                position = homeBanner.viewModel.banners.indexOf(item);
                homeBanner.viewModel.banners.splice(position,1,dataOld);
        }

        self.cancel = function(item)
        {
            if (!item.id()) {
                homeBanner.viewModel.banners.remove(item);
            } else {
                self.goBackData(item);
            }
            homeBanner.viewModel.banner(null);
        }
    }

    homeBanner.ViewModel = function()
    {
        let self = this;

        self.banner = ko.observable();
        self.banners = ko.observableArray();
        self.init = function()
        {
            let callback = function(data) {
                if (data.status) {
                    self.banners(ko.utils.arrayMap(data.response, function(obj) {
                        return new homeBanner.Banner(obj);
                    }));
                }
            };
            base.post(homeBanner.urlData, null, callback, 'GET');
        };
        self.init();

        self.addBanner = function()
        {
            let banner = new homeBanner.Banner({});
            self.banners.push(banner);
            banner.edit();
        }
    }

	homeBanner.viewModel = new homeBanner.ViewModel();
    ko.applyBindings(homeBanner.viewModel, document.getElementById('koBanner'));

</script>
@endsection
