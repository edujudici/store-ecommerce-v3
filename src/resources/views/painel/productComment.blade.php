@extends('painel.baseTemplate')

@section('content')
<div class="content" id="koComment">
    <!-- ko with: comment -->
    <div class="row" data-bind="visible: $root.comment" style="display: none">
        <div class="col-lg-12">
            <div class="card card-default">
                <div class="card-header card-header-border-bottom">
                    <h2 class="col-lg-12 pl-0">Resposta de comentário para o produto</h2>
                    <span class="col-lg-12 p-3" data-bind="text: product.pro_sku + ' - ' + product.pro_description"></span>
                    <img data-bind="attr: {src: product.pro_secure_thumbnail ? product.pro_secure_thumbnail : base.displayImage(product.pro_image)}" class="img-thumbnail rounded float-left pl-0" style="width: 200px; height: 200px">
                </div>
                <div class="card-body">
                    <form>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="answer">Pergunta</label>
                                    <textarea class="form-control" id="answer" rows="6" data-bind="value: question" disabled></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="answer">Resposta</label>
                                    <textarea class="form-control" id="answer" rows="6" placeholder="Informe a resposta" data-bind="value: answer"></textarea>
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
    <div class="row" data-bind="visible: !comment()" style="display: none">
        <div class="col-lg-12">
            <div class="card card-default">
                <div class="card-header card-header-border-bottom">
                    <h2>Listagem de Comentários de Produtos</h2>
                </div>
                <div class="card-body">
                    <table class="table table-hover ">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Nome</th>
                                <th scope="col">Pergunta</th>
                                <th scope="col">Data</th>
                                <th scope="col">Respondido</th>
                                <th scope="col">Ações</th>
                            </tr>
                        </thead>
                        <tbody data-bind="foreach: comments">
                            <tr>
                                <td scope="row" data-bind="text: sku"></td>
                                <td><span data-bind="text: name"></span></td>
                                <td><span data-bind="text: question"></span></td>
                                <td><span data-bind="text: base.dateTimeStringEn(questionDate)"></span></td>
                                <td><span data-bind="text: answer() ? 'Sim' : 'Não'"></span></td>
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

    function commentArea(){[native/code]}
    commentArea.urlData = "{{ route('api.products.comments.indexAll') }}";
    commentArea.urlSave = "{{ route('api.products.comments.store') }}";
    commentArea.urlDelete = "{{ route('api.products.comments.destroy') }}";

    commentArea.Comment = function(obj)
    {
        let self = this;
        self.origin = obj;

        self.id = ko.observable(obj.prc_id);
        self.sku = obj.pro_sku ? obj.pro_sku : seld.id;
        self.name = obj.prc_name;
        self.question = obj.prc_question;
        self.questionDate = obj.created_at;
        self.answer = ko.observable(obj.prc_answer).extend({
            required: {
                params: true,
                message: 'O campo resposta é obrigatório'
            }
        });
        self.answerDate = obj.prc_answer_date;
        self.product = obj.product;
        self.errors = ko.validation.group(self);

        self.edit = function()
        {
            commentArea.viewModel.comment(self);
        }

        self.remove = function(item)
        {
            Alert.confirm(
                'Você realmente quer deletar este item?',
                'Exclusão',
                function(resp) {

                    if (!resp) return;

                    let data = {
                        id : item.id
                    },
                    callback = function(data)
                    {
                        if(!data.status) {
                            Alert.error(data.message);
                            return;
                        } else {
                            commentArea.viewModel.comments.remove(item);
                            Alert.success(data.message);
                        }
                    };
                    base.post(commentArea.urlDelete, data, callback, 'DELETE');
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
                'sku': self.sku,
                'prc_id': self.id(),
                'prc_answer': self.answer(),
                'prc_answer_date': '{{ date("Y-m-d H:i:s") }}'
            },
            callback = function(data)
            {
                if(!data.status) {
                    Alert.error(data.message);
                    return;
                }

                commentArea.viewModel.comment(null);
                Alert.success('Item salvo com sucesso!');
            };
            base.post(commentArea.urlSave, params, callback);
        }

        self.goBackData = function(item)
        {
            let dataOld = new commentArea.Comment(self.origin),
                position = commentArea.viewModel.comments.indexOf(item);
                commentArea.viewModel.comments.splice(position,1,dataOld);
        }

        self.cancel = function(item)
        {
            if (!item.id()) {
                commentArea.viewModel.comments.remove(item);
            } else {
                self.goBackData(item);
            }
            commentArea.viewModel.comment(null);
        }
    }

    commentArea.ViewModel = function()
    {
        let self = this;

        self.comment = ko.observable();
        self.comments = ko.observableArray();
        self.init = function()
        {
            let params = {
                'page': base.getParamUrl('page'),
            },
            callback = function(data) {
                if (data.status) {
                    self.comments(ko.utils.arrayMap(data.response, function(obj) {
                        return new commentArea.Comment(obj);
                    }));
                }
            };
            base.post(commentArea.urlData, params, callback, 'GET');
        };
        self.init();

        self.addComment = function()
        {
            let comment = new commentArea.Comment({});
            self.comments.push(comment);
            comment.edit();
        }
    }

	commentArea.viewModel = new commentArea.ViewModel();
    ko.applyBindings(commentArea.viewModel, document.getElementById('koComment'));

</script>
@endsection
