<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChantierTravaux extends Model
{
    use HasFactory;
    protected $table = "chantier_travaux";
    protected $fillable = [
        'id_chantier',
        'id_travaux',
        'progress'
    ];

}
