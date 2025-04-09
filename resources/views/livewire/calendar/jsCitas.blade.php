<script>
// Re-inicializa TomSelect después de cada actualización de Livewire
document.addEventListener('livewire:update', function () {
    initializePopper()
})

document.addEventListener('livewire:load', function () {
    Livewire.on('dateUpdated-movimientos', function (newDate,newDateEnd,newDataEmpleados) {
        // Actualizar el contenido donde se muestra la fecha
        document.getElementById('currentDate').innerText = newDate
        document.getElementById('currentDateModal').innerText = newDate
        if(newDateEnd!==''){
            document.getElementById('currentDateEnd').innerText = ' - ' + newDateEnd
        }else{
            document.getElementById('currentDateEnd').innerText = ''
        }
    })   

    Livewire.on('reloadFlat',function(){
        initializeFlatpickr()
    })
    
})

function initializeFlatpickr() {
    flatpickr(document.getElementsByClassName('flatpickr'), {
        enableTime: false,
        dateFormat: 'Y-m-d',
        locale: {
            firstDateofWeek: 1,
            weekdays: {
                shorthand: ["Dom", "Lun", "Mar", "Mié", "Jue", "Vie", "Sáb"],
                longhand: ["Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado"]
            },
            months: {
                shorthand: ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"],
                longhand: ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"]
            }
        }
    })
}
    
function disableTags()
{
    tags = document.getElementById('tags');
    tagsButton = document.getElementById('tagsButton');

    tags.setAttribute("hidden"); 
    tagsButton.removeAttribute("hidden"); 
}

function addTags()
{
    tags = document.getElementById('tags');
    tagsButton = document.getElementById('tagsButton');

    tags.removeAttribute("hidden"); 
    tagsButton.setAttribute("hidden"); 

    initializeTomTags()
}
function initializeTomTags()
{
    elTom = document.querySelector('#tomTags')

    if (elTom.tomselect) {
        elTom.tomselect.destroy() // Destruye cualquier instancia previa para evitar conflictos
    }

    var myurl ="{{ route('data.etiquetas') }}"

    new TomSelect(elTom, {
        valueField: 'id',
        labelField: 'name',
        searchField: ['name'],  
        load: function(query, callback) {        
            var url = myurl + '?q=' + encodeURIComponent(query)
            fetch(url)
            .then(response => response.json())
            .then(json => {
                callback(json)        
            }).catch(()=>{
                callback()
            });        
        },
        onChange: function(value) {
            @this.set('listTags',value)
        },
        render: {
            option: function(item, escape) {
                return `<div >
                    <div >
                        <div >                
                            <span style="color:${item.color}"> ${ escape(item.name) }</span>
                        </div>
                    </div>
                </div>`;
            },    
        },
    }) 
}

function toggleDropdown(event, trigger) {
    event.preventDefault(); // Previene el comportamiento predeterminado
    event.stopPropagation(); // Detiene la propagación del evento

    // Encuentra el menú dropdown asociado
    const dropdownMenu = trigger.nextElementSibling; // Selecciona el siguiente elemento hermano (el menú)
    
    if (dropdownMenu.classList.contains('show')) {
        dropdownMenu.classList.remove('show');
    } else {
        dropdownMenu.classList.add('show');
    }
}

let isResizing = false;
let isDragging = false;
let eventoActual;

document.addEventListener('DOMContentLoaded', function(){
    initializeFlatpckr();
    initializePopper();
    initializeRightClickMenu();
    
    detectOverlaps();
    initializeDraggFunction();
    positionEvents();
    initializeResizer();

    destroyPopover();
    horarioDisponible();
})

//cerrar modal de agregar cliente
window.addEventListener('close-popover', event => {
    destroyPopover()
})

function destroyPopover()
{
    // Cierra el popover manualmente
    $('[data-toggle="popover"]').popover('hide');
}

function initializeRightClickMenu()
{
    const eventos = document.querySelectorAll('.evento');

    eventos.forEach(evento => {
      evento.addEventListener('contextmenu', function (e) {
        e.preventDefault(); // Prevenir el menú contextual por defecto

        // Ocultar cualquier menú abierto previamente
        document.querySelectorAll('.dropdown-menu').forEach(menu => {
            menu.classList.remove('show');
        });
        destroyPopover()
        // Mostrar el menú correspondiente al área clicada
        const menuId = evento.dataset.id;
        const menu = document.getElementById('menu'+menuId);

        if (menu) {
            // Mostrar el menú
            menu.classList.add('show');
        }
      });
    });
    
}

function updateBackgroundColor(citaId, colorValue) {
    // Encontrar el contenedor .event-header asociado con este id
    const eventHeader = document.getElementById(`header-${citaId}`);
    
    // Si encontramos el contenedor, actualizamos el color de fondo
    if (eventHeader) {
        eventHeader.style.backgroundColor = colorValue;
    }
}

document.addEventListener('livewire:load', function () {
    Livewire.on('recargarFlat', function () {
        initializeRightClickMenu()
        initializeFlatpckr()
        
        detectOverlaps()
        initializeDraggFunction();
        positionEvents();
        initializeResizer();

        destroyPopover();
        horarioDisponible();
    })
    Livewire.on('reloadDragg',function(){
        initializeRightClickMenu()
        initializePopper()
        initializeFlatpckr()
        
        detectOverlaps()
        initializeDraggFunction();
        positionEvents();
        initializeResizer();

        destroyPopover();
        horarioDisponible();
    })
})

function initializePopper(){
    $('[data-toggle="popover"]').popover({
        html: true,
        sanitize:false
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
                longhand: ["Domingo","Lunes","Martes","Miércoles","Jueves","Viernes","Sábado",
                ],
            },    
            months: {
                shorthand: ["Ene","Feb","Mar","Abr","May","Jun","Jul","Ago","Sep","Oct","Nov","Dic",
                ],
                longhand: ["Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre",
                ],
            }
        },
    })
}

function positionEvents() {
    const eventos = document.querySelectorAll('.evento');

    // Función para redondear una hora al cuarto de hora más cercano
    function roundToQuarterHour(time) {
        const [hour, minute] = time.split(':').map(Number); // Divide en horas y minutos
        const roundedMinute = Math.round(minute / 15) * 15; // Redondea al múltiplo de 15 más cercano

        // Ajustar las horas si el redondeo supera los 60 minutos
        const finalHour = roundedMinute === 60 ? hour + 1 : hour;
        const finalMinute = roundedMinute === 60 ? 0 : roundedMinute;

        // Asegurar el formato HH:mm
        const paddedHour = String(finalHour).padStart(2, '0');
        const paddedMinute = String(finalMinute).padStart(2, '0');
        return `${paddedHour}:${paddedMinute}`;
    }

    eventos.forEach(evento => {
      const start = evento.dataset.start; 
      const duration = evento.dataset.duration; 
      const employee = evento.dataset.employee;

    // Redondear la hora al cuarto de hora más cercano
    const roundedStart = roundToQuarterHour(start);

      // Buscar la celda de inicio basada en data-hour y data-employee
      const matchingCell = document.querySelector(
        `.cell[data-hour="${roundedStart}"][data-employee="${employee}"]`
      );

      if (matchingCell) {
        // Posicionar el evento dentro de la celda
        matchingCell.appendChild(evento);
        
        // Mostrar el menú correspondiente al área clicada
        const menuId = evento.dataset.id;
        const dropdown = document.getElementById('menu'+menuId);

        matchingCell.appendChild(dropdown);

        // Ajustar altura y top relativo a la celda
        const height = (duration/15)*16;
        if(duration<27){
          evento.style.display = 'flex';
        }else{
          evento.style.display = 'inline-block';
        }
        evento.style.height = `${height}px`;
        evento.style.position = 'absolute'; // Importante para permitir posicionamiento relativo
        evento.style.width = '90%'; // Para que ocupe todo el ancho de la celda
      } else {
        console.warn(`No se encontró celda para el evento: ${start} - ${employee}`);
      }
    });
  }
  function initializeResizer() {
    const eventos = document.querySelectorAll('.evento');
  
    eventos.forEach(evento => {
      const resizer = evento.querySelector('.cambia-tamanio');
  
      let initialY = 0; // Posición inicial del mouse
      let initialHeight = 0; // Altura inicial del evento
      let isResizing = false; // Marcar si estamos redimensionando en este evento
  
      resizer.addEventListener('mousedown', initDrag, false);
  
      function initDrag(e) {
        e.preventDefault();
        isResizing = true; // Marcar como redimensionando
        evento.setAttribute('draggable', false);
        initialY = e.clientY;
        document.body.style.userSelect = 'none';
        initialHeight = parseInt(document.defaultView.getComputedStyle(evento).height, 10);
        document.documentElement.addEventListener('mousemove', doDrag, false);
        document.documentElement.addEventListener('mouseup', stopDrag, false);
      }
  
      function doDrag(e) {
        if (!isResizing) return;
  
        // Calcula el cambio en la posición del mouse
        const deltaY = e.clientY - initialY;
  
        // Calcula el número de bloques de 18px (15 minutos) según el delta
        const blockHeight = 18; // Cada bloque equivale a 15 minutos
        const newHeight = initialHeight + Math.round(deltaY / blockHeight) * blockHeight;
  
        // Asegura un tamaño mínimo y máximo
        if (newHeight >= blockHeight) { // Por ejemplo, 15 minutos mínimo
          evento.style.height = `${newHeight}px`;
  
          // Actualiza dinámicamente la duración en base al nuevo tamaño
          const duration = Math.round(newHeight / blockHeight) * 15; // Duración en minutos
          evento.setAttribute('data-duration', duration);
  
          // Opcional: Actualiza el texto con el nuevo rango horario
          const start = evento.getAttribute('data-start');
          const end = calculateEndTime(start, duration); // Función auxiliar para calcular el fin
          evento.querySelector('.horario-evento').textContent = `${start} - ${end}`;
          positionEvents();
        }
      }
  
      function stopDrag() {
        if (isResizing) {
          isResizing = false;
          evento.setAttribute('draggable', true); // Reactiva el atributo draggable
          document.body.style.userSelect = ''; 
  
          // Aquí puedes implementar una función Livewire para actualizar en el servidor si es necesario
          // >>>>>>>>>>>>>>>>>>>IMPLEMENTAR FUNCION LIVEWIRE AQUÍ<<<<<<<<<<<<<<<<<<<<<<<<<<<
          Livewire.emit('updateDuration', evento.dataset.duration,evento.dataset.id);
        }
  
        // Elimina los listeners para evitar que el redimensionamiento de un evento afecte a otros
        document.documentElement.removeEventListener('mousemove', doDrag, false);
        document.documentElement.removeEventListener('mouseup', stopDrag, false);
      }
    });
  }
  
  
// Función auxiliar para calcular el horario de fin
function calculateEndTime(start, duration) {
    const [hours, minutes] = start.split(':').map(Number);
    const totalMinutes = hours * 60 + minutes + duration;

    const endHours = Math.floor(totalMinutes / 60);
    const endMinutes = totalMinutes % 60;

    // Retorna el horario en formato "HH:MM"
    return `${endHours.toString().padStart(2, '0')}:${endMinutes.toString().padStart(2, '0')}`;
}

function initializeDraggFunction() {
    let eventData = null;
    let eventBox = null;
    let elementosArrastrables = document.querySelectorAll('.draggable');
    let contenedorCasilla = document.querySelectorAll('.cell');

    elementosArrastrables.forEach(function(elemento) {
        // Eliminar cualquier evento previo para evitar duplicados
        elemento.removeEventListener('dragstart', comenzarArrastreHandler);
        elemento.addEventListener('dragstart', comenzarArrastreHandler);
    });

    contenedorCasilla.forEach(function(elemento) {
        // Eliminar cualquier evento previo para evitar duplicados
        elemento.removeEventListener('dragover', permitirSoltar);
        elemento.removeEventListener('drop', soltarElementoHandler);

        elemento.addEventListener('dragover', permitirSoltar);
        elemento.addEventListener('drop', soltarElementoHandler);
    });
    
    // Listener global para el evento 'dragover' en todo el documento
    document.addEventListener('dragover', function(event) {
        event.preventDefault(); // Necesario para permitir que el drop funcione en cualquier lugar
    });
    
    // Listener global para 'drop' en cualquier parte del documento
    document.addEventListener('drop', function(event) {
        // Prevenir el comportamiento por defecto
        event.preventDefault();
        event.stopPropagation();

        // Si el target no es una casilla ni un hijo de una casilla, manejar el drop fuera de una casilla
        if (!event.target.classList.contains('cell')) {

            // Livewire.emit('cancelarCaptura')

            // Aquí puedes agregar cualquier lógica adicional para manejar el drop fuera de una casilla
        }
    });
}

function comenzarArrastreHandler(event) {
    destroyPopover()
    if (isResizing) {
        event.preventDefault(); // Evita que el evento dragstart continúe si estás redimensionando
        return;
    }
    isDragging = true;
    eventData = comenzarArrastre(event);
    eventBox = event.target;
}


function soltarElementoHandler(event) {
    let targetCell = event.target.closest('.cell');
    if (!targetCell) return; // Asegúrate de que sea una celda válida

    soltarElemento(event, eventData);
    isDragging = false;
}

function comenzarArrastre(event) {
    eventData = {
        startTime: event.target.getAttribute('data-start'),
        duration: event.target.getAttribute('data-duration'),
        employee: event.target.getAttribute('data-employee'),
        id: event.target.getAttribute('data-id'),
    };

    return eventData;
}

function soltarElemento(event,eventData) {
    let casillaData = event.target.getAttribute('value');
    let targetCell = event.target.closest('.cell');

    if (targetCell) {
        targetCell.classList.remove('drag-over');
    }


    Livewire.emit('setCitaDragged', casillaData, eventData);
}

function permitirSoltar(event) {
    event.preventDefault();
    
    let targetCell = event.target.closest('.cell');
    if (targetCell) {
        targetCell.classList.add('drag-over');
        
        eventBox.querySelector('.horario-evento').textContent = `${event.target.dataset.hour}`;
    }
    // Listener para restaurar el estado original al salir del área
    event.target.addEventListener('dragleave', function handleDragLeave() {
        let targetCell = event.target.closest('.cell');
        if (targetCell) {
            targetCell.classList.remove('drag-over');
        }
    });
}
function detectOverlaps() {
  const appointments = document.querySelectorAll('.evento');

  // Limpiar clases de todos los eventos
  appointments.forEach(app => app.classList.remove('overlap', 'left'));

  for (let i = 0; i < appointments.length; i++) {
      const app1 = appointments[i];
      const horario1 = app1.querySelector('.horario-evento').textContent.trim();
      const horarioFinal1 = horario1.split(' - ')[1];
      const [start1, end1] = parseTime(app1.dataset.start, horarioFinal1);

      for (let j = i + 1; j < appointments.length; j++) {
          const app2 = appointments[j];

          if (app1 === app2) continue; // Ignorar si es el mismo elemento

          const horario2 = app2.querySelector('.horario-evento').textContent.trim();
          const horarioFinal2 = horario2.split(' - ')[1];
          const [start2, end2] = parseTime(app2.dataset.start, horarioFinal2);

          // Detectar si las citas se solapan
          const sameEmployee = app1.dataset.employee === app2.dataset.employee;
          const timeOverlap = start1 < end2 && start2 < end1;

          if (timeOverlap && sameEmployee) {
              app1.classList.add('overlap', 'left');
              app2.classList.add('overlap');
          }
      }
  }
}
function parseTime(start, end) {
  const toMinutes = (time) => {
      const [hours, minutes] = time.split(':').map(Number);
      return hours * 60 + minutes;
  };

  const startMinutes = toMinutes(start);
  const endMinutes = toMinutes(end);

  // Validar que el tiempo de fin no sea menor que el tiempo de inicio
  if (endMinutes < startMinutes) {
      console.error(`Error: El tiempo de fin (${end}) no puede ser menor que el de inicio (${start}).`);
  }

  return [startMinutes, endMinutes];
}

function horarioDisponible(){
    // Selecciona el tbody con scroll
    const tbody = document.querySelector('.table-responsive');

    // Busca la primera celda disponible
    const firstAvailableCell = tbody.querySelector('.hora-disponible');

    if (tbody && firstAvailableCell) {
        // Calcula la posición de la celda disponible relativa al tbody
        const offsetTop = firstAvailableCell.offsetTop - tbody.offsetTop;

        // Ajusta el scroll interno del tbody, sumando un margen adicional (por ejemplo, el tamaño de 6 celdas)
        const cellHeight = firstAvailableCell.offsetHeight; // Altura de una celda
        tbody.scrollTop = offsetTop - (-5.5 * cellHeight); // Ajusta hacia arriba
    }

}
</script>
