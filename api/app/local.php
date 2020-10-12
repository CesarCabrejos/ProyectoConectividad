<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class local extends Model
{
    protected $table = 'local';
    protected $primaryKey = 'Codigo';
    public $timestamps = false;
    protected $fillable = [
        'Codigo',
        'CodigoEmpresa',
        'Nombre',
        'Direccion',
        'Telefono',
        'Vigencia',
    ];
}
