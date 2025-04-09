<script>
document.addEventListener('DOMContentLoaded', function () {
    const questions = $(".question");
    const totalQuestions = questions.length - 1; // Excluye el resumen
    let currentQuestionIndex = 0;
    
    let userData = {
        name: '',
        serviceType: '',
        hairService: '',
        treatmentType: '',
        dyeType: '',
        makeupType: '',
        hairType:'',
        hairMeasurement:''
    };

    // Actualizar barra de progreso
    function updateProgressBar() {
        const progress = (currentQuestionIndex / totalQuestions) * 100;
        $("#progressBar").css("width", progress + "%");
    }

    // Función para avanzar
    function goToNextQuestion(current, next) {
        current.removeClass("active");
        next.addClass("active");
        currentQuestionIndex++;
        updateProgressBar();
    }

    // Función para retroceder
    function goToPrevQuestion(current, prev) {
        current.removeClass("active");
        prev.addClass("active");
        currentQuestionIndex--;
        updateProgressBar();
    }

    // Botón siguiente (para nombre)
    $(".next-btn").click(function() {
        let name = $("#nameInput").val().trim();
        if (name === "") {
            alert("Por favor ingresa tu nombre");
            return;
        }
        userData.name = name;
        
        let current = $(".question.active");
        let next = current.next(".question");
        goToNextQuestion(current, next);
    });

    // Selección de tipo de servicio
    $(".service-option").click(function() {
        $(".service-option").removeClass("active");
        $(this).addClass("active");
        userData.serviceType = $(this).data("service");
        
        let current = $(".question.active");
        let next;
        
        if (userData.serviceType === "hair") {
            next = $("[data-question='hair-service']");
        } else {
            next = $("[data-question='makeup-type']");
        }
        
        goToNextQuestion(current, next);
    });

    // Selección de servicio de cabello
    $(".hair-service-option").click(function() {
        $(".hair-service-option").removeClass("active");
        $(this).addClass("active");
        userData.hairService = $(this).data("hair-service");
        
        let current = $(".question.active");
        let next;
        
        if (userData.hairService === "treatment") {
            next = $("[data-question='treatment-type']");
        } else if (userData.hairService === "dye") {
            next = $("[data-question='dye-type']");
        } else {
            next = $("[data-question='hair-type']");
        }
        
        goToNextQuestion(current, next);
    });

    // Selección de tipo de tratamiento
    $(".treatment-option").click(function() {
        $(".treatment-option").removeClass("active");
        $(this).addClass("active");
        userData.treatmentType = $(this).data("treatment");
        
        let current = $(".question.active");
        let next = $("[data-question='hair-type']");
        goToNextQuestion(current, next);
    });

    // Selección de tipo de tinte
    $(".dye-option").click(function() {
        $(".dye-option").removeClass("active");
        $(this).addClass("active");
        userData.dyeType = $(this).data("dye");
        
        let current = $(".question.active");
        let next = $("[data-question='hair-type']");
        goToNextQuestion(current, next);
    });

    //Seleccion de tipo de cabello
    $(".hair-option").click(function(){
        $(".hair-option").removeClass("active");
        $(this).addClass("active");
        userData.hairType = $(this).data("hair");

        let current = $(".question.active");
        let next = $("[data-question='hair-measurement']");
        goToNextQuestion(current, next);
    });

    $(".measurement-option").click(function(){
        $(".measurement-option").removeClass("active");
        $(this).addClass("active");
        userData.hairMeasurement = $(this).data("msmt");
        showSummary();
    });

    // Selección de tipo de maquillaje
    $(".makeup-option").click(function() {
        $(".makeup-option").removeClass("active");
        $(this).addClass("active");
        userData.makeupType = $(this).data("makeup");
        showSummary();
    });

    // Botón Atrás
    $(".back-to-prev").click(function() {
        let current = $(".question.active");
        let prev;
        
        switch(current.data("question")) {
            case "service-type":
                prev = $("[data-question='name']");
                break;
            case "hair-service":
            case "makeup-type":
                prev = $("[data-question='service-type']");
                break;
            case "treatment-type":
            case "dye-type":
                prev = $("[data-question='hair-service']");
                break;
            case "hair-type":
                prev = $("[data-question='hair-service']");
                break;
            case "hair-measurement":
                prev = $("[data-question='hair-type']");
                break;
        }
        
        if (prev) {
            goToPrevQuestion(current, prev);
        }
    });

    // Mostrar resumen y redirigir a la página de reserva
    function showSummary() {
        let summaryText = `<p><strong>Hola ${userData.name},</strong></p><p>Has seleccionado: `;
        
        if (userData.serviceType === "hair") {
            summaryText += "<strong>Servicio de Cabello</strong> - ";
            
            if (userData.hairService === "cut") {
                summaryText += "Corte";
            } else if (userData.hairService === "treatment") {
                summaryText += `Tratamiento (${userData.treatmentType === "straightening" ? "Alaciado" : "Base"})`;
            } else if (userData.hairService === "dye") {
                let dyeText = "";
                switch(userData.dyeType) {
                    case "highlights": dyeText = "Mechas"; break;
                    case "rays": dyeText = "Rayos"; break;
                    case "babylights": dyeText = "Babylights"; break;
                    case "balayage": dyeText = "Balayage"; break;
                    case "full-color": dyeText = "Color completo"; break;
                }
                summaryText += `Tinte (${dyeText})`;
            }

            let hairTypeText = "";
            switch(userData.hairType) {
                case "lacio": hairTypeText = "Lacio"; break;
                case "quebrado": hairTypeText = "Quebrado"; break;
                case "ondulado": hairTypeText = "Ondulado"; break;
                case "rizado3a": hairTypeText = "Rizado 3A"; break;
                case "rizado4a": hairTypeText = "Rizado 4A"; break;
            }

            let hairMeasurementText = "";
            switch(userData.hairMeasurement) {
                case "corto": hairMeasurementText = "Corto"; break;
                case "medio": hairMeasurementText = "Medio"; break;
                case "largo": hairMeasurementText = "Largo"; break;
                case "extra-largo": hairMeasurementText = "Extra largo"; break;
            }

            summaryText += `<br>Tipo de cabello: ${hairTypeText}<br>Largo: ${hairMeasurementText}`;

        } else if (userData.serviceType === "makeup") {
            summaryText += "<strong>Servicio de Maquillaje</strong> - ";
            switch(userData.makeupType) {
                case "xv-years": summaryText += "XV años"; break;
                case "wedding": summaryText += "Bodas"; break;
                case "social-events": summaryText += "Eventos sociales"; break;
            }
        }
        
        summaryText += "</p><p>¡Gracias por confiar en nosotros!</p>";
        summaryText += "<button class='btn btn-custom mt-4' id='confirm-btn'>Continuar con la reserva</button>";
        $("#summary-text").html(summaryText);
        
        let current = $(".question.active");
        let next = $("[data-question='summary']");

        // Agregar evento click al botón de confirmación
        $(document).on('click', '#confirm-btn', function() {
        });
        goToNextQuestion(current, next);

        // Agregar evento al botón de confirmación
        $("#confirm-btn").click(function() {
            const queryParams = {  // Usa un objeto en lugar de un array
                name: userData.name,
                serviceType: userData.serviceType
            };

            if (userData.serviceType === 'hair') {
                queryParams.hairService = userData.hairService;
                queryParams.hairType = userData.hairType;
                queryParams.hairMeasurement = userData.hairMeasurement;

                if (userData.hairService === 'treatment') {
                    queryParams.treatmentType = userData.treatmentType;
                } else if (userData.hairService === 'dye') {
                    queryParams.dyeType = userData.dyeType;
                }
            } else {
                queryParams.makeupType = userData.makeupType;
            }

            Livewire.emit('returnData', queryParams);
        });

    }

    // Reiniciar cuestionario
    $("#restart-btn").click(function() {
        location.reload();
    });
});
window.addEventListener('orderByHour', function(list) {
    selElem = document.getElementById('hora');
    var tmpAry = new Array();
    for (var i=0;i<selElem.options.length;i++) {
        tmpAry[i] = new Array();
        tmpAry[i][0] = selElem.options[i].text;
        tmpAry[i][1] = selElem.options[i].value;
    }
    tmpAry.sort();
    while (selElem.options.length > 0) {
        selElem.options[0] = null;
    }
    for (var i=0;i<tmpAry.length;i++) {
        var op = new Option(tmpAry[i][0], tmpAry[i][1]);
        selElem.options[i] = op;
    }
    return;
});
</script>