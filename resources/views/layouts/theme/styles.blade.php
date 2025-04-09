<link href="{{ asset('css/chartist.min.css') }}">
<link href="{{ asset('vendor/chartist/css/chartist.min.css') }}">
<link href="{{ asset('vendor/bootstrap-select/dist/css/bootstrap-select.min.css') }}" rel="stylesheet">
<link href="{{ asset('vendor/star-rating/star-rating-svg.css') }}" rel="stylesheet">
<link href="{{ asset('css/style1.css') }}" rel="stylesheet"> 
<link href="{{ asset('css/bootstrap4.min.css') }}" rel="stylesheet" > 
<link href="{{ asset('vendor/toastr/css/toastr.min.css') }}" rel="stylesheet">
<link href="{{ asset('css/wordpress-admin.min.css') }}" rel="stylesheet">
<link href="{{ asset('css/tom-select.css') }}" rel="stylesheet">
<link href="{{ asset('css/slick-loader.min.css') }}" rel="stylesheet">
<link href="{{ asset('css/driver.css') }}" rel="stylesheet">
<link href="{{ asset('plugins/flatpickr.min.css') }}" rel="stylesheet">

<link rel="stylesheet" href="{{ asset('vendor/pickadate/themes/default.css') }}">
<link rel="stylesheet" href="{{ asset('vendor/pickadate/themes/default.date.css') }}">

<style>
/* Estilos para ocultar la flecha del select */
.no-arrow {
    -webkit-appearance: none; /* Chrome, Safari, Opera */
    -moz-appearance: none; /* Firefox */
    appearance: none; /* Estándar */
    background: none; /* Quita el fondo por defecto */
    padding-right: 0.5rem; /* Ajusta el padding si es necesario */
}

/* Opcional: Ajuste para que se vea consistente en todos los navegadores */
.no-arrow::-ms-expand {
    display: none; /* IE10+ */
}
.line-t{
    text-decoration: line-through;
}
a:hover{
    color:#e63946 !important;
    cursor:pointer !important;
}
.modal-header{
    position: sticky;
    top: 0;
    z-index: 10;
}

.tags-container {
    display: flex;
    flex-wrap: wrap;
}
input[type=number]::-webkit-inner-spin-button {
  -webkit-appearance: none;
}

.tag {
    background-color: #e4e6eb;
    border-radius: 16px;
    padding: 4px 8px;
    margin: 4px;
    display: flex;
    align-items: center;
}

.tag-name {
    margin-right: 8px;
}

.remove-tag {
    background-color: transparent;
    border: none;
    cursor: pointer;
    color: #555;
    font-weight: bold;
}

    @keyframes pulse {
    0% {
        transform: scale(1);
        opacity: 0.8;
    }
    50% {
        transform: scale(1.1);
        opacity: 1;
    }
    100% {
        transform: scale(1);
        opacity: 0.8;
    }
}
.close{
    transform: translate(-40%, 20%);
}
.hamburger {
    animation: pulse 1s infinite;
    cursor: pointer;
}

.hamburger:active {
    transform: scale(0.9);
    opacity: 0.8;
    transition: transform 0.1s, opacity 0.1s;
}
    .ts-control {
            background-color: white !important; 
        }
    ::selection {
        color: #6E6E6E;
        background: #6E6E6E;
    } 
    /* Estilo para el scroll */
::-webkit-scrollbar {
    width: 6px; /* Ancho del scrollbar */
}
.table-fix {
    margin: auto;
    padding: 0;
    width: auto;
    margin-bottom: 2rem;
}
.table-fix tbody{
    height: 35rem;
    overflow-y: auto;
    width: 110%;
}
.table-fix thead,
.table-fix tbody,
.table-fix td,
.table-fix th{
    display: block;
}
.table-fix tbody td,
.table-fix thead > tr > th{
    float: left;
    border-bottom-width: 0;
    height: 2rem;
}
.casilla{
    height: 1.5rem;
    width: 10rem;
}
.casilla:hover {
    background-color: #f5f5f5; /* Color de fondo un poco más claro que el original */
    cursor:pointer;
}
.table {
    border-bottom: 2px solid #f5f5f5; /* Borde azul para la parte inferior de la tabla */
}
/* Estilos generales para los botones */
.button-style {
    width: 2rem;
    height: 2rem;
    border-radius: 4px;
    background-color: transparent;
    border: 1px solid #ccc;
    margin: 5px; /* Añadido un margen para separar los botones */
    cursor: pointer; /* Cambia el cursor al pasar sobre el botón */
    transition: background-color 0.3s ease; /* Transición suave para el cambio de color */
}

.save{
    background-color: #1d3557 !important;
}
/* Track */
::-webkit-scrollbar-track {
    background: #f1f1f1; /* Color de fondo del track */
}

/* Handle */
::-webkit-scrollbar-thumb {
    background: #888; /* Color del handle del scrollbar */
    border-radius: 6px; /* Redondear las esquinas del handle */
}

/* Handle al pasar el mouse */
::-webkit-scrollbar-thumb:hover {
    background: #555; /* Color del handle cuando se pasa el mouse sobre él */
}
.swal2-popup {
    background-color:#f1f1f1;
    border: 1px solid #1d3557 ;
}
.swal2-title {
    font-weight: normal;
    color:#1d3557;
    text-align: center;
}
.swal2-styled.swal2-confirm {
    border: 1px solid #1d3557 !important;
    background-color: #f1f1f1 !important;
    color: #430007 !important;
}
.swal2-styled.swal2-confirm :hover{
    
    border: 1px solid #1d3557 !important;
    background-color: #f1f1f1 !important;
    color: #430007 !important;
}
.swal2-styled.swal2-cancel {
    background-color: #1d3557 !important;
}
strong{
    color:#1d3557;
}
h4{
    color:#1d3557;
}
.btn-info{
    background-color: #1d3557;
    border-color: #1d3557;
}
.separator{
    background-color: #1d3557 !important;
}

.grafica{
    margin: auto;
    width: 15.625rem;
    text-align: center;
}
.grafica-gd{
    width: 87.625rem;
    height: 34rem;
    text-align: center;
}

.user-icon{
    color:#1d3557!important;
}
</style>
<style type="text/css" media="print">
   .deznav, .nav-header, .botones-exportar {
      display: none;
      visibility: hidden;
      opacity: 0;
      height: 0;
   }
   .reporte-global{
    width: 800rem;
   }

    h1 {
        font-size: 18px;
    }


    td.precio {
        text-align: right;
        font-size:medium;
    }

    td.cantidad {
        font-size: 11px;
    }

    td.producto {
        text-align: center;
        font-size: 0.7rem;
    }

    th {
        text-align: center;
    }


    .centrado{
        text-align: center;
        align-content: center;
    }


    img {
        max-width: inherit;
        width: inherit;
    }


    .ticket {
        font-size:larger;
        font-weight: normal;
        font-family:sans-serif;
        display: block;
        width: 250px;
        max-width: 250px;
        margin: -40px;
        padding: 0;
        position: fixed;
        z-index: 1000;
    }

    .cuerpo-informe{
        display: flex;
        flex-direction: column;
        max-width: 800px;
    }
    .page-break {
        margin-top: 200px;
    }
    .page-break-2{
        margin-top: 300px;
    }
    .reporte-global{
        padding: 0;
    }
</style>