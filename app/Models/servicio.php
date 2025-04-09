<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class servicio extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'description',
        'iva',
        'gross_price',
        'discount_price',
        'duration',
        'reward_points',
        'salon_id'
    ];
    function asignaciones()
    {
        return $this->hasMany(Asignacion_servicio::class,'selected_service');
    }
    public function salon()
    {
        return $this->belongsTo(Salon::class,'salon_id');
    }

}
