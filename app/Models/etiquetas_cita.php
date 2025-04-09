<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class etiquetas_cita extends Model
{
    use HasFactory;
    protected $fillable = ['name','color'];

    public function citas()
    {
        return $this->belongsToMany(cita::class,'etiquetas_citas_pivs');
    }
    public function salon()
    {
        return $this->belongsTo(Salon::class,'salon_id');
    }
}
