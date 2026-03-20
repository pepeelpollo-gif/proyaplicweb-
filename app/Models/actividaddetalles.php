<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class actividadesdetalles extends Model
{
    use HasFactory;
    protected $primaryKey = 'ida';
    protected $fillable = ['idad','ida','idtar','horas','total','detalle,partes,observacionesS'];
}