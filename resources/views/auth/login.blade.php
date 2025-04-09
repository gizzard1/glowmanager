
<x-guest-layout>
    <x-auth-session-status class="mb-4" :status="session('status')" />
    <form method="POST" action="{{ route('login') }}" class="form-validate">
        @csrf
        <h3 class="text-center mb-4 text-black">Inicia sesión</h3>
        <div class="form-group">
            <x-input-label class="mb-1" :value="__('Email')" for="email"/>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
            <div>
                <x-text-input id="email" type="email" class="form-control" :value="old('email')"  required autocomplete="username" name="email"/>
            </div>
        </div>
        <div class="form-group">
            <x-input-label for="password" :value="__('Contraseña')"/>
            <x-text-input id="password" type="password" name="password"
            required autocomplete="current-password" class="form-control"/>
        </div>
        <div class="form-row d-flex justify-content-between mt-4 mb-2">
            <div class="form-group">
                <label for="remember_me" class="custom-control custom-checkbox ml-1">
                    <input type="checkbox" class="custom-control-input" id="remember_me" name="remember">
                    <span class="custom-control-label">{{ __('Recuérdame') }}</span>
                </label>
            </div>
            <div class="form-group">
                @if(Route::has('password.request'))
                <a href="{{ route('password.request') }}">¿Olvidaste tu contraseña?</a>
                @endif
            </div>
        </div>
        <div class="text-center form-group">
            <x-primary-button type="submit" class="btn btn-primary btn-block"  id="dz-signin-submit">{{ __('Iniciar Sesión') }}</x-primary-button>
        </div>
    </form>
<div class="new-account mt-3">
    <p>¿No tienes cuenta? <a class="text-primary"  href="{{ route('register') }}" >Registrarse</a></p>
</div>
</x-guest-layout>
