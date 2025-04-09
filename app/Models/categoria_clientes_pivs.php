<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class categoria_clientes_pivs extends Model
{
    use HasFactory;
    protected $fillable = ['categoria_cliente_id','cliente_id'];
    protected $table = 'categoria_clientes_pivs';
}
