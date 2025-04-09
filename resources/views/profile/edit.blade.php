<x-app-layout >
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div  >
        <div class="row">
            <div class="col-sm-12 col-md-6">
                @include('profile.partials.update-password-form')
            </div>
            <div class="col-sm-12 col-md-6">
                @include('profile.partials.update-profile-information-form')
            </div>

        </div>
    </div>
</x-app-layout>
