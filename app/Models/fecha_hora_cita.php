<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class fecha_hora_cita extends Model
{
    protected $table = 'fecha_hora_cita';
    protected $primaryKey = 'idfhc';
    public $incrementing = false;
    public $timestamps = false;
    protected $fillable = ['idfhc', 'fecha', 'hora'];
}