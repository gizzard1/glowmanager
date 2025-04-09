<?php

namespace App\Models;

use App\Models\cliente;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class categoria_cliente extends Model
{
    use HasFactory;
    protected $fillable = ['name'];

    public function clientes()
    {
        return $this->belongsToMany(cliente::class,'categoria_clientes_pivs');
    }
    public function salon()
    {
        return $this->belongsTo(Salon::class,'salon_id');
    }
}
