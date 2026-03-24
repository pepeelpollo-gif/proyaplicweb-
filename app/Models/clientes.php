<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class clientes extends Model
{
    protected $table = 'clientes';
    protected $primaryKey = 'idc';
    public $incrementing = false;
    public $timestamps = false;
    protected $fillable = ['idc', 'nombre', 'ap', 'telefono'];
}