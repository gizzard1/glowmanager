<!--fomulario-->
<div class="contenedor">
    <div class="formulario">
        <h2>Reservar Cita</h2>
        <div id="service-summary" class="mb-4" wire:ignore></div>
        
        <div class="container">
            <div class="row">
                <div name="formReserva" class="col-md-12">
                    <div class="form-group">
                        <label>Número de teléfono: </label>
                        <input class="c2" type="text" name="telefono" id="telefono" placeholder="Ingresa tu Número" wire:model="customer.phone">
                        @error('customer.phone') <span class="text-danger">*Corrige este campo* </span> @enderror
                    </div>
                    <div class="form-group">
                        <label>Correo electrónico: </label>
                        <input class="c2" type="email" name="correo" id="correo" placeholder="Ingresa tu Correo" wire:model="customer.email">
                        @error('customer.email') <span class="text-danger">*Corrige este campo* </span> @enderror
                    </div>
                    <div class="form-group">
                        <label>Elige un día en el calendario:</label>
                        <input class="c2" 
                            type="date" 
                            name="fecha" 
                            id="dia" 
                            wire:model="reservationDate" 
                            value="{{ old('fecha', \Carbon\Carbon::parse($reservationDate)->toDateString()) }}" 
                            min="{{ \Carbon\Carbon::today()->toDateString() }}"> 

                        @error('reservationDate') 
                            <span class="text-danger">*Corrige este campo* </span> 
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Horarios disponibles:</label>
                        <select class="c2" name="hora" id="hora" wire:model="reservationTime">
                            @foreach($availableSchedules as $schedule)
                                <option value="{{ $schedule }}">{{ $schedule }}</option>
                            @endforeach
                        </select> 
                        @error('reservationTime') <span class="text-danger">*Corrige este campo* </span> @enderror
                    </div>
                    <br>
                    <div class="form-group">
                        <label>Comentarios (opcional):</label>
                        <textarea class="c2" name="comentario" id="comentario" cols="30" rows="5" wire:model="description"></textarea>
                        @error('description') <span class="text-danger">*Corrige este campo* </span> @enderror
                    </div>
                    <a class="btn" wire:click="storeReservation">Reservar Ahora</a>
                    <a class="btn" href="{{ route('reservar') }}" style="margin-top: 10px;">Volver al Inicio</a>
                </div>
            </div>
        </div>  
    </div> 

</div> 

<script>
window.addEventListener('reservationForm', function(params) {   
    const data = params.detail; // Ahora los datos están en un objeto directamente

    // Redirigir si los parámetros son inválidos
    if (!data.name?.trim() || !data.serviceType?.trim()) {
        window.location.href = 'index.html';
        return;
    }

    let summaryHtml = `<p>Hola ${data.name}, confirma tu reserva para:</p>`;

    if (data.serviceType === 'hair') {
        let serviceText = '';

        if (data.hairService === 'cut') {
            serviceText = 'Corte';
        } else if (data.hairService === 'treatment' && data.treatmentType) {
            serviceText = `Tratamiento (${data.treatmentType === 'straightening' ? 'Alaciado' : 'Base'})`;
        } else if (data.hairService === 'dye' && data.dyeType) {
            const dyeTexts = {
                'highlights': 'Mechas',
                'rays': 'Rayos',
                'babylights': 'Babylights',
                'balayage': 'Balayage',
                'full-color': 'Color completo'
            };
            serviceText = `Tinte (${dyeTexts[data.dyeType] || 'Desconocido'})`;
        }

        const hairTypes = {
            'lacio': 'Lacio',
            'quebrado': 'Quebrado',
            'ondulado': 'Ondulado',
            'rizado3a': 'Rizado 3A',
            'rizado4a': 'Rizado 4A'
        };

        const hairMeasurements = {
            'corto': 'Corto',
            'medio': 'Medio',
            'largo': 'Largo',
            'extra-largo': 'Extra largo'
        };

        summaryHtml += `<p>Servicio de Cabello - ${serviceText || 'Sin especificar'}<br>`;
        summaryHtml += `Tipo de cabello: ${hairTypes[data.hairType] || 'No especificado'}<br>`;
        summaryHtml += `Largo: ${hairMeasurements[data.hairMeasurement] || 'No especificado'}</p>`;

    } else if (data.serviceType === 'makeup') {
        const makeupTexts = {
            'xv-years': 'XV años',
            'wedding': 'Bodas',
            'social-events': 'Eventos sociales'
        };

        summaryHtml += `<p>Servicio de Maquillaje - ${makeupTexts[data.makeupType] || 'No especificado'}</p>`;
    }

    $('#service-summary').html(summaryHtml);
});
 
</script>
