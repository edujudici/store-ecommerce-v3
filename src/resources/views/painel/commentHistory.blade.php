@extends('painel.baseTemplate')

@section('content')
<div class="content" id="koLoadQuestionHistory">
    <div class="row">
        <div class="col-lg-12">
            <div class="card card-default">
                <div class="card-header card-header-border-bottom">
                    <h2>Histórico de Cargas de Perguntas</h2>
                </div>
                <div class="card-body">
                    <table class="table table-hover ">
                        <thead>
                            <tr>
                                <th class="d-none d-md-table-cell" scope="col">#</th>
                                <th scope="col">Conta Mercado Livre</th>
                                <th class="d-none d-md-table-cell" scope="col">Total de items</th>
                                <th scope="col">Total Sincronizado</th>
                                <th scope="col">Data da Carga</th>
                            </tr>
                        </thead>
                        <tbody data-bind="foreach: loads">
                            <tr>
                                <td class="d-none d-md-table-cell" scope="row" data-bind="text: lqh_id"></td>
                                <td><span data-bind="text: lqh_account_title"></span></td>
                                <td class="d-none d-md-table-cell"><span data-bind="text: lqh_total"></span></td>
                                <td><span data-bind="text: lqh_total_sync"></span></td>
                                <td><span data-bind="text: base.dateTimeStringEn(created_at)"></span></td>
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
    function loadQuestionHistory(){[native/code]}
    loadQuestionHistory.urlData = "{{ route('api.load.question.history') }}";

    loadQuestionHistory.ViewModel = function()
    {
        let self = this;

        self.loads = ko.observableArray();

        self.init = function()
        {
            let callback = function(data) {
                if (data.status) {
                    self.loads(data.response);
                }
            };
            base.post(loadQuestionHistory.urlData, null, callback, 'GET');
        };
        self.init();
    }

	loadQuestionHistory.viewModel = new loadQuestionHistory.ViewModel();
    ko.applyBindings(loadQuestionHistory.viewModel, document.getElementById('koLoadQuestionHistory'));

</script>
@endsection