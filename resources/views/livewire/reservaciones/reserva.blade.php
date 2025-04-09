<div>
@if($quiz==2)
@include('livewire.reservaciones.reservar')
@elseif($quiz==1)
@include('livewire.reservaciones.quiz')
@endif
</div>