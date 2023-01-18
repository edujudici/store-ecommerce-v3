@extends('painel.baseTemplate')

@section('content')
<div class="content-wrapper">
    <div class="content" id="koMercadoLivreHome">
        <div class="card-header card-header-border-bottom">
            <h3 class="mb-3">Métricas da Conta:</h3>
            <select id="ml-account" class="form-control" data-bind="
                options: $root.mlAccounts,
                optionsValue: 'mel_id',
                optionsText: 'mel_title',
                value: mlAccountSelected">
            </select>
        </div>
        <hr>
        <!-- ko with: dashData-->
        <div class="card-header card-header-border-bottom">
            <h2 class="mb-3">Perguntas do dia</h2>
            <p>Visualize o total de perguntas sincronizadas para o dia de hoje e o total respondido até o momento.</p>
            <p class="mt-3">Corra e responda todas o mais rápido possível :)</p>
        </div>
        <div class="row">
            <div class="col-md-6 col-lg-6 col-xl-3">
                <div class="card widget-block p-4 rounded bg-white border mb-0">
                    <div class="card-block">
                        <i class="mdi mdi-sync text-blue mr-4"></i>
                        <h4 class="text-primary my-2" data-bind="text: loadDay"></h4>
                        <p>Perguntas sincronizadas</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-6 col-xl-3">
                <div class="card widget-block p-4 rounded bg-white border mb-0">
                    <div class="card-block">
                        <h4 class="text-primary my-2" data-bind="text: calculatePercentageDay() + '%'"></h4>
                        <p class="pb-3">Total respondido</p>
                        <div class="progress my-2" style="height: 5px;">
                            <div class="progress-bar bg-primary" role="progressbar" aria-valuemin="0" aria-valuemax="100"
                                data-bind="style: {'width': calculatePercentageDay() + '%'}, attr: {'aria-valuenow': calculatePercentageDay}"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <hr>
        <div class="card-header card-header-border-bottom">
            <h2 class="mb-3">Perguntas de ontem</h2>
            <p>Visualize o total de perguntas sincronizadas para o dia de ontem e o total respondido.</p>
            <p class="mt-3">Vamos continuar a todo vapor e responder o máximo de perguntas :)</p>
        </div>
        <div class="row">
            <div class="col-md-6 col-lg-6 col-xl-3">
                <div class="card widget-block p-4 rounded bg-white border mb-0">
                    <div class="card-block">
                        <i class="mdi mdi-sync text-blue mr-4"></i>
                        <h4 class="text-primary my-2" data-bind="text: loadYesterday"></h4>
                        <p>Perguntas sincronizadas</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-6 col-xl-3">
                <div class="card widget-block p-4 rounded bg-white border mb-0">
                    <div class="card-block">
                        <h4 class="text-primary my-2" data-bind="text: calculatePercentageYesterday() + '%'"></h4>
                        <p class="pb-3">Total respondido</p>
                        <div class="progress my-2" style="height: 5px;">
                            <div class="progress-bar bg-primary" role="progressbar" aria-valuemin="0" aria-valuemax="100"
                                data-bind="style: {'width': calculatePercentageYesterday() + '%'}, attr: {'aria-valuenow': calculatePercentageYesterday}"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <hr>
        <div class="card-header card-header-border-bottom">
            <h2 class="mb-3">Total geral de perguntas</h2>
            <p>Visualize o total de perguntas sincronizadas até o momento e o total respondido aqui no site e também em plataformas de terceiros.</p>
            <p class="mt-3">Uau espero que os números sejam bons, estes ajudam alavancar as suas vendas :)</p>
        </div>
        <div class="row">
            <div class="col-md-6 col-lg-6 col-xl-3">
                <div class="media widget-media p-4 bg-white border">
                    <div class="icon rounded-circle mr-4 bg-danger">
                        <i class="mdi mdi-sync text-white "></i>
                    </div>
                    <div class="media-body align-self-center">
                        <h4 class="text-primary mb-2" data-bind="text: totalLoad"></h4>
                        <p>Total sincronizadas</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-6 col-xl-3">
                <div class="media widget-media p-4 bg-white border">
                    <div class="icon bg-success rounded-circle mr-4">
                        <i class="mdi mdi-check text-white "></i>
                    </div>
                    <div class="media-body align-self-center">
                        <h4 class="text-primary mb-2" data-bind="text: totalAnswered"></h4>
                        <p>Respostas via painel</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-6 col-xl-3">
                <div class="media widget-media p-4 bg-white border">
                    <div class="icon rounded-circle bg-warning mr-4">
                        <i class="mdi mdi-check text-white "></i>
                    </div>
                    <div class="media-body align-self-center">
                        <h4 class="text-primary mb-2" data-bind="text: totalAnsweredPartner"></h4>
                        <p>Respostas via terceiros</p>
                    </div>
                </div>
            </div>
        </div>
        <!-- /ko -->
    </div>
</div>
@endsection

@section('custom_script')

    <script type="text/javascript">

        let urlGetData = "{{ route('api.mercadolivre.dashboard.index') }}",
            urlGetMlAccounts = "{{ route('api.mercadolivre.accounts.index') }}";

        function Data(obj)
        {
            let self = this;

            self.loadDay = ko.observable(obj.loadDay);
            self.answerDay = ko.observable(obj.answerDay);
            self.loadYesterday = ko.observable(obj.loadYesterday);
            self.answerYesterday = ko.observable(obj.answerYesterday);
            self.totalLoad = ko.observable(obj.totalLoad);
            self.totalAnswered = ko.observable(obj.totalAnswered);
            self.totalAnsweredPartner = ko.observable(obj.totalAnsweredPartner);

            self.calculatePercentageDay = ko.computed(function() {
                if (self.loadDay() == 0) {
                    return 0;
                }
                return (self.answerDay() / self.loadDay()) * 100;
            });

            self.calculatePercentageYesterday = ko.computed(function() {
                if (self.loadYesterday() == 0) {
                    return 0;
                }
                return (self.answerYesterday() / self.loadYesterday()) * 100;
            });
        }

        function MLViewModel()
        {
            let self = this;

            self.mlAccounts = ko.observableArray();
            self.mlAccountSelected = ko.observable();
            self.dashData = ko.observable();

            self.setData = function()
            {
                callback = function(data)
                {
                    if(!data.status) {
                        Alert.error(data.message);
                        return;
                    }
                    self.mlAccounts(data.response);
                };
                base.post(urlGetMlAccounts, null, callback, 'GET');
            }

            self.mlAccountSelected.subscribe(function(value) {
                if (value) {
                    let params = {
                        id: value
                    },
                    callback = function(data) {
                        if(!data.status) {
                            Alert.error(data.message);
                            return;
                        }
                        self.dashData(new Data(data.response));
                    };
                    base.post(urlGetData, params, callback, 'GET');
                }
            });
        }

        var mlViewModel = new MLViewModel();
            mlViewModel.setData();
        ko.applyBindings(mlViewModel, document.getElementById('koMercadoLivreHome'));
    </script>
@endsection
