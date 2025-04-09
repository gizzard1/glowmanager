<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class etiquetas_citas_piv extends Model
{
    protected $fillable = ['etiquetas_cita_id','cita_id'];
    protected $table = 'etiquetas_citas_pivs';
}
