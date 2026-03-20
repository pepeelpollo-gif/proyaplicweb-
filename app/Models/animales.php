<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class animales extends Model
{
    // Nombre exacto de la tabla en SQLyog
    protected $table = 'animales';
    
    // IMPORTANTE: Tu clave primaria es 'ida', no 'id'
    protected $primaryKey = 'ida';
    
    // Desactivamos timestamps si no tienes columnas created_at/updated_at
    public $timestamps = false;
    
    // Columnas que sí existen en tu tabla
    protected $fillable = ['nombre', 'foto', 'ides'];

    public function especie()
    {
        // belongsTo(Modelo, 'clave_foranea_local', 'clave_primaria_otra_tabla')
        // Usamos 'ides' que es como lo creaste en la base de datos
        return $this->belongsTo(especies::class, 'ides', 'ides');
    }
}