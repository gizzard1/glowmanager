<section class="card">
    <header  class="card-header">
        <div class="separator" style="background-color:#6E6E6E"></div>
            <div class="mr-auto">
                <h4 class="card-title mb-1">
                    {{ __('Actualizar contraseña') }}
                </h4>

                <p class="fs-14 mb-0">
                    {{ __('Asegúrate de usar una contraseña difícil de adivinar') }}
                </p>
            </div>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6 card-body p-3">
        @csrf
        @method('put')

        <x-text-input style="background-color: transparent  !important;border-color:transparent !important;border-bottom:1px solid black !important;" id="update_password_current_password" name="current_password" type="password" class="mt-1 block w-full" autocomplete="current-password" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        <div>
            <x-input-label for="update_password_current_password" :value="__('Contraseña actual')" />
        </div>

        <x-text-input style="background-color: transparent  !important;border-color:transparent !important;border-bottom:1px solid black !important;" id="update_password_password" name="password" type="password" class="mt-1 block w-full" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
        <div>
            <x-input-label for="update_password_password" :value="__('Nueva contraseña')" />
        </div>

        <x-text-input style="background-color: transparent  !important;border-color:transparent !important;border-bottom:1px solid black !important;" id="update_password_password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
        <div>
            <x-input-label for="update_password_password_confirmation" :value="__('Confirmar contraseña')" />
        </div>

        
            <x-primary-button class="btn btn-sm btn-info float-right save" style="background-color:#B59377; border-color:#B59377">{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Saved.') }}</p>
            @endif
    </form>
</section>
