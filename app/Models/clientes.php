<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class clientes extends Model
{
    protected $primaryKey = 'idc';
    public $timestamps = false;
    protected $fillable = ['idc', 'nombre', 'ap', 'telefono'];
}
