@extends('shopper.baseTemplate')

@section('shopperContent')
<div class="login_form_inner" style="padding: 20px 0;">
    <h2 class="text-center mb-4">Dados cadastrais</h2>

    @if(Session::has('success'))
        <div class="col-md-12">
            <div class="alert alert-success" style="opacity: inherit; right: 0;">
                {{ session()->get('success') }}
            </div>
        </div>
    @endif

    <form method="POST" action="{{ route('shopper.data.update') }}">
        @csrf

        <div class="col-md-12">
            <div class="row">
                <div class="form-group col-md-6">
                    <input id="name" type="text" class="input-lg form-control @error('name') is-invalid @enderror"
                        placeholder="{{ __('Nome') }}" name="name" value="{{ old('name') ?? $data->name }}" required autocomplete="name" autofocus>

                    @error('name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group col-md-6">
                    <input id="surname" type="text" class="input-lg form-control @error('surname') is-invalid @enderror"
                        placeholder="{{ __('Sobrenome') }}" name="surname" value="{{ old('surname') ?? $data->surname }}" required autocomplete="surname" autofocus>

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
                placeholder="{{ __('E-Mail') }}" name="email" value="{{ old('email') ?? $data->email }}" required autocomplete="email">

            @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <div class="form-group col-md-2 mb-0 text-left">
            <button type="submit" class="btn btn-primary">
                {{ __('Salvar dados') }}
            </button>
        </div>
    </form>


    <form method="POST" action="{{ route('shopper.password.update') }}" class="mt-5">
        @csrf

        @if(Session::has('success-password'))
            <div class="col-md-12">
                <div class="alert alert-success" style="opacity: inherit; right: 0;">
                    {{ session()->get('success-password') }}
                </div>
            </div>
        @endif

        @if(session()->has('error'))
            <div class="col-md-12">
                <span class="alert alert-danger" style="opacity: inherit; right: 0;">
                    {{ session()->get('error') }}
                </span>
            </div>
        @endif

        <div class="col-md-12">
            <div class="row">
                @if(! session('login_socialite'))
                <div class="form-group col-md-4">
                    <input id="password" type="password" class="input-lg form-control @error('current_password') is-invalid @enderror"
                        placeholder="{{ __('Senha Atual') }}" name="current_password" value="{{ old('current_password') }}">

                    @error('current_password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                @endif
                <div class="form-group col-md-4">
                    <input id="password" type="password" class="input-lg form-control @error('password') is-invalid @enderror"
                        placeholder="{{ __('Nova Senha') }}" name="password" value="{{ old('password') }}">

                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="form-group col-md-4">
                    <input id="password-confirm" type="password" class="input-lg form-control @error('password_confirmation') is-invalid @enderror"
                        placeholder="{{ __('Confirmar Nova Senha') }}" name="password_confirmation" value="{{ old('password_confirmation') }}">

                    @error('password_confirmation')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
        </div>

        <div class="form-group col-md-2 mb-0 text-left">
            <button type="submit" class="btn btn-primary">
                {{ __('Alterar senha') }}
            </button>
        </div>
    </form>

</div>
@endsection
