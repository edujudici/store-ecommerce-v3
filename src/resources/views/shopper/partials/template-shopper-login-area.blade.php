<shopper-login-area></shopper-login-area>
<template id="template-shopper-login-area">
    <!--================Login Box Area =================-->
	<section class="login_box_area">
		<div class="container">
			<div class="row">
				<div class="col-lg-6">
					<div class="login_box_img">
						<img class="img-fluid" src="{{asset('assets/site/img/login.jpg')}}" alt="">
						<div class="hover">
							<h4>Novo no nosso site?</h4>
							<p>Crie uma conta para efetuar compras e acompanhar os status de seus pedidos.</p>
							<a class="primary-btn" href="{{ route('register') }}">Criar uma conta</a>
						</div>
					</div>
				</div>
				<div class="col-lg-6">
					<div class="login_form_inner">
                        <h3>Fazer login</h3>
                        <form method="POST" action="{{ route('login') }}" class="row login_form">
                            @csrf
							<div class="col-md-12 form-group">
                                <input id="email" type="email" class="input-lg form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                                    aria-describedby="emailHelp" placeholder="{{ __('E-Mail') }}" name="email" value="{{ old('email') }}" required autofocus>

                                @if ($errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
								{{--  <input type="text" class="form-control" id="name" name="name" placeholder="Username" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Username'">  --}}
							</div>
							<div class="col-md-12 form-group">
                                <input id="password" type="password" class="input-lg form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"
                                    placeholder="{{ __('Senha') }}" name="password" required>

                                @if ($errors->has('password'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
								{{--  <input type="text" class="form-control" id="name" name="name" placeholder="Password" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Password'">  --}}
							</div>
							<div class="col-md-12 form-group">
								<div class="creat_account">
									<input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }} />
                                    <label for="f-option2">{{ __('Mantenha-me conectado') }}</label>
								</div>
							</div>
							<div class="col-md-12 form-group">
                                <button type="submit" value="submit" class="primary-btn">{{ __('Entrar') }}</button>

                                @if (Route::has('password.request'))
                                    <p><a class="text-blue" href="{{ route('password.request') }}">{{ __('Esqueceu a senha?') }}</a></p>
                                @endif
							</div>

                            <div class="col-md-12 form-group">
                                <a href="{{ route('shopper.google.login') }}" class="mt-0">
                                    <img src="https://developers.google.com/identity/images/g-logo.png" width="37" height="37">
                                    <span>{{ __('Continuar com Google ') }}</span>
                                </a>
                            </div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</section>
	<!--================End Login Box Area =================-->
</template>

<script type="text/javascript">

    function shopperLoginArea(){[native/code]}
    shopperLoginArea.shopperLoginAreaViewModel = function(params) {
        var self = this;
    }

	ko.components.register('shopper-login-area', {
	    template: { element: 'template-shopper-login-area'},
	    viewModel: shopperLoginArea.shopperLoginAreaViewModel
	});
</script>
