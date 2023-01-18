@extends('painel.baseTemplate')

@section('content')
<div class="content" id="koComment">
    <div class="row">
        <div class="col-lg-12">
            <div class="card card-default">
                <div class="card-header card-header-border-bottom">
                    <h2>Listagem de Comentários do Mercado Livre</h2>
                </div>
                <div class="card-body">
                    <p class="mb-5">Aqui você encontra todas as perguntas conforme as contas criadas do mercado livre, ordenadas inicialmente por data de criação do mais velho para o mais novo, basta clicar em uma pergunta para a opção de resposta aparecer junto com outras informações da pergunta, usuário e produto</p>
                    <div id="accordion3" class="accordion accordion-bordered" data-bind="foreach: comments">
                        <div class="card">
                            <div class="card-header" data-bind="attr: {id: 'heading' + id}">
                                <button class="btn btn-link" style="display: flex; align-items: center;" data-toggle="collapse" aria-expanded="false" data-bind="attr: {'data-target': '#collapse' + id, 'aria-controls': 'collapse' + id}, click: propCollapse">
                                    <img class="img-responsive" style="min-width: 120px" alt="prewiew" width="120" height="80" data-bind="attr: {src: image}">
                                    <div style="display: grid">
                                        <span class="ml-2 border-bottom mb-3" style="user-select: text">Comentário de <b data-bind="text: user.meu_nickname"></b> para o item <b data-bind="text: product.mep_item_id + ' - ' + product.mep_title"></b>
                                            <a style="color: #719bf8; width: 150px" class="p-0" target="_blank" data-bind="attr: {href: product.mep_permalink}">Ver mais informações</a>
                                        </span>
                                        <span class="ml-2" data-bind="text: base.dateTimeStringEn(date) + ' - ' + text" style="user-select: text"></span>
                                    </div>
                                </button>
                            </div>

                            <div class="collapse" data-parent="#accordion3" data-bind="attr: {'aria-labelledby': 'heading' + id, 'id': 'collapse' + id}">
                                <div class="card-body">
                                    <form>
                                        <div class="form-group">
                                            <label data-bind="attr: {for: 'answer' + id}">Preencher resposta para: </label> <span data-bind="text: user.meu_nickname"></span>
                                            <textarea class="form-control" rows="3" data-bind="attr: {id: 'answer' + id}, value: answer"></textarea>
                                        </div>
                                        <div class="form-footer">
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <select class="form-control mb-3" data-bind="
                                                        options: $root.answers,
                                                        optionsText: 'description',
                                                        optionsCaption: 'Selecionar resposta',
                                                        value: answerSelected">
                                                    </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <button type="submit" class="btn btn-primary btn-default" data-bind="click: send">Enviar Resposta</button>
                                                    <button type="submit" class="btn btn-danger btn-default float-right" data-bind="click: remove">Excluir Pergunta</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="card-body border-top border-bottom pb-0 pt-2">

                                    <h4 class="text-dark mb-2 text-center">Todas as perguntas</h4>

                                    <spinner params="show: showSpinner"></spinner>

                                    <div class="comment_list" data-bind="foreach: allComments">
                                        <div class="review_item mb-4">
                                            <div class="media">
                                                <div class="d-flex">
                                                    <img class="mr-2" src="{{ asset('assets/site/img/guest-user.jpg') }}" width="40" alt="Imagem do usuário">
                                                </div>
                                                <div class="media-body">
                                                    <div class="media-body align-self-center">
                                                        <a target="_blank" data-bind="attr: {href: user.meu_permalink}">
                                                            <h6 class="mt-0 text-dark font-weight-medium" data-bind="text: user.meu_nickname"></h6>
                                                        </a>
                                                        <small data-bind="text: base.dateTimeStringEn(mec_date_created)"></small>
                                                    </div>
                                                </div>
                                            </div>
                                            <p class="ml-5 font-size-13" data-bind="text: mec_text"></p>
                                        </div>
                                        <!-- ko if: mec_answer_text -->
                                            <div class="review_item reply mb-4 ml-5">
                                                <div class="media">
                                                    <div class="d-flex">
                                                        <img class="mr-2" src="{{ asset('assets/site/img/user-logo.png') }}" width="40" alt="Império do Mdf">
                                                    </div>
                                                    <div class="media-body">
                                                        <div class="media-body align-self-center">
                                                            <h6 class="mt-0 text-dark font-weight-medium">Império do Mdf</h6>
                                                            <small data-bind="text: base.dateTimeStringEn(mec_answer_date)"></small>
                                                        </div>
                                                    </div>
                                                </div>
                                                <p class="ml-5 font-size-13" data-bind="text: mec_answer_text"></p>
                                            </div>
                                        <!-- /ko -->
                                    </div>
                                </div>
                                <div class="card-body pt-2" data-bind="with: user">
                                    <div class="row mb-3" style="margin: 0">
                                        <div class="col-md-12 col-xl-12 col-lg-12">
                                            <h4 class="text-dark mb-2 text-center">Informações do usuário</h4>
                                            <address>
                                                <span class="text-dark">Nickname:</span> <span data-bind="text: meu_nickname"></span> <br>
                                                <span class="text-dark">Data de cadastro:</span> <span data-bind="text: base.monthStringEn(meu_registration_date)"></span> <br>
                                                <span class="text-dark">Transações totais:</span> <span data-bind="text: meu_transactions_total"></span> <br><br>
                                            </address>
                                            <div style="display: flex">
                                                <div class="col-md-6 col-lg-6 col-xl-3">
                                                    <div class="media widget-media p-4 bg-white border">
                                                        <div class="icon bg-success rounded-circle mr-4">
                                                            <i class="mdi mdi-diamond text-white "></i>
                                                        </div>
                                                        <div class="media-body align-self-center">
                                                            <h4 class="text-primary mb-2" data-bind="text: meu_points"></h4>
                                                            <p>Total de pontos</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-lg-6 col-xl-3">
                                                    <div class="media widget-media p-4 bg-white border">
                                                        <div class="icon rounded-circle bg-warning mr-4">
                                                            <i class="mdi mdi-cart-outline text-white "></i>
                                                        </div>
                                                        <div class="media-body align-self-center">
                                                            <h4 class="text-primary mb-2" data-bind="text: meu_transactions_completed"></h4>
                                                            <p>Transações completas</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-lg-6 col-xl-3">
                                                    <div class="media widget-media p-4 bg-white border">
                                                        <div class="icon rounded-circle mr-4 bg-danger">
                                                            <i class="mdi mdi-cart-outline text-white "></i>
                                                        </div>
                                                        <div class="media-body align-self-center">
                                                            <h4 class="text-primary mb-2" data-bind="text: meu_transactions_canceled"></h4>
                                                            <p>Transações canceladas</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <p class="text-dark"><a target="_blank" data-bind="attr: {href: meu_permalink}">Ver mais informações</a></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="pagination" data-bind="html: pagination"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('custom_script')
<script type="text/javascript">

    function homeComments(){[native/code]}
    homeComments.urlData = "{{ route('api.mercadolivre.comments.index') }}";
    homeComments.urlAnswer = "{{ route('api.mercadolivre.comments.store') }}";
    homeComments.urlDelete = "{{ route('api.mercadolivre.comments.destroy') }}";
    homeComments.urlGetAnswers = "{{ route('api.mercadolivre.answers.index') }}";

    homeComments.Comment = function(obj)
    {
        let self = this;

        self.id = obj.mec_id;
        self.date = obj.mec_date_created;
        self.text = obj.mec_text;
        self.from = obj.mec_from_id;
        self.item = obj.mec_item_id;
        self.user = obj.user;
        self.product = obj.product;
        self.title = obj.mercadolivre ? obj.mercadolivre.mel_title : 'Conta não encontrada';
        self.sku = obj.product ? obj.product.mep_item_id : 'Produto não encontrado';
        self.image = obj.product ? obj.product.mep_secure_thumbnail : 'Produto não encontrado';
        self.productTitle = obj.product ? obj.product.mep_title : 'Produto não encontrado';
        self.allComments = ko.observableArray();
        self.answer = ko.observable();
        self.collapse = ko.observable(false);
        self.showSpinner = ko.observable(false);
        self.answerSelected = ko.observable();
        self.answerSelected.subscribe(function(value) {
            if (value) {
                const description = value.description.replace(':cliente', self.user.meu_nickname);
                self.answer(description);
                self.answerSelected(null);
            }
        })

        self.commentsByItemsAndUser = function()
        {
            if (self.allComments().length > 0) {
                return;
            }
            self.showSpinner(true);

            let params = {
                'from': self.from,
                'item': self.item
            },
            callback = function(data) {
                self.showSpinner(false);
                if (data.status) {
                    self.allComments(data.response);
                }
            };
            base.post(homeComments.urlData, params, callback, 'GET');
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
                            homeComments.viewModel.comments.remove(item);
                            Alert.success('Pergunta deletada com sucesso');
                        }
                    };
                    base.post(homeComments.urlDelete, data, callback, 'DELETE');
                }
            );
        }

        self.send = function()
        {
            if (!self.answer()) {
                Alert.error('Obrigatório preencher uma resposta');
                return;
            }
            let params = {
                'id': self.id,
                'text': self.answer()
            },
            callback = function(data)
            {
                if(!data.status) {
                    Alert.error(data.message);
                    return;
                }

                Alert.success('Pergunta respondida com sucesso!');
                homeComments.viewModel.comments.remove(self);
            };
            base.post(homeComments.urlAnswer, params, callback);
        }

        self.propCollapse = function()
        {
            self.collapse(!self.collapse())
            if (self.collapse()){
                self.commentsByItemsAndUser();
            }
        }
    }

    homeComments.Answer = function(obj)
    {
        let self = this;

        self.description = obj.mea_description;
    }

    homeComments.ViewModel = function()
    {
        let self = this;

        self.comments = ko.observableArray();
        self.pagination = ko.observable();
        self.answers = ko.observableArray();

        self.init = function()
        {
            let params = {
                'page': base.getParamUrl('page'),
            },
            callback = function(data) {
                if (data.status) {
                    self.pagination(data.response.pagination);
                    self.comments(ko.utils.arrayMap(data.response.comments, function(obj) {
                        return new homeComments.Comment(obj);
                    }));
                }
            };
            base.post(homeComments.urlData, params, callback, 'GET');
        };
        self.init();

        self.loadAnswers = function()
        {
            let callback = function(data) {
                if (data.status) {
                    self.answers(ko.utils.arrayMap(data.response, function(obj) {
                        return new homeComments.Answer(obj);
                    }));
                }
            };
            base.post(homeComments.urlGetAnswers, null, callback, 'GET');
        }
        self.loadAnswers();
    }

	homeComments.viewModel = new homeComments.ViewModel();
    ko.applyBindings(homeComments.viewModel, document.getElementById('koComment'));

</script>
@endsection
