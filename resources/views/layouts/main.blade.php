<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/free.png') }}">

    <title>{{ config('app.name', 'Laravel') }}</title>
    @include('layouts.theme.webstyles')
    @livewireStyles
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
    
    {{ $slot  }}

 
    @include('layouts.theme.webscripts')
    @livewireScripts
<script>
    
    document.querySelectorAll('a[href^="#"').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault()

            const offset = 0; // Número de píxeles de espacio que deseas dejar arriba
            const targetId = this.getAttribute('href');
            const targetElement = document.querySelector(targetId);

            if (targetElement) {
                const offsetPosition = targetElement.offsetTop - offset;

                window.scrollTo({
                    top: offsetPosition,
                    behavior: 'smooth'
                });
            }
        })
    })
</script>

</body>

</html>
