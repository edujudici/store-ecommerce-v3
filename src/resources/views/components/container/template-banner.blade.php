<template id="template-banner">
    <!-- Start Component Banner Area -->
    <section class="banner-area organic-breadcrumb">
        <div class="container">
            <div class="breadcrumb-banner d-flex flex-wrap align-items-center justify-content-end">
                <div class="col-first">
                    <h1 data-bind="text: linkTitle"></h1>
                    <nav class="d-flex align-items-center">
                        <a data-bind="attr: {href: subLink}"><span class="m-0" data-bind="text: subLinkTitle"></span><span class="lnr lnr-arrow-right"></span></a>
                        <a data-bind="attr: {href: link}, text: linkTitle"></a>
                    </nav>
                </div>
            </div>
        </div>
    </section>
    <!-- End Component Banner Area -->
</template>

<script type="text/javascript">

    function componentBanner(){[native/code]}
    componentBanner.ViewModel = function(params) {
        var self = this;

        self.subLink = params.subLink;
        self.subLinkTitle = params.subLinkTitle;
        self.link = params.link;
        self.linkTitle = params.linkTitle;
    }

	ko.components.register('banner-area', {
	    template: { element: 'template-banner'},
	    viewModel: componentBanner.ViewModel
	});
</script>
