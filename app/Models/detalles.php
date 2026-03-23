<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class detalles extends Model
{
    protected $primaryKey = 'idd';
    public $timestamps = false;
    protected $fillable = ['idd', 'idac', 'idtc', 'idlch', 'idlcm', 'idtch', 'idtcm', 'idf', 'ids', 'idec'];
}
