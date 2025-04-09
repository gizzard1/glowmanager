<div class="quiz-container">
    <div class="progress-container">
        <div class="progress-bar" id="progressBar"></div>
    </div>
    
    <h2>¡Crea tu experiencia de belleza personalizada!</h2>
    <div id="questions">
        <!-- Pregunta 1: Nombre -->
        <div class="question active" data-question="name">
            <h4>¿Cuál es tu nombre?</h4>
            <input type="text" class="mt-3 form-control" wire:model="customer.first_name" id="nameInput" placeholder="Escribe tu nombre...">
            <button class="btn btn-custom mt-4 next-btn">Siguiente</button>
        </div>

        <!-- Pregunta 2: Tipo de servicio -->
        <div class="question" data-question="service-type">
            <h4>¿Qué servicio requieres?</h4>
            <button class="btn btn-option service-option" data-service="hair">Cabello</button>
            <button class="btn btn-option service-option" data-service="makeup">Maquillaje</button>
            <button class="btn back-btn back-to-prev"><i class="fas fa-arrow-left"></i> Atrás</button>
        </div>

        <!-- Preguntas para CABELLO -->
        <!-- Pregunta 3: Servicio de cabello -->
        <div class="question" data-question="hair-service">
            <h4>¿Qué servicio de cabello necesitas?</h4>
            <button class="btn btn-option hair-service-option" data-hair-service="cut">Cortes</button>
            <button class="btn btn-option hair-service-option" data-hair-service="treatment">Tratamientos</button>
            <button class="btn btn-option hair-service-option" data-hair-service="dye">Tintes</button>
            <button class="btn back-btn back-to-prev"><i class="fas fa-arrow-left"></i> Atrás</button>
        </div>

        <!-- Pregunta 4: Tipo de tratamiento -->
        <div class="question" data-question="treatment-type">
            <h4>¿Qué tipo de tratamiento necesitas?</h4>
            <button class="btn btn-option treatment-option" data-treatment="straightening">Alaciado</button>
            <button class="btn btn-option treatment-option" data-treatment="base">Base</button>
            <button class="btn back-btn back-to-prev"><i class="fas fa-arrow-left"></i> Atrás</button>
        </div>

        <!-- Pregunta 5: Tipo de tinte -->
        <div class="question" data-question="dye-type">
            <h4>¿Qué tipo de tinte deseas?</h4>
            <button class="btn btn-option dye-option" data-dye="highlights">Mechas</button>
            <button class="btn btn-option dye-option" data-dye="rays">Rayos</button>
            <button class="btn btn-option dye-option" data-dye="babylights">Babylights</button>
            <button class="btn btn-option dye-option" data-dye="balayage">Balayage</button>
            <button class="btn btn-option dye-option" data-dye="full-color">Color completo</button>
            <button class="btn back-btn back-to-prev"><i class="fas fa-arrow-left"></i> Atrás</button>
        </div>

        <!-- Pregunta tipo de cabello -->
        <div class="question" data-question="hair-type">
            <h4>Selecciona el tipo de cabello que tienes</h4>
            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 15px; max-width: 600px; margin: 0 auto;">
                <button class="btn btn-option hair-option" data-hair="lacio"><img src="{{asset('images/reservaciones/1.png')}}" alt="1" class="hair-image"></button>
                <button class="btn btn-option hair-option" data-hair="quebrado"><img src="{{asset('images/reservaciones/2.png')}}" alt="2" class="hair-image"></button>
                <button class="btn btn-option hair-option" data-hair="ondulado"><img src="{{asset('images/reservaciones/3.png')}}" alt="2b" class="hair-image"></button>
                <button class="btn btn-option hair-option" data-hair="rizado3a"><img src="{{asset('images/reservaciones/4.png')}}" alt="3a" class="hair-image"></button>
                <button class="btn btn-option hair-option" data-hair="rizado4a"><img src="{{asset('images/reservaciones/5.png')}}" alt="4a" class="hair-image"></button>
            </div>
            <button class="btn back-btn back-to-prev"><i class="fas fa-arrow-left"></i> Atrás</button>
        </div>

        <!--Largo del cabello-->
        <div class="question" data-question="hair-measurement">
            <h4>Selecciona la medida a la que se aproxima tu cabello</h4>
            <div style="display: flex; align-items: start; gap: 20px; margin: 10px 0;">
                <div style="flex: 1; max-width: 300px;">
                    <img src="{{asset('images/reservaciones/largo_cabello.jpg')}}" alt="Medidas" style="width: 100%; height: auto; display: block;">
                </div>
                <div style="flex: 1; display: flex; flex-direction: column; gap: 10px;">
                    <button class="btn btn-option measurement-option" data-msmt="corto">Corto</button>
                    <button class="btn btn-option measurement-option" data-msmt="medio">Medio</button>
                    <button class="btn btn-option measurement-option" data-msmt="largo">Largo</button>
                    <button class="btn btn-option measurement-option" data-msmt="extra-largo">Extra largo</button>
                </div>
            </div>
            <button class="btn back-btn back-to-prev"><i class="fas fa-arrow-left"></i> Atrás</button>
        </div>

        <!-- Pregunta 6: Tipo de corte -->

        <!-- Preguntas para MAQUILLAJE -->
        <!-- Pregunta 3: Tipo de maquillaje -->
        <div class="question" data-question="makeup-type">
            <h4>¿Qué tipo de maquillaje necesitas?</h4>
            <button class="btn btn-option makeup-option" data-makeup="xv-years">XV años</button>
            <button class="btn btn-option makeup-option" data-makeup="wedding">Bodas</button>
            <button class="btn btn-option makeup-option" data-makeup="social-events">Eventos sociales</button>
            <button class="btn back-btn back-to-prev"><i class="fas fa-arrow-left"></i> Atrás</button>
        </div>

        <!-- Pregunta final -->
        <div class="question" data-question="summary">
            <h4>¡Gracias por completar nuestro cuestionario!</h4>
            <div id="summary-text"></div>
            <button class="btn btn-custom mt-4" id="restart-btn">Comenzar de nuevo</button>
        </div>
    </div>
</div>