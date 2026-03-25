<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class detalles extends Model
{
    protected $table = 'detalles';
    protected $primaryKey = 'idd';
    public $incrementing = true;
    public $timestamps = false;
    protected $fillable = ['idd', 'idac', 'idtc', 'idlcm', 'idtch', 'idf', 'ids', 'idec'];
}