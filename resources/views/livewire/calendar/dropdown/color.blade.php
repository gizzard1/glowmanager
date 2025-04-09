<!-- Dropdown -->
<div class="dropdown">
    <ul class="dropdown-menu dropdown-menu-event" id="menu{{$cita->id}}">
        <li><a wire:click.prevent="setAsignacion({{ $cita->id }})" class="dropdown-item" style="padding:0 1rem!important"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" width="24" height="24" stroke-width="2">
            <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1"></path>
            <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z"></path>
            <path d="M16 5l3 3"></path>
            </svg></a></li> 
        <li>
            <a class="dropdown-item" style="padding:0 1rem!important" data-toggle="dropdown" onclick="toggleDropdown(event,this)">
                <div 
                    class="color-box m-auto" 
                    style="background-color: {{ $cita->color }}; width: 24px; height: 24px; border: 1px solid #ccc; cursor: pointer;" >
                </div>
            </a>
            <div class="dropdown-menu p-0">
                @php
                    $presetColors = ['#6f42c1', '#e83e8c', '#e63946', '#1d3557', '#278d46', '#3A82EF', '#FFAB2D'];
                @endphp

                @foreach($presetColors as $color)
                    <a class="dropdown-item p-1" onclick="event.stopPropagation(); @this.updateColor({{ $cita->id }}, '{{ $color }}')">
                        <div 
                            class="color-box m-auto" 
                            style="background-color: {{ $color }}; width: 24px; height: 24px; border: 1px solid #ccc; cursor: pointer;" >
                        </div>
                    </a>
                @endforeach

                <a class="dropdown-item p-1"><input type="color" class="color-selector color-box m-auto" value="{{ $cita->color }}" onclick="event.stopPropagation();" wire:change.prevent="updateColor({{$cita->id}}, $event.target.value)" oninput="updateBackgroundColor({{ $cita->id }}, this.value)"></a>
            </div>
        </li>
    </ul>
</div>