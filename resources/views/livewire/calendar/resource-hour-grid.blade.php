<div id="calendar-master">
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
                                
                                <div id="empleados" class="d-flex employee-tags">
                                    @if($empleados!=null)    
                                        @foreach($empleados as $empleado)
                                            <div class="tag">
                                            @php
                                                $nameParts = explode(' ', trim($empleado->first_name));
                                                $initials = count($nameParts) > 1
                                                    ? substr($nameParts[0], 0, 1) . substr($nameParts[1], 0, 1)
                                                    : substr($nameParts[0], 0, 1); // Solo la inicial del primer nombre si no hay segundo nombre
                                            @endphp

                                            <input class="tag-name remove-tag {{ $empleado->is_active ? '' : 'line-t' }}" 
                                                style="font-weight: 300;font-size:medium" 
                                                type="button" 
                                                wire:click="$emit('filterEmployee', '{{ $empleado->id }}' )" 
                                                value="{{ $initials }}{{ $empleado->last_name ? '' . substr($empleado->last_name, 0, 1) : '' }}">
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                                
                                <div wire:model="currentDateC" id="flatResource" class="flatpickrInd" wire:change.prevent="dateSelected">
                                    <h5 class="center mt-2 ml-4 mr-4 float-right " id="flatCalendar" 
                                        style="justify-content: space-between;transform: translate(10px, 10px);" >{{ $currentDate }}
                                    </h5>
                                </div>


                                <div class="m-auto">
                                    <a href="" class="btn btn-dark btn-sm btn-block " 
                                    style="background-color: white;color:#60060F;border-color:#E2BBB4">
                                        Mostrar reservas
                                    </a>
                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- AGENDA STARTS -->

                    <div style="display: none;">

                    <!-- Cargar eventos -->
                    @foreach($empleados as $empleado)
                        @if($empleado->is_active==1)
                            @if(isset($citas))
                                @foreach($citas[$empleado->id] as $cita)
                                    @if(isset($cita['start']))
                                        <div class="evento draggable" 
                                            draggable="true" 
                                            data-id="{{ $cita->id }}" 
                                            data-start="{{ $cita['start']->format('H:i') }}" 
                                            data-duration="{{$cita['duration']}}" 
                                            wire:click="setAsignacion({{ $cita->id }})" 
                                            value="{{ $cita->id }}" 
                                            id="evento-{{ $cita->id }}" 
                                            data-employee="{{ $empleado->id }}" 
                                            title="Horario: {{ Carbon\Carbon::parse($cita['start'])->format('H:i') }}-{{ Carbon\Carbon::parse($cita['end'])->format('H:i') }}" 
                                            data-toggle="popover" 
                                            data-trigger="hover" 
                                            data-content="{!! $cita->date->status==='Cancelada' ? 'Motivo de Cancelación: ' . $cita->date->motivoCancelacion . '<br>' : '' !!} {{ isset($cita->date->customer) ? $cita->date->customer->first_name : 'Cliente eliminado' }} {{ isset($cita->date->customer) ? $cita->date->customer->last_name : '' }}<br>
                                        @foreach ($cita->tags as $tag)
                                            <span class='badge' style='background-color: {{ $tag->color }}; color: white;'>
                                                {{ $tag->name }}
                                            </span><br>
                                        @endforeach
                                        Servicio: {!! $cita->title !!}<br>{!! $cita->categorias_cliente ? 'Descripción del cliente: ' . 
                                            $cita->categorias_cliente . '<br>' : '' !!}{!! $cita->date->description ? 'Recordatorio: ' . 
                                            $cita->date->description . '<br>' : '' !!}Precio de la cita: ${{ $cita->date->total }}">
                                            <div class="horario-evento" 
                                                id="header-{{$cita->id}}" 
                                                style="background-color:{{ $cita->date->status == 'Pagada' ? '#63686C' : ($cita->date->status=='Cancelada' ? '#e63946':$cita->color) }};">
                                                {{ Carbon\Carbon::parse($cita['start'])->format('H:i') }} - {{ Carbon\Carbon::parse($cita['end'])->format('H:i') }}
                                            </div>
                                            <div class="description detalles">
                                                <div>
                                                    {{ isset($cita->date->customer) ? $cita->date->customer->first_name : 'Cliente eliminado' }}
                                                </div>
                                                <div class="tags">
                                                    @foreach($cita->tags as $tag)
                                                        <div class='badge bg-{{ $tag->color }} text-white' style="background-color: {{ $tag->color }};">{{ $tag->name }}</div>
                                                    @endforeach
                                                </div>
                                                @if($cita->date->description!==null)
                                                <div>
                                                    {{ $cita->date->description }}
                                                </div>
                                                @endif
                                                <div>
                                                    {!! $cita->title !!}
                                                </div>
                                            </div>
                                            <div class="cambia-tamanio"></div>
                                        </div>
                                        @include('livewire.calendar.dropdown.color')
                                    @endif
                                @endforeach
                            @endif
                        @endif
                    @endforeach
                    </div>
                     
                    <!-- Cargar vista del calendario -->
                    <div class="vista-empleados-tiempos">
                        <div class="calendario">
                            <div class="tabla-calendario"  id="agenda">
                                <div class="vista table-responsive">
                                    <table>
                                        <thead style="display:table-header">
                                            <tr class="text-center" >
                                                <th class="sticky-left"></th>
                                                @if($empleados!=null)
                                                    @foreach($empleados as $empleado)
                                                        @if($empleado->is_active)
                                                            <th class="sticky-top" 
                                                                style="font-weight:300;font-size:medium;height:auto;cursor:default">
                                                                {{ $empleado->first_name }} {{ $empleado->last_name }}
                                                            </th>
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($times as $hora)
                                                <tr style="background-color:{{ in_array($hora,$salonTimes) ? '' : '#E9EAEC' }};border-top:{{ \Carbon\Carbon::parse($hora)->format('i') != '00' ? '' : '2px solid #e5e5e5' }}">
                                                    <td class="sticky-left">
                                                        {{ \Carbon\Carbon::parse($hora)->format('i') != '00' ? '' : $hora}}
                                                    </td>
                                                    @if($empleados!=null)
                                                        @foreach($empleados as $empleado)
                                                            @if($empleado->is_active)
                                                                <td class="casilla trigger cell  {{ in_array($hora, $salonTimes) ? 'hora-disponible' : '' }}" 
                                                                    value="('{{ $hora }}', '{{ $empleado->id }}')" 
                                                                    data-hour="{{ $hora }}" 
                                                                    data-employee="{{ $empleado->id }}"  
                                                                    wire:click="crearCita('{{ $hora }}','{{ $empleado->id }}')" 
                                                                    onclick="disableTags()" 
                                                                    id="casilla"></td>
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- AGENDA ENDS -->
                </div>
            </div>
        </div>
    </div>
</div>

@include('livewire.calendar.jsCitas')
@include('livewire.calendar.styles')
