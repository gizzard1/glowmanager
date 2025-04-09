<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/free.png') }}">

    <title>{{ config('app.name', 'Laravel') }}</title>
    @include('layouts.theme.loginstyles')
    <livewire:styles />

</head>

<body>

    <!-- ***** Preloader Start ***** -->
    <div id="preloader">
        <div class="jumper">
            <div></div>
            <div></div>
            <div></div>
        </div>
    </div>
    <div>
        <div class="authincation d-flex flex-column flex-lg-row flex-column-fluid">
            <div class="container flex-row-fluid d-flex flex-column justify-content-center position-relative overflow-hidden p-7 mx-auto">
                <div class="d-flex justify-content-center h-100 align-items-center">
                    <div class="authincation-content style-2">
                        <div class="row no-gutters">
                            <div class="col-xl-12 tab-content">
                                <div class="auth-form tab-pane fade show active  form-validation">
                                
                                        {{ $slot }}
                                
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
 
    @include('layouts.theme.loginscripts')
    <livewire:scripts />

    @yield('scripts')
    @stack('my-scripts')

</body>

</html>