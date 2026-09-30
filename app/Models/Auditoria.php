<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Auditoria extends Model
{
    protected $fillable = [
        'user_id',
        'modulo',
        'accion',
        'detalles',
        'ip_address'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
