<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cargo extends Model
{
    use HasFactory;

    protected $table = 'cargos'; 
    protected $primaryKey = 'codigocargo'; 
    public $incrementing = false;  
    protected $keyType = 'string';  

    protected $fillable = [
        'codigocargo',  
        'nombrecargo',
    ];

    
}

