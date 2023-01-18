<detail-description-area></detail-description-area>
<template id="template-detail-description-area">
    <!--================Product Description Area =================-->
	<section class="product_description_area">
		<div class="container">
			<ul class="nav nav-tabs" id="myTab" role="tablist">
				<li class="nav-item">
					<a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Descrição</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile"
					 aria-selected="false">Especificações</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact"
					 aria-selected="false">Comentários</a>
				</li>
				{{--  <li class="nav-item">
					<a class="nav-link" id="review-tab" data-toggle="tab" href="#review" role="tab" aria-controls="review"
					 aria-selected="false">Reviews</a>
				</li>  --}}
			</ul>
			<div class="tab-content">
				<div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
					<pre data-bind="text: detail.productLongDesc"></pre>
				</div>
				<div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
					<div class="table-responsive">
						<table class="table">
							<tbody data-bind="foreach: specifications">
								<tr>
									<td>
										<h5 data-bind="text: prs_key"></h5>
									</td>
									<td>
										<h5 data-bind="text: prs_value"></h5>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
				<div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab" style="max-height: 500px; overflow-x: hidden; overflow-y: auto;">
					<div class="row">
						<div class="col-lg-6 pb-4">

                            <!-- ko if: comments().length == 0 -->
                            <div class="comment_list">
                                <p class="text-center">Seja o primeiro a comentar.</p>
                            </div>
                            <!-- /ko -->

							<div class="comment_list" data-bind="foreach: comments">
								<div class="review_item border-bottom">
									<div class="media">
										<div class="d-flex">
											<img src="{{ asset('assets/site/img/guest-user.jpg') }}" width="70" alt="Imagem do usuário">
										</div>
										<div class="media-body">
											<h4 data-bind="text: prc_name"></h4>
											<h5 data-bind="text: base.monthStringEn(created_at)"></h5>
											{{-- <a class="reply_btn" href="#">Reply</a> --}}
										</div>
									</div>
									<p class="pt-3" data-bind="text: prc_question"></p>
                                </div>
                                <!-- ko if: prc_answer -->
								<div class="review_item reply border-bottom">
									<div class="media">
										<div class="d-flex">
											<img src="{{ asset('assets/site/img/user-logo.png') }}" width="70" alt="Império do Mdf">
										</div>
										<div class="media-body">
											<h4>Império do Mdf</h4>
											<h5 data-bind="text: base.monthStringEn(prc_answer_date)"></h5>
											{{-- <a class="reply_btn" href="#">Reply</a> --}}
										</div>
									</div>
									<p class="pt-3" data-bind="text: prc_answer"></p>
                                </div>
                                <!-- /ko -->
							</div>
						</div>
						<div class="col-lg-6" data-bind="with: comment">
							<div class="review_box">
								<h4 class="text-center">Realizar um comentário</h4>
								<form class="row contact_form">
									<div class="col-md-12">
										<div class="form-group">
											<input type="text" class="form-control" placeholder="Nome" data-bind="value: name">
										</div>
									</div>
									<div class="col-md-12">
										<div class="form-group">
											<textarea class="form-control" rows="2" placeholder="Mensagem" data-bind="value: question"></textarea>
										</div>
									</div>
									<div class="col-md-12 text-right">
										<button type="button" class="btn primary-btn" data-bind="click: send">Enviar</button>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
				{{--  <div class="tab-pane fade" id="review" role="tabpanel" aria-labelledby="review-tab">
					<div class="row">
						<div class="col-lg-6">
							<div class="row total_rate">
								<div class="col-6">
									<div class="box_total">
										<h5>Overall</h5>
										<h4>4.0</h4>
										<h6>(03 Reviews)</h6>
									</div>
								</div>
								<div class="col-6">
									<div class="rating_list">
										<h3>Based on 3 Reviews</h3>
										<ul class="list">
											<li><a href="#">5 Star <i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i
													 class="fa fa-star"></i><i class="fa fa-star"></i> 01</a></li>
											<li><a href="#">4 Star <i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i
													 class="fa fa-star"></i><i class="fa fa-star"></i> 01</a></li>
											<li><a href="#">3 Star <i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i
													 class="fa fa-star"></i><i class="fa fa-star"></i> 01</a></li>
											<li><a href="#">2 Star <i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i
													 class="fa fa-star"></i><i class="fa fa-star"></i> 01</a></li>
											<li><a href="#">1 Star <i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i
													 class="fa fa-star"></i><i class="fa fa-star"></i> 01</a></li>
										</ul>
									</div>
								</div>
							</div>
							<div class="review_list">
								<div class="review_item">
									<div class="media">
										<div class="d-flex">
											<img src="{{ asset('assets/site/img/product/review-1.png') }}" alt="">
										</div>
										<div class="media-body">
											<h4>Blake Ruiz</h4>
											<i class="fa fa-star"></i>
											<i class="fa fa-star"></i>
											<i class="fa fa-star"></i>
											<i class="fa fa-star"></i>
											<i class="fa fa-star"></i>
										</div>
									</div>
									<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et
										dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea
										commodo</p>
								</div>
								<div class="review_item">
									<div class="media">
										<div class="d-flex">
											<img src="{{ asset('assets/site/img/product/review-2.png') }}" alt="">
										</div>
										<div class="media-body">
											<h4>Blake Ruiz</h4>
											<i class="fa fa-star"></i>
											<i class="fa fa-star"></i>
											<i class="fa fa-star"></i>
											<i class="fa fa-star"></i>
											<i class="fa fa-star"></i>
										</div>
									</div>
									<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et
										dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea
										commodo</p>
								</div>
								<div class="review_item">
									<div class="media">
										<div class="d-flex">
											<img src="{{ asset('assets/site/img/product/review-3.png') }}" alt="">
										</div>
										<div class="media-body">
											<h4>Blake Ruiz</h4>
											<i class="fa fa-star"></i>
											<i class="fa fa-star"></i>
											<i class="fa fa-star"></i>
											<i class="fa fa-star"></i>
											<i class="fa fa-star"></i>
										</div>
									</div>
									<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et
										dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea
										commodo</p>
								</div>
							</div>
						</div>
						<div class="col-lg-6">
							<div class="review_box">
								<h4>Add a Review</h4>
								<p>Your Rating:</p>
								<ul class="list">
									<li><a href="#"><i class="fa fa-star"></i></a></li>
									<li><a href="#"><i class="fa fa-star"></i></a></li>
									<li><a href="#"><i class="fa fa-star"></i></a></li>
									<li><a href="#"><i class="fa fa-star"></i></a></li>
									<li><a href="#"><i class="fa fa-star"></i></a></li>
								</ul>
								<p>Outstanding</p>
								<form class="row contact_form" action="contact_process.php" method="post" id="contactForm" novalidate="novalidate">
									<div class="col-md-12">
										<div class="form-group">
											<input type="text" class="form-control" id="name" name="name" placeholder="Your Full name" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Your Full name'">
										</div>
									</div>
									<div class="col-md-12">
										<div class="form-group">
											<input type="email" class="form-control" id="email" name="email" placeholder="Email Address" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Email Address'">
										</div>
									</div>
									<div class="col-md-12">
										<div class="form-group">
											<input type="text" class="form-control" id="number" name="number" placeholder="Phone Number" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Phone Number'">
										</div>
									</div>
									<div class="col-md-12">
										<div class="form-group">
											<textarea class="form-control" name="message" id="message" rows="1" placeholder="Review" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Review'"></textarea></textarea>
										</div>
									</div>
									<div class="col-md-12 text-right">
										<button type="submit" value="submit" class="primary-btn">Submit Now</button>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>  --}}
			</div>
		</div>
	</section>
	<!--================End Product Description Area =================-->
</template>

<script type="text/javascript">

    function detailArea(){[native/code]}
    detail.urlGetSpecifications = "{{ route('api.productsSpecifications.index') }}";
    detail.urlGetComments = "{{ route('api.products.comments.index') }}";
    detail.urlSaveComment = "{{ route('api.products.comments.store') }}";

    detail.Comment = function(obj, vm) {
        let self = this;

        self.name = ko.observable();
        self.question = ko.observable();
        self.vm = vm;

        self.send = function() {
            let params = {
                'sku': '{{$sku}}',
                'prc_name': self.name(),
                'prc_question': self.question()
            },
            callback = function(data) {
                if(!data.status) {
                    Alert.error(data.message);
                    return;
                }
                Alert.success('Comentário enviado com sucesso.');
                self.vm.comments(data.response);
                self.vm.comment(new detail.Comment({}, self.vm));
            };
            base.post(detail.urlSaveComment, params, callback);
        }
    }

    detailArea.DetailAreaViewModel = function(params) {
        var self = this;

        self.specifications = ko.observableArray();
        self.comments = ko.observableArray();
        self.comment = ko.observable(new detail.Comment({}, self));

        self.loadSpecifications = function() {
            let params = {
                'sku': '{{$sku}}',
            },
            callback = function(data) {
                if(!data.status) {
                    Alert.error(data.message);
                    return;
                }

                self.specifications(data.response);
            };
            base.post(detail.urlGetSpecifications, params, callback, 'GET');
        }

        self.loadComments = function() {
            let params = {
                'sku': '{{$sku}}',
            },
            callback = function(data) {
                if(!data.status) {
                    Alert.error(data.message);
                    return;
                }

                self.comments(data.response);
            };
            base.post(detail.urlGetComments, params, callback, 'GET');
        }

        self.init = function() {
            self.loadSpecifications();
            self.loadComments();
        }
        self.init();

    }

	ko.components.register('detail-description-area', {
	    template: { element: 'template-detail-description-area'},
	    viewModel: detailArea.DetailAreaViewModel
	});
</script>
