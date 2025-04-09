<?php

namespace App\Http\Livewire;

use App\Models\Asignacion_servicio;
use App\Models\cliente;
use App\Models\Empleado;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;

class Reserva extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $quiz = 1,$params;

    public $customer,$reservationDate,$reservationTime,$description,$availableSchedules=[];
    public function mount()
    {
        $this->loadDefault();
    }

    protected $listeners = ['refresh' => '$refresh','returnData'];

    protected $rules = [
        'customer.first_name' => "required|min:3|max:35",
        'customer.phone' => "required|min:10|max:15",
        'customer.email' => "required|min:5|max:65",
        'reservationDate' => "required|max:65",
        'reservationTime' => 'required',
        'description' => 'nullable|max:200',
    ];
    public function updatedReservationDate()
    {
        $data = $this->obtenerHorariosDisponibles();
        $this->availableSchedules = $data['horarios_disponibles'];
        $this->dispatchBrowserEvent('orderByHour',$data['horarios_disponibles']);
    }
    private function loadEmpleados()
    {
        try{
            return Empleado::where('salon_id',5)->where('visible',1)->get()->toArray();
        }catch(\Throwable $th){
            $this->dispatchBrowserEvent('noty-error', ['msg' =>  "Código de error: 95355Reservas"] );
        }
    }
    private function loadCitas()
    {
        try{
            return Asignacion_servicio::whereDate('start',$this->reservationDate)->get()->toArray();
        }catch(\Throwable $th){
            $this->dispatchBrowserEvent('noty-error', ['msg' =>  "Código de error: 95355Reservas"] );
        }
    }

    private function obtenerHorariosDisponibles($inicio = "09:00", $fin = "19:00", $intervalo = 15) 
    {
        $horariosDisponibles = [];
        $empleadosDisponibles = [];
    
        $empleados = $this->loadEmpleados();
        $citas = $this->loadCitas();
        // Convertir horas a minutos
        $inicioMin = strtotime($inicio);
        $finMin = strtotime($fin);
    
        $citas = $citas ?? [];
        // Identificar empleados sin citas
        foreach ($empleados as $empleado) {
            // Filtrar citas del empleado
            $citasEmpleado = array_filter($citas, fn($c) => $c['empleado_id'] === $empleado['id']);
            for ($t = $inicioMin; $t <= $finMin; $t += $intervalo * 60) {
                $horaInicio = date("H:i", $t);
                $disponible = true;

                // Validar que el servicio no se traslape con citas existentes
                foreach ($citasEmpleado as $cita) {
                    $inicioCita = strtotime($cita['start']);
                    $finCita = strtotime(Carbon::parse($cita['start'])->addMinutes($cita['duration']));
                    if (!($t < $inicioCita || $t >= $finCita)) {
                        $disponible = false;
                        break;
                    }
                }

                if ($disponible) {
                    if(!in_array($horaInicio,$horariosDisponibles)){
                        $horariosDisponibles[] = $horaInicio;
                    }
                }
            }
        }
    
        return [
            'empleados_disponibles' => $empleadosDisponibles,
            'horarios_disponibles' => $horariosDisponibles
        ];
    }
    

    public function render()
    {
        return view('livewire.reservaciones.reserva')->layout('layouts.reservation');
    }
    public function returnData($params)
    {
        $this->quiz = 2;
        $this->params = $params;
        $this->dispatchBrowserEvent('reservationForm',$params);
    }
    private function loadDefault()
    {
        $this->params = null;
        $this->customer = new cliente();
        $this->reservationDate = Carbon::now()->format('d-m-Y');
        $this->reservationTime = null;
    }
    public function storeReservation()
    {
        $this->validate($this->rules);
        try{
            return redirect()->to('cita-confirmada');
        }catch(\Throwable $th){
            $this->dispatchBrowserEvent('noty-error', ['msg' =>  "Código de error: 952315Reservas"] );
        }
    }
}
