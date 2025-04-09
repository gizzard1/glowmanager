<?php

namespace App\Http\Livewire;

use App\Models\Asignacion_servicio;
use App\Models\categoria_cliente;
use App\Models\cita;
use App\Models\cliente;
use App\Models\Empleado;
use App\Models\etiquetas_cita;
use App\Models\servicio;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class Agenda extends Component
{
    use WithPagination;
    use WithFileUploads;

    public $currentDate, $currentDateEnd,$is_interval=false;
    public $start,$currentDateC,$end,$currentDateCEnd;
    public $end_date,$end_date_DB,$start_date,$start_date_DB;
    protected $paginationTheme = 'bootstrap';
    private $citas;
    private $salon_id;
    public $type;
    public $empleados;
    public Collection $cartS;
    public $description='';
    public $selectedEmpleadoId=null,$remember=0,$customerId,$listCategories,$isAdmin,$horas,$minutes_qty=0,$customer;
    public $servicios=[],$query,$categoriasTag=[];
    public $show,$totales=true;
    public $categoriesList,$categoriesListNew;
    public $cliente,$listCategoriesIds;
    public $queryTag;
    public $listTags=null;
    public function mount()
    {
        $this->isAdmin = Auth::user()->role == 'admin' || Auth::user()->role == 'recepcionista';
        $this->loadHoras();
        $this->loadFecha();
        $this->initializeCollections();
        $this->emit('reloadFlat');
    }
    function save()
    {
        try{
            session()->put('cartS', $this->cartS);
            session()->save();
        }catch(\Throwable $th){
            $this->dispatchBrowserEvent('noty-error', ['msg' =>  "Código de error: 38967Agenda"] );
        }
    }
    private function clearSession(array $keys)
    {
        $session = session();

        foreach ($keys as $key) {
            if ($session->has($key)) {
                $session->forget($key);
            }
        }
        session()->save();
    }
    public function filterEmployee($empleado_id)
    {
        try{
            $empleado = empleado::where('id',$empleado_id)->where('visible',1)->first();
            $empleado->is_active = !$empleado->is_active;
            $empleado->save();
            if(!$empleado->is_active){
                redirect('/');
            }
        }catch(\Throwable $th){
            $this->dispatchBrowserEvent('noty-error', ['msg' =>  "Código de error: 13459Agenda"] );
        }
        
    }
    public function clear()
    {
        $this->initializeCollections();
        $this->clearSession(['cartS']);
        $this->clearCliente();
        $this->description='';
        $this->loadCartTotales();
    }
    public function cancelarCaptura()
    {
        $this->clear();
        $this->minutes_qty = 0;
        $this->listTags=null;
        $this->initializeCollections();
        $this->clearCliente();
        $this->loadData();
        $this->emit('reloadFlat');
    }
    private function loadHoras()
    {
        try{
            $horaDesconcatenada = explode(":", Auth::user()->salon->start);
            $horaFinDesconcatenada = explode(":", Auth::user()->salon->end);

            $inicio = intval($horaDesconcatenada[0]);
            $fin = intval($horaFinDesconcatenada[0]);

            for ($hora = $inicio; $hora <= $fin; $hora++) {
                $this->horas[] = sprintf('%02d:00', $hora);
            }
        }catch(\Throwable $th){
            $this->dispatchBrowserEvent('noty-error', ['msg' =>  "Código de error: 35417Agenda"] );
        }
    }
    protected $listeners = [
        'refresh' => '$refresh',
        'prevDay','updateDuration','addNewService','changeStartDuration','changeEndDuration',
        'customerId' => 'setCustomerId','reimpresion','cancelarCaptura','changeDate','setCitaDragged',
        'clear-cart'=>'cancelarCaptura','storeDate','setCustomerId','filterEmployee'
    ];

    public function updateDuration($newDuration,$cita)
    {
        try{
            $detail = asignacion_servicio::find($cita);

            if($detail==null){
                preg_match("/'([^']+)'/",$cita,$matches);
                // El contenido extraído estará en $matches[1]
                $cita = $matches[1];
                // El contenido extraído estará en $matches[1]
                $cita = $matches[1];

                $detail = asignacion_servicio::find($cita);
            }

            $detail->duration = $newDuration;

            $detail->save();
        }catch(\Throwable $th){
            $this->dispatchBrowserEvent('noty-error', ['msg' =>  "Código de error: 525Agenda"] );
        }
    }
    public function createTag()
    {
        if($this->queryTag!=null){
            //guardar categoría
            $newCat =  new categoria_cliente;
            $newCat->name = $this->queryTag;
            $newCat->salon_id = Auth::user()->salon_id;
            $newCat->save();
            $this->addTag($newCat->id,$this->queryTag);
            $this->emit('refresh');
        }
    }
    public function addTag($tagId,$name)
    {
        try{
            // Verificamos si el tagId ya está en listCategoriesIds
            if (!in_array($tagId, $this->listCategoriesIds)) {
                $this->listCategories[] = $name;
                $this->listCategoriesIds[] = $tagId;
            }
        }catch(\Throwable $th){
            $this->dispatchBrowserEvent('noty-error', ['msg' =>  "Código de error: 1651350Agenda"] );
        }
    }
    public function changeDate($date)
    {
        try{
            $horario = explode(":",$this->start_date);
            $date = Carbon::parse($date[0]);

            // Extract the time components from currentDateC
            $hour = $horario[0];
            $minute = $horario[1];

            // Combine the date and time
            $new_date = $date->locale('es')->setTime($hour, $minute, '00');


            $this->start_date_DB=$new_date->format('Y-m-d H:i:s');
            $this->start_date=$this->start_date_DB;
            $this->currentDate= $new_date->locale('es')->isoFormat('dddd, D MMMM YYYY');

            $this->loadData();
        }catch(\Throwable $th){
            $this->dispatchBrowserEvent('noty-error', ['msg' =>  "Código de error: 2034Agenda"] );
        }
    }

    public function render()
    {
        try{
            $this->empleados = $this->loadEmpleados();
            $salonTimes = $this->loadSalonTimes();
            $times = $this->loadTimes();
            $isAdmin = Auth::user()->role == 'admin' || Auth::user()->role == 'recepcionista';
            if($isAdmin){
                return view('livewire.calendar.calendar',['citas'=>$this->useDate(),'type' => $this->type,'times'=>$times,'salonTimes'=>$salonTimes,'categoriasCliente' => $this->listCategories,'isAdmin' => $isAdmin]);

            }else{
                return view('livewire.calendar.resource-hour-grid-employees',['citas'=>$this->useDate(),'times'=>$times]);
            }
        }catch(\Throwable $th){
            $this->dispatchBrowserEvent('noty-error', ['msg' =>  "Código de error: 88354Agenda"] );
        }
    }
    public function dateSelected()
    {
        $this->loadDateByAgenda();
    }
    private function loadDateByAgenda()
    {
        try{
            $this->currentDateC= Carbon::parse($this->currentDateC);
            $this->currentDate= $this->currentDateC->locale('es')->isoFormat('dddd, D MMMM YYYY');
            $this->citas = $this->useDate();
        }catch(\Throwable $th){
            $this->dispatchBrowserEvent('noty-error', ['msg' =>  "Código de error: 1240255Agenda"] );
        }
    }

    private function loadSalonTimes()
    {
        try{
            $horas = [];
            $horaDesconcatenada = explode(":", Auth::user()->salon->start);
            $horaFinDesconcatenada = explode(":", Auth::user()->salon->end);

            $inicioHoras = intval($horaDesconcatenada[0]);
            $inicioMinutos = intval($horaDesconcatenada[1]);
            $finHoras = intval($horaFinDesconcatenada[0]);
            $finMinutos = intval($horaFinDesconcatenada[1]);

            $currentTime = new DateTime();
            $currentTime->setTime($inicioHoras, $inicioMinutos);

            $endTime = new DateTime();
            $endTime->setTime($finHoras, $finMinutos + 45);

            while ($currentTime <= $endTime) {
                $horas[] = $currentTime->format('H:i');
                $currentTime->modify('+15 minutes');
            }
            return $horas;
        }catch(\Throwable $th){
            $this->dispatchBrowserEvent('noty-error', ['msg' =>  "Código de error: 223234Agenda"] );
        }
    }
    private function loadTimes()
    {
        try{
            $horas = [];
            $horaDesconcatenada = explode(":", Auth::user()->salon->start);
            $horaFinDesconcatenada = explode(":", Auth::user()->salon->end);
            
            $inicioHoras = intval($horaDesconcatenada[0]);
            $inicioMinutos = intval($horaDesconcatenada[1]);
            $finHoras = intval($horaFinDesconcatenada[0]);
            $finMinutos = intval($horaFinDesconcatenada[1]);
            
            // Ajustar inicio 4 horas antes
            $currentTime = new DateTime();
            $currentTime->setTime($inicioHoras, $inicioMinutos);
            $currentTime->modify('-4 hours');
            
            // Ajustar fin 4 horas después
            $endTime = new DateTime();
            $endTime->setTime($finHoras, $finMinutos);
            $endTime->modify('+4 hours');
            
            // Generar el rango de horas
            while ($currentTime <= $endTime) {
                $horas[] = $currentTime->format('H:i');
                $currentTime->modify('+15 minutes');
            }
            
            return $horas;
            
        }catch(\Throwable $th){
            $this->dispatchBrowserEvent('noty-error', ['msg' =>  "Código de error: 223234Agenda"] );
        }
    }
    private function loadEmpleados()
    {
        try{
            return Empleado::where('salon_id',Auth::user()->salon_id)->where('visible',1)->get();
        }catch(\Throwable $th){
            $this->dispatchBrowserEvent('noty-error', ['msg' =>  "Código de error: 95355Agenda"] );
        }
    }
    public function loadFecha()
    {
        try{
            $this->currentDate=Carbon::now()->locale('es')->isoFormat('dddd, D MMMM YYYY');
            $this->start=Carbon::now()->toDateString();
            $this->currentDateC=Carbon::now();
            $this->currentDateEnd='';
            $this->end='';
            $this->currentDateCEnd='';
            $this->citas = $this->useDate();
        }catch(\Throwable $th){
            $this->dispatchBrowserEvent('noty-error', ['msg' =>  "Código de error: 103356Agenda"] );
        }
    }
    #Función que establece un día anterior 
    public function prevDay()
    {
        try{
            $this->is_interval=false;
            $this->currentDateC= $this->currentDateC->subDay();
            $this->start= $this->currentDateC->toDateString();
            $this->currentDate= $this->currentDateC->locale('es')->isoFormat('dddd, D MMMM YYYY');
            $this->citas = $this->useDate();
            $this->loadDatesWithNewPeriod();
        }catch(\Throwable $th){
            $this->dispatchBrowserEvent('noty-error', ['msg' =>  "Código de error: 161359Agenda"] );
        }
    }
    public function nextDay()
    {
        try{
            $this->addDay();
            $this->citas = $this->useDate();
        }catch(\Throwable $th){
            $this->dispatchBrowserEvent('noty-error', ['msg' =>  "Código de error: 37719Agenda"] );
        }
    }
    private function addDay()
    {
        try{
            $this->currentDate= Carbon::parse($this->currentDateC);
            $this->currentDateC= $this->currentDateC->addDay();
            $this->currentDate= $this->currentDateC->locale('es')->isoFormat('dddd, D MMMM YYYY');
        }catch(\Throwable $th){
            $this->dispatchBrowserEvent('noty-error', ['msg' =>  "Código de error: 39521Agenda"] );
        }
    }
    private function useDate()
    {
        try {
            $citas = [];
            $currentDate = $this->currentDateC->format('Y-m-d'); // Obtiene la fecha del día de $currentDateC

            foreach ($this->empleados as $empleado) {
                if ($empleado->is_active) {
                    $salon_id = Auth::user()->salon_id;

                    $citasForEmpleado = Asignacion_servicio::with('date.customer.categorias','date.etiquetas')
                        ->whereHas('empleado', function ($query) use ($salon_id) {
                            $query->where('salon_id', $salon_id);
                        })
                        ->where('empleado_id', $empleado->id)
                        ->whereDate('start', $currentDate) // Filtra por la fecha del día de $currentDateC
                        ->orderBy('start')
                        ->get();

                    // Agrupación de horarios continuos
                    $agrupadas = [];
                    $temp = null;

                    foreach ($citasForEmpleado as $cita) {
                        $cita->tags = $cita->date->etiquetas;

                        $cita->start = Carbon::parse($cita->start); // Asegura que $cita->start sea un objeto Carbon
                        $cita->end = (Carbon::parse($cita->start))->addMinutes($cita->duration);

                        $servicio = servicio::find($cita->selected_service);

                        if (isset($cita->date->customer)) {
                            $cita->categorias_cliente = implode("~ ", $cita->date->customer->categorias->pluck('name')->toArray());
                        }

                        // Si es la primera cita, inicializamos $temp
                        if ($temp === null) {
                            $temp = clone $cita;
                            $temp->title = '· ' . $servicio->name;
                        } elseif ($temp->end->equalTo($cita->start) && $temp->cita_id == $cita->cita_id) {
                            // Si es continua, extendemos la duración y unimos títulos
                            $temp->end = $cita->end;
                            $temp->duration += $cita->duration;
                            $temp->title .= "<br>" . '· ' . $servicio->name;
                        } else {
                            // Si hay una interrupción, guardamos el evento agrupado y comenzamos uno nuevo
                            $agrupadas[] = $temp;
                            $temp = clone $cita;
                            $temp->title = '· ' . $servicio->name;
                        }
                    }

                    // Guarda el último evento agrupado
                    if ($temp !== null) {
                        $agrupadas[] = $temp;
                    }

                    $citas[$empleado->id] = $agrupadas;
                }
            }

            $this->emit('reloadDragg');
            return $citas;
        } catch (\Throwable $th) {
            $this->dispatchBrowserEvent('noty-error', ['msg' => "Código de error: 235363Agenda"]);
        }
    }
    private function loadDatesWithNewPeriod()
    {
        try{
            $this->emit('dateUpdated-movimientos', $this->currentDate,$this->currentDateEnd);
        }catch(\Throwable $th){
            $this->dispatchBrowserEvent('noty-error', ['msg' =>  "Código de error: 343365Agenda"] );
        }
    }
    private function updateDates()
    {
        $this->end_date = Carbon::parse($this->start_date);
        $this->end_date->addMinutes(intval($this->minutes_qty));
        $this->end_date_DB= Carbon::parse($this->end_date)->format('Y-m-d H:i:s');
        $this->end_date= Carbon::parse($this->end_date)->format('H:i');
    }
    public function setCustomerId($customer,$recorrido=true)
    {
        try{
            if($customer!=null){
                $this->customer = cliente::with('tarjetaPuntos')->find($customer);
                $this->customerId = $customer; 
                if(session('recorrido') && $recorrido){
                    $this->dispatchBrowserEvent('play_1');
                }
            }
        }catch(\Throwable $th){
            $this->dispatchBrowserEvent('noty-error', ['msg' =>  "Código de error: 22334Agenda"] );
        }
    }
    public function loadData()
    {
        try{
            $this->updateDates();
            $this->citas = $this->useDate();
        }catch(\Throwable $th){
            $this->dispatchBrowserEvent('noty-error', ['msg' =>  "Código de error: 59031Agenda"] );
        }
    }
    private function AddItem($type,$asignacion,$item, $qty = 1,$disccount_qty=0, $ind_iva=0.16,$empleado=null,$qty_inicial=0,$uid_s=null,$discount_type="Porcentaje",$gross_price=null,$disccount_price=0,$reward_points=null)
    {
        try{

            $uid = uniqid() . $item->id;
            if($type=='servicio'){
                // iva 
                $iva = $ind_iva;
                // determinar precio venta con iva
                $salePrice = $item->disccount_price > 0 && $item->disccount_price < $item->gross_price ?  $item->disccount_price : $item->gross_price;

                if($gross_price){
                    $salePrice = $disccount_price > 0 && $disccount_price < $gross_price ?  floatval($disccount_price) : floatval($gross_price);
                }
                
                if($disccount_qty){
                    if($discount_type=='Porcentaje'){
                        $salePrice = $salePrice-($salePrice*$disccount_qty/100);
                    }elseif($discount_type=='Cantidad'){
                        $salePrice = $salePrice-$disccount_qty;
                    }
                }

                //precio unitario sin iva
                $precioUnitarioSinIva = $salePrice / (1 + $iva);
                // subtotal neto
                $subtotalNeto = $precioUnitarioSinIva * intval($qty);
                //monto del iva
                $montoIva = $subtotalNeto * $iva;
                //total con iva
                $totalConIva  = $subtotalNeto + $montoIva;

                $tax  = $montoIva;
                $total = $totalConIva;
                $data=$this->calcularHorarioCita($asignacion ? $asignacion->duration : $item->duration);
                $this->minutes_qty+= $asignacion ? $asignacion->duration : $item->duration;
                if($asignacion){
                    $end = Carbon::parse($asignacion->start)->addMinutes($asignacion->duration)->format('H:i');
                }
                $coll = collect(
                    [
                        'selected' => $asignacion ? $asignacion->selected : 1,
                        'id' => $uid,
                        'sid' => $item->id,
                        'name' => $item->name,
                        'color' => $asignacion ? $asignacion->color :'#E2BBB4',
                        'reward_points' => 0,
                        'gross_price' => $gross_price ? floatval($gross_price) : floatval($item->gross_price),
                        'disccount_price' => $asignacion ? floatval($asignacion->disccount_price) : floatval($item->disccount_price),
                        'disccount_percent' => floatval($disccount_qty),
                        'discount_type' => $asignacion ? $asignacion->discount_type:$discount_type,
                        'sale_price' => $gross_price ? floatval($gross_price) : floatval($salePrice),
                        'ind_iva' => floatval($ind_iva),
                        'tax' => floatval($tax),
                        'total' => floatval($total),
                        'vendedor' => $empleado == null ? $this->selectedEmpleadoId : $empleado,
                        'duration' => $asignacion ? $asignacion->duration : $item->duration,
                        'start' => $asignacion ? Carbon::parse($asignacion->start)->format('H:i') : $data['start'],
                        'end' => $asignacion ? $end : $data['end'],
                        'qty' => 1
                    ]
                );
            }
            $itemCart = Arr::add($coll, null, null);
            if($type=='servicio'){
                $this->cartS->push($itemCart);
            }
            $this->save();
            $this->loadCartTotales();
            return $uid;
        }catch(\Throwable $th){
            $this->dispatchBrowserEvent('noty-error', ['msg' =>  "Código de error: 578369Agenda"] );
        }
    }
    private function calcularHorarioCita($duration){
        try{
            $start_date_DB= Carbon::parse($this->start_date_DB);
            $minutes = $this->calculateTotalMinutes();
            $start = $start_date_DB->addMinutes(intval($minutes));
            $end_DB= Carbon::parse($start);
            $start = Carbon::parse($start)->format('H:i');
            $end = $end_DB->addMinutes(intval($duration));
            $end = Carbon::parse($end)->format('H:i');
            $data = [
                'start' => $start,
                'end' => $end
            ];
            return $data;
        }catch(\Throwable $th){
            $this->dispatchBrowserEvent('noty-error', ['msg' =>  "Código de error: 80239Agenda"] );
        }
    }
    protected function calculateTotalMinutes()
    {
        try{
            $totalMinutes = 0;
            foreach ($this->cartS as $item) {
                if (isset($item['duration'])) {
                    $totalMinutes += intval($item['duration']);
                }
            }
            return $totalMinutes;
        }catch(\Throwable $th){
            $this->dispatchBrowserEvent('noty-error', ['msg' =>  "Código de error: 47226Agenda"] );
        }
    }
    public function updateColor($uid,$newColor)
    {
        $asig = Asignacion_servicio::find($uid);
        $asig->color = $newColor;
        $asig->save();
    }
    private function initializeCollections()
    {
        $this->cartS = new Collection;
        $this->save();
    }

    public function addNewService($item_id)
    {
        try{
            if(session('recorrido')|| $this->recorrido){
                $this->dispatchBrowserEvent('play');
            }
            $item = servicio::find($item_id);
            $type = 'servicio';
            $this->AddItem($type,null,$item);
            $this->loadData();
            if($this->customerId!==null){
                $this->dispatchBrowserEvent('reloadForm');
            }
        }catch(\Throwable $th){
            $this->dispatchBrowserEvent('noty-error', ['msg' =>  "Código de error: 1068369Agenda"] );
        }
    }

    public function Agendar()
    {
        $this->storeDate(1);
    }
    public function storeDate($agendar=0)
    {
        try{
            session()->put('cust', $this->customer);
            session()->save();

            if (count($this->cartS)<=0) {
                $this->dispatchBrowserEvent('noty-error', ['msg' => 'NO HAY SERVICIOS AGREGADOS']);
                return;
            }
            if (session()->has('cust')) {
                $this->customer = session('cust');
                $this->customerId = $this->customer->id;
            }

            if ($this->customerId == null) {
                $this->dispatchBrowserEvent('noty-error', ['msg' => 'SELECCIONA UN CLIENTE']);
                return;
            }
            $movimiento = new cita;
            $movimiento->description = $this->description;
            $movimiento->start = $this->start_date_DB;
            $movimiento->end = $this->end_date_DB;
            $movimiento->remember =  0;
            $cita_id = $this->setMovimiento($movimiento);

            if(count($this->cartS)>0){
                foreach($this->cartS as $item){
                    $fecha = $this->calculateStart($item);
                    $asignacion = new Asignacion_servicio; 
                    $asignacion->start = $fecha;
                    $asignacion->selected = $item['selected'];
                    $asignacion->selected_service = $item['sid'];
                    $asignacion->cita_id = $cita_id;
                    $asignacion->comission = 0;
                    $asignacion->duration = $item['duration'];
                    $asignacion->color = $item['color'] ?? '#E2BBB4';
                    $this->setDetail($asignacion,$item);
                }
            }
            // Cálculo del nuevo start y end de la cita
            $data = $this->calculateStartEndDate($movimiento);
            $movimiento->start = $data['start'];
            $movimiento->end = $data['end'];
            $movimiento->save();
            $this->dispatchBrowserEvent('noty', ['msg' => "SOLICITUD PROCESADA CON ÉXITO"]);
            $this->dispatchBrowserEvent('close-form');
            if($this->isBirthDate($this->customer->birth_date,$this->start_date_DB)){
                $this->listTags = $this->addTagToArray($this->listTags,'2');
            }
            if($this->customer->citas()->count()==1){
                $this->listTags = $this->addTagToArray($this->listTags,'1');
            }
            $listTags = null;
            if ($this->listTags != null && !is_array($this->listTags)) {
                $listTags = explode(",", $this->listTags);
            } else {
                $listTags = $this->listTags;
            }

            // relacionar categorias         
            if ($listTags != null) {
                $listTags = array_map(function ($item) {
                    $catName = trim($item);
                    // verificar si el elemento no es numérico
                    if (!is_numeric($catName)) {
                        // buscar el ID de la categoría en la tabla correspondiente
                        $categoria = etiquetas_cita::where('name', $catName)
                            ->where('salon_id', Auth::user()->salon->id)
                            ->first();
                        // reemplazar el elemento con el ID de la categoría si existe
                        if ($categoria) {
                            return $categoria->id;
                        }
                    }

                    // devolver el elemento sin cambios
                    return $item;
                }, $listTags);

                // Sincronizar etiquetas
                $listTags !== null 
                    ? $movimiento->etiquetas()->sync($listTags) 
                    : $movimiento->etiquetas()->detach();
            }
            $this->clear();
            $this->cancelarCaptura();
        }catch(\Throwable $th){
            $this->dispatchBrowserEvent('noty-error', ['msg' =>  "Código de error: 1169Agenda"] );
        }
    }
    private function addTagToArray($listTags,$tagId)
    {
        if($listTags != ""){
            $listTags .= ',' . $tagId;
        }else{
            $listTags .= $tagId;
        }
        return $listTags;
    }
    private function isBirthDate($birth_date,$date)
    {
        $bd = $birth_date ?? '0000-00-00' ;
        return $bd == Carbon::parse($date)->format('Y-m-d');
    }
    private function calculateStart($item)
    {
        try{
            // Obtener la hora y los minutos de la variable $hora
            $horario = explode(":",$item['start']);

            // Establecer la nueva hora y los nuevos minutos
            $hour = $horario[0];
            $minute = $horario[1];
            
            $start = Carbon::parse($this->currentDateC);
            $fecha = $start->setTime($hour, $minute, '00');
            return $fecha;
        }catch(\Throwable $th){
            $this->dispatchBrowserEvent('noty-error', ['msg' =>  "Código de error: 1256369Agenda"] );
        }
    }
    private function setDetail($asignacion,$item)
    {
        try{
            $asignacion->empleado_id = $item['vendedor'];
            $asignacion->discount_qty = $item['disccount_percent'];
            $asignacion->discount_type = $item['discount_type'];
            $asignacion->generated_points = floatval($item['reward_points']);
            $asignacion->current_price = floatval($item['gross_price']);
            $asignacion->disccount_price = floatval($item['disccount_price']);
            $asignacion->iva = floatval($item['ind_iva']);
            $asignacion->save();
        }catch(\Throwable $th){
            $this->dispatchBrowserEvent('noty-error', ['msg' =>  "Código de error: 1268369Agenda"] );
        }   
    }
    
    private function totalCart($cart)
    {
        try{
            $amount = 0;
            $amount += $cart->sum(function ($item) {
                return $item['total'];
            });
            return $amount;
        }catch(\Throwable $th){
            $this->dispatchBrowserEvent('noty-error', ['msg' =>  "Código de error: 895369Agenda"] );
        }
    }
    private function setMovimiento($movimiento)
    {
        try{
            $movimiento->user_id = null;
            $movimiento->customer_id = $this->customerId;
            $movimiento->disccount = 0;
            $movimiento->total = $this->totalCart($this->cartS);
            $movimiento->status='Online';
            $movimiento->generated_points = 0;
            $movimiento->salon_id = 5;
            $movimiento->save();
            return $movimiento->id;
        }catch(\Throwable $th){
            $this->dispatchBrowserEvent('noty-error', ['msg' =>  "Código de error: 1288369Agenda"] );
        }   
    }

    public function changeStartDuration($uid,$new_start)
    {
        try{
            $mycart = $this->cartS;
            $oldItem = $mycart->where('id', $uid)->first();
            $this->recorrerEnd($oldItem,$new_start,$uid);

            $data = $this->determinarNewStartEnd();
            $this->recorrerHorarios($data);

            $this->save();
            $this->loadData();
        }catch(\Throwable $th){
            $this->dispatchBrowserEvent('noty-error', ['msg' =>  "Código de error: 654Agenda"] );
        }
    }
    public function changeEndDuration($uid,$new_end)
    {
        try{
            $duration = 0;
            $mycart = $this->cartS;
            $oldItem = $mycart->where('id', $uid)->first();

            $old_end = Carbon::createFromFormat('H:i', $oldItem['end']);
            $end = Carbon::createFromFormat('H:i', $new_end);

            $minutes = $old_end->diffInMinutes($end,false);

            $duration = $oldItem['duration']+$minutes;
            if($oldItem['start']>$new_end){
                $new_start = Carbon::parse($oldItem['start'])->addMinutes($minutes)->format('H:i');
                $this->changeDuration($uid,abs($duration),$new_end,$new_start);
            }else{
                $this->changeDuration($uid,$duration,$new_end);
            }
        }catch(\Throwable $th){
            $this->dispatchBrowserEvent('noty-error', ['msg' =>  "Código de error: 512325Agenda"] );
        }
    }
    private function changeDuration($uid,$duration=0,$new_end,$new_start=null)
    {
        try{
            $mycart = $this->cartS;
            $oldItem = $mycart->where('id', $uid)->first();
            $newItem  = $oldItem;

            $newItem['duration'] = intval($duration);
            $newItem['end'] = $new_end;
            if($new_start){
                $newItem['start'] = $new_start;
            }
            $this->desvincularElementoAnterior('servicio',null,$uid,$newItem);
            $this->minutes_qty=$this->recalculateDuration();
            $this->loadData();
        }catch(\Throwable $th){
            $this->dispatchBrowserEvent('noty-error', ['msg' =>  "Código de error: 5776Agenda"] );
        }
    }
    private function recalculateDuration()
    {
        try{
            $total=0;
            $mycart = $this->cartS;
            foreach($mycart as $service){
                $total+=$service['duration'];
            }
            return $total;
        }catch(\Throwable $th){
            $this->dispatchBrowserEvent('noty-error', ['msg' =>  "Código de error: 25631Agenda"] );
        }
    }
    private function recorrerEnd($oldItem,$new_start,$uid)
    {
        try{
            $newStartC = Carbon::createFromFormat('H:i', $new_start);
            $newItem  = $oldItem;

            $newItem['start'] = $new_start;
            $newItem['end'] = $newStartC->addMinutes($newItem['duration'])->format('H:i');
            $this->desvincularElementoAnterior('servicio',null,$uid,$newItem);
        }catch(\Throwable $th){
            $this->dispatchBrowserEvent('noty-error', ['msg' =>  "Código de error: 59251Agenda"] );
        }
    }
    private function determinarNewStartEnd()
    {
        try{
            $items = $this->cartS;
            $minStart = null;
            $maxEnd = null;

            foreach ($items as $item) {
                
                $start = Carbon::createFromFormat('H:i', $item['start']);
                $end = Carbon::createFromFormat('H:i', $item['end']);

                if (is_null($minStart) || $start < $minStart) {
                    $minStart = $start;
                }

                if (is_null($maxEnd) || $end > $maxEnd) {
                    $maxEnd = $end;
                }
            }
            return ['start'=>$minStart->format('H:i'),'end' => $maxEnd->format('H:i')];
        }catch(\Throwable $th){
            $this->dispatchBrowserEvent('noty-error', ['msg' =>  "Código de error: 7761Agenda"] );
        }
    }
    private function recorrerHorarios($data)
    {
        try{
            $fechas = $this->mergeFechas($data);
            $fechaConHoraStart = $fechas['start'];
            $fechaConHoraEnd = $fechas['end'];
            
            $this->start_date=$fechaConHoraStart;
            $this->start_date_DB= Carbon::parse($this->start_date)->format('Y-m-d H:i:s');
            $this->start_date= Carbon::parse($this->start_date)->format('H:i');
            $end_date = Carbon::parse($this->start_date);
            $end_date->addMinutes(intval($this->minutes_qty));
            if($data['end']>$end_date){
                $this->end_date=$fechaConHoraEnd;
                $this->end_date_DB= Carbon::parse($this->end_date)->format('Y-m-d H:i:s');
                $this->end_date= Carbon::parse($this->end_date)->format('H:i');
            }
        }catch(\Throwable $th){
            $this->dispatchBrowserEvent('noty-error', ['msg' =>  "Código de error: 1641Agenda"] );
        }
    }
    private function mergeFechas($data)
    {
        try{
            // Crear un objeto Carbon a partir de la fecha dada
            $fechaCarbon = Carbon::createFromFormat('Y-m-d H:i:s', $this->start_date_DB);
            
            // Separar la fecha y la hora
            $fechaParte = $fechaCarbon->format('Y-m-d');
            $horaParte = $data['start'];
            $horaParteE = $data['end'];
            // Unir la fecha y la hora
            $fechaConHoraStart = Carbon::createFromFormat('Y-m-d H:i', $fechaParte . ' ' . $horaParte);
            $fechaConHoraEnd = Carbon::createFromFormat('Y-m-d H:i', $fechaParte . ' ' . $horaParteE);

            return ['start'=>$fechaConHoraStart,'end'=>$fechaConHoraEnd];
        }catch(\Throwable $th){
            $this->dispatchBrowserEvent('noty-error', ['msg' =>  "Código de error: 6547Agenda"] );
        }
    }

    public function setCitaDragged($casilla,$cita)
    {
        $cita = asignacion_servicio::find($cita['id']);
        try{
            if(isset($casilla) && isset($cita)){
                $fecha = null;
                    // Eliminar los paréntesis al principio y al final de la casilla
                $casilla = trim($casilla, '()');
                
                // Dividir la casilla en dos partes usando la coma como delimitador
                $valores = explode(',', $casilla);
                
                // Limpiar los espacios en blanco alrededor de cada valor
                $hora = trim($valores[0], " '");

                // Convertir la fecha actual a objeto DateTime
                $fecha = new DateTime($this->currentDateC);


                // Obtener la hora y los minutos de la variable $hora
                $horario = explode(":",$hora);

                // Establecer la nueva hora y los nuevos minutos
                $hour = $horario[0];
                $minute = $horario[1];
                $fecha = $fecha->setTime($hour, $minute, '00');

                // Obtener la fecha y hora con la nueva hora
                $nuevaFecha = $fecha->format('Y-m-d H:i:s');
                $empleado = trim($valores[1], " '");
            }else{
                $this->loadDateByAgenda();
                return;
            }


            if(($cita->start != $nuevaFecha || $cita->empleado_id != $empleado)){
                $cita->start = $nuevaFecha;
                $cita->empleado_id = $empleado;
                $cita->save(); 
                $cita = asignacion_servicio::with('date.details')->find($cita->id); 
                $date = $cita->date;
                $data = $this->calculateStartEndDate($date);
                $date->start = $data['start'];
                $date->end = $data['end'];
                $date->save();
                $this->currentDateC= Carbon::parse($nuevaFecha);
                $this->currentDate= $this->currentDateC->locale('es')->isoFormat('dddd, D MMMM YYYY');
                $this->loadDateByAgenda();
            }else{
                $this->cancelarCaptura();
                return;
            }
        
        }catch(\Throwable $th){
            $this->dispatchBrowserEvent('noty-error', ['msg' =>  "Código de error: 1491300Agenda"] );
        }
    }
    private function calculateStartEndDate($date)
    {
        try{
            // Ordenar los detalles por el campo 'start'
            $details = $date->details->sortBy('start');
        
            // Obtener el primer y último elemento después de ordenar
            $newStart = $details->first()->start;
            $lastDetail = $details->last();
        
            // Calcular 'newEnd' sumando la duración del último detalle al 'start' del último detalle
            $lastStart = Carbon::parse($lastDetail->start);
            $newEnd = $lastStart->addMinutes($lastDetail->duration)->format('Y-m-d H:i:s');
        
            return [
                'start' => $newStart,
                'end' => $newEnd
            ];
        }catch(\Throwable $th){
            $this->dispatchBrowserEvent('noty-error', ['msg' =>  "Código de error: 645Agenda"] );
        }
    }    
}
