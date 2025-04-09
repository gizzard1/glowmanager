<div>
    <div class="card" style="font-size: small; min-height: 2rem;">
        <div class="row">
            <div class="col-12" style="height: fit-content;">
            
                <div style="width: 100%; margin: 0; padding: 0;">
                    <div style="padding: 1rem;">
                        <div style="transform: translate(0%,-7%);">
                            <div class="row-1" id="controls">
                                <button class="button-style mr-0" wire:click="prevDay()"><</button>
                                <button class="button-style ml-0" wire:click="nextDay()">></button>      
                                <button id="hoy" class="button-style wider float-right" wire:click="loadFecha()">Hoy</button>                  
                                
                <div wire:model="currentDateC" id="flatResource" class="flatpickrInd" wire:change.prevent="dateSelected">
                <h5 class="center mt-2 ml-4 mr-4 float-right " id="flatResource" style="color:#9D1466;justify-content: space-between;transform: translate(10px, 10px);" >{{ $currentDate }}</h5>
                </div>


                            </div>
                        </div>
                    </div>
                    <div class="table-responsive calendar-resource" id="agenda">
                        <table class="table-scroll table tick-borders" style="min-width: -webkit-fill-available">
                            <thead style="width: 100%;display:table-header;border-top: 2px solid #f5f5f5;">
                                <tr class="text-center" >
                                    <th width="70"></th>
                                    @foreach($empleados as $empleado)
                                    @if($empleado->is_active)
                                        <th style="font-weight:300;height:auto;cursor:default" class="casilla1 sticky-top">{{ $empleado->first_name }} {{ $empleado->last_name }}</th>
                                    @endif
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody style="width: auto;text-align-last: center;">
                                @foreach($horas as $hora)
                                <tr data-hour="{{ $hora }}">
                                    <td width="70" class="float-left sticky-left">{{ $hora }}</td>
                                    @foreach($empleados as $empleado)
                                        @if($empleado->is_active==1)
                                            <td wire:ignore.self class="casilla trigger"  data-hora="{{ $hora }}"  id="casilla" value="('{{ $hora }}', '{{ $empleado->id }}')" >
                                                @if(isset($citas))
                                                    @foreach($citas[$empleado->id] as $cita)
                                                        @if(isset($cita['start']))
                                                            <!-- Acceder a la propiedad start del array -->
                                                            @php
                                                                $startTime = $cita['start'];

                                                                $horario = explode(":",$hora);

                                                                // Extract the time components from currentDateC
                                                                $hour = $horario[0];
                                                                $minute = $horario[1];
                                                                $compareTime = $currentDateC->setTime($hour, $minute, '00');

                                                                $compareTime = Carbon\Carbon::parse($compareTime);
                                                                // Calculate the interval range
                                                                $intervalStart = $compareTime->copy()->subMinutes(7.5);
                                                                $intervalEnd = $compareTime->copy()->addMinutes(7.5);

                                                            @endphp

                                                            @if($startTime->between($intervalStart, $intervalEnd))
                                                                <div class="evento" id="evento-{{ $cita->id }}" data-duration="{{$cita['duration']}}" value="{{ $cita->id }}" draggable="true" 
                                                                title="Horario: {{ Carbon\Carbon::parse($cita['start'])->format('H:i') }}-{{ Carbon\Carbon::parse($cita['end'])->format('H:i') }}"
                                                                data-toggle="popover" 
                                                                data-trigger="hover" 
                                                                data-content="{!! $cita->date->status==='Cancelada' ? 'Motivo de Cancelación: ' . $cita->date->motivoCancelacion . '<br>' : '' !!} {{ isset($cita->date->customer) ? $cita->date->customer->first_name : 'Cliente eliminado' }} {{ isset($cita->date->customer) ? $cita->date->customer->last_name : '' }}<br>Servicio: {{ $cita->title }}<br>{!! $cita->categorias_cliente ? 'Descripción del cliente: ' . $cita->categorias_cliente . '<br>' : '' !!}{!! $cita->date->description ? 'Recordatorio: ' . $cita->date->description . '<br>' : '' !!}Precio de la cita: ${{ $cita->date->total }}">
                                                                    <div class="event-header" style="background-color:{{ ($cita->date->status === 'Pendiente' && $cita->selected ? '#ff9900' : ($cita->date->status === 'Pagada' && $cita->selected ? '#278d46' : ($cita->date->status === 'Agendada' || ($cita->date->status == 'Pagada' && !$cita->selected) || $cita->date->status == 'Confirmada' ? '#1d3557' : ($cita->date->status === 'Cancelada' ? '#e63946' : '#868E96')))
                                                                    ) }};">
                                                                        {{ Carbon\Carbon::parse($cita['start'])->format('H:i') }}-{{ Carbon\Carbon::parse($cita['end'])->format('H:i') }}
                                                                    </div>    
                                                                
                                                                    <div class="description">
                                                                        <div>
                                                                            {{ isset($cita->date->customer) ? $cita->date->customer->first_name : 'Cliente eliminado' }}
                                                                        </div>
                                                                        @if($cita->date->description!==null)
                                                                        <div>
                                                                            {{ $cita->date->description }}
                                                                        </div>
                                                                        @endif
                                                                        <div>
                                                                            {{ $cita->title }}
                                                                        </div>
                                                                    </div>

                                                                </div>
                                                            @endif
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </td>
                                        @endif
                                    @endforeach
                                </tr>
                                @php
                                if (!function_exists('renderTimeSlotRow')) {
                                    function renderTimeSlotRow($displayHora, $empleados, $citas) {
                                        $output = '<tr>';
                                            $output .= '<td width="70"></td>';
                                                foreach ($empleados as $empleado) {
                                                    if($empleado->is_active){
                                                        $output .= '<td wire:ignore.self data-hora="' . $displayHora . '" class="casilla trigger" id="casilla" value="(\'' . $displayHora . '\', \'' . $empleado->id . '\')">';
                                                        if(isset($citas)){
                                            
                                                            foreach ($citas[$empleado->id] as $cita) {
                                                                $startTime = $cita['start'];

                                                                $horario = explode(":",$displayHora);

                                                                // Extract the time components from currentDateC
                                                                $hour = $horario[0];
                                                                $minute = $horario[1];
                                                                $compareTime = $cita['start']->copy()->setTime($hour, $minute, '00');

                                                                $compareTime = Carbon\Carbon::parse($compareTime);
                                                                // Calculate the interval range
                                                                $intervalStart = $compareTime->copy()->subMinutes(7.5);
                                                                $intervalEnd = $compareTime->copy()->addMinutes(7.5);

                                                                if($startTime->between($intervalStart, $intervalEnd)){
                                                                    $output .= '<div class="evento"';
                                                                    $output .= 'data-duration="' . $cita['duration'] . '" title="Horario: ' . Carbon\Carbon::parse($cita['start'])->format('H:i') . '-' . Carbon\Carbon::parse($cita['end'])->format('H:i') . '"';
                                                                    $output .= ' data-toggle="popover" data-trigger="hover"';
                                                                    $output .= ' data-content="';

                                                                    if ($cita->date->status === 'Cancelada') {
                                                                        $output .= 'Motivo de Cancelación: ' . htmlspecialchars($cita->date->motivoCancelacion, ENT_QUOTES, 'UTF-8') . '<br>';
                                                                    }

                                                                    if (isset($cita->date->customer)) {
                                                                        $firstName = htmlspecialchars($cita->date->customer->first_name, ENT_QUOTES, 'UTF-8');
                                                                        $lastName = htmlspecialchars($cita->date->customer->last_name, ENT_QUOTES, 'UTF-8');
                                                                        $output .= "{$firstName} {$lastName}<br>";
                                                                    } else {
                                                                        $output .= 'Cliente eliminado<br>';
                                                                    }


                                                                    $output .= 'Servicio: ' . htmlspecialchars($cita->title, ENT_QUOTES, 'UTF-8') . '<br>';

                                                                    if ($cita->categorias_cliente) {
                                                                        $output .= 'Descripción del cliente: ' . htmlspecialchars($cita->categorias_cliente, ENT_QUOTES, 'UTF-8') . '<br>';
                                                                    }
                                                                    if ($cita->date->description) {
                                                                        $output .= 'Recordatorio: ' . htmlspecialchars($cita->date->description, ENT_QUOTES, 'UTF-8') . '<br>';
                                                                    }

                                                                    $output .= 'Precio de la cita: $' . htmlspecialchars($cita->date->total, ENT_QUOTES, 'UTF-8');
                                                                    $output .= '">';

                                                                    $output .= '<div class="event-header" style="background-color:' . 
                                                                        ($cita->date->status === 'Pendiente' && $cita->selected ? '#ff9900' : 
                                                                        ($cita->date->status === 'Pagada' && $cita->selected ? '#278d46' : 
                                                                        ($cita->date->status === 'Agendada' || 
                                                                        ($cita->date->status == 'Pagada' && !$cita->selected) || 
                                                                        $cita->date->status == 'Confirmada' ? '#1d3557' : 
                                                                        ($cita->date->status === 'Cancelada' ? '#e63946' : '#868E96')))) . 
                                                                        ';">';
                                                                    $output .= Carbon\Carbon::parse($cita['start'])->format('H:i') . '-' . Carbon\Carbon::parse($cita['end'])->format('H:i');
                                                                    $output .= '</div>';
                                                                    $output .= '<div class="description" data-duration="' . $cita['duration'] . '">';
                                                                    $output .= '<div>';
                                                                    if(isset($cita->date->customer)){
                                                                        $output .= $cita->date->customer->first_name;
                                                                    }else{
                                                                        'Cliente eliminado';
                                                                    }
                                                                    $output .= '</div>';
                                                                    if($cita->date->description!==null){
                                                                        $output .= '<div>' . $cita->date->description . '</div>';
                                                                    }
                                                                    $output .= '<div>' . $cita->title . '</div>';
                                                                    $output .= '</div>';
                                                                    $output .= '<div class="resize-handle"></div>';
                                                                    $output .= '</div>';
                                                                }
                                                            }
                                                        }
                                                    $output .= '</td>';
                                            
                                                    }
                                                }
                                                $output .= '</tr>';
                                                return $output;
                                    }
                                }
                                @endphp

                                {!! renderTimeSlotRow(str_replace(':00', ':15', $hora), $empleados, $citas) !!}
                                {!! renderTimeSlotRow(str_replace(':00', ':30', $hora), $empleados, $citas) !!}
                                {!! renderTimeSlotRow(str_replace(':00', ':45', $hora), $empleados, $citas) !!}
                                @endforeach
                            </tbody>

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Estilo eventos */
.trigger {
    cursor: pointer;
}
.col-3 {
    flex: 0 0 23% !important;
    max-width: 23% !important;
}
.col-9 {
    flex: 0 0 77% !important;
    max-width: 77% !important;
}

.ventana-emergente {
    position: relative;
    top: 50%; /* Coloca la ventana-emergente justo debajo del trigger */
    left: 110%;
    border: 1px solid #BFADA1;
    width: auto; /* Ancho completo */
    height: auto; /* Ancho completo */
    background-color: #fff; /* Color de fondo */
    transform: translate(-110%, -50%); /* Desplaza un poco a la izquierda cuando se muestra */
    box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1); /* Sombra suave */
    z-index: 2500; /* Asegúrate de que la ventana-emergente esté sobre otros elementos */
    opacity: 0;
}
/* .trigger:hover .ventana-emergente {
    opacity: 1; /* La ventana-emergente se muestra al pasar el mouse por encima del contenedor
    left: 110%;
} */
.evento{
    width: 10rem;
    padding: 0;
    text-align: center;
    margin: -2px 0 0 4px;
    cursor: pointer;
    overflow: hidden;
    position: absolute;
    display: flex; /* Usar flexbox */
    flex-direction: column; /* Alinear verticalmente */
    justify-content: space-between; /* Espaciar el contenido */
}
.event-header{
    border-top-left-radius: 5px;
    border-top-right-radius: 5px;
    z-index: 1000;
    color:white;
    border-color: #BFADA1;
    border-width: 1px;
    border-style: solid;
 
    flex-shrink: 0; /* Evitar que el header se reduzca */
}
.description{
    background-color: #f5f5f5;
    border-bottom-left-radius: 5px;
    border-bottom-right-radius: 5px;
    z-index: 1000;
    overflow: hidden;
    border-color: #BFADA1;
    border-width: 1px;
    border-style: solid;

    flex-grow: 1; /* Permitir que la descripción ocupe el espacio restante */
}
/* formato tabla */
.table-responsive {
    height:40rem;
    margin: auto;
    padding: 0;
    margin-bottom: 2rem;
    transform: translate(0%,-3%);
}
.table-responsive tbody{
    height: 35rem;
    overflow-y: auto;
}
.table-responsive tbody td{
    border-left:2px solid;
    border-left:2px solid;
}
.table-responsive tbody td,
.table-responsive thead > tr > th{
    border-bottom-width: 0;
    height: 1rem;
    margin:0;
    border-left: 2px solid #f5f5f5;
    padding: 0;
    font-size:small;
}
.casilla1 , .casilla{
    height: 1.5rem;
    min-width: 12rem;
    text-overflow: ellipsis;
}
.casilla1{
    height: 5px;
}
.employee-tags{
    padding: 1rem 0;
    justify-content: center;
}
/* .casilla:hover {
    background-color: #f5f5f5; /* Color de fondo un poco más claro que el original
    cursor:pointer;
} */
.casilla:hover::before {
    content: attr(data-hora); /* Usar el valor del atributo data-hora */
    position:fixed;
    background-color: #f5f5f5;
    color: black;
    font-size: 10px;
    transform: translate(0, -45%);
}
.table {
    border-bottom: 2px solid #f5f5f5; /* Borde azul para la parte inferior de la tabla */
    max-width: min-content;
}
tbody tr[data-hour]:not([data-hour=""]) {
    border-top: 4px solid #f5f5f5; /* Borde azul más grueso */
}
/* Estilos generales para los botones */
.button-style {
    margin-top:13px;
    width: 2rem;
    height: 2rem;
    border-radius: 4px;
    background-color: transparent;
    border: 1px solid #ccc;
    cursor: pointer; /* Cambia el cursor al pasar sobre el botón */
    transition: background-color 0.3s ease; /* Transición suave para el cambio de color */
}

/* Estilo al pasar el cursor sobre el botón */
.button-style:hover {
    background-color:#1d3557;
    color:#f1f1f1;
}

.button-style:active{
    border-color: transparent;
}

.popover-header{
    text-align: center;
    font-weight: 600;
    color: #1d3557;
}
.popover {
    border-width: 1px !important;
    border-color:#BFADA1 !important;
}

/* Estilos específicos para algunos botones */
.button-style.wider {
    width: 3rem;
}

.button-style.widest {
    width: 6rem;
}
.nextEmpl:hover{
    background-color:#E2BBB4 !important;
    color:#f1f1f1;
}
.sticky-left {
    position: sticky;
    left: 0;
    z-index: 2;
    background-color: white;
}
.sticky-top {
    position: sticky;
    top: 0;
    z-index: 1;
    background-color: white;
}

.resize-handle {
    width: 100%;
    height: 5px;
    background-color: transparent;
    position: relative;
    bottom: 0;
    cursor: s-resize;
}

</style>

@push('my-scripts')
<script>
document.addEventListener('DOMContentLoaded', function(){
        initializePopper()
        initializeFlatpckr()
        applyEventHeights()
})

function applyEventHeights() {
    const eventos = document.querySelectorAll('.evento');

    eventos.forEach(evento => {
        const duration = parseInt(evento.getAttribute('data-duration'), 10); // Obtén la duración en minutos
        const casillaHeightRem = getCasillaHeight(); // Obtener la altura de la casilla en rem
        const numberOfCasillas = Math.ceil(duration / 15); // Cada 15 minutos es una casilla

        // Calcula la altura en rem
        const newHeightInRem = numberOfCasillas * casillaHeightRem;

        // Aplica la nueva altura a .evento
        evento.style.height = `${newHeightInRem}rem`; // Establece la altura en rem
    });
}

function getCasillaHeight() {
    const width = screen.width;

    // Define la altura de la casilla en rem en función del ancho de la pantalla
    if (width >= 768) {
        return 1; // Altura en rem para desktop (ajustado)
    } else { // Para ancho menor a 768 px (móvil)
        return 2; // Altura en rem para móviles (ajustado)
    }
}


document.addEventListener('livewire:load', function () {
    Livewire.on('reloadDragg',function(){
        initializePopper()
        initializeFlatpckr()
        applyEventHeights()
    })
})

function initializePopper(){
    $('[data-toggle="popover"]').popover({
        html: true
    });
}

function initializeFlatpckr(){
    
    flatpickr(document.getElementsByClassName('flatpickrInd'),{
        enableTime: false,
        dateFormat: 'Y-m-d',
        locale: {
            firstDateofWeek:1,
            weekdays: {
                shorthand: ["Dom", "Lun", "Mar", "Mié", "Jue", "Vie", "Sáb"],
                longhand: [
                "Domingo",
                "Lunes",
                "Martes",
                "Miércoles",
                "Jueves",
                "Viernes",
                "Sábado",
                ],
            },    
            months: {
                shorthand: [
                "Ene",
                "Feb",
                "Mar",
                "Abr",
                "May",
                "Jun",
                "Jul",
                "Ago",
                "Sep",
                "Oct",
                "Nov",
                "Dic",
                ],
                longhand: [
                "Enero",
                "Febrero",
                "Marzo",
                "Abril",
                "Mayo",
                "Junio",
                "Julio",
                "Agosto",
                "Septiembre",
                "Octubre",
                "Noviembre",
                "Diciembre",
                ],
            }
        },
    })
}
</script>
@endpush
