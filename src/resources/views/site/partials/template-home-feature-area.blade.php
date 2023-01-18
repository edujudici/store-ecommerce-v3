<home-feature-area></home-feature-area>
<template id="template-home-feature-area">
    <!-- start features Area -->
    <section class="features-area section_gap" data-bind="visible: features().length > 0">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10 text-center">
                    <div class="section-title">
                        <h1>Somos a Império do MDF</h1>
                        <p>Disponibilizamos os principais serviços para melhor atende-los. Confira tudo aqui em nosso portal.</p>
                        <p>Simplicidade para efetuar sua compra e total segurança.</p>
                    </div>
                </div>
            </div>
            <div class="row features-inner">
                <!-- ko foreach: features-->
                    <!-- single features -->
                    <div class="col-lg-3 col-md-6 col-sm-6">
                        <div class="single-features">
                            <div class="f-icon">
                                <img alt="" data-bind="attr: {src: image}" width="50" height="38">
                            </div>
                            <h6 data-bind="text: title"></h6>
                            <p data-bind="text: description"></p>
                        </div>
                    </div>
                <!-- /ko -->
            </div>
        </div>
    </section>
    <!-- end features Area -->
</template>

<script type="text/javascript">

    function homeFeature(){[native/code]}

    homeFeature.urlGetFeatures = "{{ route('api.features.index') }}";

    homeFeature.Feature = function(obj) {
        let self = this;
        self.title = obj.fea_title;
        self.description = obj.fea_description;
        self.image = base.displayImage(obj.fea_image);
    }

    homeFeature.FeatureViewModel = function(params) {
        var self = this;

        self.features = ko.observableArray();
        self.init = function() {
            let callback = function(data) {
                if(!data.status) {
                    Alert.error(data.message);
                    return;
                }

                self.features(ko.utils.arrayMap(data.response, function(obj) {
                    return new homeFeature.Feature(obj)
                }));
            };
            base.post(homeFeature.urlGetFeatures, null, callback, 'GET');
        }
        self.init();
    }

	ko.components.register('home-feature-area', {
	    template: { element: 'template-home-feature-area'},
	    viewModel: homeFeature.FeatureViewModel
	});
</script>
