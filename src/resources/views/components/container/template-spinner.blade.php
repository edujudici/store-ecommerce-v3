<template id="template-spinner">
    <div class="card card-default" data-bind="visible: show">
        <div class="card-body d-flex align-items-center justify-content-center" style="height: 160px">
            <div class="sk-wave">
                <div class="rect1"></div>
                <div class="rect2"></div>
                <div class="rect3"></div>
                <div class="rect4"></div>
                <div class="rect5"></div>
            </div>
        </div>
    </div>
</template>

<script type="text/javascript">

    function spinner(){[native/code]}
    spinner.ViewModel = function(params) {
        var self = this;

        self.show = params.show;
    }

	ko.components.register('spinner', {
	    template: { element: 'template-spinner'},
	    viewModel: spinner.ViewModel
	});
</script>
