
<style>
    
.color-box {
    display: block;
    width: fit-content;
    border-radius: 4px;
    color: white;
    padding: 5px;
}

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
    height:35rem;
    margin: auto;
    padding: 0;
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
.employee-tags{
    padding: 1rem 0;
    justify-content: center;
}
tbody tr[data-hour]:not([data-hour=""]) {
    border-top: 2px solid #e5e5e5; /* Borde azul más grueso */
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

.sticky-left {
    position: sticky;
    left: 0;
    z-index: 2;
    background-color: white;
}
.sticky-top {
    position: sticky;
    top: 0;
    z-index: 2;
    background-color: white;
}
.dropdown-menu-event.show {
    left: 6rem;
    top: 4rem;
    display: flex;
    min-width: 8rem !important;
}
.dropdown-menu.show {
    display: flex;
}
/* Estilos para eventos */
.horario-evento {
display: flex;
-webkit-box-align: center;
align-items: center;
color: #FFFEFF;
font-weight: 300;
background-color: #5EC296;
font-size: 12px;
margin: 0;
padding: 2px;
height: 18px;
border: 1px solid #BFADA1;
min-width: 5rem;
}

.evento {
background-color: #f5f5f5;
margin-bottom: 5px;
display: inline-block;
cursor: pointer;
position: absolute;
z-index: 1;
overflow: hidden;
border-radius: 3px;
border-bottom: 1px solid #BFADA1;
transition: top 0.2s ease, height 0.2s ease;
top:0px;
}

.evento .detalles{
font-size: 14px;
display: block;
}

.detalles{
padding: 1px 3px 2px 3px;
border: 1px solid #BFADA1;
border-top-width: 0;
background-color: white;
height: inherit;
}

.evento .cambia-tamanio {
display: none; /* Ocultar por defecto */
width: 100%; /* Asegura que ocupe todo el ancho del evento */
height: 8px;
overflow: hidden;
line-height: 8px;
font-size: 11px;
font-family: monospace;
text-align: center;
cursor: s-resize;
position: absolute; /* Absoluto para no impactar el tamaño del contenedor */
bottom: 0; /* Se posiciona en la parte inferior del evento */
left: 0;
}

.evento:hover .cambia-tamanio {
display: block; /* Mostrar al hacer hover */
}
/* Termina estilos para eventos */

/* Empieza estilos para calendario */
.vista-empleados-tiempos{
font-size: 12px;
}
.calendario{
line-height: 12px;
direction: ltr;
text-align: left;
}
.calendario-herramientas{
display: flex;
-webkit-box-pack: justify;
justify-content: space-between;
-webkit-box-align: center;
align-items: center;
}
.grupo-herramientas{
position: relative;
display: inline-flex;
vertical-align: middle;
}
.tabla-calendario{
box-sizing: content-box;
}
.vista{
position: relative;
}
.calendario table{
box-sizing: border-box;
table-layout: fixed;
border-collapse: collapse;
border-spacing: 0;
font-size: 1em;
}
table{
display: table;
text-indent: initial;
unicode-bidi: isolate;
border-color: gray;
}
tr{
display: table-row;
vertical-align: inherit;
unicode-bidi: isolate;
border-color: inherit;
}
.calendario th, .calendario td{
border: 1px solid #e5e5e5;
height: 18px; /* Asegura que la celda tenga altura completa */
width: 100%; /* Asegura que la celda ocupe todo el ancho */
padding: 0;
margin: 0;
box-sizing: border-box;
    min-width: 12rem;
}
.cell {
position: relative; /* Necesario para que los hijos se posicionen dentro */
height: 16px;
}
.cell:hover::before,.cell.drag-over::before {
content: attr(data-hour); /* Usar el valor del atributo data-hora */   
font-size: smaller;
padding-left: 6px;
padding-top: 1px;
position: absolute;
bottom: 2px;
}
.cell.drag-over {
background-color: rgba(0, 123, 255, 0.2); /* Resaltar celda */
}

.evento.overlap {
width: 45% !important; /* Reduce el ancho cuando hay solapamiento */
}

.evento.left {
left: 45%;
}

.evento:hover{
    z-index: 2!important;
}

</style>