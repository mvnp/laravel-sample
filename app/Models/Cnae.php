<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cnae extends Model
{
    use HasFactory;

    protected $table = 'cnaes';

    protected $fillable = [
        'descricao',
    ];
}