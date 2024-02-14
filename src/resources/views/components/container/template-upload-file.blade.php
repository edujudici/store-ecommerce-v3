<template id="template-upload-file">
    <style>
        .editor-container {
            display: inline-block;
            width: 500px;
            height: 330px;
            margin: 1em;
        }

        .img-preview {
            margin: 1em 0;
            display: inline-block;
            overflow: hidden;
        }

        .img-preview.large {
            width: 256px;
            height: 256px;
        }

        .img-preview.medium {
            width: 128px;
            height: 128px;
        }

        .img-preview.small {
            width: 64px;
            height: 64px;
        }
    </style>

    <div class="form-group">
        <label class="btn btn-info btn-file">
            <i class="fa fa-plus" aria-hidden="true"></i>
            Carregar Imagem <input type="file" name="file" hidden
                data-bind="event: {'change': function() { fileSelected($element); }}, attr: {multiple: multiple ? 'multiple' : ''}">
        </label>
        <div class="form-row" data-bind="visible: imageInternalControl().length == 0">
            <!-- ko if: !multiple && image -->
            <div class="col-md-6">
                <img data-bind="attr: {src: image}" class="img-thumbnail rounded float-left"
                    style="width: 200px; height: 200px">
            </div>
            <!-- /ko -->

            <!-- ko if: multiple && image().length > 0 -->
            <!-- ko foreach: image -->
            <div class="col-md-3">
                <img data-bind="attr: {src: $data}" class="img-thumbnail rounded float-left"
                    style="width: 200px; height: 200px">
            </div>
            <!-- /ko -->
            <!-- /ko -->
        </div>
        <div class="form-row" data-bind="visible: imageInternalControl().length > 0">
            <!-- ko foreach: imageInternalControl -->
            <div class="col-md-3" data-bind="class: 'preview-container-' + $index()">
                <div class="img-preview large"></div>
                <div class="row">
                    <div class="col-md-12">
                        <button type="button" class="btn btn-primary btn-default"
                            data-bind="click: $parent.cropperImageToFile.bind(null, $index())">Confirmar Crop
                            Imagem</button>
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                <div class="editor-container" {{-- data-bind="style: { width: $parent.width, height: $parent.height }"
                    --}}>
                    <img
                        data-bind="attr: {src: $data, id: 'cropper-' + $index()}, cropper: { aspectRatio: $parent.aspectRatio, preview: '.preview-container-' + $index() + ' .img-preview' }" />
                </div>
            </div>
            <!-- /ko -->
        </div>
    </div>
</template>

<script type="text/javascript">
    function uploadFile(){[native/code]}

    uploadFile.Response = function(file) {
        let self = this;

        self.file = file;
        self.fileCropped = undefined;
    }

    uploadFile.UploadViewModel = function(params) {
        let self = this;

        self.fileInternalControl = params.file;
        self.image = params.image;
        self.multiple = params.multiple;
        self.imageInternalControl = ko.observableArray();
        self.width = params.size[0];
        self.height = params.size[1];
        self.aspectRatio = self.width / self.height;

        self.fileSelected = function(el) {

            if (el) {

                self.fileInternalControl([]);

                let counter = -1,
                    file,
                    imageCounter = 0;
                while ( file = el.files[ ++counter ] ) {
                    if((file.size / 1024 / 1024) < 2) {
                        let fileNamePieces = file.name.split('.'),
                            extension = fileNamePieces[fileNamePieces.length - 1],
                            extensionLowerCase = extension.toLowerCase();

                        if (extensionLowerCase !== 'cmd' && extensionLowerCase !== 'bat' &&
                            extensionLowerCase !== 'scr' && extensionLowerCase !== 'exe' &&
                            extensionLowerCase !== 'vbs' && extensionLowerCase !== 'ws'  &&
                            extensionLowerCase !== 'url') {
                            if (self.fileInternalControl() === null) {
                                self.fileInternalControl([]);
                            }
                            self.fileInternalControl.push(new uploadFile.Response(file));
                        } else {
                            Alert.error('Uma ou mais imagem é um arquivo inválido.');
                        }
                    } else {
                        Alert.error('Uma ou mais imagem ultrapassa o valor permitido de 2 MB');
                    }
                }
            }
        }

        self.cropperImageToFile = function(index)
        {
            const $element = $('#cropper-' + index),
                data = $element.cropper('getData'),
                type = self.fileInternalControl()[index].file.type,
                result = $element.cropper('getCroppedCanvas', {
                    width: data.width,
                    height: data.height,
                    fillColor: type !== 'image/png' ? "#fff" : undefined
                }),
                base64 = result.toDataURL(type),
                name = self.fileInternalControl()[index].file.name,
                file = uploadFile.dataURLtoFile(base64, name);

            self.fileInternalControl()[index].fileCropped = file;
        }

        self.loadImageComp = ko.computed(function()
        {
            if (Array.isArray(self.fileInternalControl()) && self.fileInternalControl().length > 0) {
                self.imageInternalControl(ko.utils.arrayMap(self.fileInternalControl(), function(fileInternal) {
                    return URL.createObjectURL(fileInternal.file);
                }));
            }
        });
    }

    uploadFile.dataURLtoFile = function (dataurl, filename) {
        var arr = dataurl.split(','), mime = arr[0].match(/:(.*?);/)[1],
            bstr = atob(arr[1]), n = bstr.length, u8arr = new Uint8Array(n);
        while(n--){
            u8arr[n] = bstr.charCodeAt(n);
        }
        return new File([u8arr], filename, {type:mime});
    }

	ko.components.register('upload-file', {
	    template: { element: 'template-upload-file'},
	    viewModel: uploadFile.UploadViewModel
	});
</script>