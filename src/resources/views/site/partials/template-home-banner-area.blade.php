<home-banner-area></home-banner-area>
<template id="template-home-banner-area">

    <!-- start banner Area -->
    <section class="banner-area" data-bind="visible: banners().length > 0" {{-- style="margin-top: 120px" --}}>
        <div class="container">
            <div class="row fullscreen align-items-center justify-content-start">
                <div class="col-lg-12" style="margin-top: 120px">
                    <div class="active-banner-slider owl-carousel">

                        <!-- ko foreach: banners-->
                            <!-- single-slide -->
                            <div class="row single-slide align-items-center d-flex">
                                <div class="col-lg-5 col-md-6">
                                    <div class="banner-content">
                                        <h2 data-bind="text: title"></h2>
                                        <p data-bind="text: description" style="color: #454545"></p>
                                        <div class="add-bag d-flex align-items-center" data-bind="if: url">
                                            <a class="add-btn" data-bind="attr: {href: url}"><span class="lnr lnr-cross"></span></a>
                                            <span class="add-text text-uppercase">Detalhes</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-7">
                                    <div class="banner-img">
                                        <img class="img-fluid" alt="" data-bind="attr: {src: image}">
                                    </div>
                                </div>
                            </div>
                        <!-- /ko -->

                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End banner Area -->
</template>

<script type="text/javascript">

    function homeBanner(){[native/code]}
    homeBanner.urlGetBanners = "{{ route('api.banners.index') }}";

    homeBanner.Banner = function(obj) {
        let self = this;
        self.title = obj.ban_title;
        self.description = obj.ban_description;
        self.url = obj.ban_url;
        self.image = base.displayImage(obj.ban_image);
    }

    homeBanner.BannerViewModel = function() {
        let self = this;

        self.banners = ko.observableArray();
        self.init = function() {
            let callback = function(data) {
                if(!data.status) {
                    Alert.error(data.message);
                    return;
                }

                self.banners(ko.utils.arrayMap(data.response, function(obj) {
                    return new homeBanner.Banner(obj)
                }));

                setTimeout(function() {
                    homeBanner.activeBanner();
                }, 500);
            };
            base.post(homeBanner.urlGetBanners, null, callback, 'GET');
        }
        self.init();
    }
    homeBanner.activeBanner = function() {
        $(".active-banner-slider").owlCarousel({
            items:1,
            autoplay:false,
            autoplayTimeout: 5000,
            loop:true,
            nav:true,
            navText:["<img src='{{asset('assets/site/img/elements/prev.png')}}'>","<img src='{{asset('assets/site/img/elements/next.png')}}'>"],
            dots:false
        });
    }

	ko.components.register('home-banner-area', {
	    template: { element: 'template-home-banner-area'},
	    viewModel: homeBanner.BannerViewModel
	});
</script>
