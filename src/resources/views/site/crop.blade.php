<html>
	<link>
        <link href="{{ asset('assets/cropper/cropper.min.css') }}" rel="stylesheet" type="text/css" />
	</head>
	<body>
        <style>
            /* generic page styles */
            body { font-family: Tahoma; }
            div { border-radius: 0.5em; }

            .editor-container
            {
                display: inline-block;
                width: 500px;
                height: 333px;
                margin: 1em;
            }

            .img-preview
            {
                margin: 1em;
                display: inline-block;
                overflow: hidden;
            }

            .img-preview.large
            {
                width: 256px;
                height: 256px;
            }

            .img-preview.medium
            {
                width: 128px;
                height: 128px;
            }

            .img-preview.small
            {
                width: 64px;
                height: 64px;
            }
        </style>


        <div id="simple-example">
            <h2>Simple Example</h2>
            <div class="editor-container">
                <img src="{{asset('assets/site/img/14154839804_49566bdcdc.jpg')}}"
                     data-bind="cropper: { aspectRatio: 16 / 9, preview: '#simple-example .img-preview' }"/>

            </div>
            <div class="preview-container">
                <div class="img-preview large"></div>
                <div class="img-preview medium"></div>
                <div class="img-preview small"></div>
            </div>
        </div>

        <div id="base64-output-example">
            <h2>Base 64 Output Example</h2>
            <div class="editor-container">
                <img src="{{asset('assets/site/img/14154839804_49566bdcdc.jpg')}}"
                     data-bind="cropper: { aspectRatio: 1,
                        cropend: UpdateBase64Preview, built: UpdateBase64Preview, zoom: UpdateBase64Preview,
                        cropBoxResizable: false, cropBoxMovable: false, dragCrop: false }"/>

            </div>
            <div class="preview-container">
                <img class="img-preview medium" data-bind="attr: {src: Base64Image }" />
                <textarea data-bind="value: Base64Image"></textarea>
            </div>

        </div>

        <script src="{{ asset('assets/knockoutjs-3.5.0/knockout-3.5.0.js') }}"></script>
        <script src="{{asset('assets/js/jquery.min.js')}}"></script>
        <script src="{{ asset('assets/cropper/cropper.min.js') }}"></script>
        <script src="{{ asset('assets/knockoutjs-3.5.0/knockout.cropper.js') }}"></script>

        <script>
            function CropperExamples()
            {
                var self = this;
                this.Base64Image = ko.observable();

                this.UpdateBase64Preview = function(result) {
                    var data = $(this).cropper('getCroppedCanvas', {
                      width: 200
                    }).toDataURL();
                    self.Base64Image(data);
                };
            }


            ko.applyBindings(new CropperExamples());
        </script>
	</body>
</html>
