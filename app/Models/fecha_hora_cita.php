<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class fecha_hora_cita extends Model
{
    protected $primaryKey = 'idfhc';
    public $timestamps = false;
    protected $fillable = ['idfhc', 'fecha', 'hora'];
}
