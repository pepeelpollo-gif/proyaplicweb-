<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class citas extends Model
{
    protected $table = 'citas';
    protected $primaryKey = 'idac';
    public $incrementing = false;
    public $timestamps = false;
    protected $fillable = ['idac', 'idc', 'idfhc'];
}