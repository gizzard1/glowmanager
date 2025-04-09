<section class="card">
    <header  class="card-header">
        <div class="separator" style="background-color:#6E6E6E"></div>
            <div class="mr-auto">
                <h4 class="card-title mb-1">
                    {{ __('Información de Perfil') }}
                </h4>

                <p class="fs-14 mb-0">
                    {{ __("Actualiza tu nombre y/o correo electrónico") }}
                </p>
            </div>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6 card-body p-3">
        @csrf
        @method('patch')

        <x-text-input id="name" name="name" style="background-color: transparent  !important;border-color:transparent !important;border-bottom:1px solid black !important;" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        <div>
            <x-input-label for="name" :value="__('Nombre')" />
        </div>

        <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" style="background-color: transparent  !important;border-color:transparent !important;border-bottom:1px solid black !important;"/>
            <x-input-error class="mt-2" :messages="$errors->get('email')" />
        <div>
            <x-input-label for="email" :value="__('Email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <x-primary-button class="btn btn-sm btn-info float-right save" style="background-color:#B59377; border-color:#B59377;position: relative;top: 63px;left: 0px;">{{ __('Save') }}</x-primary-button>

        @if (session('status') === 'profile-updated')
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
