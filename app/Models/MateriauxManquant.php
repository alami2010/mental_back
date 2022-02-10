<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class MateriauxManquant extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'qte'
    ];

    public static function getAll()
    {
        return DB::table('materiaux_manquants')->orderBy('qte', 'desc')->get();
    }

    public static function getOnlyMateriauxManquant()
    {
        return DB::table('materiaux_manquants')->where('qte', '>', '0')->get();
    }
}
