<shopper-register-area></shopper-register-area>
<template id="template-shopper-register-area">
    <!--================Register Box Area =================-->
	<section class="login_box_area section_gap">
		<div class="container">
			<div class="row">
                <div class="col-lg-6">
					<div class="login_box_img">
						<img class="img-fluid" src="{{asset('assets/site/img/login.jpg')}}" alt="">
						<div class="hover">
							<h4>Realizar cadastro</h4>
							<p>Crie uma conta para efetuar compras e acompanhar os status de seus pedidos.</p>
							<a class="primary-btn" href="{{ route('login') }}">Já tenho uma conta</a>
						</div>
					</div>
				</div>
				<div class="col-lg-6">
					<div class="login_form_inner" style="padding-top: 20px">
                        <h2 class="text-center mb-4">Dados cadastrais</h2>
                        <form method="POST" action="{{ route('register') }}">
                            @csrf

                            <div class="col-md-12">
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <input id="name" type="text" class="input-lg form-control @error('name') is-invalid @enderror"
                                            placeholder="{{ __('Nome') }}" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                                        @error('name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="form-group col-md-6">
                                        <input id="surname" type="text" class="input-lg form-control @error('surname') is-invalid @enderror"
                                            placeholder="{{ __('Sobrenome') }}" name="surname" value="{{ old('surname') }}" required autocomplete="surname" autofocus>

                                        @error('surname')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-group col-md-12">
                                <input id="email" type="email" class="input-lg form-control @error('email') is-invalid @enderror"
                                    placeholder="{{ __('E-Mail') }}" name="email" value="{{ old('email') }}" required autocomplete="email">

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="col-md-12">
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <input id="password" type="password" class="input-lg form-control @error('password') is-invalid @enderror"
                                            placeholder="{{ __('Senha') }}" name="password" required autocomplete="new-password">

                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-6">
                                        <input id="password-confirm" type="password" class="input-lg form-control"
                                            placeholder="{{ __('Confirmar Senha') }}" name="password_confirmation" required autocomplete="new-password">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group col-md-4 mb-3">
                                <button type="submit" class="btn btn-primary w-100">
                                    {{ __('Registrar') }}
                                </button>
                            </div>
                        </form>
					</div>
				</div>
			</div>
		</div>
	</section>
	<!--================End Register Box Area =================-->
</template>

<script type="text/javascript">

    function shopperRegisterArea(){[native/code]}
    shopperRegisterArea.shopperRegisterAreaViewModel = function(params) {
        var self = this;
    }

	ko.components.register('shopper-register-area', {
	    template: { element: 'template-shopper-register-area'},
	    viewModel: shopperRegisterArea.shopperRegisterAreaViewModel
	});
</script>
