<home-brand-area></home-brand-area>
<template id="template-home-brand-area">
    <!-- Start brand Area -->
    <section class="brand-area section_gap_top" data-bind="visible: brands().length > 0">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6 text-center">
                    <div class="section-title">
                        <h1>Nossas Marcas</h1>
                    </div>
                </div>
            </div>
            <div class="row pb-4">
                <!-- ko foreach: brands-->
                    <a class="col single-img" href="#">
                        <img class="img-fluid d-block mx-auto" data-bind="attr: {src: image, alt: title}" width="120" height="70">
                    </a>
                <!-- /ko -->
            </div>
        </div>
    </section>
    <!-- End brand Area -->
</template>

<script type="text/javascript">

    function homeBrand(){[native/code]}
    homeBrand.urlGetBrands = "{{ route('api.brands.index') }}";

    homeBrand.Brand = function(obj) {
        let self = this;
        self.title = obj.bra_title;
        self.image = base.displayImage(obj.bra_image);
    }

    homeBrand.BrandViewModel = function() {
        let self = this;

        self.brands = ko.observableArray();
        self.init = function() {
            let callback = function(data) {
                if(!data.status) {
                    Alert.error(data.message);
                    return;
                }

                self.brands(ko.utils.arrayMap(data.response, function(obj) {
                    return new homeBrand.Brand(obj)
                }));
            };
            base.post(homeBrand.urlGetBrands, null, callback, 'GET');
        }
        self.init();
    }

	ko.components.register('home-brand-area', {
	    template: { element: 'template-home-brand-area'},
	    viewModel: homeBrand.BrandViewModel
	});
</script>
