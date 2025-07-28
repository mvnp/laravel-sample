<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estabelecimento extends Model
{
    use HasFactory;

    protected $table = 'estabelecimentos';
    
    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'cnpj_basico', 'cnpj_basico');
    }  

    public function socio()
    {
        return $this->hasMany(Socio::class, 'cnpj_basico', 'cnpj_basico');
    }
}