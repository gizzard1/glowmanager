<?php

namespace App\Models;

use App\Models\categoria_cliente;
use App\Models\tarjetas_punto;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class cliente extends Model
{
    use HasFactory;
    
    protected $fillable = [ 'first_name','last_name','email','phone','birth_date','description','want_custom_messages','want_offers','sexo','postcode','procedencia_id','salon_id'];

    public function salon()
    {
        return $this->belongsTo(Salon::class,'salon_id');
    }    
    public function citas()
    {
        return $this->hasMany(cita::class,'customer_id');
    }
    public function categorias()
    {
        return $this->belongsToMany(categoria_cliente::class,'categoria_clientes_pivs');
    }

}
