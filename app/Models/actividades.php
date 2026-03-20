<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class actividades extends Model
{
    protected $primaryKey = 'ida';
    protected $fillable = ['ida','fecha','idu','idemp'];
}

