<x-guest-layout>
    <form method="POST" action="{{ route('password.store') }}">
        @csrf
        
        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <h3 class="text-center mb-4 text-black">Crea una nueva contraseña</h3>

            <div class="form-group">
                <x-input-label class="mb-1 text-black" for="email" :value="__('Email')" />
                    <x-text-input class="form-control" id="email" type="email" name="email" :value="old('email', $request->email)" required autofocus autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>
            
            <div class="form-group">
                <x-input-label class="mb-1 text-black" for="password" :value="__('Contraseña')" />
                <x-text-input type="password" class="form-control"  id="password" name="password" required autocomplete="new-password"/>
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div class="form-group">
                <x-input-label class="mb-1 text-black" for="password_confirmation" :value="__('Confirmar contraseña')" />
                <x-text-input type="password" class="form-control"  id="password_confirmation" name="password_confirmation" required autocomplete="new-password"/>
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>

            <div class="form-group text-center mt-4">
                <x-primary-button class="btn btn-primary btn-block">{{ __('Restablecer') }}</x-primary-button>
            </div>
    </form>
</x-guest-layout>


<footer>
    <a href="\aviso-privacidad">
        <h4>Aviso de privacidad</h4>
    </a>
    <a href="\seguridad">
        <h4>Seguridad</h4>
    </a>
    <a href="\condiciones-uso">
        <h4>Condiciones de uso</h4>
    </a>
</footer>
<style>
    footer{
    display: flex;
    flex-direction: row;
    transform: translate(38%, 70%);
    column-gap: 3rem;
    }
    footer h4{
        font-size: smaller;
    }
</style>
