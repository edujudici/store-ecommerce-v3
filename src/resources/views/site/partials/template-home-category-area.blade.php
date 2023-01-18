<home-category-area></home-category-area>
<template id="template-home-category-area">
    <!-- Start category Area -->
    <section class="category-area mb-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6 text-center">
                    <div class="section-title">
                        <h1>Categorias</h1>
                        <p>Encontre mais rápido os produtos que esta procurando, escolha alguma categoria e aproveite!</p>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-12 col-md-12">
                    <div class="row" data-bind="foreach: categories">
                        <div class="col-lg-4 col-md-4">
                            <a class="img-pop-up" data-bind="attr: {href: url }">
                                <div class="single-deal">
                                    <div class="overlay"></div>
                                    <img class="img-fluid w-100" style="height: 190px" alt="" data-bind="attr: {src: image}">
                                    <div class="deal-details">
                                        <h6 class="deal-title" data-bind="text: title"></h6>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End category Area -->
</template>

<script type="text/javascript">

    function homeCategory(){[native/code]}
    homeCategory.urlGetCategories = "{{ route('api.categories.index') }}";
    homeCategory.urlShop = "{{ route('site.shop.index') }}";

    homeCategory.Category = function(obj) {
        let self = this;

        self.title = obj.cat_title;
        self.image = base.displayImage(obj.cat_image);
        self.url = homeCategory.urlShop + '?category=' + obj.cat_id;
    }

    homeCategory.CategoryViewModel = function(params) {
        var self = this;

        self.categories = ko.observableArray();

        self.init = function() {
            let callback = function(data) {
                if(!data.status) {
                    Alert.error(data.message);
                    return;
                }

                ko.utils.arrayForEach(data.response, function(obj, index) {
                    if (index <= 5) {
                        self.categories.push(new homeCategory.Category(obj))
                    }
                });
            };
            base.post(homeCategory.urlGetCategories, null, callback, 'GET');
        }
        self.init();
    }

	ko.components.register('home-category-area', {
	    template: { element: 'template-home-category-area'},
	    viewModel: homeCategory.CategoryViewModel
	});
</script>
