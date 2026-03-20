<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class especies extends Model
{
    // ERROR ANTERIOR: Tenías 'especie', pero en la BD es 'especies'
    protected $table = 'especies';
    
    // IMPORTANTE: Tu clave primaria es 'ides'
    protected $primaryKey = 'ides';
    
    protected $fillable = ['nombre'];
    
    public $timestamps = false;

    public function animales()
    {
        return $this->hasMany(animales::class, 'ides', 'ides');
    }
}