<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Salon extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'phone',
        'email',
        'webPage',
        'facebook',
        'instagram',
        'tiktok',
        'youtube',
        'start',
        'end',
        'simulador',

    ];
    public function users(){
        return $this->hasMany(User::class);
    }
    public function etiquetas(){
        return $this->hasMany(etiquetas_cita::class);
    }
    public function citas(){
        return $this->hasMany(cita::class);
    }
    public function servicios(){
        return $this->hasMany(servicio::class);
    }
    public function categoriasClientes(){
        return $this->hasMany(categoria_cliente::class);
    }
    public function empleados(){
        return $this->hasMany(Empleado::class);
    }
    public function clientes(){
        return $this->hasMany(cliente::class);
    }
}
