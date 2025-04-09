<?php

namespace App\Models;

use App\Models\Comision;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Empleado extends Model
{

    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone_number',
        'birth_date',
        'is_active',
        'salon_id',
        'user_id'
    ];

    public function salon()
    {
        return $this->belongsTo(Salon::class,'salon_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }    
    public function asignacionesServicios()
    {
        return $this->hasMany(Asignacion_servicio::class, 'empleado_id');
    }
}
