<x-guest-layout>
    <h3 class="text-center mb-4 text-black">Contraseña olvidada</h3>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        

        <!-- Email Address -->
        <div class="form-group">
            <x-input-label class="mb-1" for="email" :value="__('Email')" />
            <x-text-input  class="form-control"  id="email" type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="text-center form-group">
            
            <x-primary-button class="btn btn-primary btn-block">
                {{ __('Enviar link') }}
            </x-primary-button>
        </div>
    </form>
    <div class="new-account mt-3">
        <p><a class="text-primary" href="{{ route('login') }}">{{ __('Iniciar sesión') }}</a></p>
    </div>

    
</x-guest-layout>
