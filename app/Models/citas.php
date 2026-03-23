<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class citas extends Model
{
    protected $primaryKey = 'idac';
    public $timestamps = false;
    protected $fillable = ['idac', 'idc', 'idfhc'];
}
