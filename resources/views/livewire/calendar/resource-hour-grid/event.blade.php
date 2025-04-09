
<div class="evento draggable"
    data-menu-id="menu{{ $cita->id }}" 
    value="{{ $cita->id }}" 
    draggable="true" 
    title="Horario: {{ Carbon\Carbon::parse($cita['start'])->format('H:i') }}-{{ Carbon\Carbon::parse($cita['end'])->format('H:i') }}"
    data-toggle="popover" 
    data-trigger="hover" 
    data-content="{!! $cita->date->status==='Cancelada' ? 'Motivo de Cancelación: ' . $cita->date->motivoCancelacion . '<br>' : '' !!} 
        {{ isset($cita->date->customer) ? $cita->date->customer->first_name : 'Cliente eliminado' }} 
        {{ isset($cita->date->customer) ? $cita->date->customer->last_name : '' }}
        <br>Servicio: {{ $cita->title }}<br>{!! $cita->categorias_cliente ? 'Descripción del cliente: ' . 
        $cita->categorias_cliente . '<br>' : '' !!}{!! $cita->date->description ? 'Recordatorio: ' . 
        $cita->date->description . '<br>' : '' !!}Precio de la cita: ${{ $cita->date->total }}">
    <div class="event-header" id="header-{{$cita->id}}" 
        style="background-color:{{ $cita->date->status == 'Pagada' ? 'black' : ($cita->date->status=='Cancelada' ? '#e63946':$cita->color) }};
        color: {{ $cita->date->status === 'Cancelada' || $cita->date->status === 'Pagada' ? 'white' : 'inherit' }}">
            {{ Carbon\Carbon::parse($cita['start'])->format('H:i') }}-{{ Carbon\Carbon::parse($cita['end'])->format('H:i') }}
    </div>    

    @php
        if($cita['duration']<=15){
            $height=0;
        }else{
            $height = ($cita['duration']/15)-1;
        }
    @endphp

    <div class="description" style="height:{{ $height }}rem">
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

    <div class="resize-handle"></div>
</div>

<div class="dropdown">
    <ul class="dropdown-menu dropdown-menu-event" id="menu{{$cita->id}}">
        <li>
            <a wire:click.prevent="setAsignacion({{ $cita->id }})" class="dropdown-item" style="padding:0 1rem!important">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" 
                stroke-linejoin="round" width="24" height="24" stroke-width="2">
                <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1"></path>
                <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z"></path>
                <path d="M16 5l3 3"></path>
                </svg>
            </a>
        </li> 
        <li>
            <a class="dropdown-item" style="padding:0 1rem!important">
                <input type="color" class="color-selector m-auto" value="{{ $cita->color }}" 
                    onclick="event.stopPropagation();" wire:change.prevent="updateColor({{$cita->id}}, $event.target.value)" 
                    oninput="updateBackgroundColor({{ $cita->id }}, this.value)">
            </a>
        </li>
    </ul>
</div>