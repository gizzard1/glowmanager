
    <!-- jQuery -->
    <script src="{{asset('js/web/jquery-2.1.0.min.js')}}"></script>

    <!-- Bootstrap -->
    <script src="{{asset('js/web/bootstrap.min.js')}}"></script>

    <!-- Plugins -->
    <script src="{{asset('js/web/owl-carousel.js')}}"></script>
    <script src="{{asset('js/web/scrollreveal.min.js')}}"></script>
    

    <!-- Global Init -->
    <script src="{{asset('js/web/custom.js')}}"></script>

    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>

    <!-- Template Javascript -->
    <script src="{{asset('js/web/main.js')}}"></script>
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