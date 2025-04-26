<?php

namespace App\Http\Livewire;

use App\Models\Asignacion_servicio;
use App\Models\cita;
use App\Models\cliente;
use App\Models\Empleado;
use App\Models\categoria_cliente;
use App\Models\servicio;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;

class Reserva extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $quiz = 1,$params;

    public $customer,$reservationDate,$reservationTime,$description,$availableSchedules=[],$availableEmployees=[];
    public $listCategoriesIds = [];
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
        $this->availableEmployees = $data['empleados_disponibles'];
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
                    if(!in_array($empleado['id'],$empleadosDisponibles)){
                        $empleadosDisponibles[] = $empleado['id'];
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
    private function identiftyIdService()
    {
        try{
            $id = null;
            if(isset($this->params['serviceType'])){
                switch($this->params['serviceType']){
                    case 'makeup':
                        switch($this->params['makeupType']){
                            case "wedding":
                                $id = 221;
                                break;
                            case "xv-years":
                                $id = 361;
                                break;
                            case "social-events":
                                $id = 178;
                                break;
                            default:
                                break;
                        }
                        break;
                    case 'hair':
                        switch($this->params['hairService']){
                            case "treatment":
                                switch($this->params['treatmentType']){
                                    case "straightening":
                                        $id = 362;
                                        break; 
                                    case "base":
                                        $id = 363;
                                        break;
                                    default:   
                                        break;
                                }
                                break;
                            case "dye":
                                switch($this->params['dyeType']){
                                    case "highlights":
                                        $id = 181;
                                        break; 
                                    case "rays":
                                        $id = 364;
                                        break;
                                    case "babylights":
                                        $id = 233;
                                        break;
                                    case "balayage":
                                        $id = 283;
                                        break;
                                    case "full-color":
                                        $id = 204;
                                        break;
                                    default:   
                                        break;
                                }
                                break;
                            case "cut":
                                $id = 207;
                                break;
                            default:
                                break;
                        }
                        $this->useOrCreateTags(['Tipo: ' . $this->params['hairType'],'Cabello: '. $this->params['hairMeasurement']]);
                        break;
                    default:
                        break;
                }
            }

            return $id;
        }catch(\Throwable $th){
            $this->dispatchBrowserEvent('noty-error', ['msg' =>  "Código de error: 16420Agenda"] );
        }
    }
    private function identifyCustId()
    {
        $cust = $this->custExists();
        if($cust == null){
            $cust = $this->customer;
            $cust->first_name = $this->params['name'];
            $cust->salon_id = 5;
            $cust->save();
        }
        $this->setTags($cust);

        return $cust->id;
    }
    private function custExists()
    {
        $cust =  cliente::where('phone', $this->customer->phone)
                            ->orWhere('email', $this->customer->email)->first();
        return $cust;
    }
    public function storeReservation()
    {
        $this->validate($this->rules);
        try{
            $custId = $this->identifyCustId();
            $sid = $this->identiftyIdService();
            $data = $this->setMovimiento($custId,$sid);
            $this->setDetail($data[0],$data[1],$data[2]);
            
            return redirect()->to('cita-confirmada');
        }catch(\Throwable $th){
            $this->dispatchBrowserEvent('noty-error', ['msg' =>  "Código de error: 952315Reservas"] );
        }
    }
    private function useOrCreateTags($tagNames)
    {
        try{
            foreach($tagNames as $tagName){
                $tag = categoria_cliente::where('name',$tagName)->first();
                if($tag!=null){
                    $this->addTag($tag->id);
                }else{
                    $newCat =  new categoria_cliente;
                    $newCat->name = $tagName;
                    $newCat->salon_id = 5;
                    $newCat->save();
                    $this->addTag($newCat->id);
                }
            }
        }catch(\Throwable $th){
            $this->dispatchBrowserEvent('noty-error', ['msg' =>  "Código de error: 124350Agenda"] );
        }
    }
    private function addTag($tagId)
    {
        try{
            // Verificamos si el tagId ya está en listCategoriesIds
            if (!in_array($tagId, $this->listCategoriesIds)) {
                $this->listCategoriesIds[] = $tagId;
            }
        }catch(\Throwable $th){
            $this->dispatchBrowserEvent('noty-error', ['msg' =>  "Código de error: 1651350Agenda"] );
        }
    }private function setTags($cust)
    {
        try{
            // Relacionar categorías         
            if ($this->listCategoriesIds != null) {
                $mappedTags = array_map(function ($item) {
                    $catName = trim($item);
                    
                    // Verificar si el elemento no es numérico
                    if (!is_numeric($catName)) {
                        // Buscar el ID de la categoría en la tabla correspondiente
                        $categoria = categoria_cliente::where('name', $catName)
                            ->where('salon_id', 5)
                            ->first();
                            
                        // Reemplazar con el ID si existe
                        if ($categoria) {
                            return $categoria->id;
                        }
                    }
        
                    // Devolver el elemento sin cambios
                    return $catName;
                }, $this->listCategoriesIds);
        
                // Sincronizar categorías
                !empty($mappedTags) 
                    ? $cust->categorias()->sync($mappedTags) 
                    : $cust->categorias()->detach();
            }
        }catch(\Throwable $th){
            $this->dispatchBrowserEvent('noty-error', ['msg' =>  "Código de error: 1269Agenda"] );
        }   
    }
    
    private function setDetail($cid,$item,$start)
    {
        try{
            $asignacion = new Asignacion_servicio; 
            $asignacion->start = $start;
            $asignacion->selected_service = $item['id'];
            $asignacion->empleado_id = $this->availableEmployees[0];
            $asignacion->cita_id = $cid;
            $asignacion->duration = $item['duration'];
            $asignacion->current_price = floatval($item['gross_price']);
            $asignacion->iva = floatval($item['iva']);
            $asignacion->save();
        }catch(\Throwable $th){
            $this->dispatchBrowserEvent('noty-error', ['msg' =>  "Código de error: 1212369Agenda"] );
    }   
    }
    
    private function setMovimiento($custId,$sid)
    {
        try{
            $movimiento = new cita;
            $movimiento->description = $this->description;

            $horario = explode(":",$this->reservationTime);
            $hour = $horario[0];
            $minute = $horario[1];

            $start = Carbon::parse($this->reservationDate)->setTime($hour,$minute);
            $movimiento->start = $start;
            $movimiento->customer_id = $custId;
            $movimiento->disccount = 0;
            $service = $this->getService($sid);
            $movimiento->total = $service['gross_price'];
            $movimiento->status='Online';
            $movimiento->generated_points = 0;
            $movimiento->salon_id = 5;
            $movimiento->save();
            return [$movimiento->id,$service,$start];
        }catch(\Throwable $th){
            $this->dispatchBrowserEvent('noty-error', ['msg' =>  "Código de error: 1288369Agenda"] );
        }   
    }
    private function getService($sid)
    {
        try{
            return servicio::find($sid)->toArray();
        }catch(\Throwable $th){
            $this->dispatchBrowserEvent('noty-error', ['msg' =>  "Código de error: 1288369Agenda"] );
        }   
    }
}
