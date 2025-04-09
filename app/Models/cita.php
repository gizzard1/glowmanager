<?php

namespace App\Models;

use App\Models\User;
use App\Models\cliente;
use App\Models\Asignacion_servicio;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class cita extends Model
{
    use HasFactory;
    protected $fillable = [
        'start',
        'end',
        'date_status',
        'remember',
        'total',
        'disccount',
        'items',
        'status',
        'generated_points',
        'customer_id',
        'user_id',
        'reference',
        'payment_method',
        'salon_id',
    ];

    public function details()
    {
        return $this->hasMany(Asignacion_servicio::class,'cita_id');
    }
    public function customer()
    {
        return $this->belongsTo(cliente::class,'customer_id');
    }
    public function etiquetas()
    {
        return $this->belongsToMany(etiquetas_cita::class,'etiquetas_citas_pivs');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function salon()
    {
        return $this->belongsTo(Salon::class,'salon_id');
    }
}
