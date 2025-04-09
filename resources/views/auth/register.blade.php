<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf
        
        <h3 class="text-center mb-4 text-black">Regístrate ahora</h3>

            <div class="form-group">
                <x-input-label class="mb-1 text-black" for="email" :value="__('Email')" />
                <div>
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    <x-text-input id="email" name="email" :value="old('email')" required type="email" class="form-control" placeholder="ejemplo@mail.com"/>
                </div>
            </div>
            <div class="form-group">
                <x-input-label class="mb-1 text-black" for="password" :value="__('Contraseña')" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
                <div>
                    <x-text-input type="password" class="form-control"  id="password" name="password" required autocomplete="new-password"/>
                </div>
            </div>

            <div class="form-group">
                <x-input-label class="mb-1 text-black" for="password_confirmation" :value="__('Confirmar contraseña')" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                <div>
                    <x-text-input type="password" class="form-control"  id="password_confirmation" name="password_confirmation" required autocomplete="new-password"/>
                </div>
            </div>

            <div class="form-group">
                <x-input-label class="mb-1 text-black" for="invitation_code" :value="__('Código de invitación (opcional)')" />
                <x-input-error :messages="$errors->get('invitation_code')" class="mt-2" />
                <div>
                    <x-text-input type="text" class="form-control"  id="invitation_code" name="invitation_code" autocomplete="new-password"/>
                </div>
            </div>

            <div class="form-group text-center mt-4">
                <x-primary-button class="btn btn-primary btn-block">{{ __('Registrar') }}</x-primary-button>
            </div>
    </form>

    <div class="new-account mt-3">
        <p>¿Ya tienes cuenta? <a class="text-primary" href="{{ route('login') }}">{{ __('Iniciar sesión') }}</a></p>
    </div>
</div>
</x-guest-layout>